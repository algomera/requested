<?php

	namespace App\Http\Livewire\Pages\ProductionOrders;

	use App\Models\Location;
	use App\Models\Product;
	use App\Models\ProductionOrder;
	use App\Models\WarehouseOrder;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Str;
	use Livewire\Component;
	use Livewire\WithPagination;

	class Index extends Component
	{
		use WithPagination;

		public $search = '';
		public $status = '';
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

		public function unloadWarehouseOrderMaterials($id, $i)
		{
			$production_order = ProductionOrder::find($id)->load('materials', 'serials', 'warehouse_orders', 'warehouse_orders.rows');

			// Ordine di Scarico
			$warehouse_order_scarico = $production_order->warehouse_orders->where('type', 'scarico')->first();
			// Ordine di Versamento
			$warehouse_order_versamento = $production_order->warehouse_orders->where('type', 'versamento')->first()->rows->first();
			// Prendo le righe dell'ordine di scarico
			$rows = $warehouse_order_scarico->rows;
			foreach ($rows as $row) {
				// Scarico prodotto dall'ubicazione
				$location = Location::find($row->pickup_id);
				$p = $location->products()->where('product_id', $row->product_id)->first();
				$quantity_in_location = $location->productQuantity($row->product_id);
				// (Processato versamento * quantità necessaria) - Processato riga
				$da_scaricare = ($warehouse_order_versamento->quantity_processed * $production_order->materials->where('product_id', $row->product_id)->first()->quantity) - $row->quantity_processed;
				if ($quantity_in_location <= $da_scaricare) {
					$da_scaricare = $quantity_in_location;
				}
				if ($quantity_in_location !== 0) {
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
				// Se scarico qualcosa creo un log altrimenti faccio visualizzare una notifica
				if ($da_scaricare != 0) {
					$production_order->logs()->create([
						'user_id' => auth()->id(),
						'message' => "ha scaricato {$da_scaricare} '{$row->product->code}' per l'ordine di produzione '{$production_order->code}'. Lo stato attuale dello scarico è '" . config('requested.warehouse_orders.status.' . $production_order->warehouse_orders()->where('type', 'scarico')->first()->getStatus()) . "'"
					]);
					$this->dispatchBrowserEvent('open-notification', [
						'title' => __('Scarico Materiale'),
						'subtitle' => __('Lo scarico del materiale dell\'ordine di produzione è avvenuto con successo.'),
						'type' => 'success'
					]);
				} else {
					$this->dispatchBrowserEvent('open-notification', [
						'title' => __('Scarico Materiale'),
						'subtitle' => __('Attualmente non ci sono prodotti da scaricare.'),
						'type' => 'warning'
					]);
				}
			}
		}

		public function createWarehouseOrderTrasferimentoScarico($id, $i)
		{
			$production_order = ProductionOrder::find($id);

			if (!$production_order->materials->count()) {
				$this->dispatchBrowserEvent('open-notification', [
					'title' => __('Distinta di produzione vuota'),
					'subtitle' => __("Mancano i prodotti all'interno della distinta di produzione."),
					'type' => 'error'
				]);

				return false;
			}

			// Creo un array per distribuire, per ogni materiale, la quantità in ogni location
			foreach ($production_order->materials as $item) {
				$test = Location::with('products')->whereNotIn('locations.type', ['ricevimento', 'produzione', 'scarto', 'fornitore', 'destinazione', 'spedizione'])->whereHas('products', function ($q) use ($item) {
					$q->where('id', $item->product_id);
				})->orderBy('out_priority')->get();
				if ($test->count()) {
					foreach ($test as $t) {
						$list["{$item->id}-{$item->product_id}"][$t->id] = $t->products()->find($item->product_id)->pivot->quantity;
					}
				} else {
					$list["{$item->id}-{$item->product_id}"][Location::where('type', 'grandi_quantita')->orderBy('out_priority')->first()->id] = 99999;
				}
			}

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

			$materialLocations = [];

			foreach ($list as $k => $item) {
				$id = explode('-', $k)[0];
				$productId = explode('-', $k)[1];
				$requiredQuantity = $production_order->materials()->find($id)->quantity * $production_order->quantity;

				$locations = $list[$k];

				$materialLocations[$k] = [];

				// Preleva la quantità richiesta da ogni location disponibile
				foreach ($locations as $locationId => $quantity) {
					if ($requiredQuantity <= 0) {
						break;
					}

					// Verifica se la location ha abbastanza quantità disponibile
					if ($quantity > 0) {
						$prelevato = min($requiredQuantity, $quantity);
						$materialLocations[$k][$locationId] = $prelevato;
						$list[$k][$locationId] -= $prelevato;
						$requiredQuantity -= $prelevato;
					}
				}

				// Verifica se la quantità richiesta è stata soddisfatta completamente
				if ($requiredQuantity > 0) {
					// Se non è soddisfatta, vado a pescare la quantità rimanente dalla prima location "grandi_quantità"
					$materialLocations[$k][Location::where('type', 'grandi_quantita')->orderBy('out_priority')->first()->id] = $requiredQuantity;
				}
			}
			foreach ($materialLocations as $id => $materialLocation) {
				foreach ($materialLocation as $loc => $quantity) {
					$warehouse_order_trasferimento->rows()->create([
						'product_id' => explode('-', $id)[1],
						'position' => $warehouse_order_trasferimento->rows()->count(),
						'pickup_id' => $loc,
						'destination_id' => $production_order->materials()->find(explode('-', $id)[0])->location_id,
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

			if ($this->status == 'all') {
				$production_orders;
			} elseif ($this->status == null) {
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
