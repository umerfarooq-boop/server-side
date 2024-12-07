<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureService extends Model
{
    use HasFactory;

    protected $fillable = ['tilte','description','image'];
}
