<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'category_id',
        'experience',
        'level',
        'phone_number',
        'certificate',
        'image',
        'coach_location',
        'status',
    ];

    public function playerScore(){
        return $this->hasMany(PlayerScore::class,'coach_id','id');
    }

    public function attendance(){
        return $this->hasMany(Attendacne::class,'coach_id','id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'coach_id','id');
    }


    public function coachSchedule(){
        return $this->hasMany(CoachSchedule::class,'coach_id','id');
    }

    public function post(){
        return $this->hasMany(Post::class,'coach_id','id');
    }

    public function sportCategory(){
        return $this->belongsTo(SportCategory::class,'category_id');
    }

    public function profile(){
        return $this->hasMany(Profile::class,'coach_id','id');
    }

    
    // public function academy(){
    //     return $this->belongsTo(Academy::class,'coach_id');
    // }

    public function academy()
    {
        return $this->belongsTo(Academy::class);
    }


    public function singleAcademy()
    {
        return $this->hasOne(Academy::class, 'coach_id', 'id');
    }

    public function feedbackForm(){
        return $this->hasMany(feedbackForm::class,'user_id','id');
    }

}
