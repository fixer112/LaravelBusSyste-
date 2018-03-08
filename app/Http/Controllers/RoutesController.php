<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Route;
use App\Stop;
use App\RouteStop;
use App\Schedule;


class RoutesController extends Controller
{
    public function __contruct()
    {
        $this->middleware('admin',['except'=>['index','show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $routes = Route::all('start','destination','route_id');
        $stops = Stop::all('stop_name','stop_id');
        $stops_poms = Stop::all('stop_name','stop_id')->toArray();

        $stop_infos = array();

        foreach($stops_poms as $stops_pom){
            $stop_infos[$stops_pom['stop_id']] = $stops_pom['stop_name'];
        }

        return view('routes')
            ->with('routes',$routes)
            ->with('stops',$stops)
            ->with('stop_infos',$stop_infos)
            ->with('stops_poms',$stops_poms);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $stops = Stop::pluck('stop_name','stop_id')->toArray();
        return view('create_route')->with('stops',$stops);
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
            'stops' => 'required',
            'destination' => 'required',
            'active_days' => 'required',
            'active_date_start' => 'required',
            'active_date_end' => 'required'
        ]);
        if($request->input('stops')==$request->input('destination')){
            return redirect('/')->with('error', 'Invalid Input');
        }

        $route = new Route;
        $route->start = $request->input('stops');
        $route->destination = $request->input('destination');
        $route->active_days = $request->input('active_days');
        $route->active_date_start = $request->input('active_date_start');
        $route->active_date_end = $request->input('active_date_end');

        $query=DB::table('routes')
            ->select('route_id')
            ->where('start',$route->start)
            ->where('destination',$route->destination)
            ->get();

        if($query->isEmpty()==false){
            return redirect('/')->with('error', 'Route already exists');
        }

        $query=DB::table('routes')
            ->select('route_id')
            ->where('start',$route->destination)
            ->where('destination',$route->start)
            ->get();

        if($query->isEmpty()==false){
            return redirect('/')->with('error', 'Route already exists');
        }
        
        $route->save();

        return redirect('/')->with('success', 'Route Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
        $stop_names = Stop::pluck('stop_name','stop_id')->toArray();
        $stops = Stop::all();
        $routestops = RouteStop::all()->where('route_id',$id);
        $route = Route::all()
        ->where('route_id',$id)->first();;
        $forwards = Schedule::all()->where('route_id',$id)->where('direction','forward');
        $backwards = Schedule::all()->where('route_id',$id)->where('direction','backwards');
        $stops_poms = Stop::all('stop_name','stop_id')->toArray();
        $schedules = Schedule::pluck('start_time','schedule_id')->toArray();


        $stop_infos = array();

        foreach($stops_poms as $stops_pom){
            $stop_infos[$stops_pom['stop_id']] = $stops_pom['stop_name'];
        }


        return view('show_route')
                ->with('route', $route)
                ->with('stop_names',$stop_names)
                ->with('stops',$stops)
                ->with('stop_infos',$stop_infos)
                ->with('forwards',$forwards)
                ->with('backwards',$backwards)
                ->with('schedules',$schedules)
                ->with('routestops',$routestops);
                
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
            'stops' => 'required',
            'destination' => 'required',
            'active_days' => 'required',
            'active_date_start' => 'required',
            'active_date_end' => 'required'
        ]);
        if($request->input('stops')==$request->input('destination')){
            return redirect('/')->with('error', 'Invalid Input');
        }
        
        
        $query = DB::table('routes')
            ->select('route_id')
            ->where(function ($query) use ( $request){
                $query->where('start',$request->input('stops'))
                    ->where('destination',$request->input('destination'));
            })
            ->orWhere(function ($query) use ( $request){
                $query->where('start',$request->input('destination'))
                ->where('destination',$request->input('stops'));
            })
            ->get();


        if($query->isEmpty()==false){
            return redirect('/')->with('error', 'Stop already exists');
        }

        $query = DB::table('route_stops')
            ->select('route_stop_id')
            ->where('route_id',$id)
            ->where(function ($query) use ( $request){
                $query->where('stop_id',$request->input('stops'))
                ->orWhere('stop_id',$request->input('destination'));
            })
            ->get();

            if($query->isEmpty()==false){
                return redirect('/')->with('error', 'Selected stop already exists on this route');
            }


        DB::table('routes')
        ->where('route_id', $id)
        ->update(['start' => $request->input('stops'), 
                'destination' => $request->input('destination'), 
                'active_days' => $request->input('active_days'), 
                'active_date_start' => $request->input('active_date_start'), 
                'active_date_end' => $request->input('active_date_end')]);



    return redirect('/')->with('success', 'Route Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('routes')
            ->where('route_id', $id)
            ->delete();

        return redirect('/routes')->with('success', 'Route Deleted');
    }
}
