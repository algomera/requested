<?php

	namespace App\Http\Livewire\Pages\WarehouseOrders;

	use App\Models\WarehouseOrder;
	use Livewire\Component;
	use Livewire\WithPagination;

	class Show extends Component
	{
		use WithPagination;

		public $warehouse_order;

		protected $listeners = [
			'product-transferred' => '$refresh',
		];

		public function mount(WarehouseOrder $warehouseOrder)
		{
			$this->warehouse_order = $warehouseOrder;
		}

		public function render()
		{
			$logs = $this->warehouse_order->logs()->with('user')->latest()->orderBy('id', 'desc')->get();
			return view('livewire.pages.warehouse-orders.show', [
				'rows' => $this->warehouse_order->rows()->with('product', 'pickup')->paginate(25),
				'logs' => $logs
			]);
		}
	}
