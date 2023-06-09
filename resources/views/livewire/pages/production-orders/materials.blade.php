<div class="overflow-hidden bg-white shadow sm:rounded-lg">
	<div class="px-4 py-6 sm:px-6">
		<h3 class="text-base font-semibold leading-7 text-gray-900">
			Distinta di produzione
		</h3>
		<p class="text-xs">Ordine: {{ $production_order->code }}</p>
		<p class="text-xs">Articolo: {{ $production_order->product->code }} - {{ $production_order->product->description }}</p>
	</div>
	<div class="border-t border-gray-100">
		<table class="min-w-full divide-y divide-gray-300">
			<thead>
			<tr>
				<th scope="col"
					class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 lg:pl-8">
					Codice Prodotto
				</th>
				<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Descrizione</th>
				<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Quantità singola</th>
				<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Quantità totale</th>
				<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
					Unità di misura
				</th>
			</tr>
			</thead>
			<tbody class="divide-y divide-gray-200 bg-white">
			@php($materials = $production_order->materials->load('product.unit'))
			@foreach($materials as $material)
				@php($need = $material->quantity * $production_order->serials->where('completed', 0)->count())
				<tr class="hover:bg-gray-50" wire:key="{{ $material->id }}">
					<td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
						{{ $material->product->code }}
					</td>
					<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $material->product->description }}</td>
					<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $material->quantity }}</td>
					<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $need }}</td>
					<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $material->product->unit->description }}</td>
				</tr>
			@endforeach
			</tbody>
		</table>

{{--		<dl class="divide-y divide-gray-100 grid sm:grid-cols-2 items-start sm:gap-4">--}}
{{--			<div class="px-4 py-6 sm:grid sm:grid-cols-1 sm:gap-4 sm:items-center sm:px-6">--}}
{{--				<dt class="text-sm font-medium text-gray-900">Totale</dt>--}}
{{--				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">--}}
{{--					@php($products = $production_order->load('serials')->product()->with('locations')->get())--}}
{{--					@php($materials = $production_order->materials->load('product.unit'))--}}
{{--					@foreach($materials as $material)--}}
{{--						<div class="flex items-center mb-0.5">--}}
{{--							@php($count = $material->product->locations->where('type', 'produzione')->sum('pivot.quantity'))--}}
{{--							@php($need = $material->quantity * $production_order->serials->where('completed', 0)->count())--}}
{{--							@switch($count)--}}
{{--								@case(0)--}}
{{--									<div class="w-3 h-3 rounded-full bg-red-500"></div>--}}
{{--									@break--}}
{{--								@case($count < $need)--}}
{{--									<div class="w-3 h-3 rounded-full bg-yellow-400"></div>--}}
{{--									@break--}}
{{--								@case($count >= $need)--}}
{{--									<div class="w-3 h-3 rounded-full bg-green-500"></div>--}}
{{--									@break--}}
{{--							@endswitch--}}
{{--							<p class="mr-2">--}}
{{--								<span class="font-bold">{{ $need }}{{ $material->product->unit->abbreviation }}</span>--}}
{{--								&times; {{ $material->product->description }}--}}
{{--							</p>--}}
{{--							<div--}}
{{--								class="inline-flex items-center space-x-1 rounded-md bg-gray-50 px-2 py-1 mr-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">--}}
{{--								<span class="font-bold leading-none">{{ $count }}</span>--}}
{{--							</div>--}}
{{--						</div>--}}
{{--					@endforeach--}}
{{--				</dd>--}}
{{--			</div>--}}
{{--			<div class="px-4 py-6 sm:grid sm:grid-cols-1 sm:gap-4 sm:items-center sm:px-6">--}}
{{--				<dt class="text-sm font-medium text-gray-900">Singolo</dt>--}}
{{--				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">--}}
{{--					@foreach($materials as $material)--}}
{{--						<p class="flex items-center mb-0.5">--}}
{{--							{{ $material->quantity }} &times; {{ $material->product->description }}</p>--}}
{{--					@endforeach--}}
{{--				</dd>--}}
{{--			</div>--}}
{{--		</dl>--}}
	</div>
	<div class="py-4 px-4 flex justify-end space-x-3 sm:px-6">
		<x-secondary-button type="button" wire:click="$emit('closeModal')">Chiudi</x-secondary-button>
	</div>
</div>
