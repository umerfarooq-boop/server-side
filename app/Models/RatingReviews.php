<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingReviews extends Model
{
    use HasFactory;

    protected $fillable = ['rating','player_id','coach_id','reviews'];

    public function player(){
        return $this->belongsTo(player::class,'player_id');
    }

    public function coach(){
        return $this->belongsTo(Coach ::class,'coach_id');
    }

}
