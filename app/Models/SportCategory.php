<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SportCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name','status','created_by'];

    public function coach(){
        return $this->hasMany(Coach::class,'category_id','id');
    }

    public function player(){
        return $this->hasMany(Player::class,'cat_id','id');
    }

    public function profile(){
        return $this->hasMany(Profile::class,'cat_id','id');
    }

}