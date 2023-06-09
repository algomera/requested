<div class="{{ $warehouse_order->type === 'spedizione' ? 'grid grid-cols-1 2xl:grid-cols-3 gap-1' : '' }}">
	<div
		class="flow-root space-y-5 {{ $warehouse_order->type === 'spedizione' ? 'col-span-2 2xl:pr-4 2xl:border-r' : '' }}">
		<div class="flex items-center space-x-3">
			<div class="w-full">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-4">
						<span class="py-4 text-xl font-bold">
							Ordine: {{ $warehouse_order->production_order->code ?? $warehouse_order->code ?? '-' }}
						</span>
						@if($warehouse_order->type === 'spedizione')
							<button
								x-on:click="Livewire.emit('openModal', 'components.ddts', {{ json_encode(['warehouse_order' => $warehouse_order->id]) }})"
								type="button"
								class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5 2xl:hidden"
								aria-label="Toggle ddts">
								<x-heroicon-o-queue-list class="w-4 stroke-zinc-900"/>
							</button>
						@endif
					</div>
					@if($warehouse_order->ddts()->where('generated', false)->first())
						<x-primary-button wire:click="generateDDT">
							Genera DDT
						</x-primary-button>
					@endif
				</div>
				<div class="text-xs">
					<p class="font-bold">Tipologia: <span
							class="font-light">{{ config('requested.warehouse_orders.types.' . $warehouse_order->type) }}</span>
					</p>
					<p class="font-bold">Motivo: <span class="font-light">{{ $warehouse_order->reason ?: '-' }}</span>
					</p>
					@if($warehouse_order->destination)
						<p class="font-bold">Destinazione: <span
								class="font-light">{{ $warehouse_order->destination?->code ?: '-' }}</span></p>
					@endif
					<p class="font-bold">Stato: <span
							class="font-light">{{ config('requested.warehouse_orders.status.' . $warehouse_order->getStatus()) }}</span>
					</p>
				</div>
			</div>
		</div>
		<div class="flex flex-col items-stretch space-y-4 lg:flex-row lg:items-center lg:justify-between">
			@if($warehouse_order->type === 'spedizione')
				<div class="flex space-x-3">
					<div class="flex-1">
						<x-primary-button wire:click="shipAll"
										  :disabled="$warehouse_order->getStatus() === 'transferred'"
										  class="w-full justify-center whitespace-nowrap"
						>
							Spedisci tutto
						</x-primary-button>
					</div>
				</div>
			@endif
			@if($warehouse_order->type === 'ricevimento')
				<div class="flex space-x-3">
					<div class="flex-1">
						<x-primary-button wire:click="receiveAll"
										  :disabled="$warehouse_order->getStatus() === 'transferred'"
										  class="w-full justify-center whitespace-nowrap">
							Ricevi tutto
						</x-primary-button>
					</div>
				</div>
			@endif
		</div>
		<div class="flex items-center justify-between">
			<div class="flex flex-1 space-x-3 items-center">
				<div class="flex-1 max-w-sm">
					<x-input wire:model.debounce.500ms="search" type="search" placeholder="Cerca.."
							 append="heroicon-o-magnifying-glass" iconColor="text-zinc-500"></x-input>
				</div>
			</div>
		</div>
		<div class="block border-t-2 pt-1 divide-y divide-gray-200 lg:hidden">
			@forelse($rows as $row)
				<div class="flex items-center justify-between">
					<div class="text-xs py-3 sm:px-0 space-y-0.5">
						<p class="font-bold">Articolo: <span
								class="font-light">{{ $row->product->code }} - {{ $row->product->description }}</span>
						</p>
						<p class="font-bold">Quantità totale: <span
								class="font-light">{{ $row->quantity_total }}</span>
						</p>
						<p class="font-bold">Quantità processata: <span
								class="font-light">{{ $row->quantity_processed }}</span></p>
						<p class="font-bold">Ubicazione di prelievo: <span
								class="font-light">{{ $row->pickup?->code ?: '-' }}</span></p>
						<p class="font-bold">Ubicazione di destinazione: <span
								class="font-light">{{ $row->destination?->code ?: '-' }}</span></p>
						<p class="font-bold">Data di creazione: <span
								class="font-light">{{ $warehouse_order->created_at->format('d-m-Y') }}</span></p>
						<div class="font-bold inline-flex">
							<span class="mr-2">Stato:</span>
							@switch($row->status)
								@case('to_transfer')
									<div
										class="inline-flex items-center rounded-md bg-gray-50 px-2 py-0.5 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
										{{ config('requested.warehouse_orders.status.' . $row->status) }}
									</div>
									@break
								@case('partially_transferred')
									<div
										class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-0.5 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">
										{{ config('requested.warehouse_orders.status.' . $row->status) }}
									</div>
									@break
								@case('transferred')
									<div
										class="inline-flex items-center rounded-md bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
										{{ config('requested.warehouse_orders.status.' . $row->status) }}
									</div>
									@break
							@endswitch
						</div>
					</div>
					<div>
						@if($warehouse_order->type === 'trasferimento' && $row->status !== 'transferred')
							<button
								wire:click="$emit('openModal', 'pages.warehouse-orders.transfer', {{ json_encode(['warehouse_order' => $warehouse_order->id, 'row' => $row->id]) }})"
								type="button"
								class="rounded bg-indigo-600 px-2 py-1 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
								Trasferisci
							</button>
						@endif
						@if($warehouse_order->type === 'spedizione' && $row->status !== 'transferred')
							<button
								wire:click="$emit('openModal', 'pages.warehouse-orders.ship', {{ json_encode(['warehouse_order' => $warehouse_order->id, 'row' => $row->id]) }})"
								type="button"
								class="rounded bg-indigo-600 px-2 py-1 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
								Spedisci
							</button>
						@endif
						@if($warehouse_order->type === 'ricevimento' && $row->status !== 'transferred')
							<button
								wire:click="$emit('openModal', 'pages.warehouse-orders.receive', {{ json_encode(['warehouse_order' => $warehouse_order->id, 'row' => $row->id]) }})"
								type="button"
								class="rounded bg-indigo-600 px-2 py-1 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
								Ricevi
							</button>
						@endif
					</div>
				</div>
			@empty
				<p class="text-center text-sm mt-3 text-zinc-500">Nessun elemento trovato</p>
			@endforelse
		</div>
		<div
			class="hidden lg:block {{ $warehouse_order->type === 'spedizione' ? 'overflow-x-auto' : '-mx-4 -my-2 sm:-mx-6 lg:-mx-8' }}"
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
								@if($warehouse_order->type === 'spedizione' && $row->status !== 'transferred')
									<button
										wire:click="$emit('openModal', 'pages.warehouse-orders.ship', {{ json_encode(['warehouse_order' => $warehouse_order->id, 'row' => $row->id]) }})"
										type="button"
										class="rounded bg-indigo-600 px-2 py-1 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
										Spedisci
									</button>
								@endif
								@if($warehouse_order->type === 'ricevimento' && $row->status !== 'transferred')
									<button
										wire:click="$emit('openModal', 'pages.warehouse-orders.receive', {{ json_encode(['warehouse_order' => $warehouse_order->id, 'row' => $row->id]) }})"
										type="button"
										class="rounded bg-indigo-600 px-2 py-1 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
										Ricevi
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
	@if($warehouse_order->type === 'spedizione')
		<div class="hidden 2xl:block">
			<h3 class="mb-2 text-sm text-center">DDTs</h3>
			<ul role="list" class="space-y-6">
				@forelse($ddts as $ddt)
					<li class="relative flex items-center gap-x-4">
						@if(!$loop->last)
							<div class="absolute left-0 top-0 flex w-6 justify-center -bottom-6">
								<div class="w-px bg-gray-200"></div>
							</div>
						@endif
						<div class="relative flex h-6 w-6 flex-none items-center justify-center bg-white">
							<div class="h-1.5 w-1.5 rounded-full bg-gray-100 ring-1 ring-gray-300"></div>
						</div>
						<p class="flex-auto py-0.5 text-xs leading-5 text-gray-500">
							<span class="block font-medium text-gray-900">DDT: {{ $ddt->code }}</span>
						</p>
						<span x-tooltip="{{ $ddt->generated_at->format('d-m-Y H:i:s') }}"
							  class="inline-block flex-none py-0.5 text-xs leading-5 text-gray-500">
								{{ $ddt->generated_at->diffForHumans() }}
							</span>
						<a href="{{ route('ddt.show', $ddt->id) }}" target="_blank">
							<x-heroicon-s-printer
								class="w-4 h-4 hover:cursor-pointer hover:text-indigo-500"></x-heroicon-s-printer>
						</a>
					</li>
				@empty
					<p class="text-center mt-3 text-gray-500 text-xs">Al momento non c'è nessun DDT generato.</p>
				@endforelse
			</ul>
		</div>
	@endif
</div>
