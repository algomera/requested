<form wire:submit.prevent="save" class="overflow-hidden bg-white shadow sm:rounded-lg">
	<div class="px-4 py-6 sm:px-6">
		<h3 class="text-base font-semibold leading-7 text-gray-900">Trasferisci</h3>
		<p class="text-xs">Ordine: '{{ $warehouse_order->production_order->code ?? $warehouse_order->code ?? '-' }}'</p>
		<p class="text-xs">Articolo: {{ $row->product->code }} - {{ $row->product->description }}</p>
		<p class="text-xs">Da: '{{ $row->pickup->code }}' a '{{ $row->destination->code }}'</p>
		<p class="text-xs font-semibold">Quantità da trasferire: {{ $row->quantity_total - $row->quantity_processed }}</p>
	</div>
	<div class="border-t border-gray-100">
		<dl class="divide-y divide-gray-100">
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Quantità</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
					<x-input wire:model.defer="quantity" min="0" type="number" step="{{ $row->product->decimalSteps() }}" hint="Giacenza attuale: {{ $row->pickup->productQuantity($row->product_id)}}"></x-input>
				</dd>
			</div>
		</dl>
	</div>
	<div class="py-4 px-4 flex justify-end space-x-3 sm:px-6">
		<x-secondary-button type="button" wire:click="$emit('closeModal')">Annulla</x-secondary-button>
		<x-primary-button>Salva</x-primary-button>
	</div>
</form>
