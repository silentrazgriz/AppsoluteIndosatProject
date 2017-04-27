<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $event_id
 * @property integer $sales_area_id
 * @property string $email
 * @property string $password
 * @property string $name
 * @property string $gender
 * @property string $phone
 * @property integer $balance
 * @property boolean $is_admin
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property Event $event
 * @property SalesArea $salesArea
 * @property BalanceHistory[] $balanceHistories
 * @property EventAnswer[] $eventAnswers
 * @property UserLocation[] $userLocations
 */
class User extends UuidAuthenticatable
{
	use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = ['event_id', 'sales_area_id', 'area', 'email', 'password', 'name', 'gender', 'phone', 'balance', 'is_admin', 'remember_token', 'created_at', 'updated_at', 'deleted_at'];

    protected $hidden = ['event_id', 'sales_area_id', 'password', 'remember_token', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo('App\Models\Event');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function salesArea()
    {
        return $this->belongsTo('App\Models\SalesArea');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function balanceHistories()
    {
        return $this->hasMany('App\Models\BalanceHistory');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function eventAnswers()
    {
        return $this->hasMany('App\Models\EventAnswer');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userLocations()
    {
        return $this->hasMany('App\Models\UserLocation');
    }
}
