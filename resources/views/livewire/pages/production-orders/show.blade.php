<x-slot:header>
	<div class="flex items-center space-x-3">
		<span>Ordine: {{ $production_order->code }}</span>
		<button x-on:click="Livewire.emit('openModal', 'components.logs', {{ json_encode(['model' => 'App\Models\ProductionOrder', 'id' => $production_order->id]) }})"
		        type="button"
		        class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5 2xl:hidden"
		        aria-label="Toggle logs">
			<x-heroicon-o-queue-list class="w-4 stroke-zinc-900"/>
		</button>
	</div>
</x-slot:header>
<div class="grid grid-cols-1 2xl:grid-cols-3 gap-1">
	<div class="col-span-2 flow-root space-y-5 2xl:pr-4 2xl:border-r">
		<div class="flex items-center justify-between">
			<x-primary-button
					wire:click="$emit('openModal', 'pages.production-orders.materials', {{ json_encode(['production_order' => $production_order->id]) }})">
				Distinta base
			</x-primary-button>
			@if($serials_checked)
				<x-primary-button
						class="bg-green-500 hover:bg-green-600 focus:bg-green-700 active:bg-green-700"
						wire:click="setAsCompleted">
					Completa {{ count($serials_checked) }}
				</x-primary-button>
			@endif
			@if($incompleted_serials->count() === 0 && $production_order->status !== 'completed')
				<x-primary-button wire:click="changeState" class="bg-green-500 hover:bg-green-600 focus:bg-green-700 active:bg-green-700">Completa l'Ordine</x-primary-button>
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
		@switch($currentTab)
			@case(0)
				@if($incompleted_serials->count())
					@if($production_order->maxItemsProducibles === 0)
						<p class="text-sm text-red-500">Non è possibile produrre alcuna matricola per mancanza di prodotti necessari nell'ubicazione di Produzione</p>
					@else
						<p class="text-sm text-gray-600">In base ai prodotti in Produzione, è possibile produrre <span class="font-bold">{{ $production_order->maxItemsProducibles }}</span> matricola/e</p>
					@endif
					<div class="overflow-x-auto" wire:key="incompleted">
						<div class="inline-block min-w-full py-2 align-middle">
							<table class="min-w-full divide-y divide-gray-300">
								<thead>
								<tr>
									<th scope="col"
									    class="w-[50px] py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 lg:pl-8">
										<span class="sr-only">Check</span>
										<input wire:model="selectAll" type="checkbox"
										       class="h-4 w-4 rounded border-gray-300 text-gray-600 focus:ring-gray-600">
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
								</tr>
								</thead>
								<tbody class="divide-y divide-gray-200 bg-white">
								@forelse($incompleted_serials as $incompleted)
									<tr class="hover:bg-gray-50" wire:key="{{ $incompleted->id }}">
										@if($currentTab == 0)
											<td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
												<input wire:model="serials_checked" type="checkbox"
												       value="{{ $incompleted->id }}"
												       class="h-4 w-4 rounded border-gray-300 text-gray-600 focus:ring-gray-600">
											</td>
										@endif
										<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $incompleted->code }}</td>
										@if($currentTab == 1)
											<td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 lg:pr-8">{{ $incompleted->completed_at }}</td>
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
						{{ $incompleted_serials->links() }}
					</div>
				@else
					<p class="text-sm text-gray-400">Nessuna matricola da produrre.</p>
				@endif
				@break
			@case(1)
				@if($completed_serials->count())
					<div class="overflow-x-auto" wire:key="completed">
						<div class="inline-block min-w-full py-2 align-middle">
							<table class="min-w-full divide-y divide-gray-300">
								<thead>
								<tr>
									<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
										Matricola
									</th>
									<th scope="col"
									    class="relative py-3.5 pl-3 pr-4 sm:pr-6 lg:pr-8 text-right text-sm font-semibold text-gray-900">
										Data completamento
									</th>
								</tr>
								</thead>
								<tbody class="divide-y divide-gray-200 bg-white">
								@forelse($completed_serials as $completed)
									<tr class="hover:bg-gray-50" wire:key="{{ $completed->id }}">
										@if($currentTab == 0)
											<td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
												<input wire:model="serials_checked" type="checkbox"
												       value="{{ $completed->id }}"
												       class="h-4 w-4 rounded border-gray-300 text-gray-600 focus:ring-gray-600">
											</td>
										@endif
										<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $completed->code }}</td>
										@if($currentTab == 1)
											<td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 lg:pr-8">{{ $completed->completed_at }}</td>
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
						{{ $completed_serials->links() }}
					</div>
				@else
					<p class="text-sm text-gray-400">Nessuna matricola completata.</p>
				@endif
				@break
		@endswitch
	</div>
	<div class="hidden 2xl:block">
		<h3 class="mb-2 text-sm text-center">Timeline</h3>
		<ul role="list" class="space-y-6">
			@forelse($logs as $log)
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
			@empty
				<p class="text-center mt-3 text-gray-500 text-xs">Al momento non c'è nessuna operazione.</p>
			@endforelse
		</ul>
	</div>
</div>
