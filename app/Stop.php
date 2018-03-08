<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    protected $fillable = ['stop_name'];

    public function route(){
        return $this->hasMany('App\Route');
    }
}
