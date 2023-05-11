<?php

	namespace App\Http\Livewire\Pages\ProductionOrders;

	use App\Models\Location;
	use App\Models\Log;
	use App\Models\ProductionOrder;
	use App\Models\Serial;
	use Livewire\Component;

	class Show extends Component
	{
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
				$this->serials_checked = $this->production_order->serials()->where('completed', 0)->pluck('id');
			} else {
				$this->serials_checked = [];
			}
		}

		public function setAsCompleted() {
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
				// TODO: Decremento quantità dei prodotti utilizzati per la produzione dell'articolo
				// Capire se nella location Produzione c'è il prodotto da decrementare
                $produzione = Location::with('products')->where('type', 'produzione')->first();
                dd($produzione);
                foreach ($this->production_order->item->products as $product) {
                    dd($product->pivot->quantity);
                }

				$this->production_order->logs()->create([
					'user_id' => auth()->id(),
					'message' => "ha completato la matricola '{$serial->code}'"
				]);
			}
			if($this->production_order->status === 'created') {
				$this->production_order->update([
					'status' => 'active'
				]);
			}
			$this->dispatchBrowserEvent('open-notification', [
				'title' => __('Matricola/e completate'),
				'type'  => 'success'
			]);
			$this->serials_checked = [];
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
			return view('livewire.pages.production-orders.show', [
				'incompleted_serials' => $this->production_order->serials()->where('completed', 0)->paginate(25),
				'completed_serials'   => $this->production_order->serials()->where('completed', 1)->paginate(25),
				'logs'                => $logs
			]);
		}
	}
