<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Stop;

class StopsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stops = Stop::all();

        return view('stops')->with('stops',$stops);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create_stop');
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
            'StopName' => 'required',
            'Zone' => 'required'
        ]);

        $query = Stop::select('stop_id')->where('stop_name',$request->input('StopName'))->get();

        if($query->isEmpty()==false){
            return redirect('/')->with('error', 'Stop already exists');
        }


        $stop = new Stop;
        $stop->stop_name = $request->input('StopName');
        $stop->zone = $request->input('Zone');
        $stop->save();

        return redirect('/')->with('success', 'Stop Created');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $stop = Stop::all()->where('stop_id',$id)->first();
        
        return view('show_stops')
                ->with('stop',$stop);
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
            'StopName' => 'required',
            'Zone' => 'required'
        ]);


        $query = Stop::select('stop_id')
            ->where('stop_name',$request->input('StopName'))
            ->where('stop_id','<>',$id)
            ->get();


        if($query->isEmpty()==false){
            return redirect('/')->with('error', 'Stop already exists');
        }

        DB::table('stops')
            ->where('stop_id', $id)
            ->update(['stop_name' => $request->input('StopName'), 'zone' => $request->input('Zone')]);



        return redirect('/')->with('success', 'Stop Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $stop = Stop::all()->where('stop_id',$id)->first();
        
        DB::table('stops')
            ->where('stop_id', $id)
            ->delete();

        return redirect('/stops')->with('success', 'Stop Deleted');
    }
}
