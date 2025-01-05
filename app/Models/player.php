<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class player extends Model
{
    use HasFactory;

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
