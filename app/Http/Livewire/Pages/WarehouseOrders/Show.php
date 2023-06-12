<?php

	namespace App\Http\Livewire\Pages\WarehouseOrders;

	use App\Models\Serial;
	use App\Models\WarehouseOrder;
	use Illuminate\Support\Facades\DB;
	use Livewire\Component;
	use Livewire\WithPagination;

	class Show extends Component
	{
		use WithPagination;

		public $warehouse_order;

		protected $listeners = [
			'product-transferred' => '$refresh',
		];

		public function mount(WarehouseOrder $warehouseOrder)
		{
			$this->warehouse_order = $warehouseOrder;
		}

		public function shipAll()
		{
			dd("Spedisci tutto");

			$this->emit('product-transferred');
			$this->dispatchBrowserEvent('open-notification', [
				'title' => __('Spedizione Effettuata'),
				'subtitle' => __('La spedizione è stata completata con successo!'),
				'type' => 'success'
			]);
		}

		public function receiveAll()
		{

			$rows = $this->warehouse_order->rows;
			foreach ($rows as $row) {
				if ($row->product->serial_management) {
					// Se matricolare
					$serials = $this->warehouse_order->serials;
					foreach($serials as $serial) {
						$serial->update([
							'received' => true,
							'received_at' => now()
						]);
					}

					// Trasferisco materiale in ubicazione destination
					$check_destination = $row->destination->products()->where('product_id', $row->product_id)->first();
					if ($check_destination) {
						$row->destination->products()->syncWithoutDetaching([
							$row->product_id => [
								'quantity' => $check_destination->pivot->quantity + $serials->count()
							]
						]);
					} else {
						$row->destination->products()->syncWithoutDetaching([
							$row->product_id => [
								'quantity' => $serials->count()
							]
						]);
					}

					$code = $this->warehouse_order->code ?? $this->warehouse_order->production_order->code ?? '-';
					$this->warehouse_order->logs()->create([
						'user_id' => auth()->id(),
						'message' => "ha ricevuto, riferito all'ordine di magazzino '{$code}', " . $serials->count() . " matricola/e e l'ha/le ha trasferita/e nell'ubicazione '{$row->destination->code}'"
					]);

					// Avanzo quantity_processed row
					$row->increment('quantity_processed', $serials->count());

					// Cambio stato row
					if ($row->quantity_processed > 0 && $row->quantity_processed < $row->quantity_total) {
						$row->update([
							'status' => 'partially_transferred'
						]);
					} elseif ($row->quantity_processed === $row->quantity_total) {
						$row->update([
							'status' => 'transferred'
						]);
					}
				} else {
					// Se non matricolare
					$da_ricevere = $row->quantity_total - $row->quantity_processed;
					// Trasferisco materiale in ubicazione destination
					$check_destination = $row->destination->products()->where('product_id', $row->product_id)->first();
					if ($check_destination) {
						$row->destination->products()->syncWithoutDetaching([
							$row->product_id => [
								'quantity' => $check_destination->pivot->quantity + $da_ricevere
							]
						]);
					} else {
						$row->destination->products()->syncWithoutDetaching([
							$row->product_id => [
								'quantity' => $da_ricevere
							]
						]);
					}

					$code = $this->warehouse_order->code ?? $this->warehouse_order->production_order->code ?? '-';
					$this->warehouse_order->logs()->create([
						'user_id' => auth()->id(),
						'message' => "ha ricevuto, riferito all'ordine di magazzino '{$code}', {$da_ricevere} '{$row->product->code}' e l'ha/le ha trasferita/e nell'ubicazione '{$row->destination->code}'"
					]);
					// Avanzo quantity_processed row
					$row->increment('quantity_processed', $da_ricevere);

					// Cambio stato row
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

			$this->emit('product-transferred');
			$this->dispatchBrowserEvent('open-notification', [
				'title' => __('Ricezione Effettuata'),
				'subtitle' => __('La ricezione è stata completata con successo!'),
				'type' => 'success'
			]);
		}

		public function generateDDT()
		{
			$ddt = $this->warehouse_order->ddts()->where('generated', false)->first();
			$ddt->update([
				'generated' => true,
				'generated_at' => now()
			]);
			$code = $this->warehouse_order->code ?? $this->warehouse_order->production_order->code;
			$this->warehouse_order->logs()->create([
				'user_id' => auth()->id(),
				'message' => "ha impostato lo stato del DDT n. '{$ddt->id}', riferito all'ordine di magazzino '{$code}', su 'generato'"
			]);
		}

		public function render()
		{
			$logs = $this->warehouse_order->logs()->with('user')->latest()->orderBy('id', 'desc')->get();
			return view('livewire.pages.warehouse-orders.show', [
				'rows' => $this->warehouse_order->rows()->with('product', 'pickup', 'destination')->paginate(25),
				'logs' => $logs,
				'ddts' => $this->warehouse_order->ddts()->where('generated', true)->latest()->get()
			]);
		}
	}
