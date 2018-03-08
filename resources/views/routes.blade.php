@extends('layouts.app');

@section('content')
    <h1>Routes</h1>
    @if(count($routes)>0)
        @foreach($routes as $route)
            <div class = "well">
                <p> 
                 @foreach($stops as $stop)
                 {!! Form::open(['action' => 'RouteStopsController@store', 'method' => 'POST']) !!}
                 @if($stop->stop_id==$route->start || $stop->stop_id==$route->destination)
                 <h3><a href="/routes/{{$route->route_id}}">{{$stop->stop_name}}</a></h3>
                    {{ Form::hidden('route_id', $route->route_id) }}
                @endif
                @endforeach  
                </p>  
                    @if(Auth::check() && Auth::user()->isAdmin() )
                    <div class="form-group">
                        {!! Form::select('stop_id', $stop_infos, null) !!}
                    </div>

                    <div class="form-group">
                            {{Form::label('stop_number', 'Stop Number')}}
                            {{Form::text('StopNumber', '', ['class' => 'field'])}}
                        </div>
                    {{Form::submit('Add Stop' , ['class' => 'btn btn-primary'])}}
                    @endif
                    {!! Form::close() !!}
            </div>
            
            <hr color="#1111fe" size="4" width="100%">

        @endforeach
    @else
        <p> No Routes found </p>
    @endif
@endsection