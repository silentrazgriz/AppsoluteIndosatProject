<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class UuidModel extends Model
{
	public $incrementing = false;

	public static function boot()
	{
		parent::boot();
		static::creating(function ($model) {
			$model->incrementing = false;
			if (empty($model->id)) {
				$model->id = (string)Uuid::uuid4();
			}
		});
	}
}
