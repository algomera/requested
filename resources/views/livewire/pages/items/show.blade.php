<div class="overflow-hidden bg-white shadow sm:rounded-lg">
	<div class="px-4 py-6 sm:px-6">
		<h3 class="text-base font-semibold leading-7 text-gray-900">Informazioni articolo</h3>
	</div>
	<div class="border-t border-gray-100">
		<dl class="divide-y divide-gray-100">
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Codice</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $item->product->code }}</dd>
			</div>
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Descrizione</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
					<span class="italic">{{ $item->product->description }}</span>
				</dd>
			</div>
			<div class="px-4 py-6 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Composizione</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
					<table class="min-w-full divide-y divide-gray-300">
						<thead>
						<tr>
							<th scope="col"
								class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 lg:pl-8">
								Codice Prodotto
							</th>
							<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Descrizione</th>
							<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Quantità</th>
							<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
								Unità di misura
							</th>
						</tr>
						</thead>
						<tbody class="divide-y divide-gray-200 bg-white">
						@forelse($products->load('unit') as $product)
							<tr class="hover:bg-gray-50" wire:key="{{ $product->id }}">
								<td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
									{{ $product->code }}
								</td>
								<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $product->description }}</td>
								<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $product->pivot->quantity }}</td>
								<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $product->unit->description }}</td>
							</tr>
						@empty
							<tr>
								<td colspan="100%" class="text-center whitespace-nowrap px-3 py-4 text-sm text-gray-500">
									<span>Nessun prodotto.</span>
								</td>
							</tr>
						</tbody>
						@endforelse
					</table>
				</dd>
			</div>
		</dl>
	</div>
</div>
