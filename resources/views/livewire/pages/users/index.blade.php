<x-slot:header>
	Utenti
</x-slot:header>
<div>
	<div class="flow-root space-y-5">
		<div class="max-w-sm">
			<x-input wire:model.debounce.500ms="search" type="search" placeholder="Cerca.."></x-input>
		</div>
		<div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
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
							<span class="sr-only">Modifica</span>
						</th>
					</tr>
					</thead>
					<tbody class="divide-y divide-gray-200 bg-white">
					@forelse($users as $user)
						<tr>
							<td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
								{{ $user->fullName }}
							</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $user->phone }}</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $user->email }}</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $user->role->label }}</td>
							<td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 lg:pr-8">
								<a href="#" class="text-indigo-600 hover:text-indigo-900">Modifica<span class="sr-only">, {{ $user->fullName }}</span></a>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="100%" class="py-4 px-3 text-sm text-center text-zinc-500">Nessun elemento trovato</td>
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