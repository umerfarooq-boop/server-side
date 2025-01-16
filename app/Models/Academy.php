<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Academy extends Model
{
    use HasFactory;

    protected $fillable = [
        'academy_name',
        'academy_location',
        'academy_certificate',
        'coach_id',
        'status',
        'address',
        'academy_phonenumber',
        'created_by',
        'updated_by',
    ];

    public function profile(){
        return $this->hasMany(Profile::class,'academy_id','id');
    }

    public function coach(){
        return $this->hasMany(Coach::class,'coach_id','id');
    }

    public function singleCoach()
    {
        return $this->belongsTo(Coach::class, 'coach_id', 'id');
    }

}
