<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'date',
        'player_type',
        'played_over',
        'today_give_wickets',
        'through_over',
        'today_taken_wickets',
        'score_status',
        'coach_id',
    ];
    
    public function player(){
        return $this->belongsTo(player::class,'player_id');
    }

    public function coach(){
        return $this->belongsTo(Coach::class,'coach_id');
    }

}
