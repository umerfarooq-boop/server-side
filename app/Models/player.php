<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class player extends Model
{
    use HasFactory;
    // use Notifiable;
    protected $fillable = [
        'player_name',
        'cat_id',
        'playwith',
        'player_gender',
        'player_phonenumber',
        'player_address',
        'player_dob',
        'player_location',
        'image',
        'status',
        'created_by',
        'updated_by',
        'location',
    ];


    public function reatingreviews(){
        return $this->hasMany(RatingReviews::class,'player_id','id');
    }

    public function user(){
        return $this->hasMany(User::class,'user_id','id');
    }

    public function returnequipment(){
        return $this->hasMany(ReturnEquipment::class,'player_id','id');
    }

    public function request_equipment(){
        return $this->hasMany(Request_Equipment::class,'player_id','id');
    }

    public function editappointment(){
        return $this->hasMany(EditAppointment::class,'player_id','id');
    }

    public function playerScore(){
        return $this->hasMany(PlayerScore::class,'player_id','id');
    }

    public function attendance(){
        return $this->hasMany(Attendence::class,'player_id','id');
    }

    public function notification(){
        return $this->hasMany(Notification::class,'player_id','id');
    }

    public function coachSchedule(){
        return $this->hasMany(CoachSchedule::class,'player_id','id');
    }
    
    public function sportCategory(){
        return $this->belongsTo(SportCategory::class,'cat_id');
    }

    public function playerParent(){
        return $this->hasMany(PlayerParent::class,'player_id','id');
    }

    public function profile(){
        return $this->hasMany(Player::class,'player_id','id');
    }

    public function feedbackForm(){
        return $this->hasMany(FeedbackForm::class,'user_id','id');
    }

}
