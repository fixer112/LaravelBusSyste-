<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RouteStop extends Model
{
    public function stop(){
        return $this->belongsTo('App\Stop');
    }

    public function route(){
        return $this->belongsTo('App\Route');
    }
}
