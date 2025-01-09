<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['coach_id', 'player_id', 'message', 'is_read'];


    public function coach()
    {
        return $this->belongsTo(Coach::class, 'coach_id');
    }

    public function player(){
        return $this->belongsTo(player::class,'player_id');
    }

}
