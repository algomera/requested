<?php

	namespace App\Http\Livewire\Pages\Locations;

	use App\Models\Location;
	use LivewireUI\Modal\ModalComponent;

	class Edit extends ModalComponent
	{
		public $location;

		protected function rules() {
			return [
				'location.code'        => 'required|unique:locations,code,' . $this->location->id,
				'location.description' => 'required|unique:locations,description,' . $this->location->id,
				'location.type'        => 'required|in:' . implode(',', array_keys(config('requested.locations.types'))),
				'location.out_priority' => 'nullable|min:0'
			];
		}

		public function mount(Location $location) {
			$this->location = $location;
		}

		public function save() {
			$this->validate();
			$this->location->update();
			$this->emitTo('pages.locations.index', 'location-updated');
			$this->closeModal();
			$this->dispatchBrowserEvent('open-notification', [
				'title'    => __('Ubicazione Aggiornata'),
				'subtitle' => __('L\'ubicazione è stata aggiornata con successo!'),
				'type'     => 'success'
			]);
		}

		public function render() {
			return view('livewire.pages.locations.edit');
		}
	}
