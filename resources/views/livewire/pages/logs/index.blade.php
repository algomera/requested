<x-slot:header>
	Log di sistema
</x-slot:header>
<div>
	<div class="flow-root space-y-5">
		<div class="flex items-center justify-between">
			<div class="flex flex-1 space-x-3 items-center">
				<div class="flex-1 max-w-sm">
					<x-input wire:model.debounce.500ms="search" type="search" placeholder="Cerca.."
							 append="heroicon-o-magnifying-glass" iconColor="text-zinc-500"></x-input>
				</div>
				<div class="w-full sm:w-56">
					<x-flatpickr wire:model="selectedDateRange" for="selectedDate"/>
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
							Messaggio
						</th>
						<th scope="col" class="relative py-3.5 pl-3 text-sm text-right pr-4 sm:pr-6 lg:pr-8">
							Creazione
						</th>
					</tr>
					</thead>
					<tbody class="divide-y divide-gray-200 bg-white">
					@forelse($logs as $log)
						<tr class="hover:bg-gray-50" wire:key="{{ $log->id }}">
							<td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
								{{ $log->user->fullName }} {{ $log->message }}
							</td>
							<td class="whitespace-nowrap px-3 py-4 text-sm text-right text-gray-500">
								{{ $log->created_at->format('d-m-Y H:i:s') }}
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
			{{ $logs->links() }}
		</div>
	</div>
</div>
