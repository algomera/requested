<?php

	namespace App\Http\Livewire\Pages\Locations;

	use App\Models\Location;
	use App\Models\Product;
	use LivewireUI\Modal\ModalComponent;

	class AddProduct extends ModalComponent
	{
		public $location;
		public $product;
		public $quantity;

		public static function destroyOnClose(): bool {
			return true;
		}

		protected $listeners = [
			'itemSelected',
		];

		public function itemSelected($value) {
			$this->product = $value;
		}

		protected function rules() {
			return [
				'product'  => 'required',
				'quantity' => 'required'
			];
		}

		public function mount(Location $location) {
			$this->location = $location;
		}

		public function save() {
			$this->validate();
			// Se esiste, sommo la quantità esistente con la nuova
			if ($this->location->products()->where('product_id', $this->product)->exists()) {
				$existing_quantity = $this->location->products()->where('product_id', $this->product)->first()->pivot->quantity;
				$this->location->products()->syncWithoutDetaching([
					$this->product => [
						'quantity' => $existing_quantity + $this->quantity
					]
				]);
			} else {
				// Se non esiste, aggiungo il prodotto alla location
				$this->location->products()->attach($this->product, [
					'quantity' => $this->quantity
				]);
			}
			$this->closeModal();
			$this->emit('product-added');
			$product = Product::find($this->product);
			$this->location->logs()->create([
				'user_id' => auth()->id(),
				'message' => "ha aggiunto {$this->quantity} '{$product->code}' all'ubicazione {$this->location->code}"
			]);
			$this->dispatchBrowserEvent('open-notification', [
				'title'    => __('Prodotto Aggiunto'),
				'subtitle' => __('Il prodotto è stato aggiunto all\'ubicazione con successo!'),
				'type'     => 'success'
			]);
		}

		public function render() {
			return view('livewire.pages.locations.add-product', [
				'all_products' => Product::all(),
			]);
		}
	}
