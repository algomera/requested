<?php

	namespace App\Http\Livewire;

	use Livewire\Component;
	use Spatie\Browsershot\Browsershot;

	class Ddt extends Component
	{
		public $ddt;

		public function mount(\App\Models\Ddt $ddt)
		{
			$this->ddt = $ddt;
		}

//		public function print()
//		{
//			$path = storage_path($this->ddt->id . '.pdf');
//			Browsershot::url(route('ddt.show', $this->ddt->id))
//				->authenticate('admin@example.test', 'password')
//				->format('A4')
//				->margins(1, 1, 1, 1, 'mm')
//				->savePdf($path);
//		}

		public function render()
		{
			return view('livewire.ddt', [
				'serials' => $this->ddt->serials,
				'products' => $this->ddt->products
			])->layout('layouts.blank');
		}
	}
