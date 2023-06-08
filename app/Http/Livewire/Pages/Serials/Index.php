<?php

	namespace App\Http\Livewire\Pages\Serials;

	use App\Models\ProductionOrder;
	use App\Models\Serial;
	use Livewire\Component;
	use Livewire\WithPagination;

	class Index extends Component
	{
		use WithPagination;

		public $search = '';
		public $status = 0;
		public $deletingId = null;
		protected $listeners = [
			'production_order-updated' => '$refresh',
			'production_order-created' => '$refresh',
		];

		public function updatingSearch()
		{
			$this->resetPage();
		}

		public function render()
		{
			$serials = Serial::query();
			if ($this->status == 0) {
				$serials->whereNotNull('production_order_id')->where('completed', 0);
			} elseif ($this->status == 1) {
				$serials->whereNotNull('production_order_id')->where('completed', 1);
			} elseif ($this->status == 2) {
				$serials->whereNotNull('production_order_id')->where('shipped', 1);
			} elseif ($this->status == 3) {
				$serials->whereNotNull('warehouse_order_id')->where('received', 1);
			}
			return view('livewire.pages.serials.index', [
				'serials' => $serials->search($this->search, [
					'code',
					'production_order.code',
				])->with('production_order')->paginate(25)
			]);
		}
	}
