@extends('layouts.app');

@section('content')
    <h1>User</h1>
        <h3>{{$user->name}}</h3>
        <h3> {{$user->email}}</h3>

        @if(Auth::check() && Auth::user()->isAdmin() )
        @if(count($admin)==0)
            {!! Form::open(['action' => 'AdminsController@store', 'method' => 'POST']) !!}
            {{ Form::hidden('user_id', $user->id) }}
            {{Form::submit('Promote to Admin' , ['class' => 'btn btn-primary'])}}
            {!! Form::close() !!}
        @endif

        {!! Form::open(['action' => ['UserController@update', $user->id], 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('user_name', 'User Name')}}
            {{Form::text('UserName', $user->name, ['class' => 'field'])}}
        </div>
        <div class="form-group">
            {{Form::label('emil', 'Email')}}
            {{Form::text('Email', $user->email, ['class' => 'field'])}}
        </div>
        <div class="form-group">
            {{Form::label('password', 'Password')}}
            {{Form::password('secret')}}
        </div>
            {{Form::hidden('_method' , 'PUT')}}
            {{Form::submit('Edit user' , ['class' => 'btn btn-primary'])}}
        {!! Form::close() !!}

        
        {!! Form::open(['action' => ['UserController@destroy', $user->id], 'method' => 'POST', 'class'=>'pull_right']) !!}
                {{Form::hidden('_method' , 'DELETE')}}
                {{Form::submit('Delete User' , ['class' => 'btn btn-primary'])}}
        {!! Form::close() !!}
        @endif
    @endsection