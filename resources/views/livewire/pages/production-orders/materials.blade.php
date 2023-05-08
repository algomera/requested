<div class="overflow-hidden bg-white shadow sm:rounded-lg">
	<div class="px-4 py-6 sm:px-6">
		<h3 class="text-base font-semibold leading-7 text-gray-900">{{ $production_order->item->product->name }}</h3>
	</div>
	<div class="border-t border-gray-100">
		<dl class="divide-y divide-gray-100">
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center sm:px-6">
				<dt class="text-sm font-medium text-gray-900">Totale materiali</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
					@foreach($production_order->item->products as $product)
						<p>
							{{ $product->pivot->quantity * $production_order->quantity }} &times; {{ $product->name }}</p>
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