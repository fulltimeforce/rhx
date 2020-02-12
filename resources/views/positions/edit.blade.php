@extends('layouts.app')
   
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Position</h2>
            </div>
            <div class="float-right">
                <a class="btn btn-primary" href="{{ url('/positions') }}"> Back</a>
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
        @csrf
        @method('PUT')
        <div class="form-row">
            <div class="form-group col">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{$position->name}}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col">
                <label for="description">Description</label>
                <textarea name="description" class="form-control" rows="15" id="description">{{$position->description}}</textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="form-check form-group">
                <input type="checkbox" name="status" id="status" class="form-check-input" value="enabled" {{!! ($position->status=='enabled')?'checked':'' !!}}>
                <label for="status" class="form-check-label">Enabled</label>
            </div>
        </div>
    
    <button type="submit" class="btn btn-primary">Edit</button>
    </div>
   
    </form>
@endsection

@section('javascript')
@endsection