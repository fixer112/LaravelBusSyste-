@extends('layouts.app');

@section('content')
    <a href="/routes" class="btn btn-default">Go Back</a>
    <h1>Route:</h1>
    @foreach($stops as $stop)
        {{$stop->stop_name}}     
    @endforeach

    {{$schedule->direction}}  

    <h3>{{$schedule->start_time}} </h3>

    <div class = "well">
        <p> 
        @if(Auth::check())
        {!! Form::open(['action' => 'TicketsController@store', 'method' => 'POST']) !!}
        {{ Form::hidden('route_id', $route->route_id) }}
        {{ Form::hidden('schedule_id', $schedule->schedule_id) }}
        </p>  
            
            <div class="form-group">
                {!! Form::select('start', $stop_names, null) !!}
            </div>

            <div class="form-group">
                    {!! Form::select('destination', $stop_names, null) !!}
            </div>

            {!! Form::date('active_date_end') !!}
            {{Form::submit('Buy Ticket' , ['class' => 'btn btn-primary'])}}
            {!! Form::close() !!}
        @endif
    </div>

    @if(Auth::check() && Auth::user()->isAdmin() )
    {!! Form::open(['action' => ['SchedulesController@update', $schedule->schedule_id], 'method' => 'POST']) !!}
        {!! Form::select('direction',['forward'=>'forward', 'backwards'=>'backwards']) !!}
        {{ Form::hidden('route_id', $schedule->route_id) }}
        <div class="form-group">
            {!! Form::label('start_time', 'Start Time') !!}
            {!! Form::input('time','StartTime') !!}
        </div>
        {{Form::hidden('_method' , 'PUT')}}
        {{Form::submit('Edit schedule' , ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}

    
    {!! Form::open(['action' => ['SchedulesController@destroy', $schedule->schedule_id], 'method' => 'POST', 'class'=>'pull_right']) !!}
    {{Form::hidden('_method' , 'DELETE')}}
    {{Form::submit('Delete schedule' , ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
    @endif


    @endsection