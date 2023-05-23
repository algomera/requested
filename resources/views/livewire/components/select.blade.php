@props(['event' => null, 'to' => null, 'disabled' => false, 'required' => false, 'name', 'label' => false, 'hint' => false, 'append' => false, 'prepend' => false, 'iconColor' => 'text-gray-800'])
@php
	$n = $attributes->wire('model')->value() ?: $name;
	$slug = $attributes->wire('model')->value() ?: $n;
	$inputClass = 'appearance-none w-full rounded sm:text-sm focus:ring focus:ring-opacity-50';
@endphp
@error($slug)
@php
	$inputClass .= ' pr-11 border-red-300 focus:outline-none text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500';
@endphp
@else
	@php
		$inputClass .= ' border-gray-300 focus:border-indigo-300 focus:ring-indigo-200';
	@endphp
	@enderror
	@if($prepend)
		@php
			$inputClass .= ' pl-11';
		@endphp
	@endif
	@if($append)
		@php
			$inputClass .= ' pr-11';
		@endphp
	@endif

	<div
		x-data="{
			active: false,
			query: @entangle('query'),
			selected: @entangle('selected'),
			title: @entangle('title2'),
			oldTitle: @entangle('oldTitle'),
			value() {
				let val = '';
				if(!this.selected && !this.active && !this.query) {
					val = 'Seleziona'
				}
				if(!this.selected || this.query) {
					val = this.query;
				} else if(this.selected && this.active) {
					val = this.title;
				} else if(this.selected && !this.active) {
					val = this.oldTitle
				}
				return val;
			}
		}"
		x-init="
			$watch('active', () => {
				$nextTick(() => {
					title = '';
					query = '';
				})
		})"
	>
		@if($label || isset($action))
			<div class="flex items-center justify-between">
				@if ($label)
					<x-input-label :for="$slug" :required="$required">{{ $label }}</x-input-label>
				@endif
				@isset($action)
					{{ $action }}
				@endisset
			</div>
		@endif
		<div class="relative @if($label || isset($action)) mt-1 @endif">
			@if($prepend)
				<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
					<x-icon name="{{$prepend}}" class="{{ $iconColor }} w-4 h-4"></x-icon>
				</div>
			@endif
			<input x-on:focus="active = true" x-on:click.outside="active = false" :value="value"
				   type="text"
				   tabindex="-1"
				   wire:ignore.self
				   wire:model.debounce.500ms="query"
				   {{ $attributes->merge(['class' => $inputClass]) }}
				   {{ $disabled ? 'disabled' : '' }}
				   {{ $required ? 'required' : '' }}
				   name="{{ $slug }}"
				   id="{{ $slug }}"
			/>
			@error($slug)
			<div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
				<x-icon
					name="heroicon-o-exclamation-circle"
					class="w-4 h-4 text-red-500"
				></x-icon>
			</div>
			@else
				@if($append)
					<div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
						<x-icon name="{{$append}}" class="{{ $iconColor }} w-4 h-4"></x-icon>
					</div>
				@endif
				@enderror
				<ul x-cloak x-show="active"
					class="absolute z-10 mt-2 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
					tabindex="-1">
					@if($items->count() > 0)
						@foreach($items as $item)
							<li wire:click="selectItem('{{ getTitle($titleToShow, $item) }}', {{ $item }}, '{{ $event }}', {{ $to }})"
								wire:key="{{ $item->id }}"
								class="text-gray-900 relative cursor-default select-none py-2 pl-3 pr-9 hover:cursor-pointer hover:bg-gray-200">
								<p class="{{ $selected && $selected == $item->$return ? 'font-semibold' : 'font-normal' }} truncate">{{ getTitle($titleToShow, $item) }}</p>
								<p class="text-xs text-gray-500">{{ getSubtitle($subtitleToShow, $item) }}</p>
								@if($selected && $selected == $item->$return)
									<div class="text-indigo-600 absolute inset-y-0 right-0 flex items-center pr-4">
										<svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
											<path fill-rule="evenodd"
												  d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
												  clip-rule="evenodd"/>
										</svg>
									</div>
								@endif
							</li>
						@endforeach
					@else
						<li class="select-none py-2 pl-3 pr-9">Nessun risultato</li>
					@endif
				</ul>
		</div>
		@if($hint)
			<p class="mt-1 text-xs text-gray-500">{{ $hint }}</p>
		@endif
		<x-input-error :messages="$errors->get($slug)"></x-input-error>
	</div>
