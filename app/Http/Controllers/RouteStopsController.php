<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Route;
use App\Stop;
use App\RouteStop;


class RouteStopsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $this->validate($request, [
            'route_id' => 'required',
            'stop_id' => 'required',
            'StopNumber' => 'required'
        ]);


        $routestop = new RouteStop;
        $routestop->route_id = $request->input('route_id');
        $routestop->stop_id = $request->input('stop_id');
        $routestop->stop_number = $request->input('StopNumber');


        $query=Route::select('route_id')
            ->where('route_id',$routestop->route_id)
            ->where(function ($query)use ($routestop){
                $query->where('start',$routestop->stop_id)
                ->orWhere('destination',$routestop->stop_id);
            })->get();
 

        if($query->isEmpty()==false){
            return redirect('/')->with('error', 'Stop is already on the route');
        }

        $query=RouteStop::select('route_stop_id')
            ->where('route_id',$routestop->route_id)
            ->where(function ($query)use ($routestop){
            $query->where('stop_id',$routestop->stop_id)
            ->orWhere('stop_number', $routestop->stop_number);
        })->get();


        if($query->isEmpty()==false){
            return redirect('/')->with('error', 'Stop is already on the route or theres allready a stop on designated stop number');
        }

        $routestop->save();

        return redirect('/')->with('success', 'Stop Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
            'stop_id' => 'required',
            'route_id' => 'required',
            'StopNumber' => 'required',
        ]);


        $query=Route::select('route_id')
            ->where('route_id',$request->input('route_id'))
            ->where(function ($query)use ($request){
                $query->where('start',$request->input('stop_id'))
                ->orWhere('destination',$request->input('stop_id'));
            })->get();
 

        if($query->isEmpty()==false){
            return redirect('/')->with('error', 'Stop is already on the route');
        }


        $query=RouteStop::select('route_stop_id')
        ->where(function ($query)use ($request){
        $query->where('route_id',$request->input('route_id'))
            ->where('stop_id',$request->input('stop_id'));
        })
        ->orWhere(function ($query)use ($request,$id){
            $query->where('stop_number', $request->input('StopNumber'))
            ->where('route_stop_id','<>',$id)
            ->where('route_id',$request->input('route_id'));
            })
            ->get();


        if($query->isEmpty()==false){
            return redirect('/')->with('error', 'Stop is already on the route or theres allready a stop on designated stop number');
        }


        DB::table('route_stops')
        ->where('route_stop_id', $id)
        ->update(['route_id' => $request->input('route_id'), 
                'stop_id' => $request->input('stop_id'), 
                'stop_number' => $request->input('StopNumber')]);



        return redirect('/')->with('success', 'Stop on Route Updated');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('route_stops')
            ->where('route_stop_id', $id)
            ->delete();

        return redirect('/routes')->with('success', 'Stop Deleted From Route');
    }
}
