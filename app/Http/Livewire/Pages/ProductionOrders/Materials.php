<?php

	namespace App\Http\Livewire\Pages\ProductionOrders;

	use App\Models\ProductionOrder;
	use LivewireUI\Modal\ModalComponent;

	class Materials extends ModalComponent
	{
		public $production_order;

		public static function modalMaxWidth(): string
		{
			return '4xl';
		}

		public function mount(ProductionOrder $production_order) {
			$this->production_order = $production_order;
		}
		public function render() {
			return view('livewire.pages.production-orders.materials');
		}
	}
