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

    public function sportCategory(){
        return $this->belongsTo(SportCategory::class,'category_id');
    }

    public function profile(){
        return $this->hasMany(Profile::class,'coach_id','id');
    }

    public function academy(){
        return $this->belongsTo(Academy::class,'coach_id');
    }

}
