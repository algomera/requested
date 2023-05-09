<?php

	namespace App\Http\Livewire\Components;

	use LivewireUI\Modal\ModalComponent;

	class Logs extends ModalComponent
	{
		public $logs;

		public function mount($model, $id) {
			$object = $model::find($id);
			$this->logs = $object->logs()->with('user')->latest()->orderBy('id', 'desc')->get();
		}

		public function render() {
			return view('livewire.components.logs');
		}
	}
