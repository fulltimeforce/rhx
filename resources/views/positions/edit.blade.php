@extends('layouts.app')
   
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Position</h2>
            </div>
            <div class="float-right">
                <a class="btn btn-primary" href="{{ url('/') }}"> Back</a>
            </div>
        </div>
    </div>
   
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
  
    <form action="{{ route('positions.update',$position->id) }}" method="POST">
        <button type="submit" class="btn btn-primary">Edit</button>
        @csrf
        @method('PUT')
        <div class="form-row">
            <div class="form-group col">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{$position->name}}">
            </div>
            <div class="form-group col">
                <label for="status">Status</label>
                <input type="checkbox" name="status" id="status" class="form-control" value="enabled" {{!! ($position->status=='enabled')?'checked':'' !!}}>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col">
                <label for="description">Description</label>
                <textarea name="description" class="form-control" id="description">{{$position->description}}</textarea>
            </div>
        </div>
    
    <button type="submit" class="btn btn-primary">Edit</button>
    </div>
   
    </form>
@endsection

@section('javascript')
@endsection