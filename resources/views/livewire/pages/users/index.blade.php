<x-slot:header>
	<div class="flex items-center justify-between">
		Utenti
		<div>
			<x-primary-button x-on:click="Livewire.emit('openModal', 'pages.users.create')">
				<x-heroicon-o-plus class="w-4 h-4"></x-heroicon-o-plus>
			</x-primary-button>
		</div>
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
			@forelse($users as $user)
				<div class="flex items-center justify-between">
					<div class="text-xs py-3 sm:px-0 space-y-0.5">
						<p class="font-bold">Nome: <span
								class="font-light">{{ $user->fullName }}</span>
						</p>
						<p class="font-bold">Cellulare: <span
								class="font-light">{{ $user->phone }}</span>
						</p>
						<p class="font-bold">Email: <span
								class="font-light">{{ $user->email }}</span>
						</p>
						<p class="font-bold">Ruolo: <span
								class="font-light">{{ $user->role->label }}</span>
						</p>
					</div>
					<div class="inline-flex items-center justify-end space-x-3">
						<button wire:click="$emit('openModal', 'pages.users.edit', {{ json_encode(['user' => $user->id]) }})" type="button"
								class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5">
							<x-heroicon-o-pencil class="w-4 stroke-zinc-900"/>
						</button>
						<button wire:click="$emit('openModal', 'pages.users.show', {{ json_encode(['user' => $user->id]) }})"
								type="button"
								class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5">
							<x-heroicon-o-eye class="w-4 stroke-zinc-900"/>
						</button>
						@if($deletingId != $user->id)
							<button wire:key="mobile-deleting-{{ $user->id }}" wire:click="$set('deletingId', '{{ $user->id }}')" type="button"
									class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5">
								<x-heroicon-o-trash class="w-4 stroke-zinc-900"/>
							</button>
						@else
							<button wire:key="mobile-confirm-{{ $user->id }}"
									x-init="setTimeout(() => $wire.deletingId = null, 5000)"
									wire:click="delete({{ $user->id }})" type="button"
									class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5">
								<x-heroicon-o-question-mark-circle class="w-4 stroke-orange-400"/>
							</button>
						@endif
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
						    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 lg:pl-8">Nome
						</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Cellulare</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Email</th>
						<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Ruolo</th>
						<th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 lg:pr-8">
							<span class="sr-only">Azioni</span>
						</th>
					</tr>
					</thead>
					<tbody class="divide-y divide-gray-200 bg-white">
					@forelse($users as $user)
						<tr class="hover:bg-gray-50" wire:key="{{ $user->id }}">
							<td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
								{{ $user->fullName }}
							</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $user->phone }}</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $user->email }}</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $user->role->label }}</td>
							<td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 lg:pr-8">
								<div class="inline-flex items-center justify-end space-x-3">
									<button wire:click="$emit('openModal', 'pages.users.edit', {{ json_encode(['user' => $user->id]) }})" type="button"
									        class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5">
										<x-heroicon-o-pencil class="w-4 stroke-zinc-900"/>
									</button>
									<button wire:click="$emit('openModal', 'pages.users.show', {{ json_encode(['user' => $user->id]) }})"
									        type="button"
									        class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5">
										<x-heroicon-o-eye class="w-4 stroke-zinc-900"/>
									</button>
									@if($deletingId != $user->id)
										<button wire:key="deleting-{{ $user->id }}" wire:click="$set('deletingId', '{{ $user->id }}')" type="button"
										        class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5">
											<x-heroicon-o-trash class="w-4 stroke-zinc-900"/>
										</button>
									@else
										<button wire:key="confirm-{{ $user->id }}"
										        x-init="setTimeout(() => $wire.deletingId = null, 5000)"
										        wire:click="delete({{ $user->id }})" type="button"
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
			{{ $users->links() }}
		</div>
	</div>
</div>
