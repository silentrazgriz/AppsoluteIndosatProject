<?php
namespace App\Helpers;

class TableHelpers {
	public static function getColumns($row, $exclude) {
		return array_diff(array_keys($row), $exclude);
	}
}