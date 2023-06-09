<?php

	namespace App\Http\Livewire\Components;

	use App\Models\Item;
	use App\Models\Location;
	use App\Models\Product;
	use App\Models\WarehouseOrder;
	use LivewireUI\Modal\ModalComponent;

	class InternalTransfer extends ModalComponent
	{
		public $startLocation;
		public $endLocation;
		public $product;
		public $quantity;
		public $quantity_in_location;

		protected $listeners = [
			'startLocationSelected',
			'itemSelected',
			'endLocationSelected',
		];

		public function startLocationSelected($val)
		{
			$this->startLocation = Location::find($val);
		}

		public function endLocationSelected($val)
		{
			$this->endLocation = Location::find($val);
		}

		public function itemSelected($val)
		{
			$this->product = Product::find($val);
			$this->quantity_in_location = $this->startLocation->productQuantity($this->product->id) ?? 0;
		}

		public function save()
		{
			if ($this->quantity > $this->quantity_in_location) {
				$this->addError('too_many_quantity_to_transfer', 'Stai tentando di trasferire una quantità di prodotti superiore a quella disponibile.');
				return false;
			} else {
				$exists = $this->endLocation->products()->where('product_id', $this->product->id)->first()?->pivot->exists();
				if ($exists) {
					$this->product->locations()->syncWithoutDetaching([
						$this->endLocation->id => [
							'quantity' => $this->endLocation->products()->where('product_id', $this->product->id)->first()->pivot->quantity + $this->quantity
						]
					]);
				} else {
					$this->product->locations()->syncWithoutDetaching([
						$this->endLocation->id => [
							'quantity' => $this->quantity
						]
					]);
				}
				// Genero Ordine di Magazzino (trasferimento)
				$trasferimento = WarehouseOrder::create([
					'production_order_id' => null,
					'destination_id' => $this->endLocation->id,
					'type' => 'trasferimento',
					'reason' => 'Trasferimento Interno',
					'user_id' => auth()->user()->id,
					'system' => 0,
				]);
				$trasferimento->rows()->create([
					'product_id' => $this->product->id,
					'position' => 0,
					'pickup_id' => $this->startLocation->id,
					'destination_id' => $this->endLocation->id,
					'quantity_total' => $this->quantity,
					'quantity_processed' => $this->quantity,
					'status' => 'transferred'
				]);

				$this->startLocation->logs()->create([
					'user_id' => auth()->id(),
					'message' => "ha trasferito {$this->quantity} '{$this->product->description}' dall'ubicazione '{$this->startLocation->code}' all'ubicazione '{$this->endLocation->code}'"
				]);
				$this->startLocation->products()->where('product_id', $this->product->id)->first()->pivot->update([
					'quantity' => $this->quantity_in_location - $this->quantity
				]);
				if ($this->startLocation->products()->where('product_id', $this->product->id)->first()->pivot->quantity == 0) {
					$this->startLocation->products()->where('product_id', $this->product->id)->first()->pivot->delete();
				}
				$this->closeModal();
				$this->emit('product-transferred');
				$this->emit('warehouse_order-created');
				$this->emit('$refresh');
				$this->dispatchBrowserEvent('open-notification', [
					'title' => __('Trasferimento Effettuato'),
					'subtitle' => __('Il trasferimento è stato completato con successo!'),
					'type' => 'success'
				]);
			}
		}

		public function render()
		{
			return view('livewire.components.internal-transfer', [
				'locations' => Location::all(),
			]);
		}
	}
