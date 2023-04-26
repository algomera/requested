<div class="overflow-hidden bg-white shadow sm:rounded-lg">
	<div class="px-4 py-6 sm:px-6">
		<h3 class="text-base font-semibold leading-7 text-gray-900">Informazioni articolo</h3>
	</div>
	<div class="border-t border-gray-100">
		<dl class="divide-y divide-gray-100">
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Codice</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $item->code }}</dd>
			</div>
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Nome</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $item->name }}</dd>
			</div>
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Descrizione</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $item->description }}</dd>
			</div>
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Composizione</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
					<ul role="list" class="divide-y divide-gray-200">
						@forelse($item->products as $product)
							<li class="{{ $loop->iteration === 1 ? 'pt-0' : '' }} py-3 flex items-start justify-between">
								<div class="flex flex-col space-y-0.5">
									<span>{{ $product->code }} &middot; {{ $product->name }}</span>
									<p class="text-xs text-zinc-500"><span class="text-zinc-400 font-semibold">In magazzino: {{ $product->locations()->sum('quantity') }}</span></p>
								</div>
								<div class="flex flex-col text-right space-y-0.5">
									<p class="text-xs text-zinc-700 font-semibold leading-7">{{ $product->pivot->quantity }} {{ $product->pivot->quantity === 1 ? 'pezzo' : 'pezzi'}}</span></p>
								</div>
							</li>
						@empty
							<li>
								<span>Nessun prodotto.</span>
							</li>
						@endforelse
					</ul>
				</dd>
			</div>
		</dl>
	</div>
</div>
