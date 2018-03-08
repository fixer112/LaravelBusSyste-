<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\User;
use App\Admin;

class AdminsController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required'
        ]);

        $admin = new Admin;
        $admin->admin_id = $request->input('user_id');


        $query=DB::table('admins')
            ->select('admin_id')
            ->where('admin_id',$admin->admin_id)
            ->get();

            if($query->isEmpty()==false){
                return redirect('/')->with('error', 'User already a admin');
            }
        
        $admin->save();

        return redirect('/')->with('success', 'Admin Added');
    }
}
