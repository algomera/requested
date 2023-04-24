<?php

	namespace App\Http\Livewire\Pages\Locations;

	use App\Models\Location;
	use App\Models\Product;
	use LivewireUI\Modal\ModalComponent;

	class Change extends ModalComponent
	{
		public $product, $current_location, $quantity_in_location;
		public $locations = [];

		public static function destroyOnClose(): bool {
			return true;
		}

		protected function rules() {
			return [
				'locations.*.id'       => 'required',
				'locations.*.quantity' => 'required|min:1'
			];
		}

		protected $messages = [
			'locations.*.id'       => 'Seleziona una location',
			'locations.*.quantity' => 'Quantità richiesta',
		];

		public function mount(Product $product, Location $current_location) {
			$this->product = $product;
			$this->current_location = $current_location;
			$this->quantity_in_location = $this->current_location->productQuantity($this->product->id);
		}

		public function addLocation() {
			$this->locations[] = new Location();
		}

		public function removeLocation($index) {
			unset($this->locations[$index]);
		}

		public function save() {
			$this->validate();
			$total_quantity_to_transfer = collect($this->locations)->sum('quantity');
			if ($total_quantity_to_transfer > $this->quantity_in_location) {
				$this->addError('too_many_quantity_to_transfer', 'Stai tentando di trasferire una quantità di prodotti superiore a quella disponibile.');
			} else {
				foreach ($this->locations as $loc) {
					$l = Location::find($loc['id']);
					$exists = $l->products()->where('product_id', $this->product->id)->first()?->pivot->exists();
					if ($exists) {
						$this->product->locations()->syncWithoutDetaching([
							$l->id => [
								'quantity' => $l->products()->where('product_id', $this->product->id)->first()->pivot->quantity + $loc['quantity']
							]
						]);
					} else {
						$this->product->locations()->syncWithoutDetaching([
							$loc['id'] => [
								'quantity' => $loc['quantity']
							]
						]);
					}
				}
				$this->current_location->products()->where('product_id', $this->product->id)->first()->pivot->update([
					'quantity' => $this->quantity_in_location - $total_quantity_to_transfer
				]);
				if ($this->current_location->products()->where('product_id', $this->product->id)->first()->pivot->quantity === 0) {
					$this->current_location->products()->where('product_id', $this->product->id)->first()->pivot->delete();
				}
				$this->closeModal();
				$this->emit('product-transferred');
				$this->emit('$refresh');
				$this->dispatchBrowserEvent('open-notification', [
					'title'    => __('Trasferimento Effettuato'),
					'subtitle' => __('Il trasferimento è stato completato con successo!'),
					'type'     => 'success'
				]);
			}
		}

		public function render() {
			return view('livewire.pages.locations.change', [
				'all_locations' => Location::all()->except($this->current_location->id),
			]);
		}
	}
