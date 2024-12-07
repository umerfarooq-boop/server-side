<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrequentlyQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['title','question','description','image'];
}
