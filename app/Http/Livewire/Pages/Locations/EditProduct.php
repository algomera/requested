<?php

	namespace App\Http\Livewire\Pages\Locations;

	use App\Models\Location;
	use App\Models\Product;
	use LivewireUI\Modal\ModalComponent;

	class EditProduct extends ModalComponent
	{
		public $location;
		public $product;
		public $old_quantity;
		public $quantity;

		public static function destroyOnClose(): bool
		{
			return true;
		}

		protected function rules()
		{
			return [
				'quantity' => 'required|min:0'
			];
		}

		public function mount(Product $product, Location $location)
		{
			$this->product = $product;
			$this->location = $location;
			$this->old_quantity = $this->location->products()->where('product_id', $this->product->id)->first()->pivot->quantity;
		}

		public function save()
		{
			$this->validate();
			if ($this->location->products()->where('product_id', $this->product->id)->exists()) {
				$this->location->products()->syncWithoutDetaching([
					$this->product->id => [
						'quantity' => $this->quantity
					]
				]);

				if ($this->location->products()->where('product_id', $this->product->id)->first()->pivot->quantity === 0) {
					$this->location->products()->detach($this->product->id);
				}
			}
			$this->closeModal();
			$this->emit('product-updated');
			$this->location->logs()->create([
				'user_id' => auth()->id(),
				'message' => "ha modificato la quantità di '{$this->product->name}' da {$this->old_quantity} a {$this->quantity} nell'ubicazione {$this->location->code}"
			]);
			$this->dispatchBrowserEvent('open-notification', [
				'title' => __('Quantità Modificata'),
				'subtitle' => __('La quantità del prodotto nell\'ubicazione è stata modificata successo!'),
				'type' => 'success'
			]);
		}

		public function render()
		{
			return view('livewire.pages.locations.edit-product', [
				'all_products' => Product::all(),
			]);
		}
	}
