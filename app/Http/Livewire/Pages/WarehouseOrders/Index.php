<?php

	namespace App\Http\Livewire\Pages\WarehouseOrders;

	use App\Models\ProductionOrder;
	use Livewire\Component;
	use Livewire\WithPagination;

	class Index extends Component
	{
		use WithPagination;

		public $search = '';
		public $status = null;
		public $deletingId = null;
		protected $listeners = [
			'production_order-updated' => '$refresh',
			'production_order-created' => '$refresh',
		];

		public function updatingSearch()
		{
			$this->resetPage();
		}

		public function delete(ProductionOrder $production_order)
		{
			$production_order->delete();
			$this->emitSelf('$refresh');
			$production_order->logs()->create([
				'user_id' => auth()->id(),
				'message' => "ha eliminato l'ordine di produzione '{$production_order->code}'"
			]);
			$this->dispatchBrowserEvent('open-notification', [
				'title' => __('Ordine Eliminato'),
				'subtitle' => __('L\'ordine di produzione è stato eliminato con successo!'),
				'type' => 'success'
			]);
		}

		public function render()
		{
			if ($this->status === null || $this->status === '') {
				$production_orders = ProductionOrder::with('item')->search($this->search, [
					'code',
				])->with('warehouse_order_products');
			} else {
				$production_orders = ProductionOrder::with('item')->search($this->search, [
					'code',
				])->with('warehouse_order_products')->get()->filter(function ($order) {
					return $order->getWarehouseOrderStatus() === $this->status;
				});
			}
			return view('livewire.pages.warehouse-orders.index', [
				'production_orders' => $production_orders->paginate(25)
			]);
		}
	}
