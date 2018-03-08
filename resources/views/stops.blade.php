@extends('layouts.app');

@section('content')
    <h1>Stops</h1>
    @if(count($stops)>0)
        @foreach($stops as $stop)
            <h3><a href="/stops/{{$stop->stop_id}}">{{$stop->stop_name}}</a></h3>
            <p>Zone:{{$stop->zone}}</p>

            <hr color="#1111fe" size="4" width="100%">
        @endforeach
    @else
        <p> No Stops found </p>
    @endif


@endsection