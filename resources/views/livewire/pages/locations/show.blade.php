<div class="overflow-hidden bg-white shadow sm:rounded-lg">
	<div class="px-4 py-6 sm:px-6">
		<h3 class="text-base font-semibold leading-7 text-gray-900">Informazioni ubicazione</h3>
	</div>
	<div class="border-t border-gray-100">
		<dl class="divide-y divide-gray-100">
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Codice</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $location->code }}</dd>
			</div>
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Descrizione</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $location->description }}</dd>
			</div>
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Tipologia</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ config('requested.locations.types.' . $location->type) }}</dd>
			</div>
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
				<dt class="flex flex-col space-y-0.5 text-sm font-medium text-gray-900">
					<span>Prodotti</span>
					<span wire:click="$emit('openModal', 'pages.locations.add-product', {{ json_encode(['location' => $location->id]) }})"
					      class="text-xs font-medium text-indigo-500 hover:text-indigo-800 hover:cursor-pointer">Aggiungi</span>
				</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
					<ul role="list" class="divide-y divide-gray-200">
						@forelse($location->products as $product)
							<li class="{{ $loop->iteration === 1 ? 'pt-0' : '' }} py-3 flex items-center justify-between">
								<div class="flex flex-col space-y-0.5">
									<span>{{ $product->name }}</span>
									<p class="text-xs text-zinc-500">{{ $product->code }} &middot; <span class="text-zinc-700 font-semibold">{{ $product->pivot->quantity }} {{ config('requested.products.units.' . $product->units) }}</span></p>
								</div>
								<button wire:click="$emit('openModal', 'pages.locations.change', {{ json_encode(['product' => $product->id, 'current_location' => $location->id])}})" type="button"
								        class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5">
									<x-heroicon-o-arrows-right-left class="w-4 stroke-zinc-900"/>
								</button>
							</li>
						@empty
							<li>
								<span>Nessun prodotto presente.</span>
							</li>
						@endforelse
					</ul>
				</dd>
			</div>
		</dl>
	</div>
</div>
