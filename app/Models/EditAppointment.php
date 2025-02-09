<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EditAppointment extends Model
{
    use HasFactory;
    protected $fillable = [
        'coach_id',
        'coach_schedule_id',
        'player_id',
        'start_time',
        'end_time',
        'booking_slot',
        'event_name',
        'status',
        'to_date',
        'from_date'
    ];

    public function sportcategory(){
        return $this->belongsTo(SportCategory::class,'booking_slot');
    }

    public function coach_schedule(){
        return $this->belongsTo(CoachSchedule::class,'coach_schedule_id');
    }

    public function player(){
        return $this->belongsTo(player::class,'player_id');
    }

    public function coach(){
        return $this->belongsTo(Coach::class,'coach_id');
    }


}
