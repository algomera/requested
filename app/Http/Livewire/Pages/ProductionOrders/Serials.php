<?php

	namespace App\Http\Livewire\Pages\ProductionOrders;

	use App\Models\ProductionOrder;
	use App\Models\Serial;
	use LivewireUI\Modal\ModalComponent;

	class Serials extends ModalComponent
	{
		public $production_order;
		public $serials = [];

		protected function rules() {
			return [
				'serials.*' => 'required|distinct',
			];
		}

		public function mount($production_order) {
			$this->production_order = $production_order;
			foreach (range(1, $this->production_order['quantity']) as $serial) {
				$this->serials[] = new Serial();
			}
		}

		public function save() {
			$this->validate();
			$production_order = ProductionOrder::create($this->production_order);
			$production_order->logs()->create([
				'user_id' => auth()->id(),
				'message' => "ha creato l'ordine di produzione '{$production_order->code}'"
			]);
			foreach ($this->serials as $k => $serial) {
				$production_order->serials()->create([
					'item_id' => $production_order->item_id,
					'code'    => $serial,
				]);
			}
			$this->emitTo('pages.production-orders.index', 'production_order-created');
			$this->forceClose()->closeModal();
			$production_order->logs()->create([
				'user_id' => auth()->id(),
				'message' => "ha aggiunto {$production_order->quantity} matricole all'ordine di produzione '{$production_order->code}'"
			]);
			$this->dispatchBrowserEvent('open-notification', [
				'title'    => __('Ordine di produzione Creato'),
				'subtitle' => __('L\' ordine di produzione è stato creato con successo!'),
				'type'     => 'success'
			]);
		}

		public function render() {
			return view('livewire.pages.production-orders.serials');
		}
	}
