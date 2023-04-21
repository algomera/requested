<form wire:submit.prevent="save" class="overflow-hidden bg-white shadow sm:rounded-lg">
	<div class="px-4 py-4 sm:px-6">
		<h3 class="text-base font-semibold leading-7 text-gray-900">Modifica utente: {{ $user->fullName }}</h3>
		<p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">Informazioni personali e dettagli</p>
	</div>
	<div class="px-4 sm:px-6 py-4">
		<div class="block">
			<nav class="flex space-x-4" aria-label="Tabs">
				@foreach($tabs as $k => $label)
					<span wire:click="$set('selectedTab', '{{ $k }}')"
					      class="{{ $selectedTab === $k ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:text-gray-700 hover:cursor-pointer' }} rounded-md px-3 py-2 text-sm font-medium">{{ $label }}</span>
				@endforeach
			</nav>
		</div>
	</div>

	<div class="border-t border-gray-100">
		@if($selectedTab === 'informations')
			<dl class="divide-y divide-gray-100">
				<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center sm:px-6">
					<dt class="text-sm font-medium text-gray-900">Nome</dt>
					<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
						<x-input wire:model.defer="user.first_name" type="text"></x-input>
					</dd>
				</div>
				<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center sm:px-6">
					<dt class="text-sm font-medium text-gray-900">Cognome</dt>
					<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
						<x-input wire:model.defer="user.last_name" type="text"></x-input>
					</dd>
				</div>
				<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center sm:px-6">
					<dt class="text-sm font-medium text-gray-900">Cellulare</dt>
					<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
						<x-input wire:model.defer="user.phone" type="tel"></x-input>
					</dd>
				</div>
				<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center sm:px-6">
					<dt class="text-sm font-medium text-gray-900">Email</dt>
					<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
						<x-input wire:model.defer="user.email" type="email"></x-input>
					</dd>
				</div>
				<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center sm:px-6">
					<dt class="text-sm font-medium text-gray-900">Password</dt>
					<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
						<x-input wire:model.defer="password" type="password"
						         hint="Lasciare vuoto per non modificare"></x-input>
					</dd>
				</div>
				{{--			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">--}}
				{{--				<dt class="text-sm font-medium text-gray-900">Ruolo</dt>--}}
				{{--				<dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $user->role->label }}</dd>--}}
				{{--			</div>--}}
			</dl>
		@endif
		@if($selectedTab === 'roles')
			<div class="px-4 py-6 sm:px-6">
				<div class="grid grid-cols-2 gap-3">
					@foreach($roles as $role)
						<label class="{{ $new_role === $role->name ? 'bg-indigo-600 text-white hover:bg-indigo-500' : '' }} flex items-center justify-center rounded-md py-3 px-3 text-xs font-semibold uppercase sm:flex-1 cursor-pointer focus:outline-none">
							<input wire:model="new_role" type="radio" name="role" value="{{$role->name}}"
							       class="sr-only" aria-labelledby="role-{{$role->name}}-label">
							<span id="role-{{$role->id}}-label">{{ $role->label }}</span>
						</label>
					@endforeach
				</div>
				@if($new_role === 'warehouseman')
					<div class="mt-5">
						<fieldset>
							<div class="space-y-5">
								@foreach($permissions as $permission)
									<div class="relative flex items-start">
										<div class="flex h-6 items-center">
											<input wire:model="new_permissions" value="{{ $permission->id }}" id="permission-{{ $permission->id }}" name="permissions" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
										</div>
										<div class="ml-3 text-sm leading-6">
											<label for="permission-{{ $permission->id }}" class="font-medium text-gray-900">{{ $permission->name }}</label>
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
	<div class="p-6 sm:flex sm:flex-row-reverse">
		<button type="submit"
		        class="inline-flex w-full justify-center rounded-md bg-zinc-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-zinc-700 sm:ml-3 sm:w-auto">
			Salva
		</button>
		<button wire:click="$emit('closeModal')" type="button"
		        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
			Annulla
		</button>
	</div>
</form>