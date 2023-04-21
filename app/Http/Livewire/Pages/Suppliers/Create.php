<?php

	namespace App\Http\Livewire\Pages\Suppliers;

	use App\Models\Supplier;
	use LivewireUI\Modal\ModalComponent;

	class Create extends ModalComponent
	{
		public $code, $name;
		protected $rules = [
			'code' => 'required',
			'name' => 'required',
		];

		public function save() {
			$this->validate();
			Supplier::create([
				'code' => $this->code,
				'name'  => $this->name,
			]);
			$this->emitTo('pages.suppliers.index', 'supplier-created');
			$this->closeModal();
			$this->dispatchBrowserEvent('open-notification', [
				'title'    => __('Fornitore Creato'),
				'subtitle' => __('Il fornitore è stato creato con successo!'),
				'type'     => 'success'
			]);
		}

		public function render() {
			return view('livewire.pages.suppliers.create');
		}
	}
