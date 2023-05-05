<?php

	namespace App\Http\Livewire\Pages\Destinations;

	use App\Models\Destination;
	use LivewireUI\Modal\ModalComponent;

	class Edit extends ModalComponent
	{
		public $destination;

		protected function rules() {
			return [
				'destination.name'    => 'required',
				'destination.address' => 'required',
			];
		}

		public function mount(Destination $destination) {
			$this->destination = $destination;
		}

		public function save() {
			$this->validate();
			$this->destination->update();
			$this->emitTo('pages.destinations.index', 'destination-updated');
			$this->closeModal();
			$this->dispatchBrowserEvent('open-notification', [
				'title'    => __('Destinazione Aggiornata'),
				'subtitle' => __('La destinazione è stata aggiornata con successo!'),
				'type'     => 'success'
			]);
		}

		public function render() {
			return view('livewire.pages.destinations.edit');
		}
	}
