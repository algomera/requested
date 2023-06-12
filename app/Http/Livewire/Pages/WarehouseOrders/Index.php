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
		public $status = 'not_transferred';
		public $type = null;
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
			$warehouse_order->rows()->delete();
			$this->emitSelf('$refresh');
			$code = $warehouse_order->code ?? $warehouse_order->production_order->code;
			$warehouse_order->logs()->create([
				'user_id' => auth()->id(),
				'message' => "ha eliminato l'ordine di magazzino '{$code}'"
			]);
			$this->dispatchBrowserEvent('open-notification', [
				'title' => __('Ordine Eliminato'),
				'subtitle' => __('L\'ordine di magazzino è stato eliminato con successo!'),
				'type' => 'success'
			]);
		}

		public function render()
		{
			$warehouse_orders = WarehouseOrder::with('production_order.product', 'rows')->search($this->search, [
				'code',
				'production_order.code'
			]);
			if ($this->status != null || $this->status != '') {
				if ($this->status === 'not_transferred') {
					$warehouse_orders = $warehouse_orders->get()->filter(function ($order) {
						return $order->getStatus() === 'to_transfer' || $order->getStatus() === 'partially_transferred';
					});
				} else {
					$warehouse_orders = $warehouse_orders->get()->filter(function ($order) {
						return $order->getStatus() == $this->status;
					});
				}
			}

			if ($this->type != null || $this->type != '') {
				$warehouse_orders = $warehouse_orders->where('type', $this->type);
			}

			return view('livewire.pages.warehouse-orders.index', [
				'warehouse_orders' => $warehouse_orders->paginate(25)
			]);
		}
	}
