<?php

	namespace App\Http\Livewire\Pages\Suppliers;

	use App\Models\Supplier;
	use LivewireUI\Modal\ModalComponent;

	class Edit extends ModalComponent
	{
		public $supplier;

		protected function rules() {
			return [
				'supplier.code' => 'required',
				'supplier.name'  => 'required',
			];
		}

		public function mount(Supplier $supplier) {
			$this->supplier = $supplier;
		}

		public function save() {
			$this->validate();
			$this->supplier->update();
			$this->emitTo('pages.suppliers.index', 'supplier-updated');
			$this->closeModal();
			$this->dispatchBrowserEvent('open-notification', [
				'title'    => __('Fornitore Aggiornato'),
				'subtitle' => __('Il fornitore è stato aggiornato con successo!'),
				'type'     => 'success'
			]);
		}

		public function render() {
			return view('livewire.pages.suppliers.edit');
		}
	}
