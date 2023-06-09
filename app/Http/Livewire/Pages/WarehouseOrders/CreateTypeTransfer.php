<?php

	namespace App\Http\Livewire\Pages\WarehouseOrders;

	use App\Models\Item;
	use App\Models\Location;
	use App\Models\Product;
	use App\Models\WarehouseOrder;
	use Illuminate\Support\Str;
	use LivewireUI\Modal\ModalComponent;

	class CreateTypeTransfer extends ModalComponent
	{
		public $ref;
		public $products = [];

		public static function modalMaxWidth(): string
		{
			return '7xl';
		}

		protected $listeners = [
			'itemSelected',
			'setProductToItem',
			'setPickupToItem',
			'setDestinationToItem',
		];

		protected function rules()
		{
			return [
				'products.*.id' => 'required',
				'products.*.pickup_id' => 'required|exists:locations,id',
				'products.*.quantity' => 'required|min:1',
				'products.*.destination_id' => 'required|exists:locations,id',
			];
		}

		protected $messages = [
			'products.*.id' => 'Seleziona un prodotto',
			'products.*.pickup_id' => 'Ubicazione richiesta',
			'products.*.quantity' => 'Quantità richiesta',
			'products.*.destination_id' => 'Ubicazione richiesta'
		];

		public function itemSelected($value)
		{
			$this->product_id = $value;
			$product = Product::find($value);
		}

		public function setProductToItem($value, $to)
		{
			$this->products[$to]['id'] = $value;
			$this->ref = Product::find($value);
		}

		public function setPickupToItem($value, $to)
		{
			$this->products[$to]['pickup_id'] = $value;
		}

		public function setDestinationToItem($value, $to)
		{
			$this->products[$to]['destination_id'] = $value;
		}

		public function sortItemProductsOrder($sortOrder, $previousSortOrder, $name, $from, $to)
		{
			$old_products = $this->products;
			foreach ($sortOrder as $index => $prev_index) {
				$old_products[$index] = $this->products[$prev_index];
			}
			$this->products = $old_products;
		}

		public function addProduct()
		{
			$p = new Product();
			$p->uuid = Str::random(10);
			$this->products[] = $p;
		}

		public function removeProduct($index)
		{
			unset($this->products[$index]);
		}

		public function save()
		{
			$this->validate();
			$warehouse_order_trasferimento = WarehouseOrder::factory()->create([
				'production_order_id' => null,
				'destination_id' => null,
				'type' => 'trasferimento',
				'reason' => 'Trasferimento del materiale',
				'user_id' => auth()->user()->id,
				'system' => 0,
			]);

			foreach ($this->products as $k => $product) {
				$warehouse_order_trasferimento->rows()->create([
					'product_id' => $product['id'],
					'position' => $k,
					'pickup_id' => $product['pickup_id'],
					'destination_id' => $product['destination_id'],
					'quantity_total' => $product['quantity'],
					'quantity_processed' => 0,
					'status' => 'to_transfer'
				]);
			}

			$this->emit('warehouse_order-created');
			$this->closeModal();
			$warehouse_order_trasferimento->logs()->create([
				'user_id' => auth()->id(),
				'message' => "ha creato l'ordine di trasferimento '{$warehouse_order_trasferimento->code}'"
			]);
			$this->dispatchBrowserEvent('open-notification', [
				'title' => __('Ordine di Trasferimento Creato'),
				'subtitle' => __('L\' ordine di trasferimento è stato creato con successo!'),
				'type' => 'success'
			]);
		}

		public function render()
		{
			return view('livewire.pages.warehouse-orders.create-type-transfer', [
				'all_products' => Product::all(),
			]);
		}
	}
