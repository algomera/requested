<div class="px-4 py-6 sm:px-6">
	<h3 class="mb-2 text-sm text-center">DDTs</h3>
	<ul role="list" class="space-y-6">
		@forelse($ddts as $ddt)
			<li class="relative flex items-center gap-x-4">
				@if(!$loop->last)
					<div class="absolute left-0 top-0 flex w-6 justify-center -bottom-6">
						<div class="w-px bg-gray-200"></div>
					</div>
				@endif
				<div class="relative flex h-6 w-6 flex-none items-center justify-center bg-white">
					<div class="h-1.5 w-1.5 rounded-full bg-gray-100 ring-1 ring-gray-300"></div>
				</div>
				<p class="flex-auto py-0.5 text-xs leading-5 text-gray-500">
					<span class="block font-medium text-gray-900">DDT: {{ $ddt->code }}</span>
				</p>
				<span x-tooltip="{{ $ddt->created_at->format('d-m-Y H:i:s') }}"
					  class="inline-block flex-none py-0.5 text-xs leading-5 text-gray-500">
								{{ $ddt->created_at->diffForHumans() }}
							</span>
					<a href="{{ route('ddt.show', $ddt->id) }}" target="_blank">
						<x-heroicon-s-printer
							class="w-4 h-4 hover:cursor-pointer hover:text-indigo-500"></x-heroicon-s-printer>
					</a>
			</li>
		@empty
			<p class="text-center mt-3 text-gray-500 text-xs">Al momento non c'è nessun DDT generato.</p>
		@endforelse
	</ul>
</div>
