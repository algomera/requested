<?php

	namespace App\Http\Livewire\Pages\Items;

	use App\Models\Item;
	use Livewire\Component;
	use Livewire\WithPagination;

	class Index extends Component
	{
		use WithPagination;

		public $search = '';
		public $deletingId = null;
		protected $listeners = [
			'item-updated' => '$refresh',
			'item-created' => '$refresh',
		];

		public function updatingSearch() {
			$this->resetPage();
		}

		public function delete(Item $item) {
			$item->delete();
			$this->emitSelf('$refresh');
			$this->dispatchBrowserEvent('open-notification', [
				'title'    => __('Articolo Eliminato'),
				'subtitle' => __('L\' articolo è stato eliminato con successo!'),
				'type'     => 'success'
			]);
		}

		public function render() {
			return view('livewire.pages.items.index', [
				'items' => Item::search($this->search, [
					'code',
					'name'
				])->paginate(25)
			]);
		}
	}
