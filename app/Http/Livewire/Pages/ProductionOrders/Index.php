<?php

	namespace App\Http\Livewire\Pages\ProductionOrders;

	use App\Models\ProductionOrder;
	use Livewire\Component;
	use Livewire\WithPagination;

	class Index extends Component
	{
		use WithPagination;

		public $search = '';
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
			return view('livewire.pages.production-orders.index', [
				'production_orders' => ProductionOrder::search($this->search, [
					'code',
				])->with('destination', 'item')->paginate(25)
			]);
		}
	}
