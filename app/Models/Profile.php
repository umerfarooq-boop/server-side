<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'dob',
        'gender',
        'role',
        'cat_id',
        'coach_id',
        'player_id',
        'parent_id',
        'academy_id',
        'profile_location',
        'address',
    ];  
    
    public function sportCategory(){
        return $this->belongsTo(Profile::class,'cat_id');
    }

    public function coach(){
        return $this->belongsTo(Coach::class,'coach_id');
    }

    public function player(){
        return $this->belongsTo(Player::class,'player_id');
    }

    public function playerProfile(){
        return $this->belongsTo(PlayerParent::class,'player_id');
    }

    public function academy(){
        return $this->belongsTo(Academy::class,'academy_id');
    }

}