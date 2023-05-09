<?php

	namespace App\Http\Livewire\Pages\ProductionOrders;

	use App\Models\Destination;
	use App\Models\Item;
	use App\Models\Product;
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
			$production_order = ProductionOrder::create([
				'code'           => $this->code,
				'item_id'        => $this->item_id,
				'quantity'       => $this->quantity,
				'delivery_date'  => $this->delivery_date,
				'destination_id' => $this->destination_id,
			]);
			//			foreach ($this->products as $product) {
			//				$p = Product::find($product['id']);
			//				$item->products()->attach([
			//					$p->id => [
			//						'quantity' => $product['quantity']
			//					]
			//				]);
			//			}
			$this->emitTo('pages.production-orders.index', 'production_order-created');
			$this->closeModal();
			$production_order->logs()->create([
				'user_id' => auth()->id(),
				'message' => "ha creato l'ordine di produzione n. " . $production_order->code
			]);
			$this->dispatchBrowserEvent('open-notification', [
				'title'    => __('Ordine di produzione Creato'),
				'subtitle' => __('L\' ordine di produzione è stato creato con successo!'),
				'type'     => 'success'
			]);
		}

		public function render() {
			return view('livewire.pages.production-orders.create', [
				'all_items'     => Item::all(),
				'all_destinations' => Destination::all(),
			]);
		}
	}
