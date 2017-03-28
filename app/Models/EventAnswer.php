<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $event_id
 * @property string $sales_id
 * @property string $location
 * @property string $answer
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property Event $event
 */
class EventAnswer extends UuidModel
{
	use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = ['event_id', 'user_id', 'answer', 'is_terminated', 'created_at', 'updated_at', 'deleted_at'];

	protected $casts = ['answer' => 'array'];
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
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
