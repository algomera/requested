<form wire:submit.prevent="save" class="overflow-hidden bg-white shadow sm:rounded-lg">
	<div class="px-4 py-6 sm:px-6">
		<h3 class="text-base font-semibold leading-7 text-gray-900">Ricevi</h3>
		<p class="text-xs">Ordine: '{{ $warehouse_order->production_order->code ?? $warehouse_order->code ?? '-' }}'</p>
		<p class="text-xs">Articolo: {{ $row->product->code }} - {{ $row->product->description }}</p>
		<p class="text-xs">Da: '{{ $row->pickup->code }}' a '{{ $warehouse_order->destination->code }}'</p>
	</div>
	<div class="border-t border-gray-100">
		@if($row->product->serial_management)
			<dl class="divide-y divide-gray-100">
				<div class="px-4 py-6 grid grid-cols-1 sm:gap-4 sm:items-center sm:px-6">
					<dd class="mt-1 text-sm leading-6 text-gray-700">
						<table class="min-w-full divide-y divide-gray-300">
							<thead>
							<tr>
								<th scope="col"
									class="w-[50px] py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 lg:pl-8">
									<input wire:model="selectAll" type="checkbox"
										   class="h-4 w-4 rounded border-gray-300 text-gray-600 focus:ring-gray-600">
								</th>
								<th scope="col"
									class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
									Matricola
								</th>
							</tr>
							</thead>
							<tbody class="divide-y divide-gray-200 bg-white">
							@forelse($unreceived_serials as $unreceived)
								<tr class="hover:bg-gray-50" wire:key="{{ $unreceived->id }}">
									<td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
										<input wire:model="serials_checked" type="checkbox"
											   value="{{ $unreceived->id }}"
											   class="h-4 w-4 rounded border-gray-300 text-gray-600 focus:ring-gray-600">
									</td>
									<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $unreceived->code }}</td>
								</tr>
							@empty
								<tr>
									<td colspan="100%" class="py-4 px-3 text-sm text-center text-zinc-500">
										Nessun elemento trovato
									</td>
								</tr>
							@endforelse
							</tbody>
						</table>
						<div class="mt-4">
							{{ $unreceived_serials->links() }}
						</div>
					</dd>
				</div>
			</dl>
		@else
			<dl class="divide-y divide-gray-100">
				<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center sm:px-6">
					<dt class="text-sm font-medium text-gray-900">Quantità</dt>
					<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
						<x-input wire:model.defer="quantity" min="0" type="number"
								 step="{{ $row->product->decimalSteps() }}"></x-input>
					</dd>
				</div>
			</dl>
		@endif
	</div>
	<div class="py-4 px-4 flex justify-end space-x-3 sm:px-6">
		<x-secondary-button type="button" wire:click="$emit('closeModal')">Annulla</x-secondary-button>
		<x-primary-button :disabled="$row->product->serial_management && count($serials_checked) === 0">Ricevi
		</x-primary-button>
	</div>
</form>
