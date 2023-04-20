<?php

	namespace App\Http\Livewire\Pages\Users;

	use App\Models\User;
	use Livewire\Component;
	use Livewire\WithPagination;

	class Index extends Component
	{
		use WithPagination;

		public $search = '';
		public $deletingId = null;
		protected $listeners = [
			'user-updated' => '$refresh'
		];

		public function updatingSearch() {
			$this->resetPage();
		}

		public function delete(User $user) {
			$user->delete();
			$this->emitSelf('$refresh');
			$this->dispatchBrowserEvent('open-notification', [
				'title'    => __('Utente Eliminato'),
				'subtitle' => __('L\'utente è stato eliminato con successo!'),
				'type'     => 'success'
			]);
		}

		public function render() {
			return view('livewire.pages.users.index', [
				'users' => User::whereNot('id', auth()->id())->search($this->search, [
					'first_name',
					'last_name',
					'email'
				])->paginate(25)
			]);
		}
	}
