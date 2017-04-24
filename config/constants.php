<?php
return [
	'RESET_HOUR' => 3,
	'ITEM_PER_PAGE' => 100,
	'FIELD_REQUIRED_TYPES' => [
		'require_one_field',
		'require_multiple_field'
	],
	'CHART_COLORS' => [
		'rgba(250, 214, 0, 1)',
		'rgba(239, 83, 80, 1)',
		'rgba(171, 71, 188, 1)',
		'rgba(66, 165, 245, 1)',
		'rgba(156, 204, 101, 1)',
		'rgba(38, 198, 218, 1)',
		'rgba(102, 187, 106, 1)',
		'rgba(212, 225, 87, 1)',
		'rgba(255, 235, 59, 1)',
		'rgba(255, 167, 38, 1)',
		'rgba(255, 112, 67, 1)',
		'rgba(171, 235, 198, 1)',
		'rgba(214, 234, 248, 1)',
		'rgba(211, 84, 0, 1)',
		'rgba(210, 180, 222, 1)'
	],
	'REPORT_QUESTION_TYPE' => [
		'dropdown',
		'radio',
		'checkboxes',
		'number_sales'
	],
	'CHART' => [
		'HORIZONTAL_BAR_OPTIONS' => [
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
		'NO_LEGEND_OPTIONS' => [
			'legend' => [
				'display' => false
			]
		]
	],
	'DATE_FORMAT' => [
		'CHART' => 'd-m-Y',
		'MYSQL' => 'Y-m-d H:i:s',
		'DEFAULT' => 'Y-m-d'
	]
];