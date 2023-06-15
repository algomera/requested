<?php

	namespace App\Http\Livewire\Pages\ProductionOrders;

	use App\Models\Location;
	use App\Models\ProductionOrder;
	use App\Models\Serial;
	use App\Models\WarehouseOrder;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Str;
	use Livewire\Component;
	use Livewire\WithPagination;

	class Show extends Component
	{
		use WithPagination;

		public $production_order;
		public $tabs = [
			0 => 'Da produrre',
			1 => 'Completati'
		];
		public $currentTab = 0;
		public $selectAll = false;
		public $serials_checked = [];

		protected $listeners = [
			'production_order-updated' => '$refresh',
		];

		public function mount(ProductionOrder $productionOrder)
		{
			$this->production_order = $productionOrder->load('materials', 'serials', 'warehouse_orders', 'warehouse_orders.rows');
		}

		public function updatedSelectAll($value)
		{
			if ($value) {
				$this->serials_checked = $this->production_order->serials()->where('completed', 0)->pluck('id')->toArray();
			} else {
				$this->serials_checked = [];
			}
		}

		public function setAsCompleted()
		{
			// Cambio status da "Creato" ad "Attivo"
			if ($this->production_order->status === 'created') {
				$this->production_order->update([
					'status' => 'active',
					'start_date' => now()
				]);
				$this->production_order->logs()->create([
					'user_id' => auth()->id(),
					'message' => "ha iniziato l'ordine di produzione '{$this->production_order->code}'"
				]);
				$this->dispatchBrowserEvent('open-notification', [
					'title' => __('Modifica Stato'),
					'subtitle' => __('Lo stato dell\'ordine di produzione è passato ad "Attivo".'),
					'type' => 'success'
				]);
			}

			foreach ($this->serials_checked as $id) {
				$serial = Serial::find($id);
				$serial->update([
					'completed' => true,
					'completed_at' => now()
				]);
				// Aggiungo l'articolo prodotto nell'ubicazione di versamento
				$versamento = Location::where('type', 'versamento')->first();
				if ($versamento->products()->where('product_id', $this->production_order->product->id)->exists()) {
					$existing_quantity = $versamento->products()->where('product_id', $this->production_order->product->id)->first()->pivot->quantity;
					$versamento->products()->syncWithoutDetaching([
						$this->production_order->product->id => [
							'quantity' => $existing_quantity + 1
						]
					]);
				} else {
					$versamento->products()->attach($this->production_order->product->id, [
						'quantity' => 1
					]);
				}
				// Avanzo processo in Versamento
				$warehouse_order_versamento = $this->production_order->warehouse_orders->where('type', 'versamento')->first();
				// Prendo l'unica riga dell'ordine di magazzino
				$row = $warehouse_order_versamento->rows->first();
				// Avanzo quantity_processed
				$row->increment('quantity_processed');
				// Cambio stato della riga
				if ($row->status === 'to_transfer' && $row->quantity_processed > 0) {
					$row->update([
						'status' => 'partially_transferred'
					]);
				} elseif ($row->status === 'partially_transferred' && $row->quantity_processed === $row->quantity_total) {
					$row->update([
						'status' => 'transferred'
					]);
				}

				$this->production_order->logs()->create([
					'user_id' => auth()->id(),
					'message' => "ha completato la matricola '{$serial->code}' nell'ordine di produzione '{$this->production_order->code}'"
				]);
			}
			$this->dispatchBrowserEvent('open-notification', [
				'title' => __('Matricola/e completate'),
				'type' => 'success'
			]);
			// Cambio status da "Attivo" a "Completato"
			if ($this->production_order->fresh()->status === 'active' && $this->production_order->fresh()->serials->where('completed', 0)->count() == 0) {
				$this->production_order->update([
					'status' => 'completed',
					'finish_date' => now()
				]);
				$this->production_order->logs()->create([
					'user_id' => auth()->id(),
					'message' => "ha completato l'ordine di produzione '{$this->production_order->code}'"
				]);
				$this->dispatchBrowserEvent('open-notification', [
					'title' => __('Modifica Stato'),
					'subtitle' => __('Lo stato dell\'ordine di produzione è passato a "Completato".'),
					'type' => 'success'
				]);
			}
			$this->serials_checked = [];
			$this->selectAll = false;
		}

		public function unloadMaterials()
		{
			// Ordine di Scarico
			$warehouse_order_scarico = $this->production_order->warehouse_orders->where('type', 'scarico')->first();
			// Ordine di Versamento
			$warehouse_order_versamento = $this->production_order->warehouse_orders->where('type', 'versamento')->first()->rows->first();
			// Prendo le righe dell'ordine di scarico
			$rows = $warehouse_order_scarico->rows;
			foreach ($rows as $row) {
				// Scarico prodotto dall'ubicazione
				$location = Location::find($row->pickup_id);
				$p = $location->products()->where('product_id', $row->product_id)->first();
				$quantity_in_location = $location->productQuantity($row->product_id);
				// (Processato versamento * quantità necessaria) - Processato riga
				$da_scaricare = ($warehouse_order_versamento->quantity_processed * $this->production_order->materials->where('product_id', $row->product_id)->first()->quantity) - $row->quantity_processed;
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
					$this->production_order->logs()->create([
						'user_id' => auth()->id(),
						'message' => "ha scaricato {$da_scaricare} '{$row->product->code}' per l'ordine di produzione '{$this->production_order->code}'. Lo stato attuale dello scarico è '" . config('requested.warehouse_orders.status.' . $warehouse_order_scarico->getStatus()) . "'"
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

		public function createWarehouseOrderTrasferimentoScarico()
		{
			if (!$this->production_order->materials->count()) {
				$this->dispatchBrowserEvent('open-notification', [
					'title' => __('Distinta di produzione vuota'),
					'subtitle' => __("Mancano i prodotti all'interno della distinta di produzione."),
					'type' => 'error'
				]);

				return false;
			}

			// Controllo in quali locations ci sono i materiali necessari
			$result = DB::table('products')
				->join('location_product', 'location_product.product_id', '=', 'products.id')
				->join('locations', 'locations.id', '=', 'location_product.location_id')
				->select('products.id', 'location_product.location_id', 'location_product.quantity')
				->whereIn('products.id', $this->production_order->materials->pluck('product_id'))
				->whereNotIn('locations.type', ['ricevimento', 'produzione', 'scarto', 'fornitore', 'destinazione', 'spedizione'])
				->get();

			// Creo un array per distribuire, per ogni materiale, la quantità in ogni location
			if ($result->count()) {
				foreach ($this->production_order->materials as $item) {
					$list["{$item->id}-{$item->product_id}"][Location::where('type', 'grandi_quantita')->first()->id] = 99999;
				}
				foreach ($result as $res) {
					if ($res->quantity > 0) {
						$list["{$item->id}-{$res->id}"][$res->location_id] = $res->quantity;
					}
				}
			} else {
				foreach ($this->production_order->materials as $item) {
					if (isset($list[$item->product_id])) {
						$list["{$item->id}-{$item->product_id}-" . Str::random(10)][Location::where('type', 'grandi_quantita')->first()->id] = 99999;
					} else {
						$list["{$item->id}-{$item->product_id}"][Location::where('type', 'grandi_quantita')->first()->id] = 99999;
					}
				}
			}

			// Genero Ordine di Magazzino (scarico)
			$warehouse_order_scarico = WarehouseOrder::factory()->create([
				'production_order_id' => $this->production_order->id,
				'destination_id' => null,
				'type' => 'scarico',
				'reason' => 'Scarico del materiale',
				'user_id' => null,
				'system' => 1,
			]);

			foreach ($this->production_order->materials as $k => $material) {
				$warehouse_order_scarico->rows()->create([
					'product_id' => $material->product_id,
					'position' => $k,
					'pickup_id' => $material->location_id,
					'destination_id' => null,
					'quantity_total' => $material->quantity * $this->production_order->quantity,
					'quantity_processed' => 0,
					'status' => 'to_transfer'
				]);
			}

			// Genero Ordine di Magazzino (trasferimento)
			$warehouse_order_trasferimento = WarehouseOrder::factory()->create([
				'production_order_id' => $this->production_order->id,
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
				$requiredQuantity = $this->production_order->materials()->find($id)->quantity * $this->production_order->quantity;

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
					$materialLocations[$productId] = []; // Azzeriamo l'array delle location per il materiale se la quantità richiesta non è stata soddisfatta
				}
			}

			// Per ogni materiale, creo la lista di quale materiale, da dove e quanto devo trasferire
//			foreach ($this->production_order->materials as $material) {
//				$productId = $material->product_id;
//				$requiredQuantity = $material->quantity * $this->production_order->quantity; // Quantità richiesta per ogni materiale
//
//				// Verifica se il prodotto è presente nella lista $list
//				if (isset($list[$productId])) {
//					$locations = $list[$productId];
//
//					$materialLocations[$productId] = [];
//
//					// Preleva la quantità richiesta da ogni location disponibile
//					foreach ($locations as $locationId => $quantity) {
//						if ($requiredQuantity <= 0) {
//							break;
//						}
//
//						// Verifica se la location ha abbastanza quantità disponibile
//						if ($quantity > 0) {
//							$prelevato = min($requiredQuantity, $quantity);
//							$materialLocations[$productId][$locationId] = $prelevato;
//							$list[$productId][$locationId] -= $prelevato;
//							$requiredQuantity -= $prelevato;
//						}
//					}
//
//					// Verifica se la quantità richiesta è stata soddisfatta completamente
//					if ($requiredQuantity > 0) {
//						$materialLocations[$productId] = []; // Azzeriamo l'array delle location per il materiale se la quantità richiesta non è stata soddisfatta
//					}
//				}
//			}
			foreach ($materialLocations as $id => $materialLocation) {
				foreach ($materialLocation as $loc => $quantity) {
					$warehouse_order_trasferimento->rows()->create([
						'product_id' => explode('-', $id)[0],
						'position' => $warehouse_order_trasferimento->rows()->count(),
						'pickup_id' => $loc,
						'destination_id' => Location::where('type', 'produzione')->first()->id,
						'quantity_total' => $quantity,
						'quantity_processed' => 0,
						'status' => 'to_transfer'
					]);
				}
			}

			$this->production_order->update([
				'status' => 'active'
			]);

			$this->production_order->logs()->create([
				'user_id' => auth()->id(),
				'message' => "ha creato gli ordini di Trasferimento e di Scarico per l'ordine di produzione '{$this->production_order->code}'"
			]);
			$this->dispatchBrowserEvent('open-notification', [
				'title' => __('Ordini generati'),
				'subtitle' => __('Gli ordini di Trasferimento e di Scarico dell\'ordine di produzione sono stati creati con successo.'),
				'type' => 'success'
			]);
		}

		public function render()
		{
			$logs = $this->production_order->logs()->with('user')->latest()->orderBy('id', 'desc')->get();
			return view('livewire.pages.production-orders.show', [
				'incompleted_serials' => $this->production_order->serials()->where('completed', 0)->paginate(25),
				'completed_serials' => $this->production_order->serials()->where('completed', 1)->paginate(25),
				'logs' => $logs
			]);
		}
	}
