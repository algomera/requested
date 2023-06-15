<x-slot:header>
	<div class="flex items-center justify-between">
		Ordini di produzione
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
						<option value="">Non completati</option>
						<option value="completed">Completati</option>
						<option value="active">Attivi</option>
						<option value="created">Creati</option>
					</x-select>
				</div>
			</div>
		</div>
		<div class="block border-t-2 pt-1 divide-y divide-gray-200 lg:hidden">
			@forelse($production_orders as $production_order)
				<div class="flex items-center justify-between">
					<div class="text-xs py-3 sm:px-0 space-y-0.5">
						<p class="font-bold">Codice: <span
								class="font-light">{{ $production_order->code }}</span>
						</p>
						<p class="font-bold">Articolo: <span
								class="font-light">{{ $production_order->product->code }} - {{ $production_order->product->description }}</span>
						</p>
						<p class="font-bold">Quantità totale: <span
								class="font-light">{{ $production_order->quantity }}</span>
						</p>
						<p class="font-bold">Quantità completata: <span
								class="font-light">{{ $production_order->warehouse_orders()->where('type', 'versamento')->first()->rows()->first()->quantity_processed ?? 0 }}</span>
						</p>
						<p class="font-bold">Data di creazione: <span
								class="font-light">{{ $production_order->created_at->format('d-m-Y') }}</span>
						</p>
						<p class="font-bold">Data di creazione: <span
								class="font-light">{{ $production_order->delivery_date->format('d-m-Y') }}</span>
						</p>
						<div class="font-bold inline-flex">
							<span class="mr-2">Stato:</span>
							@switch($production_order->status)
								@case('created')
									<div
										class="inline-flex items-center rounded-md bg-gray-50 px-2 py-0.5 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
										{{ config('requested.production_orders.status.' . $production_order->status) }}
									</div>
									@break
								@case('active')
									<div
										class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-0.5 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">
										{{ config('requested.production_orders.status.' . $production_order->status) }}
									</div>
									@break
								@case('completed')
									<div
										class="inline-flex items-center rounded-md bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
										{{ config('requested.production_orders.status.' . $production_order->status) }}
									</div>
									@break
							@endswitch
						</div>
						<div class="!mt-3">
							@if($production_order->warehouse_orders()->where('type', 'trasferimento')->exists())
								<button wire:click="unloadWarehouseOrderMaterials({{ $production_order->id }}, {{ $loop->index }})"
										wire:target="unloadWarehouseOrderMaterials({{ $production_order->id }}, {{ $loop->index }})"
										wire:loading.attr="disabled"
										type="button"
										class="rounded bg-indigo-600 px-2 py-0.5 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:cursor-not-allowed disabled:opacity-25"
									{{ $production_order->warehouse_orders()->where('type', 'scarico')->first()->getStatus() === 'transferred' ? 'disabled' : ''}}
								>
									Scarica materiale
								</button>
							@else
								<button
									wire:click="createWarehouseOrderTrasferimentoScarico({{ $production_order->id }}, {{ $loop->index }})"
									wire:target="createWarehouseOrderTrasferimentoScarico({{ $production_order->id }}, {{ $loop->index }})"
									wire:loading.attr="disabled"
									type="button"
									class="rounded bg-indigo-600 px-2 py-0.5 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:cursor-not-allowed disabled:opacity-25">
									Genera trasferimento
								</button>
							@endif
						</div>
					</div>
					<div class="inline-flex items-center justify-end space-x-3">
						<a href="{{ route('production-orders.show', $production_order->id) }}"
						   class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5">
							<x-heroicon-o-eye class="w-4 stroke-zinc-900"/>
						</a>
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
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Codice
							Articolo
						</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Articolo</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Quantità
							totale
						</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Quantità
							completata
						</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Data di
							creazione
						</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Data di
							consegna
						</th>
						{{--						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Destinazione--}}
						{{--						</th>--}}
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Stato</th>
						<th scope="col"
							class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{-- Ordine di Trasferimento --}}</th>
						<th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 lg:pr-8">
							<span class="sr-only">Azioni</span>
						</th>
					</tr>
					</thead>
					<tbody class="divide-y divide-gray-200 bg-white">
					@forelse($production_orders as $production_order)
						<tr class="hover:bg-gray-50" wire:key="{{ $production_order->id }}">
							<td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
								{{ $production_order->code }}
							</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $production_order->product->code }}</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $production_order->product->description }}</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $production_order->quantity }}</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $production_order->warehouse_orders()->where('type', 'versamento')->first()->rows()->first()->quantity_processed ?? 0 }}</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $production_order->created_at->format('d-m-Y') }}</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $production_order->delivery_date->format('d-m-Y') }}</td>
							{{--							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $production_order->destination?->code }}</td>--}}
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
								@switch($production_order->status)
									@case('created')
										<div
											class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
											{{ config('requested.production_orders.status.' . $production_order->status) }}
										</div>
										@break
									@case('active')
										<div
											class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">
											{{ config('requested.production_orders.status.' . $production_order->status) }}
										</div>
										@break
									@case('completed')
										<div
											class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
											{{ config('requested.production_orders.status.' . $production_order->status) }}
										</div>
										@break
								@endswitch
							</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
								@if($production_order->warehouse_orders()->where('type', 'trasferimento')->exists())
									<button wire:click="unloadWarehouseOrderMaterials({{ $production_order->id }}, {{ $loop->index }})"
											wire:target="unloadWarehouseOrderMaterials({{ $production_order->id }}, {{ $loop->index }})"
											wire:loading.attr="disabled"
											type="button"
											class="rounded bg-indigo-600 px-2 py-1 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:cursor-not-allowed disabled:opacity-25"
										{{ $production_order->warehouse_orders()->where('type', 'scarico')->first()->getStatus() === 'transferred' ? 'disabled' : ''}}
									>
										Scarica materiale
									</button>
								@else
									<button
										wire:click="createWarehouseOrderTrasferimentoScarico({{ $production_order->id }}, {{ $loop->index }})"
										wire:target="createWarehouseOrderTrasferimentoScarico({{ $production_order->id }}, {{ $loop->index }})"
										wire:loading.attr="disabled"
										type="button"
										class="rounded bg-indigo-600 px-2 py-1 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:cursor-not-allowed disabled:opacity-25">
										Genera trasferimento
									</button>
								@endif
							</td>
							<td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 lg:pr-8">
								<div class="inline-flex items-center justify-end space-x-3">
									<a href="{{ route('production-orders.show', $production_order->id) }}"
									   class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5">
										<x-heroicon-o-eye class="w-4 stroke-zinc-900"/>
									</a>
									{{--									@if(!in_array($production_order->status, ['active', 'completed']))--}}
									{{--										@if($deletingId != $production_order->id)--}}
									{{--											<button wire:key="deleting-{{ $production_order->id }}"--}}
									{{--													wire:click.stop="$set('deletingId', '{{ $production_order->id }}')"--}}
									{{--													type="button"--}}
									{{--													class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5">--}}
									{{--												<x-heroicon-o-trash class="w-4 stroke-zinc-900"/>--}}
									{{--											</button>--}}
									{{--										@else--}}
									{{--											<button wire:key="confirm-{{ $production_order->id }}"--}}
									{{--													x-init="setTimeout(() => $wire.deletingId = null, 5000)"--}}
									{{--													wire:click.stop="delete({{ $production_order->id }})" type="button"--}}
									{{--													class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5">--}}
									{{--												<x-heroicon-o-question-mark-circle class="w-4 stroke-orange-400"/>--}}
									{{--											</button>--}}
									{{--										@endif--}}
									{{--									@endif--}}
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
			{{ $production_orders->links() }}
		</div>
	</div>
</div>
