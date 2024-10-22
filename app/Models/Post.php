<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    
protected $fillable = [
    'post_title',
    'post_name',
    'post_description',
    'post_image',
    'post_time',
    'post_status',
    'post_location',
    'coach_id',
];

}
