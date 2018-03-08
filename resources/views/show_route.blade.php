@extends('layouts.app');

@section('content')
    <a href="/routes" class="btn btn-default">Go Back</a>
    <h1>Route:</h1>
    @foreach($stops as $stop)
        <h3>
        @if($stop->stop_id==$route->start || $stop->stop_id==$route->destination)
        {{$stop->stop_name}}     
        @endif
    @endforeach
        </h3>
        <p>Active Days: {{$route->active_days}}</p>
        <p>Active from: {{$route->active_date_start}} - {{$route->active_date_end}}</p>

        @if(Auth::check() && Auth::user()->isAdmin() )
        <p>Edit Route: </p>
        {!! Form::open(['action' => ['RoutesController@update', $route->route_id], 'method' => 'POST']) !!}
        <div class="form-group">
                {!! Form::select('stops', $stop_names, null) !!}
        </div>

        <div class="form-group">
                {!! Form::select('destination', $stop_names, null) !!}
        </div>
        {!! Form::select('active_days',['whole week'=>'whole week','work days'=>'work days', 'weekends'=>'weekends']) !!}
        {!! Form::date('active_date_start', \Carbon\Carbon::now()) !!}
        {!! Form::date('active_date_end') !!}
        {{Form::hidden('_method' , 'PUT')}}
        {{Form::submit('Edit Route' , ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
    @endif

    <hr color="#1111fe" size="4" width="100%">

    <h2>Stops:</h2>
    @foreach($stops as $stop)
        @foreach($routestops as $routestop)
            @if($stop->stop_id==$routestop->stop_id)
            <h3><a href="/stops/{{$stop->stop_id}}">{{$stop->stop_name}} </a> : 
                Zone : {{$stop->zone}} : 
                Stop Number : {{$routestop->stop_number}}
                @if(Auth::check() && Auth::user()->isAdmin() )
                {!! Form::open(['action' => ['RouteStopsController@update', $routestop->route_stop_id], 'method' => 'POST']) !!}
                        <div class="form-group">
                            {!! Form::select('stop_id', $stop_infos, null) !!}
                        </div>

                        {{ Form::hidden('route_id', $route->route_id) }}
                        <div class="form-group">
                        {{Form::label('stop_number', 'Stop Number')}}
                        {{Form::text('StopNumber', $routestop->stop_number, ['class' => 'field'])}}
                    </div>
                    {{Form::hidden('_method' , 'PUT')}}
                    {{Form::submit('Edit Stops' , ['class' => 'btn btn-primary'])}}
                {!! Form::close() !!}

                
                {!! Form::open(['action' => ['RouteStopsController@destroy', $routestop->route_stop_id], 'method' => 'POST', 'class'=>'pull_right']) !!}
                {{Form::hidden('_method' , 'DELETE')}}
                {{Form::submit('Remove Stop from Route' , ['class' => 'btn btn-primary'])}}
                {!! Form::close() !!}
                @endif
            </h3>        
            @endif
        @endforeach
        
    @endforeach

    <h2>Schedule:</h2>
    @foreach($forwards as $forward)
        <a href="/schedules/{{$forward->schedule_id}}">{{$forward->start_time}}</a>
        |
    @endforeach

    <hr color="#1111fe" size="4" width="100%">

    @foreach($backwards as $backward)
        <a href="/schedules/{{$backward->schedule_id}}">{{$backward->start_time}}</a>
        |
    @endforeach

    <hr color="#1111fe" size="4" width="100%">

    @if(Auth::check() && Auth::user()->isAdmin() )
    {!! Form::open(['action' => 'SchedulesController@store', 'method' => 'POST']) !!}
    {{ Form::hidden('route_id', $route->route_id) }}
    {!! Form::select('direction',['forward'=>'forward', 'backwards'=>'backwards']) !!}
    <div class="form-group">
        {!! Form::label('start_time', 'Start Time') !!}
        {!! Form::input('time','StartTime') !!}
    </div>
    {{Form::submit('Add Schedule' , ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}

    {!! Form::open(['action' => ['RoutesController@destroy', $route->route_id], 'method' => 'POST', 'class'=>'pull_right']) !!}
    {{Form::hidden('_method' , 'DELETE')}}
    {{Form::submit('Delete route' , ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
    @endif
@endsection