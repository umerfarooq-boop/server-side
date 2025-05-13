<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['player_id','coach_id','amount','payment_id','file_path','coach_user_id'];

    public function player()
    {
        return $this->belongsTo(User::class, 'player_id');
    }
    
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_user_id');
    }
    
}
