<?php

	namespace App\Http\Livewire\Pages\Locations;

	use App\Models\Location;
	use LivewireUI\Modal\ModalComponent;

	class Show extends ModalComponent
	{
		public $location;
		protected $listeners = [
			'product-added'       => '$refresh',
			'product-transferred' => '$refresh'
		];

		public function mount(Location $location) {
			$this->location = $location;
		}

		public function render() {
			return view('livewire.pages.locations.show');
		}
	}
