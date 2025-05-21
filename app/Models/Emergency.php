<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emergency extends Model
{
    use HasFactory;

    protected $fillable = ['emergencyType','subemergencyType','description','player_id','parent_id'];

    public function player(){
        return $this->belongsTo(player::class,'player_id');
    }

    public function PlayerParent(){
        return $this->belongsTo(PlayerParent::class,'parent_id');
    }

}
