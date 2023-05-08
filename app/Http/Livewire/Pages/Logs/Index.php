<?php

	namespace App\Http\Livewire\Pages\Logs;

	use App\Models\Item;
	use App\Models\Log;
	use Livewire\Component;
	use Livewire\WithPagination;

	class Index extends Component
	{
		use WithPagination;

		public $search = '';

		public function updatingSearch() {
			$this->resetPage();
		}

		public function render() {
			return view('livewire.pages.logs.index', [
				'logs' => Log::search($this->search, [
					'user.first_name',
					'user.last_name',
					'message',
				])->paginate(25)
			]);
		}
	}
