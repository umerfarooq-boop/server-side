<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\User;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    
        protected $fillable = ['name', 'email', 'password', 'otp', 'otp_expires_at', 'role','email_verified_at','otp'];

        public function profile(){
            return $this->hasOne(Profile::class,'user_id','id');
        }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function player(){
        return $this->belongsTo(player::class,'user_id');
    }

    public function coach(){
        return $this->belongsTo(Coach::class,'user_id');
    }

    public function coachSchedule(){
        return $this->hasMany(CoachSchedule::class);
    }

    public function profileData(){
        return $this->belongsTo(Profile::class,'user_id');
    }

    // Player Coach Invoice
    public function playerInvoices()
    {
        return $this->hasMany(Invoice::class, 'player_id');
    }
    
    public function coachInvoices()
    {
        return $this->hasMany(Invoice::class, 'coach_user_id');
    }
    
    // Player Coach Invoice


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'forgot_otp' => 'string',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    
}
