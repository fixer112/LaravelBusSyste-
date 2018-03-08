<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Schedule;
use App\Route;
use App\Stop;
use App\RouteStop;



class SchedulesController extends Controller
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
            'direction' => 'required',
            'StartTime' => 'required'
        ]);

        $schedule = new Schedule;
        $schedule->route_id = $request->input('route_id');
        $schedule->direction = $request->input('direction');
        $schedule->start_time = $request->input('StartTime');


        $query=DB::table('schedules')
            ->select('schedule_id')
            ->where('route_id',$schedule->route_id)
            ->where('direction',$schedule->direction)
            ->where('start_time',$schedule->start_time)
            ->get();


        if($query->isEmpty()==false){
            return redirect('/')->with('error', 'Drive with indentical information is already scheduled');
        }


        $schedule->save();

        return redirect('/')->with('success', 'Drive Scheduled');
  
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $schedule = Schedule::all()->where('schedule_id',$id)->first();
        $stop_names = Stop::pluck('stop_name','stop_id')->toArray();
        $routestops = RouteStop::all()->where('route_id',$schedule->route_id);
        $stops_poms = Stop::all('stop_name','stop_id')->toArray();


        $route = Route::all()->where('route_id',$schedule->route_id)->first();



        $query = DB::table('stops')
            ->join('route_stops', 'stops.stop_id', '=', 'route_stops.stop_id')
            ->join('routes', 'route_stops.route_id', '=', 'routes.route_id')
            ->pluck('stops.stop_name','stops.stop_id')
            ->toArray();

        $startquery = DB::table('routes')
                ->select('start')
                ->where('route_id',$route->route_id)
                ->get();

        $start = array_pluck($startquery,'start');

        $startquery = DB::table('stops')
                ->select('stop_name')
                ->where('stop_id',array_first($start))
                ->get();
        
        $start_name = array_pluck($startquery,'stop_name');

        $destinationquery = DB::table('routes')
                ->select('destination')
                ->where('route_id',$route->route_id)
                ->get();

        $destination = array_pluck($destinationquery,'destination');

        $destinationquery = DB::table('stops')
                ->select('stop_name')
                ->where('stop_id',array_first($destination))
                ->get();
        
        $destination_name = array_pluck($destinationquery,'stop_name');

            $stop_names = array_add($query,array_first($destination),array_first($destination_name) );
            $stop_names = array_add($stop_names,array_first($start),array_first($start_name) );

        $route = Route::all()
        ->where('route_id',$schedule->route_id)->first();

        $stops=DB::table('stops')
            ->where('stop_id',$route->start)
            ->orWhere('stop_id',$route->destination)
            ->get();

        
        return view('show_schedule')
                ->with('schedule', $schedule)
                ->with('stops', $stops)
                ->with('stop_names', $stop_names)
                ->with('routestops', $routestops)
                ->with('route',$route);
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
            'direction' => 'required',
            'StartTime' => 'required',
            'route_id' => 'required'
        ]);

        $query=DB::table('schedules')
            ->select('schedule_id')
            ->where('route_id',$request->input('route_id'))
            ->where('direction',$request->input('direction'))
            ->where('start_time',$request->input('StartTime'))
            ->get();

        if($query->isEmpty()==false){
            return redirect('/')->with('error', 'Drive with indentical information is already scheduled');
        }

        DB::table('schedules')
            ->where('schedule_id', $id)
            ->update(['direction' => $request->input('direction'), 'start_time' => $request->input('StartTime')]);



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
     
        DB::table('schedules')
            ->where('schedule_id', $id)
            ->delete();

        return redirect('/routes')->with('success', 'Stop Updated');
    }
}
