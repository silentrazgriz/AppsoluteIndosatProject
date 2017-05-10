<?php
return [
	'RESET_HOUR' => 3,
	'ITEM_PER_PAGE' => 100,
	'IMAGE_PER_PAGE' => 12,
	'FIELD_REQUIRED_TYPES' => [
		'require_one_field',
		'require_multiple_field'
	],
	'CHART_COLORS' => [
		'rgba(250, 214, 0, 0.7)',
		'rgba(126, 149, 217, 0.7)',
		'rgba(238, 42, 123, 0.7)',
		'rgba(204, 191, 116, 0.7)',
		'rgba(231, 126, 35, 0.7)',
		'rgba(217, 198, 47, 0.7)',
		'rgba(52, 73, 94, 0.7)',
		'rgba(154, 141, 47, 0.7)',
		'rgba(149, 165, 165, 0.7)',
		'rgba(241, 196, 15, 0.7)',
		'rgba(169, 124, 80, 0.7)',
		'rgba(27, 188, 155, 0.7)',
		'rgba(232, 76, 61, 0.7)',
		'rgba(209, 211, 212, 0.7)',
		'rgba(53, 152, 219, 0.7)',
		'rgba(155, 88, 181, 0.7)',
		'rgba(45, 204, 112, 0.7)',
		'rgba(251, 176, 64, 0.7)',
		'rgba(215, 223, 35, 0.7)',
		'rgba(158, 31, 99, 0.7)'
	],
	'REPORT_QUESTION_TYPE' => [
		'dropdown',
		'radio',
		'checkboxes',
		'number_sales'
	],
	'CHART' => [
		'HIDE_LABEL_OPTIONS' => [
			'tooltips' => [
				'enabled' => false,
			],
			'legend' => [
				'display' => false
			],
			'scales' => [
				'yAxes' => [['display' => false]],
				'xAxes' => [['ticks' => ['beginAtZero' => true]]]
			]
		],
		'KPI_OPTIONS' => [
			'hover' => [
				'mode' => null
			],
			'scales' => [
				'yAxes' => [['ticks' => ['beginAtZero' => true]]],
				'xAxes' => [['ticks' => ['beginAtZero' => true]]]
			]
		],
		'NO_LEGEND_OPTIONS' => [
			'legend' => [
				'display' => false
			]
		],
		'POINT_STYLE_LEGEND_OPTIONS' => [
			'legend' => [
				'labels' => [
					'useLineStyle' => true
				]
			]
		]
	],
	'DATE_FORMAT' => [
		'CHART' => 'd-m-Y',
		'MYSQL' => 'Y-m-d H:i:s',
		'DEFAULT' => 'Y-m-d'
	],
	'EVENT' => [
		'DEFAULT_SURVEY' => '[{"key":"act1","description":"Profil Customer","questions":[{"key":"provider","text":"Provider yang digunakan","type":"dropdown","values":[{"key":"im3_ooredoo_pascabayar","text":"IM3 Ooredoo Pascabayar"},{"key":"im3_ooredoo_prabayar","text":"IM3 Ooredoo Prabayar"},{"key":"telkomsel_halo","text":"Telkomsel Halo"},{"key":"telkomsel_simpati","text":"Telkomsel Simpati"},{"key":"telkomsel_loop","text":"Telkomsel Loop"},{"key":"telkomsel_kartu_as","text":"Telkomsel Kartu As"},{"key":"xl_axiata_pascabayar","text":"XL Axiata Pascabayar"},{"key":"xl_axiata_prabayar","text":"XL Axiata Prabayar"},{"key":"tri","text":"Tri"},{"key":"smartfren","text":"SmartFren"},{"key":"bolt","text":"Bolt"}],"class":"border-round"},{"key":"data_usage","text":"Seberapa banyak data yang digunakan dalam sebulan?","type":"dropdown","values":[{"key":"0-1_gb","text":"0 - 1 GB"},{"key":"1-3_gb","text":"1 - 3 GB"},{"key":"3-6_gb","text":"3 - 6 GB"},{"key":"6-10_gb","text":"6 - 10 GB"},{"key":"10-20_gb","text":"10 - 20 GB"},{"key":"20-30_gb","text":"20 - 30 GB"},{"key":">30_gb","text":"> 30 GB"}],"class":"border-round"},{"key":"phone_usage","text":"Seberapa banyak penggunaan telepon per bulannya?","type":"dropdown","values":[{"key":"0-5_menit","text":"0 - 5 menit"},{"key":"6-10_menit","text":"6 - 10 menit"},{"key":"11-20_menit","text":"11 - 20 menit"},{"key":"21-50_menit","text":"21 - 50 menit"},{"key":"51-90_menit","text":"51 - 90 menit"},{"key":">90_menit","text":"> 90 menit"}],"class":"border-round"},{"key":"sms_usage","text":"Seberapa banyak penggunaan SMS per bulannya?","type":"dropdown","values":[{"key":"0-10_sms","text":"0 - 10 SMS"},{"key":"11-25_sms","text":"11 - 25 SMS"},{"key":"25-50_sms","text":"25 - 50 SMS"},{"key":">50_sms","text":"> 50 SMS"}],"class":"border-round"},{"key":"name","text":"Nama","type":"text","class":"border-round required"},{"key":"phone","text":"No. Handphone","type":"phone","class":"border-round terminate-empty"},{"key":"gender","text":"Gender","type":"radio","values":[{"key":"laki-laki","text":"Laki-laki"},{"key":"perempuan","text":"Perempuan"}],"class":"border-round"},{"key":"job","text":"Pekerjaan","type":"dropdown","values":[{"key":"pelajar","text":"Pelajar"},{"key":"mahasiswa","text":"Mahasiswa"},{"key":"karyawan_swasta","text":"Karyawan Swasta"},{"key":"pns","text":"PNS"},{"key":"ibu_rumah_tangga","text":"Ibu Rumah Tangga"},{"key":"lainnya","text":"Lainnya"}],"class":"border-round"},{"key":"age","text":"Range usia","type":"dropdown","values":[{"key":"<15","text":"< 15"},{"key":"15-25","text":"15 - 25"},{"key":"26-35","text":"26 - 35"},{"key":">35","text":"> 35"}],"class":"border-round"}]},{"key":"act2","description":"Edukasi & Trial","questions":[{"key":"education","text":"Edukasi paket","type":"checkboxes","values":[{"key":"data_rollover","text":"Data Rollover"},{"key":"stream_on","text":"Stream On"}],"class":"border-round"},{"key":"customer_photo","text":"Upload Foto Pelanggan","type":"image","class":"border-round required"}]},{"key":"act3","description":"Form Penjualan","questions":[{"key":"sales","type":"number_sales","number":{"new":{"key":"new_number","text":"No. Hp pelanggan, jika membeli SP baru"},"old":{"key":"old_number","text":"No. Hp pelanggan yang sekarang"}},"package":{"key":"package","text":"Paket yang di beli","values":[{"key":"tidak_membeli_0","text":"Tidak membeli"},{"key":"paket_pertama_50000","text":"Paket Pertama"}]},"voucher":{"key":"voucher","text":"Denom voucher yang dibeli","values":[{"key":"5000","text":"5K"},{"key":"10000","text":"10K"}]},"class":"border-round"}]},{"key":"act4","description":"Customer Sharing Moment","questions":[{"key":"twitter","text":"Twitter","type":"text","class":"border-round"},{"key":"facebook","text":"Facebook","type":"text","class":"border-round"},{"key":"instagram","text":"Instagram","type":"text","class":"border-round"},{"key":"hadiah_voucher","text":"Hadiah voucher","type":"checkbox","class":"border-round"}]}]',
		'DEFAULT_KPI' => '[{"text":"Edukasi & Trial","short_text":"Edu","field":"education","type":"require_multiple","goal":"30","unit":"orang","report_unit":"1","required":"","values":["data_rollover","stream_on","kuota_bonus_4g"]},{"text":"Share Moment","short_text":"SM","field":"act4","type":"require_one_field","goal":"10","unit":"orang","report_unit":"1","required":"education","values":["twitter","facebook","instagram"]},{"text":"SP","short_text":"SP","field":"sales.new_number","type":"count","goal":"10","unit":"pcs","report_unit":"1","required":""},{"text":"Add-On","short_text":"Add-On","field":"sales.package","type":"sum","goal":"100000","unit":"","report_unit":"10000","required":""}]'
	]
];