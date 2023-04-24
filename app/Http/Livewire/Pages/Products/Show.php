<?php

	namespace App\Http\Livewire\Pages\Products;

	use App\Models\Product;
	use LivewireUI\Modal\ModalComponent;

	class Show extends ModalComponent
	{
		public $product;
		protected $listeners = [
			'product-transferred' => '$refresh'
		];

		public function mount(Product $product) {
			$this->product = $product;
		}

		public function render() {
			return view('livewire.pages.products.show');
		}
	}
