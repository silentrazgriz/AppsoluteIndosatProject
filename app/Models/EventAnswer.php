<?php

namespace App\Models;

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
 * @property Sale $sale
 */
class EventAnswer extends UuidModel
{
    /**
     * @var array
     */
    protected $fillable = ['event_id', 'sales_id', 'location', 'answer', 'created_at', 'updated_at', 'deleted_at'];

	protected $casts = ['location' => 'array', 'answer' => 'array'];
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
    public function sale()
    {
        return $this->belongsTo('App\Models\Sale', 'sales_id');
    }
}
