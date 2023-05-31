<x-slot:header>
	<div class="flex items-center justify-between">
		Ordini di magazzino
{{--		<div>--}}
{{--			<x-primary-button x-on:click="Livewire.emit('openModal', 'pages.production-orders.create')">--}}
{{--				<x-heroicon-o-plus class="w-4 h-4"></x-heroicon-o-plus>--}}
{{--			</x-primary-button>--}}
{{--		</div>--}}
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
						<option value="">Tutti</option>
						<option value="to_transfer">Da trasferire</option>
						<option value="partially_transferred">Parzialmente trasferiti</option>
						<option value="transferred">Trasferiti</option>
					</x-select>
				</div>
			</div>
		</div>
		<div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
			<div class="inline-block min-w-full py-2 align-middle">
				<table class="min-w-full divide-y divide-gray-300">
					<thead>
					<tr>
						<th scope="col"
							class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 lg:pl-8">
							Codice
						</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Motivo</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Articolo</th>
						{{--						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Quantità--}}
						{{--							totale--}}
						{{--						</th>--}}
						{{--						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Quantità--}}
						{{--							completata--}}
						{{--						</th>--}}
						{{--						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Data di--}}
						{{--							creazione--}}
						{{--						</th>--}}
						{{--						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Data di--}}
						{{--							consegna--}}
						{{--						</th>--}}
						{{--						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Destinazione--}}
						{{--						</th>--}}
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Stato</th>
						<th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 lg:pr-8">
							<span class="sr-only">Azioni</span>
						</th>
					</tr>
					</thead>
					<tbody class="divide-y divide-gray-200 bg-white">
					@forelse($warehouse_orders as $warehouse_order)
						<tr class="hover:bg-gray-50" wire:key="{{ $warehouse_order->id }}">
							<td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
								{{ $warehouse_order->production_order->code }}
							</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $warehouse_order->reason }}</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $warehouse_order->production_order->product->description }}</td>
							{{--							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $warehouse_order->rows->sum('quantity_total') }}</td>--}}
							{{--							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $warehouse_order->rows->sum('quantity_processed') }}</td>--}}
							{{--							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ \Carbon\Carbon::parse($production_order->created_at)->format('d-m-Y') }}</td>--}}
							{{--							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ \Carbon\Carbon::parse($production_order->delivery_date)->format('d-m-Y') }}</td>--}}
							{{--							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $production_order->destination->name }}</td>--}}
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
								@switch($warehouse_order->getStatus())
									@case('to_transfer')
										<div
											class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
											{{ config('requested.warehouse_orders.status.to_transfer') }}
										</div>
										@break
									@case('partially_transferred')
										<div
											class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">
											{{ config('requested.warehouse_orders.status.partially_transferred') }}
										</div>
										@break
									@case('transferred')
										<div
											class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
											{{ config('requested.warehouse_orders.status.transferred') }}
										</div>
										@break
								@endswitch
							</td>
							<td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 lg:pr-8">
								<div class="inline-flex items-center justify-end space-x-3">
									<a href="{{ route('warehouse-orders.show', $warehouse_order->id) }}"
									   class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5">
										<x-heroicon-o-eye class="w-4 stroke-zinc-900"/>
									</a>
									@if(!$warehouse_order->system)
										@if($deletingId != $warehouse_order->id)
											<button wire:key="deleting-{{ $warehouse_order->id }}"
													wire:click.stop="$set('deletingId', '{{ $warehouse_order->id }}')"
													type="button"
													class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5">
												<x-heroicon-o-trash class="w-4 stroke-zinc-900"/>
											</button>
										@else
											<button wire:key="confirm-{{ $warehouse_order->id }}"
													x-init="setTimeout(() => $wire.deletingId = null, 5000)"
													wire:click.stop="delete({{ $warehouse_order->id }})" type="button"
													class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5">
												<x-heroicon-o-question-mark-circle class="w-4 stroke-orange-400"/>
											</button>
										@endif
									@endif
								</div>
							</td>
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
			{{ $warehouse_orders->links() }}
		</div>
	</div>
</div>
