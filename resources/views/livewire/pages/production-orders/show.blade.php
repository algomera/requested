<x-slot:header>
	Ordine: {{ $production_order->code }}
</x-slot:header>
<div>
	<div class="flow-root space-y-5">
		<div class="flex items-center justify-between">
			<x-primary-button
					wire:click="$emit('openModal', 'pages.production-orders.materials', {{ json_encode(['production_order' => $production_order->id]) }})">
				Distinta base
			</x-primary-button>
			@if($serials_checked)
				<x-primary-button
						class="bg-green-400 hover:bg-green-500 focus:bg-green-600 active:bg-green-600"
						wire:click="setAsCompleted">
					Completa {{ count($serials_checked) }}
				</x-primary-button>
			@endif
		</div>

		<div class="block">
			<nav class="flex space-x-4" aria-label="Tabs">
				@foreach($tabs as $k => $tab)
					<span wire:click="$set('currentTab', '{{ $k }}')"
					      class="{{ $currentTab == $k ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:text-gray-700 hover:cursor-pointer' }} rounded-md px-3 py-2 text-sm font-medium">{{ $tab }}</span>
				@endforeach
			</nav>
		</div>
		@if($serials->count())
			<div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
				<div class="inline-block min-w-full py-2 align-middle">
					<table class="min-w-full divide-y divide-gray-300">
						<thead>
						<tr>
							<th scope="col"
							    class="w-[50px] py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 lg:pl-8">
								<span class="sr-only">Check</span>
							</th>
							<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
								Matricola
							</th>
							@if($currentTab == 1)
								<th scope="col"
								    class="relative py-3.5 pl-3 pr-4 sm:pr-6 lg:pr-8 text-right text-sm font-semibold text-gray-900">
									Data completamento
								</th>
							@endif
							{{--						<th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 lg:pr-8">--}}
							{{--							<span class="sr-only">Azioni</span>--}}
							{{--						</th>--}}
						</tr>
						</thead>
						<tbody class="divide-y divide-gray-200 bg-white">
						@forelse($serials as $serial_number)
							<tr class="hover:bg-gray-50" wire:key="{{ $serial_number->id }}">
								<td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
									<input wire:model="serials_checked" type="checkbox"
									       value="{{ $serial_number->id }}"
									       class="h-4 w-4 rounded border-gray-300 text-gray-600 focus:ring-gray-600">
								</td>
								<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $serial_number->code }}</td>
								@if($currentTab == 1)
									<td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 lg:pr-8">{{ $serial_number->completed_at }}</td>
								@endif
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
		@else
			<p class="text-sm text-gray-400">Nessuna matricola da produrre</p>
		@endif
	</div>
</div>
