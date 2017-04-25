<?php
return [
	'RESET_HOUR' => 3,
	'ITEM_PER_PAGE' => 100,
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
	]
];