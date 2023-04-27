<?php

	namespace App\Http\Livewire\Pages\Items;

	use App\Models\Item;
	use App\Models\Product;
	use LivewireUI\Modal\ModalComponent;

	class Create extends ModalComponent
	{
		public $code, $name, $description;
		public $products = [];

		protected function rules() {
			return [
				'code'                => 'required|unique:products,code',
				'name'                => 'required',
				'description'         => 'nullable',
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
			unset($this->products[$index]);
		}

		public function save() {
			$this->validate();
			$item = Item::create([
				'code'        => $this->code,
				'name'        => $this->name,
				'description' => $this->description,
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
