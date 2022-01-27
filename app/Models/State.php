<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Lga;
use App\User;
class State extends Model
{
    protected $fillable = [
        'state_name'
    ];

    public function lgas(){
        return $this->hasMany('App\Models\Lga', 'state_id');
    }

    public function region(){
        return $this->belongsTo('App\Models\Region', 'region_id');
    }
}
