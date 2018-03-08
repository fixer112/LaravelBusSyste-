<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function admin(){
        return $this->hasOne('App\Admin');
    }

    public function isAdmin()
    {
        $query = DB::table('admins')
        ->select('admin_id')
        ->where('admin_id',$this->id)->get();
        if($query->isEmpty()==false){
            return true;
        }
        return false;
    }

    
}
