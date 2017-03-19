<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Ramsey\Uuid\Uuid;

class UuidAuthenticatable extends Authenticatable
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
