<?php

	namespace App\Http\Livewire\Components;

	use App\Models\WarehouseOrder;
	use LivewireUI\Modal\ModalComponent;

	class Ddts extends ModalComponent
	{
		public $ddts;

		public function mount(WarehouseOrder $warehouse_order) {
			$this->ddts = $warehouse_order->ddts()->latest()->get();
		}

		public function render() {
			return view('livewire.components.ddts');
		}
	}
