<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request_Equipment extends Model
{
    use HasFactory;

    protected $fillable = ['player_id','coach_id','equipment_name_id','equipment_quantity','equipment_status','return_date_time','now_date_time'];

    public function PlayerParent(){
        return $this->belongsTo(PlayerParent::class,'player_id','id');
    }

    public function player(){
        return $this->belongsTo(player::class,'player_id');
    }

    public function coach(){
        return $this->belongsTo(Coach::class,'coach_id');
    }

    public function equipment(){
        return $this->belongsTo(AssignEquipment::class,'equipment_name_id');
    }

}
