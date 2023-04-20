<?php

	namespace App\Http\Livewire\Pages\Users;

	use App\Models\User;
	use LivewireUI\Modal\ModalComponent;

	class Edit extends ModalComponent
	{
		public $user;
		protected $rules = [
			'user.first_name' => 'required',
			'user.last_name'  => 'required',
			'user.phone'      => 'required',
			'user.email'      => 'required',
		];

		public function mount(User $user) {
			$this->user = $user;
		}

		public function save() {
			$this->validate();
			$this->user->update();
			$this->emitTo('pages.users.index', 'user-updated');
			$this->closeModal();
			$this->dispatchBrowserEvent('open-notification', [
				'title'    => __('Utente Aggiornato'),
				'subtitle' => __('L\'utente è stato aggiornato con successo!'),
				'type'     => 'success'
			]);
		}

		public function render() {
			return view('livewire.pages.users.edit');
		}
	}
