<form wire:submit.prevent="save" class="overflow-hidden bg-white shadow sm:rounded-lg">
    <div class="px-4 py-6 sm:px-6">
        <h3 class="text-base font-semibold leading-7 text-gray-900">
            Modifica prodotto in: {{ $location->code }}
        </h3>
		<p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">
			Quantità attualmente presente: <span class="text-zinc-700 font-semibold">{{ $old_quantity }} {{ config('requested.products.units.' . $product->units) }}</span>
		</p>
    </div>
    <div class="border-t border-gray-100">
        <dl class="divide-y divide-gray-100">
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Nuova quantità</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
					<div class="w-[120px]">
						<x-input wire:model="quantity" type="number" step="1" min="0"/>
					</div>
				</dd>
			</div>
        </dl>
    </div>
    <div class="py-4 px-4 flex justify-end space-x-3 sm:px-6">
        <x-secondary-button type="button" wire:click="$emit('closeModal')">Annulla</x-secondary-button>
        <x-primary-button :disabled="!$product">Aggiungi</x-primary-button>
    </div>
</form>
