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
			// TODO: eliminare anche i warehouse_orders collegati?
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

		public function unloadWarehouseOrderMaterials($id) {
			$production_order = ProductionOrder::find($id);

			// Ordine di Scarico
			$warehouse_order_scarico = $production_order->warehouse_orders()->where('type', 'scarico')->first();
			// Ordine di Versamento
			$warehouse_order_versamento = $production_order->warehouse_orders()->where('type', 'versamento')->first()->rows()->first();
			// Prendo le righe dell'ordine di scarico
			$rows = $warehouse_order_scarico->rows;
			foreach ($rows as $row) {
				// Scarico prodotto dall'ubicazione
				$location = Location::with('products')->find($row->pickup_id);
				$p = $location->products()->where('product_id', $row->product_id)->first();
				$quantity_in_location = $location->productQuantity($row->product_id);
				// (Processato versamento * quantità necessaria) - Processato riga
				$da_scaricare = ($warehouse_order_versamento->quantity_processed * $production_order->materials()->where('product_id', $row->product_id)->first()->quantity) - $row->quantity_processed;
				if($quantity_in_location <= $da_scaricare) {
					$da_scaricare = $quantity_in_location;
				}
				if ($p->pivot->quantity !== 0) {
					if ($p) {
						$p->pivot->decrement('quantity', $da_scaricare);
					}
					// Avanzo quantity_processed
					$row->increment('quantity_processed', $da_scaricare);
					// Cambio stato della riga
					if ($row->quantity_processed > 0 && $row->quantity_processed < $row->quantity_total) {
						$row->update([
							'status' => 'partially_transferred'
						]);
					} elseif ($row->quantity_processed === $row->quantity_total) {
						$row->update([
							'status' => 'transferred'
						]);
					}
				}
			}

			$production_order->logs()->create([
				'user_id' => auth()->id(),
				'message' => "ha scaricato del materiale per l'ordine di produzione '{$production_order->code}'. Lo stato attuale dello scarico è '" . config('requested.warehouse_orders.status.' . $production_order->warehouse_orders()->where('type', 'scarico')->first()->getStatus()) ."'"
			]);
			$this->dispatchBrowserEvent('open-notification', [
				'title' => __('Scarico Materiale'),
				'subtitle' => __('Lo scarico del materiale dell\'ordine di produzione è avvenuto con successo.'),
				'type' => 'success'
			]);
		}

		public function createWarehouseOrderTrasferimentoScarico($id)
		{
			$production_order = ProductionOrder::find($id);

			// Genero Ordine di Magazzino (scarico)
			$warehouse_order_scarico = WarehouseOrder::factory()->create([
				'production_order_id' => $production_order->id,
				'destination_id' => null,
				'type' => 'scarico',
				'reason' => 'Scarico del materiale',
				'user_id' => null,
				'system' => 1,
			]);

			foreach ($production_order->materials as $k => $material) {
				$warehouse_order_scarico->rows()->create([
					'product_id' => $material->product_id,
					'position' => $k,
					'pickup_id' => $material->location_id,
					'destination_id' => null,
					'quantity_total' => $material->quantity * $production_order->quantity,
					'quantity_processed' => 0,
					'status' => 'to_transfer'
				]);
			}

			// Genero Ordine di Magazzino (trasferimento)
			$warehouse_order_trasferimento = WarehouseOrder::factory()->create([
				'production_order_id' => $production_order->id,
				'destination_id' => Location::where('type', 'produzione')->first()->id,
				'type' => 'trasferimento',
				'reason' => 'Trasferimento del materiale',
				'user_id' => null,
				'system' => 1,
			]);

			// Controllo in quali locations ci sono i materiali necessari
			$result = DB::table('products')
				->join('location_product', 'location_product.product_id', '=', 'products.id')
				->join('locations', 'locations.id', '=', 'location_product.location_id')
				->select('products.id', 'location_product.location_id', 'location_product.quantity')
				->whereIn('products.id', $production_order->materials->pluck('product_id'))
				->whereNotIn('locations.type', ['ricevimento', 'produzione', 'scarto', 'fornitore', 'destinazione', 'spedizione'])
				->get();

			// Creo un array per distribuire, per ogni materiale, la quantità in ogni location
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

			// Per ogni materiale, creo la lista di quale materiale, da dove e quanto devo trasferire
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
			}
			foreach ($materialLocations as $id => $materialLocation) {
				foreach ($materialLocation as $loc => $quantity) {
					$warehouse_order_trasferimento->rows()->create([
						'product_id' => $id,
						'position' => $warehouse_order_trasferimento->rows()->count(),
						'pickup_id' => $loc,
						'destination_id' => Location::where('type', 'produzione')->first()->id,
						'quantity_total' => $quantity,
						'quantity_processed' => 0,
						'status' => 'to_transfer'
					]);
				}
			}

			$production_order->update([
				'status' => 'active'
			]);

			$production_order->logs()->create([
				'user_id' => auth()->id(),
				'message' => "ha creato gli ordini di Trasferimento e di Scarico per l'ordine di produzione '{$production_order->code}'"
			]);
			$this->dispatchBrowserEvent('open-notification', [
				'title' => __('Ordini generati'),
				'subtitle' => __('Gli ordini di Trasferimento e di Scarico dell\'ordine di produzione sono stati creati con successo.'),
				'type' => 'success'
			]);
		}

		public function render()
		{
			$production_orders = ProductionOrder::query();
			if ($this->status == null) {
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
