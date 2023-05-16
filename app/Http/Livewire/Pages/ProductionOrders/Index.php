<?php

	namespace App\Http\Livewire\Pages\ProductionOrders;

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
			'production_order-updated'    => '$refresh',
			'production_order-created'    => '$refresh',
		];

		public function updatingSearch() {
			$this->resetPage();
		}

		public function delete(ProductionOrder $production_order) {
			$production_order->delete();
			$this->emitSelf('$refresh');
			$this->dispatchBrowserEvent('open-notification', [
				'title'    => __('Ordine Eliminato'),
				'subtitle' => __('L\'ordine di produzione è stato eliminato con successo!'),
				'type'     => 'success'
			]);
		}

		public function render() {
			$production_orders = ProductionOrder::query();
			if ($this->status) {
				$production_orders->where('status', $this->status);
			}
			return view('livewire.pages.production-orders.index', [
				'production_orders' => $production_orders->search($this->search, [
					'code',
				])->with('destination', 'item')->paginate(25)
			]);
		}
	}
