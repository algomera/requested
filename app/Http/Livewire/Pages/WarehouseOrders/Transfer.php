<?php

	namespace App\Http\Livewire\Pages\WarehouseOrders;

	use App\Models\WarehouseOrder;
	use App\Models\WarehouseOrderRow;
	use LivewireUI\Modal\ModalComponent;

	class Transfer extends ModalComponent
	{
		public $warehouse_order, $row;
		public $quantity = 0;

		protected function rules()
		{
			return [
				'quantity' => 'required|numeric|min:0|max:' . $this->row->quantity_total - $this->row->quantity_processed
			];
		}

		public function mount(WarehouseOrder $warehouse_order, WarehouseOrderRow $row)
		{
			$this->warehouse_order = $warehouse_order;
			$this->row = $row;
		}

		public function save()
		{
			$this->validate();
			$check_pickup = $this->row->pickup->products()->where('product_id', $this->row->product_id)->first();
			$in_location = $this->row->pickup->productQuantity($this->row->product_id);
			// Controllo che il prodotto esista nella location di pickup
			if($check_pickup) {
				// Controllo che la quantità in location sia >= alla quantità che voglio trasferire
				if($in_location >= $this->quantity) {
					// Riduco materiale ubicazione pickup
					$check_pickup->pivot->decrement('quantity', $this->quantity);
					// Trasferisco materiale in ubicazione destination
					$check_destination = $this->row->destination->products()->where('product_id', $this->row->product->id)->first();
					if($check_destination) {
						$this->row->destination->products()->syncWithoutDetaching([
							$this->row->product_id => [
								'quantity' => $check_destination->pivot->quantity + $this->quantity
							]
						]);
					} else {
						$this->row->destination->products()->syncWithoutDetaching([
							$this->row->product_id => [
								'quantity' => $this->quantity
							]
						]);
					}

					$code = $this->warehouse_order->code ?? $this->warehouse_order->production_order->code;
					$this->warehouse_order->logs()->create([
						'user_id' => auth()->id(),
						'message' => ", in riferimento all'ordine di magazzino '{$code}', ha trasferito {$this->quantity} '{$this->row->product->description}' dall'ubicazione '{$this->row->pickup->code}' all'ubicazione '{$this->row->destination->code}'"
					]);

					// Avanzo quantity_processed row
					$this->row->increment('quantity_processed', $this->quantity);

					// Cambio stato row
					if ($this->row->quantity_processed > 0 && $this->row->quantity_processed < $this->row->quantity_total) {
						$this->row->update([
							'status' => 'partially_transferred'
						]);
					} elseif ($this->row->quantity_processed === $this->row->quantity_total) {
						$this->row->update([
							'status' => 'transferred'
						]);
					}

					$this->closeModal();
					$this->emit('product-transferred');
					$this->dispatchBrowserEvent('open-notification', [
						'title'    => __('Trasferimento Effettuato'),
						'subtitle' => __('Il trasferimento è stato completato con successo!'),
						'type'     => 'success'
					]);
				} else {
					$this->dispatchBrowserEvent('open-notification', [
						'title'    => __('Errore di trasferimento'),
						'subtitle' => __("La giacenza del prodotto attualmente presente nell'ubicazione '{$this->row->pickup->code}' è insufficiente!"),
						'type'     => 'error'
					]);
				}
			} else {
				$this->dispatchBrowserEvent('open-notification', [
					'title'    => __('Errore di trasferimento'),
					'subtitle' => __("Il prodotto che si sta trasferendo non è presente nell'ubicazione '{$this->row->pickup->code}'!"),
					'type'     => 'error'
				]);
			}
		}

		public function render()
		{
			return view('livewire.pages.warehouse-orders.transfer');
		}
	}
