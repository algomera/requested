<?php

	namespace App\Http\Livewire\Pages\Suppliers;

	use App\Models\Supplier;
	use LivewireUI\Modal\ModalComponent;

	class Show extends ModalComponent
	{
		public $supplier;

		public function mount(Supplier $supplier) {
			$this->supplier = $supplier;
		}

		public function render() {
			return view('livewire.pages.suppliers.show');
		}
	}
