<?php

	namespace App\Http\Livewire\Pages\ProductionOrders;

	use App\Models\Location;
	use App\Models\ProductionOrder;
	use App\Models\Serial;
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
			$this->production_order = $productionOrder;
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
				$warehouse_order_versamento = $this->production_order->warehouse_orders()->where('type', 'versamento')->first();
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
			if ($this->production_order->status === 'active' && $this->production_order->serials()->where('completed', 0)->count() === 0) {
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
			$warehouse_order_scarico = $this->production_order->warehouse_orders()->where('type', 'scarico')->first();
			// Ordine di Versamento
			$warehouse_order_versamento = $this->production_order->warehouse_orders()->where('type', 'versamento')->first()->rows()->first();
			// Prendo le righe dell'ordine di scarico
			$rows = $warehouse_order_scarico->rows;
			foreach ($rows as $row) {
				// Scarico prodotto dall'ubicazione
				$location = Location::with('products')->find($row->pickup_id);
				$p = $location->products()->where('product_id', $row->product_id)->first();
				$quantity_in_location = $location->productQuantity($row->product_id);
				// (Processato versamento * quantità necessaria) - Processato riga
				$da_scaricare = ($warehouse_order_versamento->quantity_processed * $this->production_order->materials()->where('product_id', $row->product_id)->first()->quantity) - $row->quantity_processed;
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

			$this->production_order->logs()->create([
				'user_id' => auth()->id(),
				'message' => "ha scaricato del materiale per l'ordine di produzione '{$this->production_order->code}'. Lo stato attuale dello scarico è '" . config('requested.warehouse_orders.status.' . $this->production_order->warehouse_orders()->where('type', 'scarico')->first()->getStatus()) ."'"
			]);
			$this->dispatchBrowserEvent('open-notification', [
				'title' => __('Scarico Materiale'),
				'subtitle' => __('Lo scarico del materiale dell\'ordine di produzione è avvenuto con successo.'),
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
