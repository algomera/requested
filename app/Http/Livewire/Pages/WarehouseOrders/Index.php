<?php

	namespace App\Http\Livewire\Pages\WarehouseOrders;

	use App\Models\ProductionOrder;
	use App\Models\WarehouseOrder;
	use Livewire\Component;
	use Livewire\WithPagination;

	class Index extends Component
	{
		use WithPagination;

		public $search = '';
		public $status = null;
		public $deletingId = null;
		protected $listeners = [
			'warehouse_order-updated' => '$refresh',
			'warehouse_order-created' => '$refresh',
		];

		public function updatingSearch()
		{
			$this->resetPage();
		}

		public function delete(WarehouseOrder $warehouse_order)
		{
			$warehouse_order->delete();
			$this->emitSelf('$refresh');
			$warehouse_order->logs()->create([
				'user_id' => auth()->id(),
				'message' => "ha eliminato l'ordine di magazzino '{$warehouse_order->production_order->code}'"
			]);
			$this->dispatchBrowserEvent('open-notification', [
				'title' => __('Ordine Eliminato'),
				'subtitle' => __('L\'ordine di magazzino è stato eliminato con successo!'),
				'type' => 'success'
			]);
		}

		public function render()
		{
			if ($this->status === null || $this->status === '') {
				$warehouse_orders = WarehouseOrder::with('production_order.item')->search($this->search, [
					'code',
				])->with('rows');
			} else {
				$warehouse_orders = WarehouseOrder::with('production_order.item')->search($this->search, [
					'code',
				])->with('rows')->get()->filter(function ($order) {
					return $order->getStatus() === $this->status;
				});
			}
			return view('livewire.pages.warehouse-orders.index', [
				'warehouse_orders' => $warehouse_orders->paginate(25)
			]);
		}
	}
