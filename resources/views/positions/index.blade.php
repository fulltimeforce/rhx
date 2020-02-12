@extends('layouts.app')
 
@section('content')
    <div class="row">
        <div class="col">
            <h1>Careers</h1>
            @auth
            <a class="btn btn-secondary float-right" href="{{ route('positions.create') }}">New Position</a>
            @endauth
        </div>
    </div>
   
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <br>
    
    @foreach($positions as $pid => $position)
    <div class="card">
        <div class="card-header" data-toggle="collapse" href="#position-{{$position->id}}" role="button" aria-expanded="false" aria-controls="position-{{$position->id}}">
            <h4>{{$position->name}}</h4>
        </div>
        <div class="card-body">    
            <div class="card-text collapse" id="position-{{$position->id}}">{!! nl2br($position->description) !!}</div>
        </div>
        <div class="card-footer">
            @guest
            <a href="{{ route('experts.apply',$position->id) }}" class="btn btn-primary float-right">Apply!</a>
            @else
            <a href="{{ route('positions.edit', $position->id) }}" class="card-link">Edit</a>
            @endguest
        </div>
    </div>
    @endforeach
@endsection

@section('javascript')

@endsection