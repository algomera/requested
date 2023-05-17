<?php

	namespace App\Http\Livewire\Pages\Products;

	use App\Models\Product;
	use Livewire\Component;
	use Livewire\WithPagination;

	class Index extends Component
	{
		use WithPagination;

		public $search = '';
		public $deletingId = null;

		protected $listeners = [
			'product-updated'    => '$refresh',
			'product-created'    => '$refresh',
		];

		public function updatingSearch() {
			$this->resetPage();
		}

		public function delete(Product $product) {
			$product->delete();
			$this->emitSelf('$refresh');
			$product->logs()->create([
				'user_id' => auth()->id(),
				'message' => "ha eliminato il prodotto '{$product->name}'"
			]);
			$this->dispatchBrowserEvent('open-notification', [
				'title'    => __('Prodotto Eliminato'),
				'subtitle' => __('Il prodotto è stato eliminato con successo!'),
				'type'     => 'success'
			]);
		}

		public function render() {
			return view('livewire.pages.products.index', [
				'products' => Product::search($this->search, [
					'code',
					'name'
				])->paginate(25)
			]);
		}
	}
