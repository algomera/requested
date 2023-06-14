<?php

	namespace App\Http\Livewire;

	use App\Models\Product;
	use Illuminate\Database\Eloquent\Collection;
	use Livewire\Component;
	use Spatie\Browsershot\Browsershot;

	class Ddt extends Component
	{
		public $ddt;

		public function mount(\App\Models\Ddt $ddt)
		{
			$this->ddt = $ddt;
		}

		public function render()
		{
			$serials = $this->ddt->serials()->with('product.unit')->get();
			$products = $this->ddt->products()->with('unit')->get();
			$items = $products->concat($serials);
			return view('livewire.ddt', [
				'serials_count' => $serials->count(),
				'items' => $items->chunk(19),
			])->layout('layouts.blank');
		}
	}
