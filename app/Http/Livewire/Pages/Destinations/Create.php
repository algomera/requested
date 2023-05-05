<?php

	namespace App\Http\Livewire\Pages\Destinations;

	use App\Models\Destination;
	use App\Models\Location;
	use LivewireUI\Modal\ModalComponent;

	class Create extends ModalComponent
	{
		public $name, $address;

		protected function rules() {
			return [
				'name'    => 'required',
				'address' => 'required',
			];
		}

		public function save() {
			Destination::create([
				'name'    => $this->name,
				'address' => $this->address,
			]);
			$this->emitTo('pages.destinations.index', 'destination-created');
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
