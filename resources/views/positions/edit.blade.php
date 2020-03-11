@extends('layouts.app' , ['controller' => 'positions-edit'])
   
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
                <input type="checkbox" name="status" id="status" class="form-check-input" value="enabled" {{ ($position->status=='enabled')?'checked':'' }} >
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
                    @foreach($position->requirements as $epid => $requirement)
                    <li class="list-group-item d-flex justify-content-between align-items-center">{{ $requirement->name }} <input type="hidden" value="{{ $requirement->name }}" name="req[]">  <span class='badge badge-primary badge-pill requirement-remove'>R</span> </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Edit</button>
        
    </div>
   
    </form>
@endsection

@section('javascript')

<script>
    $(document).ready(function(){
        $("#add-requirement").on('click' , function(ev){
            ev.preventDefault();
            $("#list-requirements").append( " <li class='list-group-item d-flex justify-content-between align-items-center'>"+ $("#requirement").val() +"<input type='hidden' name='req[]' value='"+$("#requirement").val()+"' /> <span class='badge badge-primary badge-pill'>R</span></li>" )
            $("#requirement").val('');
        });

        $("#list-requirements").on('click', '.requirement-remove' , function(ev){
            $(this).parent().slideUp(400 , () => { $(this).parent().remove(); });
        });
    });

    
</script>

@endsection