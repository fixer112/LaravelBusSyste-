@extends('layouts.app');

@section('content')
    <h1>Tickets:</h1>
    @if(count($tickets)>0)
        @foreach($tickets as $ticket)
            @foreach($stops as $stop)
                @if($stop->stop_id == $ticket->start || $stop->stop_id == $ticket->destination)
                <h3>{{$stop->stop_name}}</h3>
                @endif
            @endforeach
            @foreach($schedules as $schedule)
                    @if($schedule->schedule_id == $ticket->scheduled_drive)
                    <h4>{{$schedule->direction}}</h4>
                    <p>{{$schedule->start_time}}</p>
                    @endif
            @endforeach

            @if(Auth::check())
            {!! Form::open(['action' => ['TicketsController@destroy', $ticket->ticket_id], 'method' => 'POST', 'class'=>'pull_right']) !!}
            {{Form::hidden('_method' , 'DELETE')}}
            {{Form::submit('Delete ticket' , ['class' => 'btn btn-primary'])}}
            {!! Form::close() !!}
            @endif
            
            <hr color="#1111fe" size="4" width="100%">
        @endforeach
    @else
        <p> No tickets found </p>
    @endif
@endsection