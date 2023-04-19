{{--
$this->dispatchBrowserEvent('open-notification', [
	'title'    => __('Profilo Salvato'),
	'subtitle' => __('Hai salvato il tuo profilo con successo!'),
	'type' => 'success',
	'actions'  => [
		'primary' => [
			'label' => __('Vedi'),
			'url'   => '#',
			'target' => 'self',
		]
	]
]);
--}}

<div
		x-data="{ open: false, data: '' }"
		x-ref="notification"
		x-on:open-notification.window="
		open = true;
		data = $event.detail;
		setTimeout(() => open = false, 7000);
	"
		class="fixed inset-0 z-50 flex items-end justify-center px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end">
	<div
			x-show="open"
			x-cloak
			x-transition:enter="transform ease-out duration-300 transition"
			x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
			x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
			x-transition:leave="transition ease-in duration-100"
			x-transition:leave-start="opacity-100"
			x-transition:leave-end="opacity-0"
			class="w-full max-w-sm overflow-hidden bg-white rounded-lg shadow-lg pointer-events-auto ring-1 ring-black ring-opacity-5">
		<div class="p-4">
			<div class="flex items-start">
				<div class="flex-shrink-0">

					<template x-if="data.type === 'error'">
						<svg class="w-6 h-6 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none"
						     viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
						</svg>
					</template>
					<template x-if="data.type === 'warning'">
						<svg class="w-6 h-6 text-yellow-400" xmlns="http://www.w3.org/2000/svg" fill="none"
						     viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
						</svg>
					</template>
					<template x-if="data.type === 'success' || !data.type">
						<svg class="w-6 h-6 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none"
						     viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
						</svg>
					</template>
				</div>
				<div class="ml-3 w-0 flex-1 pt-0.5">
					<p class="text-sm font-medium text-gray-900" x-text="data.title"></p>
					<p class="mt-1 text-sm text-gray-500" x-text="data.subtitle"></p>
					<template x-if="data.actions">
						<div class="mt-2">
							<template x-if="data.actions.primary">
								<a :href="data.actions.primary.url" :target="data.actions.primary.target"
								   class="text-sm font-medium text-brand-purple bg-white rounded-md hover:text-brand-purple-light focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-purple"
								   x-text="data.actions.primary.label"></a>
							</template>
							<button x-on:click="open = false"
							        class="ml-6 text-sm font-medium text-gray-400 bg-white rounded-md hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
								{{ __('Chiudi') }}
							</button>
						</div>
					</template>
				</div>
				<div class="flex flex-shrink-0 ml-4">
					<button x-on:click="open = false"
					        class="inline-flex text-gray-400 bg-white rounded-md hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
						<span class="sr-only">Close</span>
						<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
						     aria-hidden="true">
							<path fill-rule="evenodd"
							      d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
							      clip-rule="evenodd"/>
						</svg>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>