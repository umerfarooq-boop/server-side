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
