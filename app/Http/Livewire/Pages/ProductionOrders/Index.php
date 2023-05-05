<?php

	namespace App\Http\Livewire\Pages\ProductionOrders;

	use App\Models\Location;
	use Livewire\Component;
	use Livewire\WithPagination;

	class Index extends Component
	{
		use WithPagination;

		public $search = '';
		public $deletingId = null;
		protected $listeners = [
			'location-updated'    => '$refresh',
			'location-created'    => '$refresh',
			'product-transferred' => '$refresh'
		];

		public function updatingSearch() {
			$this->resetPage();
		}

		public function delete(Location $location) {
			if ($location->products()->count()) {
				$this->dispatchBrowserEvent('open-notification', [
					'title'    => __('Errore'),
					'subtitle' => __('L\'ubicazione non può essere cancellata perché contiene dei prodotti'),
					'type'     => 'error'
				]);
				return false;
			}
			$location->delete();
			$this->emitSelf('$refresh');
			$this->dispatchBrowserEvent('open-notification', [
				'title'    => __('Ubicazione Eliminata'),
				'subtitle' => __('L\'ubicazione è stata eliminata con successo!'),
				'type'     => 'success'
			]);
		}

		public function render() {
			return view('livewire.pages.production-orders.index', [
				'locations' => Location::search($this->search, [
					'code',
					'description',
					'type'
				])->paginate(25)
			]);
		}
	}
