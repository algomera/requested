<form wire:submit.prevent="save" class="bg-white shadow sm:rounded-lg">
    <div class="px-4 py-6 sm:px-6">
        <h3 class="text-base font-semibold leading-7 text-gray-900">
            Aggiungi prodotto in: {{ $location->code }}
        </h3>
    </div>
    <div class="border-t border-gray-100">
        <dl class="divide-y divide-gray-100">
            <div class="px-4 py-6 grid grid-cols-1 gap-4 sm:px-6">
	            <div class="px-4 py-6 grid grid-cols-1 gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-900">Prodotto</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                        <ul role="list" class="divide-y divide-gray-200">
	                        <li class="py-3 flex items-center justify-between">
		                        <div class="flex items-center w-full space-x-3">
			                        <div class="flex-1">
										<livewire:components.select return="id" :items="\App\Models\Product::all()" title="description" subtitle="code" />
			                        </div>
			                        <div class="w-[120px]">
				                        <x-input wire:model="quantity" type="number" step="1" min="1"/>
			                        </div>
		                        </div>
	                        </li>
                        </ul>
                    </dd>
                </div>
            </div>
        </dl>
    </div>
    <div class="py-4 px-4 flex justify-end space-x-3 sm:px-6">
        <x-secondary-button type="button" wire:click="$emit('closeModal')">Annulla</x-secondary-button>
        <x-primary-button :disabled="!$product">Aggiungi</x-primary-button>
    </div>
</form>
