<?php

	namespace App\Http\Livewire\Pages\ProductionOrders;

	use App\Models\Location;
	use App\Models\Product;
	use App\Models\ProductionOrder;
	use App\Models\WarehouseOrder;
	use Illuminate\Support\Facades\DB;
	use Livewire\Component;
	use Livewire\WithPagination;

	class Index extends Component
	{
		use WithPagination;

		public $search = '';
		public $status = null;
		public $deletingId = null;
		protected $listeners = [
			'production_order-updated' => '$refresh',
			'production_order-created' => '$refresh',
		];

		public function updatingSearch()
		{
			$this->resetPage();
		}

		public function delete(ProductionOrder $production_order)
		{
			$production_order->delete();
			$this->emitSelf('$refresh');
			$production_order->logs()->create([
				'user_id' => auth()->id(),
				'message' => "ha eliminato l'ordine di produzione '{$production_order->code}'"
			]);
			$this->dispatchBrowserEvent('open-notification', [
				'title' => __('Ordine Eliminato'),
				'subtitle' => __('L\'ordine di produzione è stato eliminato con successo!'),
				'type' => 'success'
			]);
		}

		public function createWarehouseOrderTrasferimento($id)
		{
//			dd("Genero Ordine di Magazzino trasferimento per l'ordine {$id}");
			$production_order = ProductionOrder::find($id);

//			$warehouse_order_trasferimento = WarehouseOrder::factory()->create([
//				'production_order_id' => $production_order->id,
//				'destination_id' => Location::where('type', 'produzione')->first()->id,
//				'type' => 'trasferimento',
//				'reason' => 'Trasferimento del materiale',
//				'user_id' => auth()->user()->id,
//				'system' => 0,
//			]);

			$result = DB::table('products')
				->join('location_product', 'location_product.product_id', '=', 'products.id')
				->join('locations', 'locations.id', '=', 'location_product.location_id')
				->select('products.id', 'location_product.location_id', 'location_product.quantity')
				->whereIn('products.id', $production_order->materials->pluck('product_id'))
				->whereNotIn('locations.type', ['produzione', 'scarto', 'fornitore', 'destinazione', 'spedizione'])
				->get();

			if ($result->count()) {
				foreach ($result as $item) {
					if ($item->quantity > 0) {
						$list[$item->id][$item->location_id] = $item->quantity;
					}
				}
			} else {
				dd('Not found');
			}

			$materialLocations = [];

			foreach ($production_order->materials as $k => $material) {
				$productId = $material->product_id;
				$requiredQuantity = $material->quantity * $production_order->quantity; // Quantità richiesta per ogni materiale

				// Verifica se il prodotto è presente nella lista $list
				if (isset($list[$productId])) {
					$locations = $list[$productId];

					$materialLocations[$productId] = [];

					// Preleva la quantità richiesta da ogni location disponibile
					foreach ($locations as $locationId => $quantity) {
						if ($requiredQuantity <= 0) {
							break;
						}

						// Verifica se la location ha abbastanza quantità disponibile
						if ($quantity > 0) {
							$prelevato = min($requiredQuantity, $quantity);
							$materialLocations[$productId][$locationId] = $prelevato;
							$list[$productId][$locationId] -= $prelevato;
							$requiredQuantity -= $prelevato;
						}
					}

					// Verifica se la quantità richiesta è stata soddisfatta completamente
					if ($requiredQuantity > 0) {
						$materialLocations[$productId] = []; // Azzeriamo l'array delle location per il materiale se la quantità richiesta non è stata soddisfatta
					}
				}


//				$warehouse_order_trasferimento->rows()->create([
//					'product_id' => $material->product_id,
//					'position' => $k,
//					'pickup_id' => $material->location_id,
//					'quantity_total' => $material->quantity * $production_order->quantity,
//					'quantity_processed' => 0,
//					'status' => 'to_transfer'
//				]);
			}
			dump($materialLocations);
		}

		public function render()
		{
			$production_orders = ProductionOrder::query();
			if ($this->status === null) {
				$production_orders->whereIn('status', ['created', 'active']);
			} else {
				$production_orders->where('status', $this->status);
			}
			return view('livewire.pages.production-orders.index', [
				'production_orders' => $production_orders->search($this->search, [
					'code',
				])->with('destination', 'product')->paginate(25)
			]);
		}
	}
