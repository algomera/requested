<?php

	namespace App\Http\Livewire\Pages\Items;

	use App\Models\Item;
	use LivewireUI\Modal\ModalComponent;

	class Show extends ModalComponent
	{
		public $item;

		public function mount(Item $item) {
			$this->item = $item;
		}

		public function render() {
			return view('livewire.pages.items.show');
		}
	}
