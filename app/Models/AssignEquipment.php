<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignEquipment extends Model
{
    use HasFactory;

    protected $fillable = ['equipment_name','equipment_quantity','coach_id','status'];

    public function coach(){                                                                                                    
        return $this->belongsTo(Coach::class,'coach_id');
    }

    public function requestequipment(){
        return $this->hasMany(Request_Equipment::class,'equipment_name_id','id');
    }

}
