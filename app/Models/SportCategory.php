<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SportCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name','status','created_by'];

    public function editappointment(){
        return $this->hasMany(EditAppointment::class,'booking_slot','id');
    }

    public function coachSchedule(){
        return $this->hasOne(CoachSchedule::class,'booking_slot','id');
    }

    public function coach(){
        return $this->hasMany(Coach::class,'category_id','id');
    }

    public function player(){
        return $this->hasMany(Player::class,'cat_id','id');
    }

    public function profile(){
        return $this->hasMany(Profile::class,'cat_id','id');
    }

}
