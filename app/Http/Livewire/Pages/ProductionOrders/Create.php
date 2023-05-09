<?php

	namespace App\Http\Livewire\Pages\ProductionOrders;

	use App\Models\Destination;
	use App\Models\Item;
	use App\Models\ProductionOrder;
	use LivewireUI\Modal\ModalComponent;

	class Create extends ModalComponent
	{
		public $code, $item_id, $quantity, $delivery_date, $destination_id;

		protected function rules() {
			return [
				'code'           => 'required',
				'item_id'        => 'required|exists:items,id',
				'quantity'       => 'required|numeric|min:1',
				'delivery_date'  => 'required|date|after:today',
				'destination_id' => 'required|exists:destinations,id'
			];
		}

		public function save() {
			$this->validate();
			$production_order = [
				'code'           => $this->code,
				'item_id'        => $this->item_id,
				'quantity'       => $this->quantity,
				'delivery_date'  => $this->delivery_date,
				'destination_id' => $this->destination_id,
			];
			$this->emit('openModal', 'pages.production-orders.serials', ['production_order' => $production_order]);
		}

		public function render() {
			return view('livewire.pages.production-orders.create', [
				'all_items'        => Item::all(),
				'all_destinations' => Destination::all(),
			]);
		}
	}
