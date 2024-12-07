<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeSlidder extends Model
{
    use HasFactory;
    protected $fillable = [
        'slidder_image',
        'slidder_text'
    ];
}
