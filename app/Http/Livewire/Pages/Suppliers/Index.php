<?php

	namespace App\Http\Livewire\Pages\Suppliers;

	use App\Models\Supplier;
	use Livewire\Component;
	use Livewire\WithPagination;

	class Index extends Component
	{
		use WithPagination;

		public $search = '';
		public $deletingId = null;
		protected $listeners = [
			'supplier-updated' => '$refresh',
			'supplier-created' => '$refresh'
		];

		public function updatingSearch() {
			$this->resetPage();
		}

		public function delete(Supplier $supplier) {
			$supplier->delete();
			$this->emitSelf('$refresh');
			$this->dispatchBrowserEvent('open-notification', [
				'title'    => __('Fornitore Eliminato'),
				'subtitle' => __('Il fornitore è stato eliminato con successo!'),
				'type'     => 'success'
			]);
		}

		public function render() {
			return view('livewire.pages.suppliers.index', [
				'suppliers' => Supplier::search($this->search, [
					'code',
					'name',
				])->paginate(25)
			]);
		}
	}
