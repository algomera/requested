<?php
	return [
		'products' => [
			'units' => [
				'l' => 'Litri',
				'mt' => 'Metri',
				'pz' => 'Pezzi',
			]
		],
		'locations' => [
			'types' => [
				'ricevimento' => 'Ricevimento',
				'grandi_quantita' => 'Grandi quantità',
				'produzione' => 'Produzione',
				'scarto' => 'Scarto',
				'versamento' => 'Versamento',
				'spedizione' => 'Spedizione',
			]
		],
		'production_orders' => [
			'status' => [
				'created' => 'Creato',
				'active' => 'Attivo',
				'completed' => 'Completato',
			]
		],
		'warehouse_orders' => [
			'status' => [
				'to_transfer' => 'Da trasferire',
				'partially_transferred' => 'Parzialmente trasferito',
				'transferred' => 'Trasferito',
			]
		]
	];
