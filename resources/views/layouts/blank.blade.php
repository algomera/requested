<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>
		DDT
	</title>

{{--	<style>--}}
{{--		body {--}}
{{--			width: 21cm;--}}
{{--			height: 29.7cm;--}}
{{--			margin: 30mm 45mm 30mm 45mm !important;--}}
{{--		}--}}
{{--	</style>--}}

	@vite(['resources/css/app.css', 'resources/js/app.js'])
	@livewireStyles
</head>
<body class="mx-28 print:mx-0">
{{ $slot }}

@livewireScripts
</body>
