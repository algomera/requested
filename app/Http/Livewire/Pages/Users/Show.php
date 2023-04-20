<?php

	namespace App\Http\Livewire\Pages\Users;

	use App\Models\User;
	use LivewireUI\Modal\ModalComponent;

	class Show extends ModalComponent
	{
		public $user;

		public function mount(User $user) {
			$this->user = $user;
		}

		public function render() {
			return view('livewire.pages.users.show');
		}
	}
