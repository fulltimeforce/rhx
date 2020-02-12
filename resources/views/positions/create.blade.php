@extends('layouts.app')
  
@section('content')
<div class="row">
    <div class="col-lg-12 mt-5 mb-5">
        <div class="float-left">
            <h2>New Position</h2>
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
   
<form action="{{ route('positions.store') }}" method="POST">
    @csrf
    <div class="form-row">
        <div class="form-group col">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control">
        </div>
        
    </div>
    <div class="form-row">
        <div class="form-group col">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" rows="15" id="description"></textarea>
        </div>
    </div>
    <div class="form-row">
        <div class="form-check form-group">
            <input type="checkbox" name="status" id="status" class="form-check-input" value="enabled" checked>
            <label for="status" class="form-check-label">Enabled</label>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    </div>
   
</form>
@endsection

@section('javascript')

@endsection