<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckoutForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'coach_id',
        'booking_id',
        'player_name',
        'player_email',
        'player_phone_number',
        'player_address',
        'coach_name',
        'start_time',
        'end_time',
        'to_date',
        'from_date',
        'payment_type',
        'per_hour_charges',
        'total_charges'
    ];
    
}
