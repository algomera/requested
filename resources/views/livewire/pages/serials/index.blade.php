<x-slot:header>
	<div class="flex items-center justify-between">
		Matricole
	</div>
</x-slot:header>
<div>
	<div class="flow-root space-y-5">
		<div class="flex items-center justify-between">
			<div class="flex flex-1 space-x-3 items-center">
				<div class="flex-1 max-w-sm">
					<x-input wire:model.debounce.500ms="search" type="search" placeholder="Cerca.."
							 append="heroicon-o-magnifying-glass" iconColor="text-zinc-500"></x-input>
				</div>
				<div>
					<x-select wire:model="status">
						<option value="0">Non completati</option>
						<option value="1">Completati</option>
					</x-select>
				</div>
			</div>
		</div>
		<div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
			<div class="inline-block min-w-full py-2 align-middle">
				<table class="min-w-full divide-y divide-gray-300">
					<thead>
					<tr>
						<th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 lg:pl-8">Codice</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Ordine di produzione</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Articolo</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Stato</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Data di completamento</th>
{{--						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Data di spedizione</th>--}}
{{--						<th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 lg:pr-8">--}}
{{--							<span class="sr-only">Azioni</span>--}}
{{--						</th>--}}
					</tr>
					</thead>
					<tbody class="divide-y divide-gray-200 bg-white">
					@forelse($serials as $serial)
						<tr class="hover:bg-gray-50" wire:key="{{ $serial->id }}">
							<td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
								{{ $serial->code }}
							</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $serial->production_order->code }}</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $serial->production_order->item->product->description }}</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
								@if($serial->completed)
										<div
											class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
											Completato
										</div>
									@else
									<div
										class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
										Non Completato
									</div>
								@endif
									@if($serial->shipped)
										<div
											class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
											Spedito
										</div>
									@endif
							</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $serial->completed_at ? \Carbon\Carbon::parse($serial->completed_at)->format('d-m-Y') : '-' }}</td>
{{--							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $serial->shipped_at ? \Carbon\Carbon::parse($serial->shipped_at)->format('d-m-Y') : '-' }}</td>--}}
{{--							<td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 lg:pr-8">--}}
{{--								<div class="inline-flex items-center justify-end space-x-3">--}}
{{--									<a href="{{ route('production-orders.show', $serial->id) }}"--}}
{{--									   class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5">--}}
{{--										<x-heroicon-o-eye class="w-4 stroke-zinc-900"/>--}}
{{--									</a>--}}
{{--									@if(!in_array($serial->status, ['active', 'completed']))--}}
{{--										@if($deletingId != $serial->id)--}}
{{--											<button wire:key="deleting-{{ $serial->id }}"--}}
{{--													wire:click.stop="$set('deletingId', '{{ $serial->id }}')"--}}
{{--													type="button"--}}
{{--													class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5">--}}
{{--												<x-heroicon-o-trash class="w-4 stroke-zinc-900"/>--}}
{{--											</button>--}}
{{--										@else--}}
{{--											<button wire:key="confirm-{{ $serial->id }}"--}}
{{--													x-init="setTimeout(() => $wire.deletingId = null, 5000)"--}}
{{--													wire:click.stop="delete({{ $serial->id }})" type="button"--}}
{{--													class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5">--}}
{{--												<x-heroicon-o-question-mark-circle class="w-4 stroke-orange-400"/>--}}
{{--											</button>--}}
{{--										@endif--}}
{{--									@endif--}}
{{--								</div>--}}
{{--							</td>--}}
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
			</div>
		</div>
		<div>
			{{ $serials->links() }}
		</div>
	</div>
</div>
