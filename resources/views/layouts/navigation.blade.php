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
{{--					<ul role="list" style="opacity:1">--}}
{{--						<li>--}}
{{--							<a class="flex justify-between gap-2 py-1 pr-3 text-sm transition pl-7 text-zinc-600 hover:text-zinc-900"--}}
{{--							   href="#">--}}
{{--								<span class="truncate">Item 1.1</span>--}}
{{--							</a>--}}
{{--						</li>--}}
{{--						<li>--}}
{{--							<a class="flex justify-between gap-2 py-1 pr-3 text-sm transition pl-7 text-zinc-600 hover:text-zinc-900"--}}
{{--							   href="#">--}}
{{--								<span class="truncate">Item 1.2</span>--}}
{{--							</a>--}}
{{--						</li>--}}
{{--					</ul>--}}
				</li>
			</ul>
		</div>
	</li>
	<li>
		<a class="{{ request()->is('users*') ? 'text-emerald-500 font-medium' : 'text-zinc-600 hover:text-zinc-900' }} block py-1 text-sm transition"
		   href="{{ route('users.index') }}">
			Utenti
		</a>
	</li>
	<li>
		<a class="{{ request()->is('suppliers*') ? 'text-emerald-500 font-medium' : 'text-zinc-600 hover:text-zinc-900' }} block py-1 text-sm transition"
		   href="{{ route('suppliers.index') }}">
			Fornitori
		</a>
	</li>
</ul>