@extends('layouts.app');

@section('content')
    @if(Auth::check() && Auth::user()->isAdmin() )
    <h1>Add Stops on Route</h1>
    {!! Form::open(['action' => 'RouteStopsController@store', 'method' => 'POST']) !!}
        <div class="form-group">
                {{$routes = Route::all()->get()}}
                {!! Form::select('routes', $routes, null) !!}
        </div>

        <div class="form-group">
                {!! Form::select('destination', $stops, null) !!}
        </div>
        {{Form::submit('Submit' , ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
    @endif

@endsection