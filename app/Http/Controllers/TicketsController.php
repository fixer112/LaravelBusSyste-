<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Stop;
use App\Route;
use App\Schedule;
use App\RouteStop;
use App\Ticket;
use Auth;


class TicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user()->id;
        $tickets = Ticket::all()
                ->where('ticket_owner',$user);
        $stops = Stop::all();
        $schedules = Schedule::all();

        
        return view('tickets')->with('tickets',$tickets)->with('stops',$stops)->with('schedules',$schedules);
        
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
            'schedule_id' => 'required',
            'start' => 'required',
            'destination' => 'required',
            'active_date_end' => 'required'
        ]);
        
        $ticket = new Ticket;
        $ticket->ticket_owner = Auth::user()->id;
        $ticket->scheduled_drive = $request->input('schedule_id');
        $ticket->start = $request->input('start');
        $ticket->destination = $request->input('destination');
        $ticket->ticket_date = $request->input('active_date_end');


        if($ticket->start == $ticket->destination){
            return redirect('/')->with('error', 'Invalid Stops');
        }

        $direction = DB::table('schedules')
                    ->select('direction')
                    ->where('schedule_id',$ticket->scheduled_drive)
                    ->get();

        $direction = array_pluck($direction, 'direction');
        $direction = array_first($direction);
        
        if($direction == 'forward'){
            $query = DB::table('routes')
                ->select('start')
                ->where('route_id',$request->input('route_id'))
                ->get();

            $query = array_pluck($query, 'start');
            $start = array_first($query);

            if($start <> $ticket->start){
                $query = DB::table('routes')
                ->select('destination')
                ->where('route_id',$request->input('route_id'))
                ->get();

                $query = array_pluck($query, 'destination');
                $destination = array_first($query);

                if($destination <> $ticket->destination){
                    $query = DB::table('route_stops')
                        ->select('stop_number')
                        ->where('route_id',$request->input('route_id'))
                        ->where('stop_id', $ticket->start)
                        ->get();

                        $query = array_pluck($query, 'stop_number');
                        $start = array_first($query);

                        $query = DB::table('route_stops')
                        ->select('stop_number')
                        ->where('route_id',$request->input('route_id'))
                        ->where('stop_id', $ticket->destination)
                        ->get();

                        $query = array_pluck($query, 'stop_number');
                        $destination = array_first($query);

                        if($start >= $destination){
                            return redirect('/')->with('error', 'Scheduled Drive is not in selected direction');
                        }


                }

            }
            

            
        }elseif($direction == 'backwards'){
            $query = DB::table('routes')
                ->select('start')
                ->where('route_id',$request->input('route_id'))
                ->get();

            $query = array_pluck($query, 'start');
            $start = array_first($query);


            if($start <> $ticket->destination){
                $query = DB::table('routes')
                ->select('destination')
                ->where('route_id',$request->input('route_id'))
                ->get();

                $query = array_pluck($query, 'destination');
                $destination = array_first($query);

                if($destination <> $ticket->start){
                    $query = DB::table('route_stops')
                        ->select('stop_number')
                        ->where('route_id',$request->input('route_id'))
                        ->where('stop_id', $ticket->start)
                        ->get();

                        $query = array_pluck($query, 'stop_number');
                        $start = array_first($query);

                        $query = DB::table('route_stops')
                        ->select('stop_number')
                        ->where('route_id',$request->input('route_id'))
                        ->where('stop_id', $ticket->destination)
                        ->get();

                        $query = array_pluck($query, 'stop_number');
                        $destination = array_first($query);

                        if($start <= $destination){
                            return redirect('/')->with('error', 'Scheduled Drive is not in selected direction');
                        }
                }   
            }   
        }else{
            return redirect('/')->with('error', 'Invalid Input');
        }

        $query = DB::table('routes')
                ->select('active_date_start','active_date_end')
                ->where('route_id',$request->input('route_id'))
                ->get();

        $date_start = array_pluck($query, 'active_date_start');
        $date_start = array_first($date_start);

        $date_end = array_pluck($query, 'active_date_end');
        $date_end = array_first($date_end);

        if($request->input('active_date_end') < $date_start || $request->input('active_date_end') > $date_end)
        {
            return redirect('/')->with('error', 'Invalid Date');
        }

        $query = DB::table('stops')
                ->select('zone')
                ->where('stop_id',$request->input('start'))
                ->get();

        $start = array_pluck($query, 'zone');
        $start = array_first($start);

        $query = DB::table('stops')
                ->select('zone')
                ->where('stop_id',$request->input('destination'))
                ->get();

        $destination = array_pluck($query, 'zone');
        $destination = array_first($destination);

        $price = $destination - $start;
        if($price<0){
            $price = $price * -1;
        }elseif($price == 0){
            $price = 0.5;
        }
        
        $ticket->ticket_price = $price;

        $ticket->save();


        return redirect('/')->with('success', 'Ticket Bought');


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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('tickets')
            ->where('ticket_id', $id)
            ->delete();

        return redirect('/tickets')->with('success', 'Ticket Deleted');
    }
}
