<?php

	namespace App\Http\Livewire\Pages\Items;

	use App\Models\Item;
	use App\Models\Product;
	use LivewireUI\Modal\ModalComponent;

	class Edit extends ModalComponent
	{
		public $item;
		public $products = [];
		public $toRemove = [];

		public function mount(Item $item) {
			$this->item = $item;
			foreach ($item->products as $product) {
				$this->products[] = [
					'id' => $product->id,
					'quantity' => $product->pivot->quantity
				];
			}
		}

		protected function rules() {
			return [
				'item.code'                => 'required|unique:products,code',
				'item.name'                => 'required',
				'item.description'         => 'required',
				'products.*.id'       => 'required',
				'products.*.quantity' => 'required|min:1'
			];
		}

		protected $messages = [
			'products.*.id'       => 'Seleziona un prodotto',
			'products.*.quantity' => 'Quantità richiesta',
		];

		public function addProduct() {
			$this->products[] = new Product();
		}

		public function removeProduct($index) {
			$this->toRemove[] = $this->products[$index]['id'];
			unset($this->products[$index]);
		}

		public function save() {
			$this->validate();
			$this->item->update();
			$this->item->products()->detach($this->toRemove);
			foreach ($this->products as $product) {
				$p = Product::find($product['id']);
				$exists = $this->item->products()->where('product_id', $p->id)->first()?->pivot->exists();
				if($exists) {
					$this->item->products()->updateExistingPivot($product['id'], [
						'quantity' => $product['quantity']
					]);
				} else {
					$this->item->products()->attach($product['id'], [
						'quantity' => $product['quantity']
					]);
				}
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
			return view('livewire.pages.items.edit', [
				'all_products' => Product::all(),
			]);
		}
	}
