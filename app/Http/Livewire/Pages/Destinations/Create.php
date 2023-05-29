<?php

	namespace App\Http\Livewire\Pages\Destinations;

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
				'type' => 'destinazione'
			]);
			$this->emitTo('pages.destinations.index', 'location-created');
			$this->closeModal();
			$this->dispatchBrowserEvent('open-notification', [
				'title'    => __('Destinazione Creata'),
				'subtitle' => __('La destinazione è stata creata con successo!'),
				'type'     => 'success'
			]);
		}

		public function render() {
			return view('livewire.pages.destinations.create');
		}
	}
