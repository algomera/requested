<?php

	namespace App\Http\Livewire\Pages\ProductionOrders;

	use App\Models\Log;
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
			foreach ($this->serials_checked as $id) {
				$serial = Serial::find($id);
				$serial->update([
					'completed'    => true,
					'completed_at' => now()
				]);
				$this->production_order->logs()->create([
					'user_id' => auth()->id(),
					'message' => "ha completato la matricola '{$serial->code}'"
				]);
			}
			$this->dispatchBrowserEvent('open-notification', [
				'title'    => __('Matricola/e completate'),
				'type'     => 'success'
			]);
			$this->serials_checked = [];
		}

		public function changeState() {
			$this->production_order->update([
				'status' => 'completed'
			]);
			$this->production_order->logs()->create([
				'user_id' => auth()->id(),
				'message' => "ha completato l'ordine di produzione '{$this->production_order->code}'"
			]);
			$this->dispatchBrowserEvent('open-notification', [
				'title'    => __('Ordine di produzione completato'),
				'subtitle' => __('L\'ordine di produzione è stato completato con successo.'),
				'type'     => 'success'
			]);
		}

		public function render() {
			$logs = $this->production_order->logs()->with('user')->latest()->orderBy('id', 'desc')->get();
			return view('livewire.pages.production-orders.show', [
				'incompleted_serials' => $this->production_order->serials()->where('completed', 0)->paginate(25),
				'completed_serials' => $this->production_order->serials()->where('completed', 1)->paginate(25),
				'logs' => $logs
			]);
		}
	}
