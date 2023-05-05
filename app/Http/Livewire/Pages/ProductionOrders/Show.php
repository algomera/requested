<?php

	namespace App\Http\Livewire\Pages\ProductionOrders;

	use App\Models\ProductionOrder;
	use Livewire\Component;

	class Show extends Component
	{
		public $production_order;

		public function mount(ProductionOrder $productionOrder) {
			$this->production_order = $productionOrder;
		}
		public function render() {
			return view('livewire.pages.production-orders.show');
		}
	}
