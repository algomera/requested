<?php

	namespace App\Http\Livewire\Pages\Locations;

	use App\Models\Location;
	use LivewireUI\Modal\ModalComponent;

	class Create extends ModalComponent
	{
		public $code, $description, $type, $out_priority;

		protected function rules()
		{
			return [
				'code' => 'required|unique:locations,code',
				'description' => 'required|unique:locations,description',
				'type' => 'required|in:' . implode(',', array_keys(config('requested.locations.types'))),
				'out_priority' => 'nullable|numeric|min:0'
			];
		}

		public function save()
		{
			$this->validate();
			$location = Location::create([
				'code' => $this->code,
				'description' => $this->description,
				'type' => $this->type,
				'out_priority' => $this->out_priority ?? 99999,
			]);
			$this->emitTo('pages.locations.index', 'location-created');
			$this->closeModal();
			$location->logs()->create([
				'user_id' => auth()->id(),
				'message' => "ha creato l'ubicazione '{$location->code}' (tipologia: " . config('requested.locations.types.' . $location->type . '.label') . ")"
			]);
			$this->dispatchBrowserEvent('open-notification', [
				'title' => __('Ubicazione Creata'),
				'subtitle' => __('L\'ubicazione è stata creata con successo!'),
				'type' => 'success'
			]);
		}

		public function render()
		{
			return view('livewire.pages.locations.create');
		}
	}
