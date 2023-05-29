<?php

	namespace App\Http\Livewire\Pages\Destinations;

	use App\Models\Location;
	use LivewireUI\Modal\ModalComponent;

	class Edit extends ModalComponent
	{
		public $location;

		protected function rules() {
			return [
				'location.code'    => 'required',
				'location.description' => 'required',
			];
		}

		public function mount(Location $location) {
			$this->location = $location;
		}

		public function save() {
			$this->validate();
			$this->location->update();
			$this->emitTo('pages.destinations.index', 'location-updated');
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
