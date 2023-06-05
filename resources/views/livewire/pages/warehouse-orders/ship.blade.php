<form wire:submit.prevent="save" class="overflow-hidden bg-white shadow sm:rounded-lg">
	<div class="px-4 py-6 sm:px-6">
		<h3 class="text-base font-semibold leading-7 text-gray-900">Spedisci</h3>
		<p>Da: '{{ $row->pickup->code }}' a '{{ $warehouse_order->destination->code }}'</p>
	</div>
	<div class="border-t border-gray-100">
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
						@forelse($unshipped_serials as $unshipped)
							<tr class="hover:bg-gray-50" wire:key="{{ $unshipped->id }}">
								<td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
									<input wire:model="serials_checked" type="checkbox"
										   value="{{ $unshipped->id }}"
										   class="h-4 w-4 rounded border-gray-300 text-gray-600 focus:ring-gray-600">
								</td>
								<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $unshipped->code }}</td>
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
						{{ $unshipped_serials->links() }}
					</div>
				</dd>
			</div>
		</dl>
	</div>
	<div class="py-4 px-4 flex justify-end space-x-3 sm:px-6">
		<x-secondary-button type="button" wire:click="$emit('closeModal')">Annulla</x-secondary-button>
		<x-primary-button :disabled="count($serials_checked) === 0">Spedisci</x-primary-button>
	</div>
</form>
