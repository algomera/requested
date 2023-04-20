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

		public function updatingSearch() {
			$this->resetPage();
		}

		public function delete(User $user) {
			$user->delete();
			$this->emitSelf('$refresh');
		}

		public function render() {
			return view('livewire.pages.users.index', [
				'users' => User::search($this->search, [
					'first_name',
					'last_name',
					'email'
				])->paginate(25)
			]);
		}
	}
