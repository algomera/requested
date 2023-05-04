<?php

	namespace App\Http\Livewire\Pages\Items;

	use App\Models\Item;
	use App\Models\Product;
	use LivewireUI\Modal\ModalComponent;

	class Create extends ModalComponent
	{
		public $product_id;
		public $ref;
		public $products = [];

		protected function rules() {
			return [
				'product_id'          => 'required|exists:products,id',
				'products.*.id'       => 'required',
				'products.*.quantity' => 'required|min:1'
			];
		}

		protected $messages = [
			'products.*.id'       => 'Seleziona un prodotto',
			'products.*.quantity' => 'Quantità richiesta',
		];

		public function updatedProductId($value) {
			$product = Product::find($value);
			$this->ref = $product;
		}

		public function addProduct() {
			$this->products[] = new Product();
		}

		public function removeProduct($index) {
			unset($this->products[$index]);
		}

		public function save() {
			$this->validate();
			$item = Item::create([
				'product_id' => $this->ref->id,
			]);
			foreach ($this->products as $product) {
				$p = Product::find($product['id']);
				$item->products()->attach([
					$p->id => [
						'quantity' => $product['quantity']
					]
				]);
			}
			$this->emitTo('pages.items.index', 'item-created');
			$this->closeModal();
			$this->dispatchBrowserEvent('open-notification', [
				'title'    => __('Articolo Creato'),
				'subtitle' => __('L\' articolo è stato creato con successo!'),
				'type'     => 'success'
			]);
		}

		public function render() {
			return view('livewire.pages.items.create', [
				'all_products' => Product::all(),
			]);
		}
	}
