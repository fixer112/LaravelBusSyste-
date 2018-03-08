@extends('layouts.app');

@section('content')
    <a href="/routes" class="btn btn-default">Go Back</a>
    <h1>Stop: {{$stop->stop_name}}</h1>
    <p>Zone: {{$stop->zone}}</p>

    @if(Auth::check() && Auth::user()->isAdmin() )
    {!! Form::open(['action' => ['StopsController@update', $stop->stop_id], 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('stop_name', 'Stop Name')}}
            {{Form::text('StopName', $stop->stop_name, ['class' => 'field'])}}
        </div>
        <div class="form-group">
            {{Form::label('zone', 'Zone')}}
            {{Form::text('Zone', $stop->zone, ['class' => 'field'])}}
        </div>
        {{Form::hidden('_method' , 'PUT')}}
        {{Form::submit('Edit stop' , ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}

    {!! Form::open(['action' => ['StopsController@destroy', $stop->stop_id], 'method' => 'POST', 'class'=>'pull_right']) !!}
    {{Form::hidden('_method' , 'DELETE')}}
    {{Form::submit('Delete stop' , ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
    @endif

@endsection