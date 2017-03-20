<?php

namespace App\Models;

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
    /**
     * @var array
     */
    protected $fillable = ['name', 'date', 'location', 'survey', 'created_at', 'updated_at', 'deleted_at'];

	protected $casts = ['location' => 'array', 'survey' => 'array'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function eventAnswers()
    {
        return $this->hasMany('App\Models\EventAnswer');
    }
}
