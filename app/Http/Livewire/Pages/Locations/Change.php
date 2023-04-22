<?php

	namespace App\Http\Livewire\Pages\Locations;

	use App\Models\Location;
	use App\Models\Product;
	use LivewireUI\Modal\ModalComponent;

	class Change extends ModalComponent
	{
		public $product, $current_location;

		public function mount(Product $product, Location $current_location) {
			$this->product = $product;
			$this->current_location = $current_location;
		}

		public function render() {
			return view('livewire.pages.locations.change');
		}
	}
