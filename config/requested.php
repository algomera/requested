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
				'ricevimento' => [
					'user_can_show' => true,
					'label' => 'Ricevimento'
				],
				'grandi_quantita' => [
					'user_can_show' => true,
					'label' => 'Grandi quantità'
				],
				'produzione' => [
					'user_can_show' => true,
					'label' => 'Produzione'
				],
				'scarto' => [
					'user_can_show' => true,
					'label' => 'Scarto'
				],
				'versamento' => [
					'user_can_show' => true,
					'label' => 'Versamento'
				],
				'spedizione' => [
					'user_can_show' => true,
					'label' => 'Spedizione'
				],
				'fornitore' => [
					'user_can_show' => false,
					'label' => 'Fornitore'
				],
				'destinazione' => [
					'user_can_show' => false,
					'label' => 'Destinazione'
				],
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
			'types' => [
				'versamento' => 'Versamento',
				'scarico' => 'Scarico',
				'trasferimento' => 'Trasferimento',
				'spedizione' => 'Spedizione',
				'ricevimento' => 'Ricevimento',
			],
			'status' => [
				'to_transfer' => 'Da trasferire',
				'partially_transferred' => 'Parzialmente trasferito',
				'transferred' => 'Trasferito',
			]
		]
	];
