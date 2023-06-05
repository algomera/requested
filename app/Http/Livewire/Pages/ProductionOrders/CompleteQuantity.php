<?php

	namespace App\Http\Livewire\Pages\ProductionOrders;

	use App\Models\Location;
	use App\Models\ProductionOrder;
	use LivewireUI\Modal\ModalComponent;

	class CompleteQuantity extends ModalComponent
	{
		public $production_order;
		public $warehouse_order_versamento;
		public $quantity = 0;

		protected function rules()
		{
			return [
				'quantity' => 'required|numeric|min:1|max:' . $this->production_order->quantity - $this->warehouse_order_versamento->quantity_processed
			];
		}

		public function mount(ProductionOrder $production_order)
		{
			$this->production_order = $production_order;
			$this->warehouse_order_versamento = $production_order->warehouse_order()->where('type', 'versamento')->first()->rows()->first();
		}

		public function save()
		{
			$this->validate();
			// Aggiungo l'articolo prodotto nell'ubicazione di versamento
			$versamento = Location::where('type', 'versamento')->first();
			if ($versamento->products()->where('product_id', $this->production_order->product->id)->exists()) {
				$existing_quantity = $versamento->products()->where('product_id', $this->production_order->product->id)->first()->pivot->quantity;
				$versamento->products()->syncWithoutDetaching([
					$this->production_order->product->id => [
						'quantity' => $existing_quantity + $this->quantity
					]
				]);
			} else {
				$versamento->products()->attach($this->production_order->product->id, [
					'quantity' => $this->quantity
				]);
			}
			// Avanzo processo in Versamento
			$warehouse_order_versamento = $this->production_order->warehouse_order()->where('type', 'versamento')->first();
			// Prendo l'unica riga dell'ordine di magazzino
			$row = $warehouse_order_versamento->rows->first();
			// Avanzo quantity_processed
			$row->increment('quantity_processed', $this->quantity);

			// Cambio stato della riga
			if ($row->quantity_processed > 0 && $row->quantity_processed < $row->quantity_total) {
				$row->update([
					'status' => 'partially_transferred'
				]);
				$this->production_order->update([
					'status' => 'active',
				]);
				$this->production_order->logs()->create([
					'user_id' => auth()->id(),
					'message' => "ha iniziato l'ordine di produzione '{$this->production_order->code}'"
				]);
				$this->production_order->logs()->create([
					'user_id' => auth()->id(),
					'message' => "ha completato {$this->quantity} pezzo/i dell'ordine di produzione '{$this->production_order->code}'"
				]);
				$this->dispatchBrowserEvent('open-notification', [
					'title' => __('Modifica Stato'),
					'subtitle' => __('Lo stato dell\'ordine di produzione è passato ad "Attivo".'),
					'type' => 'success'
				]);
			} elseif ($row->quantity_processed === $row->quantity_total) {
				$row->update([
					'status' => 'transferred'
				]);
				$this->production_order->update([
					'status' => 'completed',
					'finish_date' => now()
				]);
				$this->production_order->logs()->create([
					'user_id' => auth()->id(),
					'message' => "ha completato {$this->quantity} pezzo/i dell'ordine di produzione '{$this->production_order->code}'"
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

			$this->closeModal();
			$this->emit('production_order-updated');
		}

		public function render()
		{
			return view('livewire.pages.production-orders.complete-quantity');
		}
	}
