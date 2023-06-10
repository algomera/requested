<form wire:submit.prevent="save" class="bg-white shadow sm:rounded-lg">
	<div class="px-4 py-6 sm:px-6">
		<h3 class="text-base font-semibold leading-7 text-gray-900">Nuovo Ordine di Trasferimento</h3>
	</div>
	<div class="border-t border-gray-100">
		<dl class="divide-y divide-gray-100">
			<div class="px-4 py-6 grid grid-cols-1 gap-4 sm:px-6">
				<dt class="flex items-center justify-between text-sm font-medium text-gray-900">
					<span>Articoli</span>
					<span wire:click="addProduct"
						  class="text-xs font-medium text-indigo-500 hover:text-indigo-800 hover:cursor-pointer">Aggiungi</span>
				</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:mt-0">
					<x-laravel-blade-sortable::sortable
						as="ul" wire:onSortOrderChange="sortItemProductsOrder"
						class="divide-y divide-gray-200">
						@foreach($products as $k => $product)
							<x-laravel-blade-sortable::sortable-item
								as="li" sort-key="{{ $k }}" wire:key="{{$product['uuid']}}"
								class="{{ $loop->iteration === 1 ? 'pt-0' : '' }} bg-white py-3 flex items-center justify-between">
								<div class="bg-white flex flex-1 items-center justify-between py-3">
									<div class="flex flex-col items-center space-y-3 pr-4 text-gray-400">
										<div class="p-2 rounded-md hover:bg-gray-100 hover:cursor-pointer">
											<x-heroicon-o-bars-2 class="w-4 h-4 text-gray-400"></x-heroicon-o-bars-2>
										</div>
									</div>
									<div class="flex items-center w-full space-x-3">
										<div class="flex-1 grid grid-cols-4 gap-x-4 gap-y-2">
											<div class="w-full flex-1 col-span-4 lg:col-span-1">
												<livewire:components.select label="Ubicazione di Prelievo" wire:key="pickup-{{$k}}-{{$product['uuid']}}" return="id" :items="App\Models\Location::whereNotIn('type', ['fornitore', 'destinazione'])->get()" title="code"
																			subtitle="description" selected="{{$product['pickup_id']}}" event="setPickupToItem" to="{{ $k }}"/>
												@error('products.'. $k .'.pickup_id')
												<span class="text-sm text-red-600 space-y-1">{{ $message }}</span>
												@enderror
											</div>
											<div class="w-full flex-1 col-span-4 lg:col-span-1">
												<livewire:components.select label="Articolo" wire:key="item-{{$k}}-{{$product['uuid']}}" return="id" :items="App\Models\Product::all()" title="description"
																			subtitle="code" selected="{{$product['id']}}" event="setProductToItem" to="{{ $k }}"/>
												@error('products.'. $k .'.id')
												<span class="text-sm text-red-600 space-y-1">{{ $message }}</span>
												@enderror
											</div>
											<div class="w-full col-span-4 lg:col-span-1">
												<x-input label="Quantità" wire:model="products.{{$k}}.quantity" type="number" step="{{ $ref ? $ref->decimalSteps() : 1 }}"
														 min="1" />
											</div>
											<div class="w-full flex-1 col-span-4 lg:col-span-1">
												<livewire:components.select label="Ubicazione di Destinazione" wire:key="destination-{{$k}}-{{$product['uuid']}}" return="id" :items="App\Models\Location::whereNotIn('type', ['fornitore', 'destinazione'])->get()" title="code"
																			subtitle="description" selected="{{$product['destination_id']}}" event="setDestinationToItem" to="{{ $k }}"/>
												@error('products.'. $k .'.destination_id')
												<span class="text-sm text-red-600 space-y-1">{{ $message }}</span>
												@enderror
											</div>
										</div>
										<div wire:click="removeProduct({{$k}})"
											 class="p-2 rounded-md hover:bg-red-100 hover:cursor-pointer @error('products.' . $k . '.quantity') transform -translate-y-[0.6rem] @enderror">
											<x-heroicon-o-trash class="w-4 h-4 text-red-500"></x-heroicon-o-trash>
										</div>
									</div>
								</div>
							</x-laravel-blade-sortable::sortable-item>
						@endforeach
					</x-laravel-blade-sortable::sortable>
				</dd>
			</div>
		</dl>
	</div>
	<div class="py-4 px-4 flex justify-end space-x-3 sm:px-6">
		<x-secondary-button type="button" wire:click="$emit('closeModal')">Annulla</x-secondary-button>
		<x-primary-button :disabled="count($products) == 0">Salva</x-primary-button>
	</div>
</form>
