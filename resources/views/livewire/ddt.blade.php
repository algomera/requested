<div class="grid gap-1">
	<div class="text-center my-4 print:hidden">
		<x-primary-button onclick="window.print()" class="justify-center">Stampa</x-primary-button>
	</div>
	<div>
		<div class="ddt-box text-center">
			<p class="font-semibold">Documento di Trasporto</p>
			<span class="font-light text-xs">(D.P.R 14 Agosto 1996 n.472)</span>
		</div>
	</div>
	<div class="grid grid-cols-2 gap-1">
		<div class="ddt-box">
			<span class="label">destinatario</span>
			<div class="content">
				<p>Baltur S.p.a</p>
				<p>Via Ferrarese 10</p>
				<p>Cento (FE) 42042</p>
			</div>
		</div>
		<div class="ddt-box">
			<span class="label">luogo di destinazione della merce</span>
			<div class="content">
				<p>{{ $ddt->warehouse_order->destination->code }}</p>
				<p>{{ $ddt->warehouse_order->destination->description }}</p>
			</div>
		</div>
	</div>
	<div class="flex !border-x border-x-gray-400 !divide-x !divide-x-gray-400">
		<div class="flex-1 ddt-box !border-x-0 col-span-7">
			<span class="label">ordine</span>
			<div class="content">
				{{ $ddt->warehouse_order->production_order->code ?? $ddt->warehouse_order->code ?? '-' }}
			</div>
		</div>
		<div class="w-36 ddt-box !border-x-0 col-span-2">
			<span class="label">data e n. conferma</span>
			<div class="content"></div>
		</div>
		<div class="w-36 ddt-box !border-x-0 col-span-2">
			<p class="label flex justify-between">Data <span
					class="font-bold">{{ $ddt->generated_at->format('d-m-Y') }}</span></p>
			<p class="label flex justify-between !top-6">D.D.T <span class="font-bold">{{ $ddt->id }}</span></p>
		</div>
		<div class="w-20 ddt-box !border-x-0 col-span-1">
			<span class="label">n. pagina 1</span>
		</div>
	</div>
	<div class="grid grid-cols-12 !border-x border-x-gray-400 !divide-x !divide-x-gray-400">
		<div class="ddt-box !border-x-0 col-span-7">
			<span class="label">condizioni di pagamento</span>
			<div class="content"></div>
		</div>
		<div class="ddt-box !border-x-0 col-span-5">
			<span class="label">banca d'appoggio</span>
			<div class="content"></div>
		</div>
	</div>
	<div class="flex !border-x border-x-gray-400 !divide-x !divide-x-gray-400">
		<div class="flex-1 ddt-box !border-x-0">
			<span class="label">cliente</span>
			<div class="content"></div>
		</div>
		<div class="flex-1 ddt-box !border-x-0">
			<span class="label">partita iva</span>
			<div class="content"></div>
		</div>
		<div class="flex-1 ddt-box !border-x-0">
			<span class="label">resa</span>
			<div class="content"></div>
		</div>
		<div class="w-72 ddt-box !border-x-0">
			<span class="label">vettore</span>
			<div class="content"></div>
		</div>
	</div>
	<div>
		<div class="flex !border-x border-x-gray-400 !divide-x !divide-x-gray-400 h-9">
			<div class="flex-1 print:w-96 ddt-box !border-x-0">
				<span class="label !text-center">codice articolo</span>
			</div>
			<div class="w-40 ddt-box !border-x-0">
				<span class="label !text-center">quantità</span>
			</div>
			<div class="w-24 ddt-box !border-x-0">
				<span class="label !text-center">u.m.</span>
			</div>
			<div class="flex-1 print:w-96 ddt-box !border-x-0">
				<span class="label !text-center">descrizione</span>
			</div>
		</div>
		{{-- foreach --}}
		@if($serials->count())
			<div class="flex !border-x border-x-gray-400 !divide-x !divide-x-gray-400 h-9">
				<div class="flex-1 print:w-96 ddt-box !border-t-0 !border-x-0">
					<div class="content !mt-0">
						{{ $serials->first()->product->code }} - {{ $serials->first()->product->description }}
					</div>
				</div>
				<div class="w-40 ddt-box !border-t-0 !border-x-0">
					<div class="content !mt-0">{{ $serials->count() }}</div>
				</div>
				<div class="w-24 ddt-box !border-t-0 !border-x-0">
					<div class="content !mt-0">{{ $serials->first()->product->unit->description }}</div>
				</div>
				<div class="flex-1 print:w-96 ddt-box !border-t-0 !border-x-0">
					<div class="content !mt-0">{{ $serials->first()->code }}</div>
				</div>
			</div>
			@foreach($serials->skip(1) as $serial)
				<div class="flex !border-x border-x-gray-400 !divide-x !divide-x-gray-400 h-9">
					<div class="flex-1 print:w-96 ddt-box !border-t-0 !border-x-0">
						<div class="content !mt-0"></div>
					</div>
					<div class="w-40 ddt-box !border-t-0 !border-x-0">
						<div class="content !mt-0"></div>
					</div>
					<div class="w-24 ddt-box !border-t-0 !border-x-0">
						<div class="content !mt-0"></div>
					</div>
					<div class="flex-1 print:w-96 ddt-box !border-t-0 !border-x-0">
						<div class="content !mt-0">{{ $serial->code }}</div>
					</div>
				</div>
			@endforeach
		@endif
		@if($products->count())
			@foreach($products as $product)
				<div class="flex !border-x border-x-gray-400 !divide-x !divide-x-gray-400 h-9">
					<div class="flex-1 print:w-96 ddt-box !border-t-0 !border-x-0">
						<div class="content !mt-0">{{ $product->code }} - {{ $product->description }}</div>
					</div>
					<div class="w-40 ddt-box !border-t-0 !border-x-0">
						<div class="content !mt-0">{{ $product->pivot->quantity }}</div>
					</div>
					<div class="w-24 ddt-box !border-t-0 !border-x-0">
						<div class="content !mt-0">{{ $product->unit->description }}</div>
					</div>
					<div class="flex-1 print:w-96 ddt-box !border-t-0 !border-x-0">
						<div class="content !mt-0"></div>
					</div>
				</div>
			@endforeach
		@endif
	</div>
	<div>
		<div class="grid grid-cols-12 !border-x border-x-gray-400 !divide-x !divide-x-gray-400">
			<div class="ddt-box !border-x-0 col-span-3">
				<span class="label">trasporto a cura del</span>
				<div class="content flex items-center justify-between">
					<div>
						<input type="checkbox" disabled>
						<span class="text-[8px]">destinatario</span>
					</div>
					<div>
						<input type="checkbox" disabled>
						<span class="text-[8px]">vettore</span>
					</div>
					<div>
						<input type="checkbox" disabled>
						<span class="text-[8px]">altro</span>
					</div>
				</div>
			</div>
			<div class="ddt-box !border-x-0 col-span-3">
				<span class="label">causale del trasporto</span>
				<div class="content"></div>
			</div>
			<div class="ddt-box !border-x-0 col-span-3">
				<span class="label">inizio trasporto</span>
				<div class="mt-4 relative">
					<p class="label">data</p>
					<p class="label left-1/2">ora</p>
				</div>
			</div>
			<div class="ddt-box !border-x-0 col-span-3">
				<span class="label">firma del conducente</span>
				<div class="content"></div>
			</div>
		</div>
		<div class="grid grid-cols-12 !border-x border-x-gray-400 !divide-x !divide-x-gray-400">
			<div class="ddt-box !border-x-0 !border-t-0 col-span-6">
				<span class="label"></span>
				<div class="content"></div>
			</div>
			<div class="ddt-box !border-x-0 !border-t-0 col-span-3">
				<span class="label">data e ora del ritiro</span>
				<div class="mt-4 relative">
					<p class="label">data</p>
					<p class="label left-1/2">ora</p>
				</div>
			</div>
			<div class="ddt-box !border-x-0 !border-t-0 col-span-3">
				<span class="label">firma del vettore</span>
				<div class="content"></div>
			</div>
		</div>
		<div class="grid grid-cols-12 !border-x border-x-gray-400 !divide-x !divide-x-gray-400">
			<div class="ddt-box !border-x-0 !border-t-0 col-span-6">
				<span class="label">aspetto esteriore dei beni</span>
				<div class="content"></div>
			</div>
			<div class="ddt-box !border-x-0 !border-t-0 col-span-3 relative">
				<div class="absolute top-0">
					<span class="label">peso</span>
					<div class="content"></div>
				</div>
				<div class="absolute h-full top-0 left-1/2 border-l border-l-gray-400">
					<span class="label">colli</span>
					<div class="content"></div>
				</div>
			</div>
			<div class="ddt-box !border-x-0 !border-t-0 col-span-3">
				<span class="label">firma del destinatario</span>
				<div class="content"></div>
			</div>
		</div>
		<div class="grid grid-cols-12 !border-x border-x-gray-400 !divide-x !divide-x-gray-400">
			<div class="ddt-box !border-x-0 !border-t-0 col-span-12">
				<span class="label">annotazioni</span>
				<div class="content h-16"></div>
			</div>
		</div>
	</div>
	<div class="items-center grid grid-cols-2 gap-6 mt-6">
		<div>
			<img src="{{ asset('images/ddt-logo.png') }}" class="h-14 w-full"/>
		</div>
		<div class="text-sm">
			<p>{{ env('DDT_FOOTER_ADDRESS') }}</p>
			<p>C.F. e P.I: {{ env('DDT_FOOTER_CF_VAT') }} - Tel. {{ env('DDT_FOOTER_PHONE') }}</p>
			<p>mail: {{ env('DDT_FOOTER_EMAIL') }}</p>
		</div>
	</div>
</div>
