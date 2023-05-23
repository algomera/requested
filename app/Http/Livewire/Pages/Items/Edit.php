<?php

	namespace App\Http\Livewire\Pages\Items;

	use App\Models\Item;
	use App\Models\Product;
	use LivewireUI\Modal\ModalComponent;

	class Edit extends ModalComponent
	{
		public $item;
		public $ref;
		public $products = [];

		protected $listeners = [
			'itemSelected',
		];

		protected function rules() {
			return [
				'item.product_id'           => 'required|exists:products,id',
				'products.*.id'       => 'required',
				'products.*.quantity' => 'required|min:1'
			];
		}

		protected $messages = [
			'products.*.id'       => 'Seleziona un prodotto',
			'products.*.quantity' => 'Quantità richiesta',
		];

		public function itemSelected($value)
		{
			$this->item->product_id = $value;
			$product = Product::find($value);
			$this->ref = $product;
		}

		public function sortItemProductsOrder($sortOrder, $previousSortOrder, $name, $from, $to) {
			$old_products = $this->products;
			foreach ($sortOrder as $index => $prev_index) {
				$old_products[$index] = $this->products[$prev_index];
			}
			$this->products = $old_products;
		}

		public function mount(Item $item) {
			$this->item = $item;
			$this->ref = $item->product;
			foreach ($item->products()->orderBy('position')->get() as $product) {
				$this->products[] = [
					'id'       => $product->id,
					'quantity' => $product->pivot->quantity
				];
			}
		}

		public function updatedItemProductId($value) {
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
			$this->item->update();
			$this->item->products()->detach();
			foreach ($this->products as $k => $product) {
				$this->item->products()->attach([
					$product['id'] => [
						'position' => $k,
						'quantity' => $product['quantity']
					]
				]);
			}
			$this->emitTo('pages.items.index', 'item-created');
			$this->closeModal();
			$this->dispatchBrowserEvent('open-notification', [
				'title'    => __('Articolo Creato'),
				'subtitle' => __('L\' articolo è stato aggiornato con successo!'),
				'type'     => 'success'
			]);
		}

		public function render() {
			return view('livewire.pages.items.edit', [
				'all_products' => Product::all(),
			]);
		}
	}
