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

		public function mount(ProductionOrder $productionOrder) {
			$this->production_order = $productionOrder;
		}

		public function updatedSelectAll($value) {
			if ($value) {
				$this->serials_checked = $this->production_order->serials()->where('completed', 0)->take($this->production_order->maxItemsProducibles)->pluck('id')->toArray();
			} else {
				$this->serials_checked = [];
			}
		}

		public function setAsCompleted() {
			// Verifica matricole
			if(count($this->serials_checked) > $this->production_order->maxItemsProducibles) {
				$this->dispatchBrowserEvent('open-notification', [
					'title' => __('ATTENZIONE!'),
					'subtitle' => __("Puoi produrre {$this->production_order->maxItemsProducibles} matricola/e, ma stai cercando di produrne " . count($this->serials_checked) . "!"),
					'type'  => 'error'
				]);
				return;
			}

			// Cambio status da "Creato" ad "Attivo"
			if($this->production_order->status === 'created') {
				$this->production_order->update([
					'status' => 'active'
				]);
				$this->production_order->logs()->create([
					'user_id' => auth()->id(),
					'message' => "ha iniziato l'ordine di produzione '{$this->production_order->code}'"
				]);
				$this->dispatchBrowserEvent('open-notification', [
					'title' => __('Modifica Stato'),
					'subtitle' => __('Lo stato dell\'ordine di produzione è passato ad "Attivo".'),
					'type'  => 'success'
				]);
			}

			foreach ($this->serials_checked as $id) {
				$serial = Serial::find($id);
				$serial->update([
					'completed'    => true,
					'completed_at' => now()
				]);
				// Aggiungo l'articolo prodotto nell'ubicazione di versamento
				$versamento = Location::where('type', 'versamento')->first();
				if($versamento->products()->where('product_id', $this->production_order->item->product->id)->exists()) {
					$existing_quantity = $versamento->products()->where('product_id', $this->production_order->item->product->id)->first()->pivot->quantity;
					$versamento->products()->syncWithoutDetaching([
						$this->production_order->item->product->id => [
							'quantity' => $existing_quantity + 1
						]
					]);
				} else {
					$versamento->products()->attach($this->production_order->item->product->id, [
						'quantity' => 1
					]);
				}
                $produzione = Location::with('products')->where('type', 'produzione')->first();
				foreach ($produzione->products as $product) {
					$product->pivot->decrement('quantity', $this->production_order->item->products()->where('product_id', $product->id)->first()->pivot->quantity);
					//TODO: eliminare record pivot se quantità è 0
				}

				$this->production_order->logs()->create([
					'user_id' => auth()->id(),
					'message' => "ha completato la matricola '{$serial->code}' nell'ordine di produzione '{$this->production_order->code}'"
				]);
			}
			$this->dispatchBrowserEvent('open-notification', [
				'title' => __('Matricola/e completate'),
				'type'  => 'success'
			]);
			// Cambio status da "Attivo" a "Completato"
			if($this->production_order->status === 'active' && $this->production_order->serials()->where('completed', 0)->count() === 0) {
				$this->production_order->update([
					'status' => 'completed'
				]);
				$this->production_order->logs()->create([
					'user_id' => auth()->id(),
					'message' => "ha completato l'ordine di produzione '{$this->production_order->code}'"
				]);
				$this->dispatchBrowserEvent('open-notification', [
					'title' => __('Modifica Stato'),
					'subtitle' => __('Lo stato dell\'ordine di produzione è passato a "Completato".'),
					'type'  => 'success'
				]);
			}
			$this->serials_checked = [];
			$this->selectAll = false;
		}

		public function changeState() {
			$this->production_order->update([
				'status' => 'completed'
			]);
			$this->production_order->logs()->create([
				'user_id' => auth()->id(),
				'message' => "ha completato l'ordine di produzione '{$this->production_order->code}'"
			]);
			$this->dispatchBrowserEvent('open-notification', [
				'title'    => __('Ordine di produzione completato'),
				'subtitle' => __('L\'ordine di produzione è stato completato con successo.'),
				'type'     => 'success'
			]);
		}

		public function render() {
			$logs = $this->production_order->logs()->with('user')->latest()->orderBy('id', 'desc')->get();
			$serials = $this->production_order->serials();
			return view('livewire.pages.production-orders.show', [
				'incompleted_serials' => $serials->where('completed', 0)->paginate(25),
				'completed_serials'   => $serials->where('completed', 1)->paginate(25),
				'logs'                => $logs
			]);
		}
	}
