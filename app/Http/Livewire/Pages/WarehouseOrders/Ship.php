<?php

	namespace App\Http\Livewire\Pages\WarehouseOrders;

	use App\Models\Serial;
	use App\Models\WarehouseOrder;
	use App\Models\WarehouseOrderRow;
	use Illuminate\Support\Facades\DB;
	use Livewire\WithPagination;
	use LivewireUI\Modal\ModalComponent;

	class Ship extends ModalComponent
	{
		use WithPagination;

		public $warehouse_order, $row;
		public $selectAll = false;
		public $serials_checked = [];
		public $quantity;

		protected function rules()
		{
			return [
				'quantity' => !$this->row->product->serial_management ? 'required|numeric|min:0|max:' . $this->row->quantity_total - $this->row->quantity_processed : 'nullable'
			];
		}

		public function mount(WarehouseOrder $warehouse_order, WarehouseOrderRow $row)
		{
			$this->warehouse_order = $warehouse_order;
			$this->row = $row;
		}

		public function updatedSelectAll($value)
		{
			if ($value) {
				$this->serials_checked = $this->warehouse_order->production_order->serials()->where('completed', 1)->where('shipped', 0)->pluck('id')->toArray();
			} else {
				$this->serials_checked = [];
			}
		}

		public function save()
		{
			$this->validate();
			// Riduco materiale ubicazione pickup
			if ($this->row->product->serial_management) {
				if ($this->row->pickup->products()->where('product_id', $this->row->product_id)->exists()) {
					$this->row->pickup->products()->where('product_id', $this->row->product_id)->first()->pivot->decrement('quantity', count($this->serials_checked));
				} else {
					$this->dispatchBrowserEvent('open-notification', [
						'title' => __('Errore'),
						'subtitle' => __("Nella location {$this->row->pickup->code} non c'è il prodotto da spedire!"),
						'type' => 'error'
					]);
					return false;
				}
			} else {
				$in_location = $this->row->pickup->productQuantity($this->row->product_id);
				if($in_location >= $this->quantity) {
					if ($this->row->pickup->products()->where('product_id', $this->row->product_id)->exists()) {
						$this->row->pickup->products()->where('product_id', $this->row->product_id)->first()->pivot->decrement('quantity', $this->quantity);
					} else {
						$this->dispatchBrowserEvent('open-notification', [
							'title' => __('Errore'),
							'subtitle' => __("Nella location {$this->row->pickup->code} non c'è il prodotto da spedire!"),
							'type' => 'error'
						]);
						return false;
					}
				} else {
					$this->dispatchBrowserEvent('open-notification', [
						'title' => __('Errore'),
						'subtitle' => __("Nella location '{$this->row->pickup->code}' non ci sono abbastanza '{$this->row->product->code}' da spedire!"),
						'type' => 'error'
					]);
					return false;
				}
			}

			// Imposto ogni matricola selezionata in "spedita"
			foreach ($this->serials_checked as $s) {
				$serial = Serial::find($s);

				$serial->update([
					'shipped' => true,
					'shipped_at' => now()
				]);
			}

			// Genero DDT e righe
			if ($this->warehouse_order->ddts()->where('generated', false)->latest()->first()) {
				$ddt = $this->warehouse_order->ddts()->where('generated', false)->latest()->first();
			} else {
				$ddt = $this->warehouse_order->ddts()->create();
				$code = $this->warehouse_order->code ?? $this->warehouse_order->production_order->code;
				$this->warehouse_order->logs()->create([
					'user_id' => auth()->id(),
					'message' => "ha creato il DDT n. '{$ddt->id}', riferito all'ordine di magazzino '{$code}'"
				]);
			}

			if ($this->row->product->serial_management) {
				// Se matricolare
				foreach ($this->serials_checked as $s) {
					$serial = Serial::find($s);

					DB::table('ddt_product')->insert([
						'ddt_id' => $ddt->id,
						'serial_id' => $serial->id,
						'quantity' => 1,
						'created_at' => now(),
						'updated_at' => now()
					]);
				}
				$code = $this->warehouse_order->code ?? $this->warehouse_order->production_order->code;
				$this->warehouse_order->logs()->create([
					'user_id' => auth()->id(),
					'message' => "ha aggiungo al DDT n. '{$ddt->id}', riferito all'ordine di magazzino '{$code}', " . count($this->serials_checked) . " matricola/e"
				]);

				// Avanzo quantity_processed row
				$this->row->increment('quantity_processed', count($this->serials_checked));

				// Cambio stato row
				if ($this->row->quantity_processed > 0 && $this->row->quantity_processed < $this->row->quantity_total) {
					$this->row->update([
						'status' => 'partially_transferred'
					]);
				} elseif ($this->row->quantity_processed === $this->row->quantity_total) {
					$this->row->update([
						'status' => 'transferred'
					]);
				}
			} else {
				// Se non matricolare
				$exists = DB::table('ddt_product')->where('ddt_id', $ddt->id)->where('product_id', $this->row->product_id)->first();
				DB::table('ddt_product')->updateOrInsert([
					'ddt_id' => $ddt->id,
					'product_id' => $this->row->product_id,
				], [
					'quantity' => $exists ? $exists->quantity + $this->quantity : $this->quantity,
					'created_at' => now(),
					'updated_at' => now()
				]);

				$code = $this->warehouse_order->code ?? $this->warehouse_order->production_order->code;
				$this->warehouse_order->logs()->create([
					'user_id' => auth()->id(),
					'message' => "ha aggiunto al DDT n. '{$ddt->id}', riferito all'ordine di magazzino '{$code}', {$this->quantity} '{$this->row->product->code}'"
				]);

				// Avanzo quantity_processed row
				$this->row->increment('quantity_processed', $this->quantity);

				// Cambio stato row
				if ($this->row->quantity_processed > 0 && $this->row->quantity_processed < $this->row->quantity_total) {
					$this->row->update([
						'status' => 'partially_transferred'
					]);
				} elseif ($this->row->quantity_processed === $this->row->quantity_total) {
					$this->row->update([
						'status' => 'transferred'
					]);
				}
			}

			$this->closeModal();
			$this->emit('product-transferred');
			$this->dispatchBrowserEvent('open-notification', [
				'title' => __('Spedizione Effettuata'),
				'subtitle' => __('La spedizione è stata completata con successo!'),
				'type' => 'success'
			]);
		}

		public function render()
		{
			return view('livewire.pages.warehouse-orders.ship', [
				'unshipped_serials' => $this->warehouse_order->production_order->serials()->where('completed', 1)->where('shipped', 0)->paginate(25),
			]);
		}
	}
