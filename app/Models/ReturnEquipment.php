<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnEquipment extends Model
{
    use HasFactory;
    protected $fillable = ['player_id','coach_id','equipment_name','quantity','return_note','return_date_time'];

    public function player(){
        return $this->belongsTo(player::class,'player_id');
    }

    public function coach(){
        return $this->belongsTo(Coach::class,'coach_id');
    }
}
