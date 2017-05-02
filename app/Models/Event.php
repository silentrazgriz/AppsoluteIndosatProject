<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $name
 * @property string $date
 * @property string $location
 * @property string $survey
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property EventAnswer[] $eventAnswers
 */
class Event extends UuidModel
{
	use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = ['name', 'start_date', 'end_date', 'auth_code', 'survey', 'kpi', 'created_at', 'updated_at', 'deleted_at'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

	protected $casts = ['survey' => 'array', 'kpi' => 'array', 'column' => 'array'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
    	return $this->hasMany('App\Models\User');
    }

    public function eventAnswers()
    {
        return $this->hasMany('App\Models\EventAnswer');
    }
}
