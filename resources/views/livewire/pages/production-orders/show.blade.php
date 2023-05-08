<x-slot:header>
	Ordine: {{ $production_order->code }}
</x-slot:header>
<div class="grid grid-cols-3 gap-4">
	<div class="col-span-2 flow-root space-y-5">
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
			<div class="overflow-x-auto">
				<div class="inline-block min-w-full py-2 align-middle">
					<table class="min-w-full divide-y divide-gray-300">
						<thead>
						<tr>
							@if($currentTab == 0)
								<th scope="col"
								    class="w-[50px] py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 lg:pl-8">
									<span class="sr-only">Check</span>
								</th>
							@endif
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
								@if($currentTab == 0)
									<td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
										<input wire:model="serials_checked" type="checkbox"
										       value="{{ $serial_number->id }}"
										       class="h-4 w-4 rounded border-gray-300 text-gray-600 focus:ring-gray-600">
									</td>
								@endif
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
	<div>
		<h3 class="mb-2 text-sm text-center">Timeline</h3>
		<ul role="list" class="space-y-6">
			@foreach($logs as $log)
				<li class="relative flex gap-x-4">
					@if(!$loop->last)
						<div class="absolute left-0 top-0 flex w-6 justify-center -bottom-6">
							<div class="w-px bg-gray-200"></div>
						</div>
					@endif
					<div class="relative flex h-6 w-6 flex-none items-center justify-center bg-white">
						<div class="h-1.5 w-1.5 rounded-full bg-gray-100 ring-1 ring-gray-300"></div>
					</div>
					<p class="flex-auto py-0.5 text-xs leading-5 text-gray-500">
						<span class="font-medium text-gray-900">{{ $log->user->fullName }}</span>
						{{ $log->message }}
					</p>
					<span x-tooltip="{{ $log->created_at->format('d-m-Y H:i:s') }}"
					      class="flex-none py-0.5 text-xs leading-5 text-gray-500">
						{{ $log->created_at->diffForHumans() }}
					</span>
				</li>
			@endforeach
		</ul>
	</div>
</div>
