<div class="overflow-hidden bg-white shadow sm:rounded-lg">
	<div class="px-4 py-6 sm:px-6">
		<h3 class="text-base font-semibold leading-7 text-gray-900">{{ $production_order->item->product->name }}</h3>
	</div>
	<div class="border-t border-gray-100">
		<dl class="divide-y divide-gray-100">
			<div class="px-4 py-6 sm:grid sm:grid-cols-1 sm:gap-4 sm:items-center sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Totale materiali</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
					@foreach($production_order->item->products as $product)
						<div class="flex items-center mb-0.5">
							@php($count = $product->locations()->where('type', 'produzione')->get()->sum('pivot.quantity')) {{-- 3 --}}
							@php($need = $production_order->serials()->where('completed', 0)->count()) {{-- 4 --}}
							@switch($count)
								@case(0)
									<div class="w-3 h-3 rounded-full bg-red-500"></div>
								@break
								@case($count < $need)
									<div class="w-3 h-3 rounded-full bg-yellow-400"></div>
									@break
								@case($count >= $need)
									<div class="w-3 h-3 rounded-full bg-green-500"></div>
									@break
							@endswitch
							<p class="ml-1 mr-2">
								{{ $product->pivot->quantity * $production_order->serials()->where('completed', 0)->count() }} &times; {{ $product->name }}
							</p>
							<div class="inline-flex items-center space-x-1 rounded-md bg-gray-50 px-2 py-1 mr-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
								<span class="font-bold leading-none">{{ $product->locations()->where('type', 'produzione')->get()->sum('pivot.quantity') }}</span>
							</div>
						</div>
					@endforeach
				</dd>
			</div>
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Singolo</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
					@foreach($production_order->item->products as $product)
						<p>{{ $product->pivot->quantity }} &times; {{ $product->name }}</p>
					@endforeach
				</dd>
			</div>
		</dl>
	</div>
	<div class="py-4 px-4 flex justify-end space-x-3 sm:px-6">
		<x-secondary-button type="button" wire:click="$emit('closeModal')">Chiudi</x-secondary-button>
	</div>
</div>
