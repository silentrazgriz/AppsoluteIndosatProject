<?php

namespace App\Models;

/**
 * @property string $id
 * @property string $email
 * @property string $password
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class Admin extends UuidAuthenticatable
{
    /**
     * @var array
     */
    protected $fillable = ['email', 'password', 'name', 'created_at', 'updated_at', 'deleted_at'];

}
