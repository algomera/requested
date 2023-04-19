@props(['disabled' => false, 'required' => false, 'name', 'label' => false, 'hint' => false, 'append' => false, 'prepend' => false, 'iconColor' => 'text-gray-800'])
@php
	$n = $attributes->wire('model')->value() ?: $name;
	$slug = $attributes->wire('model')->value() ?: $n;
	$inputClass = 'h-12 appearance-none w-full rounded sm:text-sm focus:ring focus:ring-opacity-50';
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

	<div>
		@if($label || isset($action))
			<div class="flex items-center justify-between">
				@if ($label)
					<x-input-label :for="$name" :required="$required">{{ $label }}</x-input-label>
				@endif
				@isset($action)
					{{ $action }}
				@endisset
			</div>
		@endif
		<div class="relative @if($label || isset($action)) mt-1 @endif">
			@if($prepend)
				<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
					<x-icon name="{{$prepend}}" class="{{ $iconColor }} w-5 h-5"></x-icon>
				</div>
			@endif
			<select {{ $attributes->merge(['class' => $inputClass]) }} {{ $disabled ? 'disabled' : '' }} name="{{ $slug }}"
			        id="{{ $slug }}" {{ $required ? 'required' : '' }}>
				{{ $slot }}
			</select>
			@error($slug)
			<div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
				<x-icon
						name="heroicon-o-exclamation-circle"
						class="w-5 h-5 text-red-500"
				></x-icon>
			</div>
			@else
				@if($append)
					<div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
						<x-icon name="{{$append}}" class="{{ $iconColor }} w-5 h-5"></x-icon>
					</div>
				@endif
				@enderror
		</div>
		@if($hint)
			<p class="mt-1 text-xs text-gray-500">{{ $hint }}</p>
		@endif
		<x-input-error :messages="$errors->get($slug)"></x-input-error>
	</div>