@extends('layouts.app' , ['controller' => 'position'])

@section('styles')
<style>
#showURL{
    word-break: break-all;
}
.txt-description {
    white-space: pre-line;
}
.card-header .card-title a{
    vertical-align: middle;
    text-decoration: underline;
}
</style>
@endsection
   
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Postulant</h2>
            </div>
            <div class="float-right">
                <a class="btn btn-primary" href="{{ route('recruit.menu') }}"> Back</a>
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
  
    <form name="update-recruit" id="update-recruit" action="{{ route('recruit.update',$recruit[0]->id) }}" method="POST" enctype="multipart/form-data">
        <button type="submit" id="update_recruit" class="btn btn-success">Save</button>
        
        @csrf
   
        <div class="row mt-4">
            <div class="col">
                <h3 class="mb-5">General Information</h3>
            </div>
            
            <div class="col-12 col-sm-6 col-md-5">
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="file_path_update" id="file_path_update" accept="application/msword, application/pdf, .doc, .docx">
                        <label class="custom-file-label" for="file_path">UPLOAD CV (max 2M)</label>
                    </div>
                    @if( $recruit[0]->file_path != '' )
                    <div class="input-group-append">
                        <a href="{{ $recruit[0]->file_path }}" download class="btn btn-outline-secondary">DOWNLOAD</a>
                    </div>
                    @endif
                </div>
            </div>
            
        </div>
        <div class="form-row">
            <div class="form-group col-12 col-sm-4">
                <label for="fullname">Name</label>
                <input type="text" name="fullname" id="fullname" class="form-control" value="{{$recruit[0]->fullname}}">
            </div>
            <div class="form-group col-12 col-sm-4">
                <label for="identification_number">DNI/CE/Pasaporte</label>
                <input type="text" name="identification_number" id="identification_number" class="form-control" value="{{$recruit[0]->identification_number}}">
            </div>
            <div class="form-group col-12 col-sm-4">
                <label for="platform">Platform</label>
                <select name="platform" id="platform" class="form-control" >
                    <option value="">None</option>
                    @foreach($platforms as $pid => $platform)
                        @if ($platform->value == $recruit[0]->platform)
                            <option value="{{$platform->value}}" selected>{{$platform->label}}</option>
                        @endif
                        @if ($platform->value != $recruit[0]->platform)
                            <option value="{{$platform->value}}">{{$platform->label}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-12 col-sm-4">
                <label for="phone">Teléfono/Celular</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{$recruit[0]->phone_number}}" phone>
            </div>
            <div class="form-group col-12 col-sm-4">
                <label for="email_address">Email</label>
                <input type="text" name="email_address" id="email_address" class="form-control" value="{{$recruit[0]->email_address}}" required>
            </div>
            <div class="form-group col-12 col-sm-4">
                <label for="phone">Link</label>
                <input type="text" name="profile_link" id="profile_link" class="form-control" value="{{$recruit[0]->profile_link}}" phone>
            </div>
        </div>
   
    </form>
@endsection

@section('javascript')
    <script type="text/javascript">
        
    </script>
    <script>
        /*$("#update_recruit").on('click', function(ev){        
            ev.preventDefault();
            var link = $("#profile_link").val();
            var file_path = $("#file_path").val();

            if(!link && !file_path){
                alert('We need "Link" or "CV"')
            }else{
                $("#update-recruit").submit();
            }
        });*/
        
        $('#file_path_update').on('change',function(ev){
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(ev.target.files[0].name);
        });
    </script>
@endsection