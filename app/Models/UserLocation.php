<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property string $id
 * @property string $user_id
 * @property string $state
 * @property string $location
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property User $user
 */
class UserLocation extends Model
{
	use SoftDeletes;
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'state', 'location', 'created_at', 'updated_at', 'deleted_at'];

    protected $hidden = ['updated_at', 'deleted_at'];

    protected $casts = ['location' => 'array'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('app\Models\User');
    }
}
