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
		<a class="{{ request()->is('locations*') ? 'text-emerald-500 font-medium' : 'text-zinc-600 hover:text-zinc-900' }} block py-1 text-sm transition"
		   href="{{ route('locations.index') }}">
			<span class="truncate">Ubicazioni</span>
		</a>
	</li>
	<li>
		<a class="{{ request()->is('products*') ? 'text-emerald-500 font-medium' : 'text-zinc-600 hover:text-zinc-900' }} block py-1 text-sm transition"
		   href="{{ route('products.index') }}">
			<span class="truncate">Anagrafica Prodotti</span>
		</a>
	</li>
	<li>
		<a class="{{ request()->is('items*') ? 'text-emerald-500 font-medium' : 'text-zinc-600 hover:text-zinc-900' }} block py-1 text-sm transition"
		   href="{{ route('items.index') }}">
			<span class="truncate">Articoli - BOM</span>
		</a>
	</li>
	<li>
		<a class="{{ request()->is('production-orders*') ? 'text-emerald-500 font-medium' : 'text-zinc-600 hover:text-zinc-900' }} block py-1 text-sm transition"
		   href="{{ route('production-orders.index') }}">
			Ordini di Produzione
		</a>
	</li>
	<li>
		<a class="{{ request()->is('warehouse-orders*') ? 'text-emerald-500 font-medium' : 'text-zinc-600 hover:text-zinc-900' }} block py-1 text-sm transition"
		   href="{{ route('warehouse-orders.index') }}">
			Ordini di Magazzino
		</a>
	</li>
	<li>
		<a class="{{ request()->is('serials*') ? 'text-emerald-500 font-medium' : 'text-zinc-600 hover:text-zinc-900' }} block py-1 text-sm transition"
		   href="{{ route('serials.index') }}">
			Matricole
		</a>
	</li>
	<li>
		<span x-on:click="Livewire.emit('openModal', 'components.internal-transfer')"
			  class="block py-1 text-sm text-zinc-600 hover:text-zinc-900 hover:cursor-pointer transition">
			Trasferimento interno
		</span>
	</li>
	@role('admin')
	<li>
		<a class="{{ request()->is('users*') ? 'text-emerald-500 font-medium' : 'text-zinc-600 hover:text-zinc-900' }} block py-1 text-sm transition"
		   href="{{ route('users.index') }}">
			<span class="truncate">Utenti</span>
		</a>
	</li>
	@endif
	<li>
		<a class="{{ request()->is('suppliers*') ? 'text-emerald-500 font-medium' : 'text-zinc-600 hover:text-zinc-900' }} block py-1 text-sm transition"
		   href="{{ route('suppliers.index') }}">
			<span class="truncate">Fornitori</span>
		</a>
	</li>
	<li>
		<a class="{{ request()->is('destinations*') ? 'text-emerald-500 font-medium' : 'text-zinc-600 hover:text-zinc-900' }} block py-1 text-sm transition"
		   href="{{ route('destinations.index') }}">
			<span class="truncate">Destinazioni</span>
		</a>
	</li>
	<li>
		<a class="{{ request()->is('logs*') ? 'text-emerald-500 font-medium' : 'text-zinc-600 hover:text-zinc-900' }} block py-1 text-sm transition"
		   href="{{ route('logs.index') }}">
			<span class="truncate">Log di sistema</span>
		</a>
	</li>

	{{--	<li>--}}
	{{--		<h2 class="text-xs font-semibold text-zinc-900">Magazzino</h2>--}}
	{{--		<div class="relative mt-3 pl-2">--}}
	{{--			<div class="absolute inset-y-0 left-2 w-px bg-zinc-900/10"></div>--}}
	{{--			<ul role="list" class="border-l border-transparent">--}}
	{{--				<li class="relative group">--}}
	{{--					<a class="{{ request()->is('locations*') ? 'text-zinc-900 font-medium' : 'text-zinc-600 hover:text-zinc-900' }} flex justify-between gap-2 py-1 pr-3 text-sm transition pl-4"--}}
	{{--					   href="{{ route('locations.index') }}">--}}
	{{--						<div--}}
	{{--							class="absolute -left-px top-1 h-6 w-px {{ request()->is('locations*') ? 'bg-emerald-500' : 'group-hover:bg-gray-300' }}"></div>--}}
	{{--						<span class="truncate">Ubicazioni</span>--}}
	{{--					</a>--}}
	{{--				</li>--}}
	{{--				<li class="relative group">--}}
	{{--					<a class="{{ request()->is('products*') ? 'text-zinc-900 font-medium' : 'text-zinc-600 hover:text-zinc-900' }} flex justify-between gap-2 py-1 pr-3 text-sm transition pl-4"--}}
	{{--					   href="{{ route('products.index') }}">--}}
	{{--						<div--}}
	{{--							class="absolute -left-px top-1 h-6 w-px {{ request()->is('products*') ? 'bg-emerald-500' : 'group-hover:bg-gray-300' }}"></div>--}}
	{{--						<span class="truncate">Anagrafica Prodotti</span>--}}
	{{--					</a>--}}
	{{--				</li>--}}
	{{--				<li class="relative group">--}}
	{{--					<a class="{{ request()->is('items*') ? 'text-zinc-900 font-medium' : 'text-zinc-600 hover:text-zinc-900' }} flex justify-between gap-2 py-1 pr-3 text-sm transition pl-4"--}}
	{{--					   href="{{ route('items.index') }}">--}}
	{{--						<div--}}
	{{--							class="absolute -left-px top-1 h-6 w-px {{ request()->is('items*') ? 'bg-emerald-500' : 'group-hover:bg-gray-300' }}"></div>--}}
	{{--						<span class="truncate">Articoli</span>--}}
	{{--					</a>--}}
	{{--				</li>--}}
	{{--			</ul>--}}
	{{--		</div>--}}
	{{--	</li>--}}
	{{--	<li>--}}
	{{--		<h2 class="text-xs font-semibold text-zinc-900">Anagrafiche</h2>--}}
	{{--		<div class="relative mt-3 pl-2">--}}
	{{--			<div class="absolute inset-y-0 left-2 w-px bg-zinc-900/10"></div>--}}
	{{--			<ul role="list" class="border-l border-transparent">--}}
	{{--				<li class="relative group">--}}
	{{--					<a class="{{ request()->is('users*') ? 'text-emerald-500 font-medium' : 'text-zinc-600 hover:text-zinc-900' }} block py-1 text-sm transition pl-4"--}}
	{{--					   href="{{ route('users.index') }}">--}}
	{{--						<div--}}
	{{--							class="absolute -left-px top-1 h-6 w-px {{ request()->is('users*') ? 'bg-emerald-500' : 'group-hover:bg-gray-300' }}"></div>--}}
	{{--						<span class="truncate">Utenti</span>--}}
	{{--					</a>--}}
	{{--				</li>--}}
	{{--				<li class="relative group">--}}
	{{--					<a class="{{ request()->is('suppliers*') ? 'text-emerald-500 font-medium' : 'text-zinc-600 hover:text-zinc-900' }} block py-1 text-sm transition pl-4"--}}
	{{--					   href="{{ route('suppliers.index') }}">--}}
	{{--						<div--}}
	{{--							class="absolute -left-px top-1 h-6 w-px {{ request()->is('suppliers*') ? 'bg-emerald-500' : 'group-hover:bg-gray-300' }}"></div>--}}
	{{--						<span class="truncate">Fornitori</span>--}}
	{{--					</a>--}}
	{{--				</li>--}}
	{{--				<li class="relative group">--}}
	{{--					<a class="{{ request()->is('destinations*') ? 'text-emerald-500 font-medium' : 'text-zinc-600 hover:text-zinc-900' }} block py-1 text-sm transition pl-4"--}}
	{{--					   href="{{ route('destinations.index') }}">--}}
	{{--						<div--}}
	{{--							class="absolute -left-px top-1 h-6 w-px {{ request()->is('destinations*') ? 'bg-emerald-500' : 'group-hover:bg-gray-300' }}"></div>--}}
	{{--						<span class="truncate">Destinazioni</span>--}}
	{{--					</a>--}}
	{{--				</li>--}}
	{{--			</ul>--}}
	{{--		</div>--}}
	{{--	</li>--}}
	{{--	<li>--}}
	{{--		<h2 class="text-xs font-semibold text-zinc-900">Altro</h2>--}}
	{{--		<div class="relative mt-3 pl-2">--}}
	{{--			<div class="absolute inset-y-0 left-2 w-px bg-zinc-900/10"></div>--}}
	{{--			<ul role="list" class="border-l border-transparent">--}}
	{{--				<li class="relative group">--}}
	{{--					<a class="{{ request()->is('logs*') ? 'text-emerald-500 font-medium' : 'text-zinc-600 hover:text-zinc-900' }} block py-1 text-sm transition pl-4"--}}
	{{--					   href="{{ route('logs.index') }}">--}}
	{{--						<div--}}
	{{--							class="absolute -left-px top-1 h-6 w-px {{ request()->is('logs*') ? 'bg-emerald-500' : 'group-hover:bg-gray-300' }}"></div>--}}
	{{--						<span class="truncate">Log di sistema</span>--}}
	{{--					</a>--}}
	{{--				</li>--}}
	{{--			</ul>--}}
	{{--		</div>--}}
	{{--	</li>--}}
</ul>
