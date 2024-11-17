<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoachSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'coach_id',
        'player_id',
        'start_time',
        'end_time',
        'booking_slot',
        'event_name',
        'status',
        'to_date',
        'from_date'
    ];    

}
