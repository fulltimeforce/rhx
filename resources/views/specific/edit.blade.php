@extends('layouts.app' , ['controller' => 'positions-edit'])

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>
@endsection

@section('content')
    <!--
    TITLE AND BACK BUTTON SECTION
    -->
    <div class="row">
        <div class="col-lg-12 mt-5 mb-5">
            <div class="float-left">
                <h2>Edit Position</h2>
            </div>
            <div class="float-right">
                <a class="btn btn-primary" href="{{ route('specific.menu') }}"> Back</a>
            </div>
        </div>
    </div>

    <!--
    ERROR - SUCCESS MESSAGE SECTION
    -->
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
    @if ($message = Session::get('error'))
        <div class="alert alert-danger">
            <p>{!! $message !!}</p>
        </div>
    @endif

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <!--
    EDIT SPECIFIC POSITION FORM
    -->
    <form action="{{ route('specific.update', $position->id) }}" method="POST">
        @csrf
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
                <input type="checkbox" name="status" id="status" class="form-check-input" value="enabled" {{ ($position->status=='enabled')?'checked':'' }} >
                <label for="status" class="form-check-label">Enabled</label>
            </div>
        </div>
        <div class="form-row">
            <div class="form-check form-group">
                <input type="checkbox" name="private" id="private" class="form-check-input" value="1" {{ ($position->private==1)?'checked':'' }} >
                <label for="status" class="form-check-label">Private</label>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <h4>Technologies</h4>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="technology_basic">Basic</label>
                    <select multiple id="technology_basic" name="technology_basic[]" class="form-control search-level basic" size="1">
                        @foreach( $a_basic_new as $tid => $tlabel)
                            <option value="{{ $tid }}" selected > {{ $tlabel }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="technology_inter">Intermediate</label>
                    <select multiple id="technology_inter" name="technology_inter[]" class="form-control search-level intermediate" size="1">
                        @foreach( $a_inter_new as $tid => $tlabel)
                            <option value="{{ $tid }}" selected > {{ $tlabel }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="technology_advan">Advanced</label>
                    <select multiple id="technology_advan" name="technology_advan[]" class="form-control search-level advanced" size="1">
                        @foreach( $a_advan_new as $tid => $tlabel)
                            <option value="{{ $tid }}" selected > {{ $tlabel }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>   
    </form>
@endsection

@section('javascript')
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script type="text/javascript">
  
    $(document).ready(function(){

        //===================================================================================
        //=====================EDIT SPECIFIC POSITION BUTTON FUNCTION========================
        //===================================================================================
        
        //LOAD SELECT OPTIONS FUNCTION
        $(".search-level").select2({
            ajax: {
                url: "{{ route('specific.technologies') }}",
                dataType: 'json',
                data: function (params) {
                    var query = {
                        search: params.term,
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                }
            }
        });
    });
    
</script>

@endsection