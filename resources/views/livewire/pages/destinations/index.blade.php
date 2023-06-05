<x-slot:header>
	<div class="flex items-center justify-between">
		Destinazioni
		<div>
			<x-primary-button x-on:click="Livewire.emit('openModal', 'pages.destinations.create')">
				<x-heroicon-o-plus class="w-4 h-4"></x-heroicon-o-plus>
			</x-primary-button>
		</div>
	</div>
</x-slot:header>
<div>
	<div class="flow-root space-y-5">
		<div class="flex items-center justify-between">
			<div class="flex-1 max-w-sm">
				<x-input wire:model.debounce.500ms="search" type="search" placeholder="Cerca.."
						 append="heroicon-o-magnifying-glass" iconColor="text-zinc-500"></x-input>
			</div>
		</div>
		<div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
			<div class="inline-block min-w-full py-2 align-middle">
				<table class="min-w-full divide-y divide-gray-300">
					<thead>
					<tr>
						<th scope="col"
							class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 lg:pl-8">Codice
						</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Descrizione</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tipologia</th>
						<th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 lg:pr-8">
							<span class="sr-only">Azioni</span>
						</th>
					</tr>
					</thead>
					<tbody class="divide-y divide-gray-200 bg-white">
					@forelse($locations as $location)
						<tr class="hover:bg-gray-50" wire:key="{{ $location->id }}">
							<td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
								{{ $location->code }}
							</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $location->description }}</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ config('requested.locations.types.' . $location->type . '.label') }}</td>
							<td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 lg:pr-8">
								<div class="inline-flex items-center justify-end space-x-3">
									<button wire:click.stop="$emit('openModal', 'pages.destinations.edit', {{ json_encode(['location' => $location->id]) }})"
											type="button"
											class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5">
										<x-heroicon-o-pencil class="w-4 stroke-zinc-900"/>
									</button>
									@if($deletingId != $location->id)
										<button wire:key="deleting-{{ $location->id }}"
												wire:click.stop="$set('deletingId', '{{ $location->id }}')"
												type="button"
												class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5">
											<x-heroicon-o-trash class="w-4 stroke-zinc-900"/>
										</button>
									@else
										<button wire:key="confirm-{{ $location->id }}"
												x-init="setTimeout(() => $wire.deletingId = null, 5000)"
												wire:click.stop="delete({{ $location->id }})" type="button"
												class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5">
											<x-heroicon-o-question-mark-circle class="w-4 stroke-orange-400"/>
										</button>
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
			{{ $locations->links() }}
		</div>
	</div>
</div>
