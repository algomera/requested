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
			$item->products()->detach();
			$this->emitSelf('$refresh');
			$item->logs()->create([
				'user_id' => auth()->id(),
				'message' => "ha eliminato l'articolo '{$item->product->description}'"
			]);
			$this->dispatchBrowserEvent('open-notification', [
				'title'    => __('Articolo Eliminato'),
				'subtitle' => __('L\' articolo è stato eliminato con successo!'),
				'type'     => 'success'
			]);
		}

		public function render() {
			return view('livewire.pages.items.index', [
				'items' => Item::search($this->search, [
					'product.code',
					'product.description'
				])->paginate(25)
			]);
		}
	}
