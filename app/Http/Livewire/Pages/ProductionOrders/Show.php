<?php

	namespace App\Http\Livewire\Pages\ProductionOrders;

	use App\Models\ProductionOrder;
	use App\Models\Serial;
	use Livewire\Component;

	class Show extends Component
	{
		public $production_order;
		public $tabs = [
			0 => 'Da produrre',
			1 => 'Completati'
		];
		public $currentTab = 0;
		public $serials_checked = [];

		public function mount(ProductionOrder $productionOrder) {
			$this->production_order = $productionOrder;
		}

		public function setAsCompleted() {
			foreach ($this->serials_checked as $serial) {
				Serial::find($serial)->update([
					'completed'    => true,
					'completed_at' => now()
				]);
			}
			$this->dispatchBrowserEvent('open-notification', [
				'title'    => __('Matricole completate'),
				'subtitle' => __(count($this->serials_checked) . ' matricole sono state completate!'),
				'type'     => 'success'
			]);
			// TODO: Log
			$this->serials_checked = [];
		}

		public function render() {
			$serials = $this->production_order->serials()->where('completed', $this->currentTab)->paginate(25);
			return view('livewire.pages.production-orders.show', [
				'serials' => $serials
			]);
		}
	}
