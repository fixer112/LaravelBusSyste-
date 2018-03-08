<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $fillable = ['start', 'destination'];

    public function stop(){
        return $this->belongsTo('App\Stop');
    }
}
