<div class="space-y-3">
	<p>Production Order: {{ $production_order->code }}</p>
	<hr>
	<p>Articoli da produrre: {{ $production_order->quantity }} &times; {{ $production_order->item->product->name }}</p>
	<hr>
	<div class="space-y-2">
		<p>Materiali da utilizzare:</p>
		<div>
			<p class="font-bold">Totale:</p>
			@foreach($production_order->item->products as $product)
				<p>{{ $product->pivot->quantity * $production_order->quantity }} &times; {{ $product->name }}</p>
			@endforeach
		</div>
		<div>
			<p class="font-bold">Singolo:</p>
			@foreach($production_order->item->products as $product)
				<p>{{ $product->pivot->quantity }} &times; {{ $product->name }}</p>
			@endforeach
		</div>
	</div>
</div>
