<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<title>{{ config('app.name', 'Laravel') }}</title>

		<!-- Fonts -->
		<link rel="preconnect" href="https://fonts.bunny.net">
		<link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

		<!-- Scripts -->
		@vite(['resources/css/app.css', 'resources/js/app.js'])

		@stack('styles')
		@livewireStyles
	</head>
	<body x-data="{sidebarOpen: false}" class="font-sans antialiased">
		{{--            @include('layouts.navigation')--}}
		<div class="lg:ml-72 xl:ml-80">
			<header class="contents lg:pointer-events-none lg:fixed lg:inset-0 lg:z-40 lg:flex">
				<div class="contents lg:pointer-events-auto lg:block lg:w-72 lg:overflow-y-auto lg:border-r lg:border-zinc-900/10 lg:px-6 lg:pb-8 lg:pt-4 xl:w-80">
					{{-- LOGO DESKTOP --}}
					<div class="hidden lg:flex">
						<a class="text-2xl" aria-label="Home" href="/">
							<x-application-logo></x-application-logo>
						</a>
					</div>
					{{-- TOPBAR --}}
					<div class="border-b fixed inset-x-0 top-0 z-50 flex h-14 items-center justify-between gap-12 px-4 transition sm:px-6 lg:left-72 lg:z-30 lg:px-8 xl:left-80 backdrop-blur-sm bg-white/[var(--bg-opacity-light)]">
						<div class="absolute inset-x-0 top-full h-px transition bg-zinc-900/7.5"></div>
						<div class="hidden lg:block lg:max-w-md lg:flex-auto">
							<button type="button"
							        class="hidden h-8 w-full items-center gap-2 rounded-full bg-white pl-2 pr-3 text-sm text-zinc-500 ring-1 ring-zinc-900/10 transition hover:ring-zinc-900/20 lg:flex focus:[&amp;:not(:focus-visible)]:outline-none">
								<svg viewBox="0 0 20 20" fill="none" aria-hidden="true" class="h-5 w-5 stroke-current">
									<path stroke-linecap="round" stroke-linejoin="round"
									      d="M12.01 12a4.25 4.25 0 1 0-6.02-6 4.25 4.25 0 0 0 6.02 6Zm0 0 3.24 3.25"></path>
								</svg>
								Find something...<kbd class="ml-auto text-xs text-zinc-400"><kbd
											class="font-sans">⌘</kbd><kbd class="font-sans">K</kbd></kbd></button>
						</div>
						<div class="flex items-center gap-5 lg:hidden">
							<button x-on:click="sidebarOpen = !sidebarOpen" type="button"
							        class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5"
							        aria-label="Toggle navigation">
								<x-heroicon-o-bars-3 x-cloak x-show="!sidebarOpen"
								                     class="w-4 stroke-zinc-900"></x-heroicon-o-bars-3>
								<x-heroicon-o-x-mark x-cloak x-show="sidebarOpen"
								                     class="w-4 stroke-zinc-900"></x-heroicon-o-x-mark>
							</button>
							<a class="text-lg" aria-label="Home" href="/">
								<x-application-logo></x-application-logo>
							</a>
						</div>
						<div class="flex items-center gap-5">
							<nav class="hidden md:block">
								<ul role="list" class="flex items-center gap-8">
									<li>
										<a class="text-sm leading-5 text-zinc-600 transition hover:text-zinc-900"
										   href="/">
											API
										</a>
									</li>
									<li>
										<a class="text-sm leading-5 text-zinc-600 transition hover:text-zinc-900"
										   href="#">
											Documentation
										</a>
									</li>
									<li>
										<a class="text-sm leading-5 text-zinc-600 transition hover:text-zinc-900"
										   href="#">
											Support
										</a>
									</li>
								</ul>
							</nav>
							<div class="hidden md:block md:h-5 md:w-px md:bg-zinc-900/10"></div>
							<div class="flex gap-4 lg:hidden">
								<div class="contents">
									<button type="button"
									        class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5 lg:hidden focus:[&amp;:not(:focus-visible)]:outline-none"
									        aria-label="Find something...">
										<svg viewBox="0 0 20 20" fill="none" aria-hidden="true"
										     class="h-5 w-5 stroke-zinc-900">
											<path stroke-linecap="round" stroke-linejoin="round"
											      d="M12.01 12a4.25 4.25 0 1 0-6.02-6 4.25 4.25 0 0 0 6.02 6Zm0 0 3.24 3.25"></path>
										</svg>
									</button>
								</div>
							</div>
							<div class="hidden min-[416px]:contents">
								<a class="inline-flex gap-0.5 justify-center overflow-hidden text-sm font-medium transition rounded-full bg-zinc-900 py-1 px-3 text-white hover:bg-zinc-700"
								   href="#">
									Sign in
								</a>
							</div>
						</div>
					</div>
					{{-- NAVIGATION MENU --}}
					<nav class="hidden lg:mt-8 lg:block">
						<ul role="list">
							{{-- MENU ITEMS: HIDDEN DESKTOP/VISIBLE MOBILE --}}
							<li class="md:hidden">
								<a class="block py-1 text-sm text-zinc-600 transition hover:text-zinc-900"
								   href="/">
									API
								</a>
							</li>
							<li class="md:hidden">
								<a class="block py-1 text-sm text-zinc-600 transition hover:text-zinc-900"
								   href="#">
									Documentation
								</a>
							</li>
							{{-- MENU ITEMS --}}
							<li class="relative mt-6 md:mt-0">
								<h2 class="text-xs font-semibold text-zinc-900">Guides</h2>
								<div class="relative mt-3 pl-2">
									<div class="absolute inset-y-0 left-2 w-px bg-zinc-900/10"></div>
									<div class="absolute left-2 top-1 h-6 w-px bg-emerald-500"></div>
									<ul role="list" class="border-l border-transparent">
										<li class="relative">
											{{-- MAIN ITEM --}}
											<a aria-current="page"
											   class="flex justify-between gap-2 py-1 pr-3 text-sm transition pl-4 text-zinc-900"
											   href="/">
												<span class="truncate">Introduction</span>
											</a>
											{{-- SUB ITEMS --}}
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
					</nav>
					{{-- MOBILE --}}
					<div x-cloak x-show="sidebarOpen"
					     class="fixed inset-0 bg-zinc-900/5 top-14 z-10 backdrop-blur-sm lg:hidden"></div>
					<div x-cloak x-show="sidebarOpen"
					     class="fixed top-14 bg-white px-6 pt-4 pb-8 z-20 h-full shadow-lg shadow-zinc-900/10 w-80 overflow-y-scroll lg:hidden">
						<ul role="list">
							{{-- MENU ITEMS: HIDDEN DESKTOP/VISIBLE MOBILE --}}
							<li class="md:hidden">
								<a class="block py-1 text-sm text-zinc-600 transition hover:text-zinc-900"
								   href="/">
									API
								</a>
							</li>
							<li class="md:hidden">
								<a class="block py-1 text-sm text-zinc-600 transition hover:text-zinc-900"
								   href="#">
									Documentation
								</a>
							</li>
							{{-- MENU ITEMS --}}
							<li class="relative mt-6 md:mt-0">
								<h2 class="text-xs font-semibold text-zinc-900">Guides</h2>
								<div class="relative mt-3 pl-2">
									<div class="absolute inset-y-0 left-2 w-px bg-zinc-900/10"></div>
									<div class="absolute left-2 top-1 h-6 w-px bg-emerald-500"></div>
									<ul role="list" class="border-l border-transparent">
										<li class="relative">
											{{-- MAIN ITEM --}}
											<a aria-current="page"
											   class="flex justify-between gap-2 py-1 pr-3 text-sm transition pl-4 text-zinc-900"
											   href="/">
												<span class="truncate">Introduction</span>
											</a>
											{{-- SUB ITEMS --}}
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
					</div>
				</div>
			</header>

			<div class="relative px-4 pt-14 sm:px-6 lg:px-8">
				<main class="py-8">
					{{ $slot }}
				</main>
			</div>

			<!-- Page Heading -->
			{{--			@if (isset($header))--}}
			{{--				<header class="bg-white shadow">--}}
			{{--					<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">--}}
			{{--						{{ $header }}--}}
			{{--					</div>--}}
			{{--				</header>--}}
			{{--			@endif--}}

			<!-- Page Content -->
			{{--			<main>--}}
			{{--				{{ $slot }}--}}
			{{--			</main>--}}
		</div>
		<x-notification></x-notification>
		@stack('scripts')
		@livewireScripts
		@livewire('livewire-ui-modal')
	</body>
</html>
