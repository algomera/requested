<x-slot:header>
	<div class="flex items-center justify-between">
		Anagrafica Prodotti
		{{--		<div>--}}
		{{--			<x-primary-button x-on:click="Livewire.emit('openModal', 'pages.products.create')">--}}
		{{--				<x-heroicon-o-plus class="w-4 h-4"></x-heroicon-o-plus>--}}
		{{--			</x-primary-button>--}}
		{{--		</div>--}}
	</div>
</x-slot:header>
<div>
	<div class="flow-root space-y-5">
		<div class="flex items-center justify-between">
			<div class="flex-1 w-full lg:max-w-sm">
				<x-input wire:model.debounce.500ms="search" type="search" placeholder="Cerca.."
						 append="heroicon-o-magnifying-glass" iconColor="text-zinc-500"></x-input>
			</div>
		</div>
		<div class="block border-t-2 pt-1 divide-y divide-gray-200 lg:hidden">
			@forelse($products as $product)
				<div class="flex items-center justify-between">
					<div class="text-xs py-3 sm:px-0 space-y-0.5">
						<p class="font-bold">Articolo: <span
								class="font-light">{{ $product->code }} - {{ $product->description }}</span>
						</p>
						<p class="font-bold">Matricolare: <span
								class="font-light">{{ $product->serial_management ? 'Si' : 'No' }}</span>
						</p>
						<p class="font-bold">Unità di misura: <span
								class="font-light">{{ $product->unit->description }}</span>
						</p>
						<p class="font-bold">In magazzino: <span
								class="font-light">{{ $product->locations()->sum('quantity') }} {{ $product->unit->description }}</span>
						</p>
						@php($check = $product->locations()->select('id', 'code', 'quantity'))
						<div class="font-bold">
							<span class="{{ $check->count() > 0 ? 'mr-2' : '' }}">Ubicazione:</span>
							<div class="{{ $check->count() > 0 ? 'flex' : 'inline-flex' }} flex-wrap">
								@if($check->count() > 0)
									@foreach($check->get() as $location)
										<div
											wire:click.stop="$emit('openModal', 'pages.locations.show', {{ json_encode(['location' => $location->id]) }} )"
											class="flex mr-1 mt-1 items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10 hover:cursor-pointer hover:bg-gray-700 hover:text-white">
											<span class="font-bold leading-none">{{ $location->code }}</span>
											<span
												class="text-[9px] leading-none ml-1">({{ $location->quantity }})</span>
										</div>
									@endforeach
								@else
									<span class="font-light">-</span>
								@endif
							</div>
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
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Descrizione
						</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Matricolare
						</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Unità di
							misura
						</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">In magazzino
						</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Ubicazione
						</th>
						<th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 lg:pr-8">
							<span class="sr-only">Azioni</span>
						</th>
					</tr>
					</thead>
					<tbody class="divide-y divide-gray-200 bg-white">
					@forelse($products as $product)
						<tr class="hover:bg-gray-50" wire:key="{{ $product->id }}">
							<td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
								{{ $product->code }}
							</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $product->description }}</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $product->serial_management ? 'Si' : 'No' }}</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $product->unit->description }}</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $product->locations()->sum('quantity') }} {{ $product->unit->description }}</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
								@forelse($product->locations()->select('id', 'code', 'quantity')->get() as $location)
									<div
										wire:click.stop="$emit('openModal', 'pages.locations.show', {{ json_encode(['location' => $location->id]) }} )"
										class="inline-flex flex-col items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10 hover:cursor-pointer hover:bg-gray-700 hover:text-white">
										<span class="font-bold leading-none">{{ $location->code }}</span>
										<span class="text-[9px] leading-none mt-0.5">({{ $location->quantity }})</span>
									</div>
								@empty
									-
								@endforelse
							</td>
							<td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 lg:pr-8">
								{{--								<div class="inline-flex items-center justify-end space-x-3">--}}
								{{--									<button wire:click.stop="$emit('openModal', 'pages.products.edit', {{ json_encode(['product' => $product->id]) }})"--}}
								{{--									        type="button"--}}
								{{--									        class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5">--}}
								{{--										<x-heroicon-o-pencil class="w-4 stroke-zinc-900"/>--}}
								{{--									</button>--}}
								{{--									<button wire:click.stop="$emit('openModal', 'pages.products.show', {{ json_encode(['product' => $product->id]) }})"--}}
								{{--									        type="button"--}}
								{{--									        class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5">--}}
								{{--										<x-heroicon-o-eye class="w-4 stroke-zinc-900"/>--}}
								{{--									</button>--}}
								{{--									@if($deletingId != $product->id)--}}
								{{--										<button wire:key="deleting-{{ $product->id }}"--}}
								{{--										        wire:click.stop="$set('deletingId', '{{ $product->id }}')"--}}
								{{--										        type="button"--}}
								{{--										        class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5">--}}
								{{--											<x-heroicon-o-trash class="w-4 stroke-zinc-900"/>--}}
								{{--										</button>--}}
								{{--									@else--}}
								{{--										<button wire:key="confirm-{{ $product->id }}"--}}
								{{--										        x-init="setTimeout(() => $wire.deletingId = null, 5000)"--}}
								{{--										        wire:click.stop="delete({{ $product->id }})" type="button"--}}
								{{--										        class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5">--}}
								{{--											<x-heroicon-o-question-mark-circle class="w-4 stroke-orange-400"/>--}}
								{{--										</button>--}}
								{{--									@endif--}}
								{{--								</div>--}}
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
			{{ $products->links() }}
		</div>
	</div>
</div>
