@extends('layouts.app');

@section('content')

    <h1>Create Route</h1>
    {!! Form::open(['action' => 'RoutesController@store', 'method' => 'POST']) !!}
        <div class="form-group">
                {!! Form::select('stops', $stops, null) !!}
        </div>

        <div class="form-group">
                {!! Form::select('destination', $stops, null) !!}
        </div>
        {!! Form::select('active_days',['whole week'=>'whole week','work days'=>'work days', 'weekends'=>'weekends']) !!}
        {!! Form::date('active_date_start', \Carbon\Carbon::now()) !!}
        {!! Form::date('active_date_end') !!}
        {{Form::submit('Submit' , ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}

@endsection