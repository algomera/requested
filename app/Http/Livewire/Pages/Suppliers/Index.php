<?php

	namespace App\Http\Livewire\Pages\Suppliers;

	use App\Models\Location;
	use Livewire\Component;
	use Livewire\WithPagination;

	class Index extends Component
	{
		use WithPagination;

		public $search = '';
		public $deletingId = null;
		protected $listeners = [
			'location-updated' => '$refresh',
			'location-created' => '$refresh',
			'product-transferred' => '$refresh',
			'product-added' => '$refresh',
			'product-updated' => '$refresh',
		];

		public function updatingSearch()
		{
			$this->resetPage();
		}

		public function delete(Location $location)
		{
			if ($location->products()->count()) {
				$location->logs()->create([
					'user_id' => auth()->id(),
					'message' => "ha provato ad eliminare l'ubicazione '{$location->code}', ma non è stato possibile perché contiene dei prodotti al suo interno"
				]);
				$this->dispatchBrowserEvent('open-notification', [
					'title' => __('Errore'),
					'subtitle' => __('L\'ubicazione non può essere cancellata perché contiene dei prodotti'),
					'type' => 'error'
				]);
				return false;
			}
			$location->delete();
			$this->emitSelf('$refresh');
			$location->logs()->create([
				'user_id' => auth()->id(),
				'message' => "ha eliminato l'ubicazione '{$location->code}'"
			]);
			$this->dispatchBrowserEvent('open-notification', [
				'title' => __('Ubicazione Eliminata'),
				'subtitle' => __('L\'ubicazione è stata eliminata con successo!'),
				'type' => 'success'
			]);
		}

		public function render()
		{
			return view('livewire.pages.suppliers.index', [
				'locations' => Location::where('type', 'fornitore')->search($this->search, [
					'code',
					'description',
					'type'
				])->paginate(25)
			]);
		}
	}
