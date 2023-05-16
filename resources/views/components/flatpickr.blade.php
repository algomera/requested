@props(['disabled' => false, 'for' => '', 'label' => ''])
<div>
	@if($for && $label)
		<x-input-label for="{{ $for }}" class="block text-sm font-medium text-gray-700">{{ $label }}</x-input-label>
	@endif
	<div
			wire:ignore
			x-data="{
				value: @entangle($attributes->wire('model')),
				init() {
				    let picker = flatpickr(this.$refs.picker, {
				        locale: 'it',
				        mode: 'single',
				        dateFormat: 'd-m-Y',
				        defaultDate: this.value,
				        onChange: (date, dateString) => {
				            this.value = dateString.split(' al ')
				            Livewire.emit('daterange_changed', this.value)
				        }
				    })

{{--				    this.$watch('value', () => picker.setDate(this.value))--}}
				},
			}"
			class="min-w-max w-full @if($for && $label) mt-1 @endif">
		<input class="w-52 min-w-full rounded-md border border-gray-200 px-3 py-2 sm:text-sm" x-ref="picker" type="text">
		<x-input-error :messages="$errors->get($for)"></x-input-error>
	</div>
</div>
