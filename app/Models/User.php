<?php

namespace App\Models;

/**
 * @property string $id
 * @property string $email
 * @property string $password
 * @property string $name
 * @property string $gender
 * @property string $phone
 * @property integer $balance
 * @property string $last_location
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property EventAnswer[] $eventAnswers
 */
class User extends UuidAuthenticatable
{
    /**
     * @var array
     */
    protected $fillable = ['email', 'password', 'name', 'gender', 'phone', 'balance', 'last_location', 'is_admin', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = ['last_location' => 'array'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function eventAnswers()
    {
        return $this->hasMany('App\Models\EventAnswer', 'user_id');
    }
}
