<?php

	namespace App\Http\Livewire\Pages\Products;

	use App\Models\Product;
	use LivewireUI\Modal\ModalComponent;

	class Create extends ModalComponent
	{
		public $code, $name, $description, $units;

		protected function rules() {
			return [
				'code'        => 'required|unique:products,code',
				'name'        => 'required',
				'description' => 'nullable',
				'units'       => 'required',
			];
		}

		public function save() {
			$this->validate();
			$product = Product::create([
				'code'        => $this->code,
				'name'        => $this->name,
				'description' => $this->description,
				'units'       => $this->units,
			]);
			$this->emitTo('pages.products.index', 'product-created');
			$this->closeModal();
			$product->logs()->create([
				'user_id' => auth()->id(),
				'message' => "ha creato il prodotto '{$product->name}'"
			]);
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
