<div class="overflow-hidden bg-white shadow sm:rounded-lg">
	<div class="px-4 py-6 sm:px-6">
		<h3 class="text-base font-semibold leading-7 text-gray-900">Informazioni prodotto</h3>
	</div>
	<div class="border-t border-gray-100">
		<dl class="divide-y divide-gray-100">
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Codice</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $product->code }}</dd>
			</div>
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Nome</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $product->name }}</dd>
			</div>
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Descrizione</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $product->description ?: '-' }}</dd>
			</div>
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Unità di misura</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ config('requested.products.units.' . $product->units) }}</dd>
			</div>
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Ubicazioni</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
					<ul role="list" class="divide-y divide-gray-200">
						@forelse($product->locations as $location)
							<li class="{{ $loop->iteration === 1 ? 'pt-0' : '' }} py-3 flex items-center justify-between">
								<div class="flex flex-col space-y-0.5">
									<span>{{ $location->code }}</span>
									<p class="text-xs text-zinc-500">{{ config('requested.locations.types.' . $location->type) }} &middot; <span class="text-zinc-700 font-semibold">{{ $location->productQuantity($product->id) }} pezzi</span></p>
								</div>
								<button wire:click="$emit('openModal', 'pages.locations.change', {{ json_encode(['product' => $product->id, 'current_location' => $location->id])}})" type="button"
								        class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5">
									<x-heroicon-o-arrows-right-left class="w-4 stroke-zinc-900"/>
								</button>
							</li>
						@empty
							<li>
								<span>Nessuna location.</span>
							</li>
						@endforelse
					</ul>
				</dd>
			</div>
		</dl>
	</div>
</div>
