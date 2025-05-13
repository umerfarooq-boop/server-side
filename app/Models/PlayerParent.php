<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerParent extends Model
{
    use HasFactory;

    protected $fillable = [
        'cnic',
        'name',
        'email',
        'address',
        'player_id',
        'phone_number',
        'location',
        'status',
    ];  

    public function playerequipment(){
        return $this->hasMany(Request_Equipment::class, 'player_id', 'player_id');
    }
    
    public function coachschedule(){
        return $this->hasMany(CoachSchedule::class, 'player_id', 'player_id');
    }

    public function player_score(){
        return $this->hasMany(PlayerScore::class, 'player_id', 'player_id');
    }

    public function player_attendance(){
        return $this->hasMany(Attendence::class, 'player_id', 'player_id');
    }

    public function player(){
        return $this->belongsTo(Player::class,'player_id');
    }

    public function playerParet(){
        return $this->hasOne(Profile::class,'player_id','id');
    }

    public function profile(){
        return $this->hasMany(Profile::class,'player_id','id');
    }
}
