@extends('layouts.app' , ['controller' => 'positions-create'])

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

@endsection
@section('content')
<div class="row">
    <div class="col-lg-12 mt-5 mb-5">
        <div class="float-left">
            <h2>New Position</h2>
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

    <div class="row mb-3">
        <div class="col-12">
            <h4>Requirements</h4>
        </div>
        <div class="col-6">
            <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control" id="requirement" placeholder="Username">
                    <div class="input-group-append">
                        <a href="#" class="btn btn-outline-primary float-right" id="add-requirement">Add</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <ul class="list-group" id="list-requirements">
            </ul>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <h4>Technologies</h4>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label for="technology_basic">Basic</label>
                <select multiple id="technology_basic" name="technology_basic[]" class="form-control search-level basic" size="1"></select>
            </div>
            <div class="form-group">
                <label for="technology_inter">Intermediate</label>
                <select multiple id="technology_inter" name="technology_inter[]" class="form-control search-level intermediate" size="1"></select>
            </div>
            <div class="form-group">
                <label for="technology_advan">Advanced</label>
                <select multiple id="technology_advan" name="technology_advan[]" class="form-control search-level advanced" size="1"></select>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    </div>
   
</form>
@endsection

@section('javascript')
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function(){

        $(".search-level").select2({
            
            ajax: {
                url: "{{ route('expert.technologies') }}",
                dataType: 'json',
                data: function (params) {
                    var query = {
                        search: params.term,
                    }
                    // Query parameters will be ?search=[term]&type=public
                    return query;
                },
                processResults: function (data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: data
                    };
                }

            }
        });

        $("#add-requirement").on('click' , function(ev){
            ev.preventDefault();
            $("#list-requirements").append( " <li class='list-group-item d-flex justify-content-between align-items-center'>"+ $("#requirement").val() +"<input type='hidden' name='req[]' value='"+$("#requirement").val()+"' /> <span class='badge badge-primary badge-pill requirement-remove'>R</span></li>" )
            $("#requirement").val('');
        });
        $("#list-requirements").on('click', '.requirement-remove' , function(ev){
            $(this).parent().slideUp(400 , () => { $(this).parent().remove(); });
        });

    });
</script>

@endsection