<form wire:submit.prevent="save" class="bg-white shadow sm:rounded-lg">
	<div class="px-4 py-6 sm:px-6">
		<h3 class="text-base font-semibold leading-7 text-gray-900">
			Trasferimento interno
		</h3>
		@error('too_many_quantity_to_transfer')
		<x-input-error :messages="$errors->get('too_many_quantity_to_transfer')"></x-input-error>
		@enderror
	</div>
	<div class="border-t border-gray-100">
		<dl class="divide-y divide-gray-100">
			<div class="px-4 py-6 sm:grid items-center sm:grid-cols-3 sm:gap-4 sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Ubicazione di partenza</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
					<div class="flex items-center w-full space-x-3">
						<div class="flex-1">
							<livewire:components.select event="startLocationSelected" return="id"
														model="App\\Models\\Location" title="code" subtitle="type"/>
						</div>
					</div>
				</dd>
			</div>
			<div class="px-4 py-6 sm:grid items-center sm:grid-cols-3 sm:gap-4 sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Prodotto</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
					<div class="flex items-center w-full space-x-3">
						<div class="flex-1">
							<livewire:components.select return="id" model="App\\Models\\Product"
														title="description" subtitle="code"/>
						</div>
					</div>
				</dd>
			</div>
			<div class="px-4 py-6 sm:grid items-center sm:grid-cols-3 sm:gap-4 sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Quantità</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
					<div class="flex items-center w-full space-x-3">
						<div class="flex-1">
							<x-input type="number" wire:model.defer="quantity"></x-input>
						</div>
					</div>
				</dd>
			</div>
			<div class="px-4 py-6 sm:grid items-center sm:grid-cols-3 sm:gap-4 sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Ubicazione di destinazione</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
					<div class="flex items-center w-full space-x-3">
						<div class="flex-1">
							<livewire:components.select event="endLocationSelected" return="id"
														model="App\\Models\\Location" title="code" subtitle="type"/>
						</div>
					</div>
				</dd>
			</div>
		</dl>
	</div>
	<div class="py-4 px-4 flex justify-end space-x-3 sm:px-6">
		<x-secondary-button type="button" wire:click="$emit('closeModal')">Annulla</x-secondary-button>
		<x-primary-button :disabled="!$startLocation || !$endLocation || !$product || !$quantity">Trasferisci</x-primary-button>
	</div>
</form>
