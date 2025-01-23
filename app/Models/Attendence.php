<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendence extends Model
{
    use HasFactory;

    protected $fillable = ['start_time','end_time','date','attendance_status','coach_id','appointment_id','player_id'];

    public function coach(){
        return $this->belongsTo(Coach::class,'coach_id');
    }

    public function schedule(){
        return $this->belongsTo(CoachSchedule::class,'appointment_id');
    }

    public function player(){
        return $this->belongsTo(player::class,'player_id');
    }

}
