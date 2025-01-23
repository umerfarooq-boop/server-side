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

    public function attendance(){
        return $this->hasMany(Attendance::class,'appointment_id','id');
    }

    public function coach(){
        return $this->belongsTo(Coach::class,'coach_id');
    }

    public function sportCategory(){
        return $this->belongsTo(sportCategory::class,'booking_slot');
    }
    
    public function player(){
        return $this->belongsTo(player::class,'player_id');
    }

}
