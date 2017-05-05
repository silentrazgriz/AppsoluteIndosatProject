<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompressImage extends Model
{
	protected $fillable = ['path'];

	public $timestamps = false;
}
