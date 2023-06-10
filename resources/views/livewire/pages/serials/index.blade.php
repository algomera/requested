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
						<option value="">Tutte</option>
						<option value="0">Non completate</option>
						<option value="1">Completate</option>
						<option value="2">Spedite</option>
						<option value="3">Ricevute</option>
					</x-select>
				</div>
			</div>
		</div>
		<div class="block border-t-2 pt-1 divide-y divide-gray-200 lg:hidden">
			@forelse($serials as $serial)
				<div class="flex items-center justify-between">
					<div class="text-xs py-3 sm:px-0 space-y-0.5">
						<p class="font-bold">Codice: <span
								class="font-light">{{ $serial->code }}</span>
						</p>
						<p class="font-bold">Articolo: <span
								class="font-light">{{ $serial->production_order->product->code ?? $serial->product->code }} - {{ $serial->production_order->product->description ?? $serial->product->description }}</span>
						</p>
						<div class="font-bold inline-flex">
							<span class="mr-2">Stato:</span>
							@if($serial->production_order_id)
								@if($serial->completed)
									<div
										class="inline-flex items-center rounded-md bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
										Completato
									</div>
								@else
									<div
										class="inline-flex items-center rounded-md bg-red-50 px-2 py-0.5 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
										Non Completato
									</div>
								@endif
								@if($serial->shipped)
									<div
										class="inline-flex items-center rounded-md bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
										Spedito
									</div>
								@endif
							@else
								@if($serial->received)
									<div
										class="inline-flex items-center rounded-md bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
										Ricevuto
									</div>
								@else
									<div
										class="inline-flex items-center rounded-md bg-red-50 px-2 py-0.5 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
										Non Ricevuto
									</div>
								@endif
							@endif
						</div>
					</div>
				</div>
			@empty
				<p class="text-center text-sm mt-3 text-zinc-500">Nessun elemento trovato</p>
			@endforelse
		</div>
		<div class="hidden lg:block -mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
			<div class="inline-block min-w-full py-2 align-middle">
				<table class="min-w-full divide-y divide-gray-300">
					<thead>
					<tr>
						<th scope="col"
							class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 lg:pl-8">
							Codice
						</th>
{{--						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Ordine di--}}
{{--							produzione--}}
{{--						</th>--}}
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Articolo</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Descrizione Articolo</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Stato</th>
{{--						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Data di--}}
{{--							completamento--}}
{{--						</th>--}}
{{--						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Data di--}}
{{--							spedizione--}}
{{--						</th>--}}
{{--						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Data di--}}
{{--							ricevimento--}}
{{--						</th>--}}
					</tr>
					</thead>
					<tbody class="divide-y divide-gray-200 bg-white">
					@forelse($serials as $serial)
						<tr class="hover:bg-gray-50" wire:key="{{ $serial->id }}">
							<td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
								{{ $serial->code }}
							</td>
{{--							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $serial->production_order->code ?? '-' }}</td>--}}
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $serial->production_order->product->code ?? $serial->product->code }}</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $serial->production_order->product->description ?? $serial->product->description }}</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
								@if($serial->production_order_id)
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
								@else
									@if($serial->received)
										<div
											class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
											Ricevuto
										</div>
									@else
										<div
											class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
											Non Ricevuto
										</div>
									@endif
								@endif
							</td>
{{--							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $serial->completed_at ? $serial->completed_at->format('d-m-Y H:i:s') : '-' }}</td>--}}
{{--							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $serial->shipped_at ? $serial->shipped_at->format('d-m-Y H:i:s') : '-' }}</td>--}}
{{--							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $serial->received_at ? $serial->received_at->format('d-m-Y H:i:s') : '-' }}</td>--}}
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
