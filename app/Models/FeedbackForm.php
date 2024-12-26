<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackForm extends Model
{
    use HasFactory;
    protected $fillable = ['name','email','message','user_id'];

    public function Profile(){
        return $this->belongsTo(Profile::class,'user_id','id');
    }

    public function player(){
        return $this->belongsTo(Player::class,'user_id');
    }

    public function coach(){
        return $this->belongsTo(Coach::class,'user_id');
    }

}
