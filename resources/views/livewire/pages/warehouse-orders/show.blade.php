<div>
	<div class="flow-root space-y-5">
		<div class="flex items-center space-x-3">
			<div>
				<div class="flex items-center space-x-4">
					<span class="py-4 text-xl font-bold">Ordine: {{ $warehouse_order->production_order->code ?? '-' }}</span>
				</div>
				<div class="text-xs">
					<p class="font-bold">Tipologia: <span class="font-light">{{ config('requested.warehouse_orders.types.' . $warehouse_order->type) }}</span></p>
					<p class="font-bold">Motivo: <span class="font-light">{{ $warehouse_order->reason ?: '-' }}</span></p>
					<p class="font-bold">Destinazione: <span class="font-light">{{ $warehouse_order->destination?->code ?: '-' }}</span></p>
					<p class="font-bold">Stato: <span class="font-light">{{ config('requested.warehouse_orders.status.' . $warehouse_order->getStatus()) }}</span></p>
				</div>
			</div>
		</div>

		<div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8"
			 wire:key="{{ $warehouse_order->id }}-{{ $warehouse_order->type }}">
			<div class="inline-block min-w-full py-2 align-middle">
				<table class="min-w-full divide-y divide-gray-300">
					<thead>
					<tr>
						<th scope="col"
							class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 lg:pl-8">
							Codice Articolo
						</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Articolo</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Quantità
							totale
						</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Quantità
							processata
						</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Ubicazione di
							prelievo
						</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Ubicazione di
							destinazione
						</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Data di
							creazione
						</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Stato</th>
						<th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 lg:pr-8">
							<span class="sr-only">Azioni</span>
						</th>
					</tr>
					</thead>
					<tbody class="divide-y divide-gray-200 bg-white">
					@forelse($rows as $row)
						<tr class="hover:bg-gray-50" wire:key="{{ $row->id }}">
							<td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
								{{ $row->product->code }}
							</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $row->product->description }}</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $row->quantity_total }}</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $row->quantity_processed }}</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $row->pickup?->code ?: '-' }}</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $row->destination?->code ?: '-' }}</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ \Carbon\Carbon::parse($warehouse_order->created_at)->format('d-m-Y') }}</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
								@switch($row->status)
									@case('to_transfer')
										<div
											class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
											{{ config('requested.warehouse_orders.status.' . $row->status) }}
										</div>
										@break
									@case('partially_transferred')
										<div
											class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">
											{{ config('requested.warehouse_orders.status.' . $row->status) }}
										</div>
										@break
									@case('transferred')
										<div
											class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
											{{ config('requested.warehouse_orders.status.' . $row->status) }}
										</div>
										@break
								@endswitch
							</td>
							<td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 lg:pr-8">
								@if($warehouse_order->type === 'trasferimento' && $row->status !== 'transferred')
									<button
										wire:click="$emit('openModal', 'pages.warehouse-orders.transfer', {{ json_encode(['warehouse_order' => $warehouse_order->id, 'row' => $row->id]) }})"
										type="button"
										class="rounded bg-indigo-600 px-2 py-1 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
										Trasferisci
									</button>
								@endif
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
			{{ $rows->links() }}
		</div>
	</div>
	{{--	<div class="hidden 2xl:block">--}}
	{{--		<h3 class="mb-2 text-sm text-center">Timeline</h3>--}}
	{{--		<ul role="list" class="space-y-6">--}}
	{{--			@forelse($logs as $log)--}}
	{{--				<li class="relative flex gap-x-4">--}}
	{{--					@if(!$loop->last)--}}
	{{--						<div class="absolute left-0 top-0 flex w-6 justify-center -bottom-6">--}}
	{{--							<div class="w-px bg-gray-200"></div>--}}
	{{--						</div>--}}
	{{--					@endif--}}
	{{--					<div class="relative flex h-6 w-6 flex-none items-center justify-center bg-white">--}}
	{{--						<div class="h-1.5 w-1.5 rounded-full bg-gray-100 ring-1 ring-gray-300"></div>--}}
	{{--					</div>--}}
	{{--					<p class="flex-auto py-0.5 text-xs leading-5 text-gray-500">--}}
	{{--						<span class="font-medium text-gray-900">{{ $log->user->fullName }}</span>--}}
	{{--						{{ $log->message }}--}}
	{{--					</p>--}}
	{{--					<span x-tooltip="{{ $log->created_at->format('d-m-Y H:i:s') }}"--}}
	{{--						  class="flex-none py-0.5 text-xs leading-5 text-gray-500">--}}
	{{--						{{ $log->created_at->diffForHumans() }}--}}
	{{--					</span>--}}
	{{--				</li>--}}
	{{--			@empty--}}
	{{--				<p class="text-center mt-3 text-gray-500 text-xs">Al momento non c'è nessuna operazione.</p>--}}
	{{--			@endforelse--}}
	{{--		</ul>--}}
	{{--	</div>--}}
</div>
