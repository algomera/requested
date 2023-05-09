<div class="px-4 py-6 sm:px-6">
	<h3 class="mb-2 text-sm text-center">Timeline</h3>
	<ul role="list" class="space-y-6">
		@forelse($logs as $log)
			<li class="relative flex gap-x-4">
				@if(!$loop->last)
					<div class="absolute left-0 top-0 flex w-6 justify-center -bottom-6">
						<div class="w-px bg-gray-200"></div>
					</div>
				@endif
				<div class="relative flex h-6 w-6 flex-none items-center justify-center bg-white">
					<div class="h-1.5 w-1.5 rounded-full bg-gray-100 ring-1 ring-gray-300"></div>
				</div>
				<p class="flex-auto py-0.5 text-xs leading-5 text-gray-500">
					<span class="font-medium text-gray-900">{{ $log->user->fullName }}</span>
					{{ $log->message }}
				</p>
				<span x-tooltip="{{ $log->created_at->format('d-m-Y H:i:s') }}"
				      class="flex-none py-0.5 text-xs leading-5 text-gray-500">
						{{ $log->created_at->diffForHumans() }}
					</span>
			</li>
		@empty
			<p class="text-center mt-3 text-gray-500 text-xs">Al momento non c'è nessuna operazione.</p>
		@endforelse
	</ul>
</div>
