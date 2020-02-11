@extends('layouts.app')
 
@section('content')
    <div class="row">
        <div class="col">
            <h1>Fulltimeforce Careers</h1>
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
        <div class="card-body">
            <h4 class="card-title">{{$position->name}}</h4>
            <p class="card-text">{{$position->description}}</p>
            <a href="#" class="card-link">Edit</a>
            <a href="#" class="btn btn-primary">Apply!</a>
        </div>
    </div>
    @endforeach
@endsection

@section('javascript')

@endsection