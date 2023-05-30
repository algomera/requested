<?php

	namespace App\Http\Livewire\Pages\ProductionOrders;

	use App\Models\Item;
	use App\Models\Location;
	use App\Models\Product;
	use App\Models\ProductionOrder;
	use LivewireUI\Modal\ModalComponent;

	class Create extends ModalComponent
	{
		public $code, $product, $product_id, $quantity, $delivery_date, $destination_id;

		protected $listeners = [
			'itemSelected',
		];

		protected function rules()
		{
			return [
				'code' => 'required|unique:production_orders,code',
				'product_id' => 'required|exists:products,id',
				'quantity' => 'required|numeric|min:1',
				'delivery_date' => 'required|date|after:today',
				'destination_id' => 'required|exists:locations,id'
			];
		}

		public function itemSelected($value)
		{
			$this->product = Product::find($value);
			$this->product_id = $this->product->id;
		}

		public function save()
		{
			$this->validate();
			$production_order_data = [
				'code' => $this->code,
				'product_id' => $this->product_id,
				'quantity' => $this->quantity,
				'delivery_date' => $this->delivery_date,
				'destination_id' => $this->destination_id,
			];
			if ($this->product->serial_management) {
				$this->emit('openModal', 'pages.production-orders.serials', ['production_order' => $production_order_data]);
			} else {
				$production_order = ProductionOrder::create($production_order_data);
				$production_order->logs()->create([
					'user_id' => auth()->id(),
					'message' => "ha creato l'ordine di produzione '{$production_order->code}' (non matricolare)"
				]);
				$this->emitTo('pages.production-orders.index', 'production_order-created');
				$this->dispatchBrowserEvent('open-notification', [
					'title' => __('Ordine di produzione Creato'),
					'subtitle' => __('L\' ordine di produzione è stato creato con successo!'),
					'type' => 'success'
				]);
				$this->closeModal();
			}
		}

		public function render()
		{
			return view('livewire.pages.production-orders.create', [
				'all_items' => Item::all(),
				'all_destinations' => Location::where('type', 'destinazione')->get(),
			]);
		}
	}
