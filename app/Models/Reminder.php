<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Reminder
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string|null $description
 * @property int $event_at
 * @property int|null $remind_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Reminder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reminder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reminder query()
 * @method static \Illuminate\Database\Eloquent\Builder|Reminder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reminder whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reminder whereEventAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reminder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reminder whereRemindAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reminder whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reminder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reminder whereUserId($value)
 * @mixin \Eloquent
 */
class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'user_id',
        'description',
        'event_at',
        'remind_at',
    ];

    protected $hidden = [
        'user_id',
        'created_at',
        'updated_at'
    ];

    /* RELATIONS */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
