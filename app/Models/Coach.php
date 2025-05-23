<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coach extends Model
{
    use HasFactory;
    // use Notifiable;
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

    public function ratingreviews(){
        return $this->hasMany(RatingReviews::class,'coach_id','id');
    }

    public function user(){
        return $this->hasMany(User::class,'user_id','id');
    }

    public function returnequipment(){
        return $thsi->hasMany(ReturnEquipment::class,'coach_id','id');
    }

    public function request_equipment(){
        return $this->belongsTo(Request_Equipment::class,'coach_id','id');
    }

    public function assignEquipment(){
        return $this->hasMany(AssignEquipment::class,'coach_id','id');
    }

    public function editappointment(){
        return $this->hasMany(EditAppointment::class,'coach_id','id');
    }

    public function playerScore(){
        return $this->hasMany(PlayerScore::class,'coach_id','id');
    }

    public function attendance(){
        return $this->hasMany(Attendacne::class,'coach_id','id');
    }

    public function playernotifications()
    {
        return $this->hasMany(PlayerNotification::class, 'coach_id','id');
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
