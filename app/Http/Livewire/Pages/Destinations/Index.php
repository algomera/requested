<?php

	namespace App\Http\Livewire\Pages\Destinations;

	use App\Models\Destination;
	use Livewire\Component;
	use Livewire\WithPagination;

	class Index extends Component
	{
		use WithPagination;

		public $search = '';
		public $deletingId = null;
		protected $listeners = [
			'destination-updated'    => '$refresh',
			'destination-created'    => '$refresh',
		];

		public function updatingSearch() {
			$this->resetPage();
		}

		public function delete(Destination $destination) {
			$destination->delete();
			$this->emitSelf('$refresh');
			$this->dispatchBrowserEvent('open-notification', [
				'title'    => __('Destinazione Eliminata'),
				'subtitle' => __('La destinazione è stata eliminata con successo!'),
				'type'     => 'success'
			]);
		}

		public function render() {
			return view('livewire.pages.destinations.index', [
				'destinations' => Destination::search($this->search, [
					'name',
					'address',
				])->paginate(25)
			]);
		}
	}
