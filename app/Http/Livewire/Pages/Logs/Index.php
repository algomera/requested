<?php

	namespace App\Http\Livewire\Pages\Logs;

	use App\Models\Item;
	use App\Models\Log;
	use Carbon\Carbon;
	use Livewire\Component;
	use Livewire\WithPagination;

	class Index extends Component
	{
		use WithPagination;

		public $search = '';
		public $selectedDateRange = null;

		public function dateRangeChanged($val) {
			$this->selectedDateRange = $val;
		}

		public function updatingSearch() {
			$this->resetPage();
		}

		public function render() {
			$logs = Log::with('user')->latest()->orderBy('id', 'desc');
			if($this->selectedDateRange) {
				$logs->whereDate('created_at', Carbon::parse($this->selectedDateRange[0])->format('Y-m-d'));
			}
			return view('livewire.pages.logs.index', [
				'logs' => $logs->search($this->search, [
					'user.first_name',
					'user.last_name',
					'message',
				])->paginate(25)
			]);
		}
	}
