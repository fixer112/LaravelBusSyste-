<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\User;
use App\Admin;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        $admins = Admin::all();

        return view('users')->with('users',$users)->with('admins',$admins);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::all()->where('id',$id)->first();
        $admin = Admin::all()->where('admin_id',$id)->first();
        
        return view('show_user')
                ->with('user', $user)
                ->with('admin', $admin);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'UserName' => 'required',
            'Email' => 'required',
            'secret' => 'required'
        ]);
        
        
        $query = DB::table('users')
            ->select('id')
            ->where('id','<>',$id)
            ->where(function ($query) use ( $request){
                $query->where('name',$request->input('UserName'))
                    ->orWhere('email',$request->input('Email'));
            })
            ->get();

        if($query->isEmpty()==false){
            return redirect('/')->with('error', 'Username or Email taken');
        }



        DB::table('users')
        ->where('id', $id)
        ->update(['name' => $request->input('UserName'), 
                'password' => $request->input('secret'), 
                'email' => $request->input('Email')]);



    return redirect('/home')->with('success', 'User Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('users')
            ->where('id', $id)
            ->delete();

        return redirect('/home')->with('success', 'Route Deleted');
    }
}
