<?php

	namespace App\Http\Livewire\Pages\Products;

	use App\Models\Product;
	use LivewireUI\Modal\ModalComponent;

	class Create extends ModalComponent
	{
		public $code, $name, $description, $units, $quantity;

		protected function rules() {
			return [
				'code'        => 'required|unique:products,code',
				'name'        => 'required',
				'description' => 'required',
				'units'       => 'required',
				'quantity'    => 'required'
			];
		}

		public function save() {
			$this->validate();
			Product::create([
				'code'        => $this->code,
				'name'        => $this->name,
				'description' => $this->description,
				'units'       => $this->units,
				'quantity'    => $this->quantity,
			]);
			$this->emitTo('pages.products.index', 'product-created');
			$this->closeModal();
			$this->dispatchBrowserEvent('open-notification', [
				'title'    => __('Prodotto Creato'),
				'subtitle' => __('Il prodotto è stato creato con successo!'),
				'type'     => 'success'
			]);
		}

		public function render() {
			return view('livewire.pages.products.create');
		}
	}
