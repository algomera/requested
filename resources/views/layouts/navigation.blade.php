<ul role="list" class="space-y-4">
	{{-- Normal Item --}}
	<li>
		<a class="{{ request()->is('dashboard') ? 'text-emerald-500 font-medium' : 'text-zinc-600 hover:text-zinc-900' }} block py-1 text-sm transition"
		   href="{{ route('dashboard') }}">
			Dashboard
		</a>
	</li>
	{{-- MENU ITEMS --}}
	<li>
		<h2 class="text-xs font-semibold text-zinc-900">Magazzino</h2>
		<div class="relative mt-3 pl-2">
			<div class="absolute inset-y-0 left-2 w-px bg-zinc-900/10"></div>
			<ul role="list" class="border-l border-transparent">
				<li class="relative">
					<a class="{{ request()->is('locations*') ? 'text-zinc-900 font-medium' : 'text-zinc-600 hover:text-zinc-900' }} flex justify-between gap-2 py-1 pr-3 text-sm transition pl-4"
					   href="{{ route('locations.index') }}">
						@if(request()->is('locations*'))
							<div class="absolute -left-px top-1 h-6 w-px bg-emerald-500"></div>
						@endif
						<span class="truncate">Ubicazioni</span>
					</a>
				</li>
				<li class="relative">
					<a class="{{ request()->is('products*') ? 'text-zinc-900 font-medium' : 'text-zinc-600 hover:text-zinc-900' }} flex justify-between gap-2 py-1 pr-3 text-sm transition pl-4"
					   href="{{ route('products.index') }}">
						@if(request()->is('products*'))
							<div class="absolute -left-px top-1 h-6 w-px bg-emerald-500"></div>
						@endif
						<span class="truncate">Anagrafica Prodotti</span>
					</a>
				</li>
				<li class="relative">
					<a class="{{ request()->is('items*') ? 'text-zinc-900 font-medium' : 'text-zinc-600 hover:text-zinc-900' }} flex justify-between gap-2 py-1 pr-3 text-sm transition pl-4"
					   href="{{ route('items.index') }}">
						@if(request()->is('items*'))
							<div class="absolute -left-px top-1 h-6 w-px bg-emerald-500"></div>
						@endif
						<span class="truncate">Articoli</span>
					</a>
				</li>
			</ul>
		</div>
	</li>
	<li>
		<a class="{{ request()->is('production-orders*') ? 'text-emerald-500 font-medium' : 'text-zinc-600 hover:text-zinc-900' }} block py-1 text-sm transition"
		   href="{{ route('production-orders.index') }}">
			Ordini di Produzione
		</a>
	</li>
	<li>
		<h2 class="text-xs font-semibold text-zinc-900">Anagrafiche</h2>
		<div class="relative mt-3 pl-2">
			<div class="absolute inset-y-0 left-2 w-px bg-zinc-900/10"></div>
			<ul role="list" class="border-l border-transparent">
				<li class="relative">
					<a class="{{ request()->is('users*') ? 'text-emerald-500 font-medium' : 'text-zinc-600 hover:text-zinc-900' }} block py-1 text-sm transition pl-4"
					   href="{{ route('users.index') }}">
						@if(request()->is('users*'))
							<div class="absolute -left-px top-1 h-6 w-px bg-emerald-500"></div>
						@endif
						<span class="truncate">Utenti</span>
					</a>
				</li>
				<li class="relative">
					<a class="{{ request()->is('suppliers*') ? 'text-emerald-500 font-medium' : 'text-zinc-600 hover:text-zinc-900' }} block py-1 text-sm transition pl-4"
					   href="{{ route('suppliers.index') }}">
						@if(request()->is('suppliers*'))
							<div class="absolute -left-px top-1 h-6 w-px bg-emerald-500"></div>
						@endif
						<span class="truncate">Fornitori</span>
					</a>
				</li>
				<li class="relative">
					<a class="{{ request()->is('destinations*') ? 'text-emerald-500 font-medium' : 'text-zinc-600 hover:text-zinc-900' }} block py-1 text-sm transition pl-4"
					   href="{{ route('destinations.index') }}">
						@if(request()->is('destinations*'))
							<div class="absolute -left-px top-1 h-6 w-px bg-emerald-500"></div>
						@endif
						<span class="truncate">Destinazioni</span>
					</a>
				</li>
			</ul>
		</div>
	</li>
	<li>
		<h2 class="text-xs font-semibold text-zinc-900">Altro</h2>
		<div class="relative mt-3 pl-2">
			<div class="absolute inset-y-0 left-2 w-px bg-zinc-900/10"></div>
			<ul role="list" class="border-l border-transparent">
				<li class="relative">
					<a class="{{ request()->is('logs*') ? 'text-emerald-500 font-medium' : 'text-zinc-600 hover:text-zinc-900' }} block py-1 text-sm transition pl-4"
					   href="#">
						@if(request()->is('logs*'))
							<div class="absolute -left-px top-1 h-6 w-px bg-emerald-500"></div>
						@endif
						<span class="truncate">Log di sistema</span>
					</a>
				</li>
			</ul>
		</div>
	</li>
</ul>