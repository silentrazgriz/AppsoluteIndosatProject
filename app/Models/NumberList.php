<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $id
 * @property string $number
 * @property boolean $is_taken
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class NumberList extends Model
{
	use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = ['number', 'is_taken', 'created_at', 'updated_at', 'deleted_at'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
}
