<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'name',
        'event_visibility',
        'event_max_participants'
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
