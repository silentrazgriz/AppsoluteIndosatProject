<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $user_id
 * @property integer $balance
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property User $user
 */
class BalanceHistory extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'balance', 'added_by_admin', 'created_at', 'updated_at', 'deleted_at'];

    protected $hidden = ['updated_at', 'deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('app\Models\User');
    }
}
