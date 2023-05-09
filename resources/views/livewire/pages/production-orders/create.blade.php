<form wire:submit.prevent="save" class="overflow-hidden bg-white shadow sm:rounded-lg">
	<div class="px-4 py-6 sm:px-6">
		<h3 class="text-base font-semibold leading-7 text-gray-900">Nuovo ordine di produzione</h3>
	</div>
	<div class="border-t border-gray-100">
		<dl class="divide-y divide-gray-100">
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Codice d'ordine</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
					<x-input wire:model.defer="code" type="text"></x-input>
				</dd>
			</div>
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Articolo da produrre</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
					<x-select wire:model.defer="item_id">
						<option value="" selected>Seleziona</option>
						@foreach($all_items as $item)
							<option value="{{ $item->id }}">{{ $item->product->code }}</option>
						@endforeach
					</x-select>
				</dd>
			</div>
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Quantità da produrre</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
					<x-input wire:model.defer="quantity" type="number" step="1"></x-input>
				</dd>
			</div>
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Data di consegna</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
					<x-input wire:model.defer="delivery_date" type="date"></x-input>
				</dd>
			</div>
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Destinazione</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
					<x-select wire:model.defer="destination_id">
						<option value="" selected>Seleziona</option>
						@foreach($all_destinations as $dest)
							<option value="{{ $dest->id }}">{{ $dest->name }}</option>
						@endforeach
					</x-select>
				</dd>
			</div>
{{--			<div class="px-4 py-6 grid grid-cols-1 gap-4 sm:px-6">--}}
{{--				<dt class="flex items-center justify-between text-sm font-medium text-gray-900">--}}
{{--					<span>Composizione</span>--}}
{{--					<span wire:click="addProduct"--}}
{{--					      class="text-xs font-medium text-indigo-500 hover:text-indigo-800 hover:cursor-pointer">Aggiungi</span>--}}
{{--				</dt>--}}
{{--				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:mt-0">--}}
{{--					<ul role="list" class="divide-y divide-gray-200">--}}
{{--						@foreach($products as $k => $product)--}}
{{--							<li class="{{ $loop->iteration === 1 ? 'pt-0' : '' }} py-3 flex items-center justify-between">--}}
{{--								<div class="flex items-center w-full space-x-3">--}}
{{--									<div class="flex-1">--}}
{{--										<x-select wire:model="products.{{$k}}.id">--}}
{{--											<option value="" selected>Seleziona</option>--}}
{{--											@foreach($all_products as $prod)--}}
{{--												<option {{ in_array($prod->id, array_column($products, 'id')) || $prod->id == $product_id ? 'disabled' : '' }} value="{{ $prod->id }}">{{ $prod->code }} ({{ $prod->units }})</option>--}}
{{--											@endforeach--}}
{{--										</x-select>--}}
{{--									</div>--}}
{{--									<div class="w-[120px]">--}}
{{--										<x-input wire:model="products.{{$k}}.quantity" type="number" step="1" min="1"/>--}}
{{--									</div>--}}
{{--									<div wire:click="removeProduct({{$k}})"--}}
{{--									     class="p-2 rounded-md hover:bg-red-100 hover:cursor-pointer @error('products.' . $k . '.quantity') transform -translate-y-[0.6rem] @enderror">--}}
{{--										<x-heroicon-o-trash class="w-4 h-4 text-red-500"></x-heroicon-o-trash>--}}
{{--									</div>--}}
{{--								</div>--}}
{{--							</li>--}}
{{--						@endforeach--}}
{{--					</ul>--}}
{{--				</dd>--}}
{{--			</div>--}}
		</dl>
	</div>
	<div class="py-4 px-4 flex justify-end space-x-3 sm:px-6">
		<x-secondary-button type="button" wire:click="$emit('closeModal')">Annulla</x-secondary-button>
		<x-primary-button>Continua</x-primary-button>
	</div>
</form>