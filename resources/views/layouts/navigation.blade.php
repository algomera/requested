<ul role="list">
	{{-- Normal Item --}}
	<li class="">
		<a class="block py-1 text-sm text-zinc-600 transition hover:text-zinc-900"
		   href="/">
			Dashboard
		</a>
	</li>
	{{-- MENU ITEMS --}}
	<li class="relative mt-4">
		{{-- Section Title --}}
		<h2 class="text-xs font-semibold text-zinc-900">Guides</h2>
		<div class="relative mt-3 pl-2">
			<div class="absolute inset-y-0 left-2 w-px bg-zinc-900/10"></div>
			<div class="absolute left-2 top-1 h-6 w-px bg-emerald-500"></div>
			<ul role="list" class="border-l border-transparent">
				<li class="relative">
					{{-- First Level Item --}}
					<a aria-current="page"
					   class="flex justify-between gap-2 py-1 pr-3 text-sm transition pl-4 text-zinc-900"
					   href="/">
						<span class="truncate">Introduction</span>
					</a>
					{{-- Second Level Items --}}
					<ul role="list" style="opacity:1">
						<li>
							<a class="flex justify-between gap-2 py-1 pr-3 text-sm transition pl-7 text-zinc-600 hover:text-zinc-900"
							   href="#">
								<span class="truncate">Guides</span>
							</a>
						</li>
						<li>
							<a class="flex justify-between gap-2 py-1 pr-3 text-sm transition pl-7 text-zinc-600 hover:text-zinc-900"
							   href="#">
								<span class="truncate">Resources</span>
							</a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</li>
	<li class="sticky bottom-0 z-10 mt-6 min-[416px]:hidden">
		<a class="inline-flex gap-0.5 justify-center overflow-hidden text-sm font-medium transition rounded-full bg-zinc-900 py-1 px-3 text-white hover:bg-zinc-700 w-full"
		   href="#">
			Sign in
		</a>
	</li>
</ul>