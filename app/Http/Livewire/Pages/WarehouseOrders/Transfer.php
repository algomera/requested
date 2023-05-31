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
			// Riduco materiale ubicazione pickup
			$this->row->pickup->products()->where('product_id', $this->row->product_id)->first()->pivot->decrement('quantity', $this->quantity);

			// Trasferisco materiale in ubicazione destination
			$check = $this->warehouse_order->destination->products()->where('product_id', $this->row->product->id)->first();
			if($check) {
				$this->warehouse_order->destination->products()->syncWithoutDetaching([
					$this->row->product_id => [
						'quantity' => $check->pivot->quantity + $this->quantity
					]
				]);
			} else {
				$this->warehouse_order->destination->products()->syncWithoutDetaching([
					$this->row->product_id => [
						'quantity' => $this->quantity
					]
				]);
			}

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
		}

		public function render()
		{
			return view('livewire.pages.warehouse-orders.transfer');
		}
	}
