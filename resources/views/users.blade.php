@extends('layouts.app');

@section('content')
    @if(Auth::check() && Auth::user()->isAdmin() )
    <h1>Users</h1>

    @if(count($users)>0)
        @foreach($users as $user)
        <div class = "well">
            <p> 
            <h3><a href="/users/{{$user->id}}">{{$user->name}}</a></h3>
             <h3>{{$user->email}}</h3>

                {!! Form::open(['action' => ['UserController@destroy', $user->id], 'method' => 'POST', 'class'=>'pull_right']) !!}
                {{Form::hidden('_method' , 'DELETE')}}
                {{Form::submit('Delete User' , ['class' => 'btn btn-primary'])}}
                {!! Form::close() !!}

                @foreach($admins as $admin)
                    @if($admin->admin_id == $user->id)
                    <p>Admin</p>
                    @endif
                @endforeach
                   
            </p>  
            </div>
        
            <hr color="#1111fe" size="4" width="100%">
        @endforeach
    @else
        <p> No Users found </p>
    @endif
    @endif
    @endsection