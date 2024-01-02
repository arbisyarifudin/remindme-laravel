<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
