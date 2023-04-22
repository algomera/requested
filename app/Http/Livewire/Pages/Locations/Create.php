<?php

	namespace App\Http\Livewire\Pages\Locations;

	use App\Models\Location;
	use LivewireUI\Modal\ModalComponent;

	class Create extends ModalComponent
	{
		public $code, $description, $type;

		protected function rules() {
			return [
				'code'        => 'required|unique:locations,code',
				'description' => 'required|unique:locations,description',
				'type'        => 'required|in:' . implode(',', array_keys(config('requested.locations.types'))),
			];
		}

		public function save() {
			$this->validate();
			Location::create([
				'code'        => $this->code,
				'description' => $this->description,
				'type'        => $this->type
			]);
			$this->emitTo('pages.locations.index', 'location-created');
			$this->closeModal();
			$this->dispatchBrowserEvent('open-notification', [
				'title'    => __('Ubicazione Creata'),
				'subtitle' => __('L\'ubicazione è stata creata con successo!'),
				'type'     => 'success'
			]);
		}

		public function render() {
			return view('livewire.pages.locations.create');
		}
	}
