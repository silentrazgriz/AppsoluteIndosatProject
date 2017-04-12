<?php


namespace App\Helpers;


class SurveyHelpers
{
	public static function getQuestions($columns, array $extra = null) {
		$questions = array();

		foreach($columns as $column) {
			foreach ($column['questions'] as $question) {
				if(isset($question['key'])) {
					array_push($questions, $question);
				}
			}
		}

		if (isset($extra)) {
			array_merge($questions, $extra);
		}

		return $questions;
	}
}