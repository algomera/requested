<?php

	namespace App\Http\Livewire\Pages\WarehouseOrders;

	use App\Models\Serial;
	use App\Models\WarehouseOrder;
	use App\Models\WarehouseOrderRow;
	use Livewire\WithPagination;
	use LivewireUI\Modal\ModalComponent;

	class Ship extends ModalComponent
	{
		use WithPagination;

		public $warehouse_order, $row;
		public $selectAll = false;
		public $serials_checked = [];

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
			// Riduco materiale ubicazione pickup
			if($this->row->pickup->products()->where('product_id', $this->row->product_id)->exists()) {
				$this->row->pickup->products()->where('product_id', $this->row->product_id)->first()->pivot->decrement('quantity', count($this->serials_checked));
			} else {
				$this->dispatchBrowserEvent('open-notification', [
					'title' => __('Errore'),
					'subtitle' => __("Nella location {$this->row->pickup->code} non c'è il prodotto da spedire!"),
					'type' => 'error'
				]);
			}

			// Imposto ogni matricola selezionata in "spedita"
			foreach ($this->serials_checked as $s) {
				$serial = Serial::find($s);

				$serial->update([
					'shipped' => true,
					'shipped_at' => now()
				]);
			}

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
