<form wire:submit.prevent="save" class="overflow-hidden bg-white shadow sm:rounded-lg">
	<div class="px-4 py-6 sm:px-6">
		<h3 class="text-base font-semibold leading-7 text-gray-900">Aggiungi le Matricole</h3>
	</div>
	<div class="border-t border-gray-100">
		<dl class="divide-y divide-gray-100">
			<div class="px-4 py-6 grid grid-cols-1 gap-4 sm:px-6">
				<dt class="flex items-center justify-between text-sm font-medium text-gray-900">
					<span>Matricole</span>
				</dt>
				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:mt-0">
					<ul role="list" class="divide-y divide-gray-200">
						@foreach($serials as $k => $serial)
							<li class="{{ $loop->iteration === 1 ? 'pt-0' : '' }} py-3 flex items-center justify-between">
								<div class="flex items-center w-full space-x-3">
									<div class="w-[50px] flex items-center justify-center bg-gray-50 text-xs py-2.5 px-3 rounded">
										{{ $loop->iteration }}
									</div>
									<div class="flex-1">
										<x-input wire:model.defer="serials.{{ $k }}" type="text"></x-input>
									</div>
								</div>
							</li>
						@endforeach
					</ul>
				</dd>
			</div>
		</dl>
	</div>
	<div class="py-4 px-4 flex justify-end space-x-3 sm:px-6">
		<x-secondary-button type="button" wire:click="$emit('closeModal')">Annulla</x-secondary-button>
		<x-primary-button>Salva</x-primary-button>
	</div>
</form>