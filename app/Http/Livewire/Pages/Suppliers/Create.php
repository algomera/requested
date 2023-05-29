<?php

	namespace App\Http\Livewire\Pages\Suppliers;

	use App\Models\Location;
	use LivewireUI\Modal\ModalComponent;

	class Create extends ModalComponent
	{
		public $code, $description;

		protected function rules() {
			return [
				'code'    => 'required',
				'description' => 'required',
			];
		}

		public function save() {
			Location::create([
				'code'    => $this->code,
				'description' => $this->description,
				'type' => 'fornitore'
			]);
			$this->emitTo('pages.suppliers.index', 'location-created');
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
