<?php

	namespace App\Http\Livewire\Pages\Products;

	use App\Models\Product;
	use LivewireUI\Modal\ModalComponent;

	class Edit extends ModalComponent
	{
		public $product;

		protected function rules() {
			return [
				'product.code'        => 'required|unique:products,code,' . $this->product->id,
				'product.name'        => 'required',
				'product.description' => 'required',
				'product.units'       => 'required',
				'product.quantity'    => 'required',
			];
		}

		public function mount(Product $product) {
			$this->product = $product;
		}

		public function save() {
			$this->validate();
			$this->product->update();
			$this->emitTo('pages.products.index', 'product-updated');
			$this->closeModal();
			$this->dispatchBrowserEvent('open-notification', [
				'title'    => __('Prodotto Aggiornato'),
				'subtitle' => __('Il prodotto è stato aggiornato con successo!'),
				'type'     => 'success'
			]);
		}

		public function render() {
			return view('livewire.pages.products.edit');
		}
	}
