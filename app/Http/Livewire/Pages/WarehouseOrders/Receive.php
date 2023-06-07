<?php

	namespace App\Http\Livewire\Pages\WarehouseOrders;

	use App\Models\Serial;
	use App\Models\WarehouseOrder;
	use App\Models\WarehouseOrderRow;
	use Illuminate\Support\Facades\DB;
	use Livewire\WithPagination;
	use LivewireUI\Modal\ModalComponent;

	class Receive extends ModalComponent
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
				$this->serials_checked = $this->warehouse_order->serials()->where('received', 0)->pluck('id')->toArray();
			} else {
				$this->serials_checked = [];
			}
		}

		public function save()
		{
			$this->validate();

			// Imposto ogni matricola selezionata in "ricevuta"
			foreach ($this->serials_checked as $s) {
				$serial = Serial::find($s);

				$serial->update([
					'received' => true,
					'received_at' => now()
				]);
			}

			if ($this->row->product->serial_management) {
				// Se matricolare
				// Trasferisco materiale in ubicazione destination
				$check = $this->warehouse_order->destination->products()->where('product_id', $this->row->product->id)->first();
				if ($check) {
					$this->warehouse_order->destination->products()->syncWithoutDetaching([
						$this->row->product_id => [
							'quantity' => $check->pivot->quantity + count($this->serials_checked)
						]
					]);
				} else {
					$this->warehouse_order->destination->products()->syncWithoutDetaching([
						$this->row->product_id => [
							'quantity' => count($this->serials_checked)
						]
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
			} else {
				// Se non matricolare
				// Trasferisco materiale in ubicazione destination
				$check = $this->warehouse_order->destination->products()->where('product_id', $this->row->product->id)->first();
				if ($check) {
					$this->warehouse_order->destination->products()->syncWithoutDetaching([
						$this->row->product_id => [
							'quantity' => $check->pivot->quantity + $this->quantity
						]
					]);
				} else {
					$this->warehouse_order->destination->products()->syncWithoutDetaching([
						$this->row->product_id => [
							'quantity' => $this->quantity
						]
					]);
				}
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
				'title' => __('Ricezione Effettuata'),
				'subtitle' => __('La ricezione è stata completata con successo!'),
				'type' => 'success'
			]);
		}

		public function render()
		{
			return view('livewire.pages.warehouse-orders.receive', [
				'unreceived_serials' => $this->warehouse_order->serials()->where('received', 0)->paginate(25),
			]);
		}
	}
