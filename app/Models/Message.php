<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = [
        'sender_id',
        'sender_type',
        'receiver_id',
        'receiver_type',
        'message',
        'is_read',
    ];

    public function sender()
    {
        return $this->morphTo();
    }

    public function receiver()
    {
        return $this->morphTo();
    }

    // public function sender() {
    //     return $this->belongsTo(User::class, 'sender_id');
    // }

    // public function receiver() {
    //     return $this->belongsTo(User::class, 'receiver_id');
    // }

    public function coach(){
        
    }

    public function player(){

    }
}
