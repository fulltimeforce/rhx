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
    <div class="row row-cols-1 ">
    @foreach($positions as $pid => $position)
    <div class="col mb-4">
        <div class="card">
            <div class="card-header" data-toggle="collapse" href="#position-{{$position->id}}" role="button" aria-expanded="true" aria-controls="position-{{$position->id}}">
                <h4>{{$position->name}}</h4>
            </div>
            <div class="card-body">
                <div class="card-text collapse show" id="position-{{$position->id}}">{!! nl2br($position->description) !!}</div>
            </div>
            <div class="card-footer">
                @guest
                <a href="{{ route('experts.apply',$position->id) }}" class="btn btn-primary float-right">Apply!</a>
                @else
                <a href="{{ route('positions.edit', $position->id) }}" class="card-link">Edit</a>
                @endguest
            </div>
        </div>
    </div>
    @endforeach
    </div>
@endsection

@section('javascript')

@endsection