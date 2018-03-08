@extends('layouts.app');

@section('content') 
    @if(Auth::check() && Auth::user()->isAdmin() )
    <h1>Create Stop</h1>

    {!! Form::open(['action' => 'StopsController@store', 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('stop_name', 'Stop Name')}}
            {{Form::text('StopName', '', ['class' => 'field'])}}
        </div>
        <div class="form-group">
            {{Form::label('zone', 'Zone')}}
            {{Form::text('Zone', '', ['class' => 'field'])}}
        </div>
        {{Form::submit('Submit' , ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
    @endif
@endsection