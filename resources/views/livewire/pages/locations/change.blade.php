<form wire:submit.prevent="save" class="overflow-hidden bg-white shadow sm:rounded-lg">
	<div class="px-4 py-6 sm:px-6">
		<h3 class="text-base font-semibold leading-7 text-gray-900">
			Trasferimento interno da: {{ $current_location->code }}
		</h3>
		<p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">
			Prodotti attualmente presenti: {{ $quantity_in_location }}
		</p>
		@error('too_many_quantity_to_transfer')
		<x-input-error :messages="$errors->get('too_many_quantity_to_transfer')"></x-input-error>
		@enderror
	</div>
	<div class="border-t border-gray-100">
		<dl class="divide-y divide-gray-100">
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
				<dt class="text-sm font-medium text-gray-900">
					<p class="block">Destinazione</p>
					<span wire:click="addLocation"
					      class="text-xs font-medium text-indigo-500 hover:text-indigo-800 hover:cursor-pointer">Aggiungi</span>
				</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
					<ul role="list" class="divide-y divide-gray-200">
						@foreach($locations as $k => $location)
							<li class="{{ $loop->iteration === 1 ? 'pt-0' : '' }} py-3 flex items-center justify-between">
								<div class="flex items-center w-full space-x-3">
									<div class="flex-1">
										<x-select wire:model="locations.{{$k}}.id">
											<option value="" selected>Seleziona</option>
											@foreach($all_locations as $loc)
												<option {{ in_array($loc->id, array_column($locations, 'id')) ? 'disabled' : '' }} value="{{ $loc->id }}">{{ $loc->code }}</option>
											@endforeach
										</x-select>
									</div>
									<div class="w-[120px]">
										<x-input wire:model="locations.{{$k}}.quantity" type="number" step="1" min="1"/>
									</div>
									<div wire:click="removeLocation({{$k}})"
									     class="p-2 rounded-md hover:bg-red-100 hover:cursor-pointer @error('locations.' . $k . '.quantity') transform -translate-y-[0.6rem] @enderror">
										<x-heroicon-o-trash class="w-4 h-4 text-red-500"></x-heroicon-o-trash>
									</div>
								</div>
							</li>
						@endforeach
					</ul>
				</dd>
			</div>
		</dl>
	</div>
	<div class="py-4 px-4 flex justify-end space-x-3 sm:px-6">
		<x-secondary-button type="button" wire:click="$emit('closeModal')">Annulla</x-secondary-button>
		<x-primary-button :disabled="!count($locations)">Trasferisci</x-primary-button>
	</div>
</form>