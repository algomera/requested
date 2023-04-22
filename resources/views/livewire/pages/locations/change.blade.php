<div class="overflow-hidden bg-white shadow sm:rounded-lg">
	<div class="px-4 py-6 sm:px-6">
		<h3 class="text-base font-semibold leading-7 text-gray-900">Informazioni ubicazione</h3>
	</div>
	<div class="border-t border-gray-100 px-4 py-6 sm:px-6">
		<p class="text-center">Attualmente il prodotto <span class="font-bold">{{ $product->name}}</span> si trova nella location <span class="font-bold">{{ $current_location->code }}</span>.</p>
		<p class="text-center text-sm">In quale location vuoi spostare il prodotto?</p>
	</div>
</div>