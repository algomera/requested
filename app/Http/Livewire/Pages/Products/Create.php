<?php

	namespace App\Http\Livewire\Pages\Products;

	use App\Models\Product;
	use App\Models\Unit;
	use LivewireUI\Modal\ModalComponent;

	class Create extends ModalComponent
	{
		public $code, $description, $unit_id, $serial_management;

		protected function rules()
		{
			return [
				'code' => 'required|unique:products,code',
				'description' => 'required',
				'unit_id' => 'required|exists:units,id',
				'serial_management' => 'boolean'
			];
		}

		public function save()
		{
			$this->validate();
			$product = Product::create([
				'code' => $this->code,
				'description' => $this->description,
				'unit_id' => $this->unit_id,
				'serial_management' => $this->serial_management ?? false,
			]);
			$this->emitTo('pages.products.index', 'product-created');
			$this->closeModal();
			$product->logs()->create([
				'user_id' => auth()->id(),
				'message' => "ha creato il prodotto '{$product->description}'"
			]);
			$this->dispatchBrowserEvent('open-notification', [
				'title' => __('Prodotto Creato'),
				'subtitle' => __('Il prodotto è stato creato con successo!'),
				'type' => 'success'
			]);
		}

		public function render()
		{
			return view('livewire.pages.products.create', [
				'units' => Unit::all()
			]);
		}
	}
