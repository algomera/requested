<form wire:submit.prevent="save" class="overflow-hidden bg-white shadow sm:rounded-lg">
	<div class="px-4 py-6 sm:px-6">
		<h3 class="text-base font-semibold leading-7 text-gray-900">Nuovo utente</h3>
		<p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">Informazioni personali e dettagli</p>
	</div>
	<div class="px-4 sm:px-6 py-4">
		<div class="block">
			<nav class="flex space-x-4" aria-label="Tabs">
				<span wire:click="$set('selectedTab', 'informations')"
				      class="{{ $selectedTab === 'informations' ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:text-gray-700 hover:cursor-pointer' }} rounded-md px-3 py-2 text-sm font-medium">Informazioni</span>
				@if($new_role === 'warehouseman')
					<span wire:click="$set('selectedTab', 'permissions')"
					      class="{{ $selectedTab === 'permissions' ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:text-gray-700 hover:cursor-pointer' }} rounded-md px-3 py-2 text-sm font-medium">Permessi</span>
				@endif
			</nav>
		</div>
	</div>
	<div class="border-t border-gray-100">
		@if($selectedTab === 'informations')
			<dl class="divide-y divide-gray-100">
				<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center sm:px-6">
					<dt class="text-sm font-medium text-gray-900">Ruolo</dt>
					<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
						<div class="grid grid-cols-2 gap-3">
							@foreach($roles as $role)
								<label class="{{ $new_role === $role->name ? 'bg-indigo-600 text-white hover:bg-indigo-500' : 'ring-1 ring-inset ring-gray-300 bg-white text-gray-900 hover:bg-gray-50' }} flex items-center justify-center rounded-md py-3 px-3 text-xs font-semibold uppercase sm:flex-1 cursor-pointer focus:outline-none">
									<input wire:model="new_role" type="radio" name="role" value="{{$role->name}}"
									       class="sr-only" aria-labelledby="role-{{$role->name}}-label">
									<span id="role-{{$role->id}}-label">{{ $role->label }}</span>
								</label>
							@endforeach
						</div>
						@error('new_role')
						<x-input-error messages="{{ $message }}"/>
						@enderror
					</dd>
				</div>
				<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center sm:px-6">
					<dt class="text-sm font-medium text-gray-900">Nome</dt>
					<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
						<x-input wire:model.defer="first_name" type="text"></x-input>
					</dd>
				</div>
				<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center sm:px-6">
					<dt class="text-sm font-medium text-gray-900">Cognome</dt>
					<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
						<x-input wire:model.defer="last_name" type="text"></x-input>
					</dd>
				</div>
				<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center sm:px-6">
					<dt class="text-sm font-medium text-gray-900">Cellulare</dt>
					<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
						<x-input wire:model.defer="phone" type="tel"></x-input>
					</dd>
				</div>
				<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center sm:px-6">
					<dt class="text-sm font-medium text-gray-900">Email</dt>
					<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
						<x-input wire:model.defer="email" type="email"></x-input>
					</dd>
				</div>
				<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center sm:px-6">
					<dt class="text-sm font-medium text-gray-900">Sicurezza</dt>
					<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
						<div class="grid grid-cols-1 gap-4">
							<x-input wire:model.defer="password" type="password" label="Password"></x-input>
							<x-input wire:model.defer="password_confirmation" type="password"
							         label="Conferma Password"></x-input>
						</div>
					</dd>
				</div>
			</dl>
		@endif
		@if($selectedTab === 'permissions')
			<div class="px-4 py-6 sm:px-6">
				@if($new_role === 'warehouseman')
					<div class="mt-5">
						<fieldset>
							<div class="space-y-5">
								@foreach($permissions as $permission)
									<div class="relative flex items-start">
										<div class="flex h-6 items-center">
											<input wire:model="new_permissions" value="{{ $permission->id }}"
											       id="permission-{{ $permission->id }}" name="permissions"
											       type="checkbox"
											       class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
										</div>
										<div class="ml-3 text-sm leading-6">
											<label for="permission-{{ $permission->id }}"
											       class="font-medium text-gray-900">{{ $permission->name }}</label>
										</div>
									</div>
								@endforeach
							</div>
						</fieldset>
					</div>
				@endif
			</div>
		@endif
	</div>
	<div class="py-4 px-4 flex justify-end space-x-3 sm:px-6">
		<x-secondary-button type="button" wire:click="$emit('closeModal')">Annulla</x-secondary-button>
		<x-primary-button>Salva</x-primary-button>
	</div>
</form>