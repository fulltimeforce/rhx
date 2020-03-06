@extends('layouts.app' , ['controller' => 'experts-edit'])

@section('styles')
<style>
#showURL{
    word-break: break-all;
}
</style>
@endsection
   
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Expert</h2>
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
  
    <form action="{{ route('experts.update',$expert->id) }}" method="POST" enctype="multipart/form-data">
        <button type="submit" class="btn btn-success">Editar</button>
        <a href="#" data-expert="{{ $expert->id }}" id="url-generate"  class="btn btn-info ">Link</a>
        <div class="alert alert-warning alert-dismissible mt-3" role="alert" style="display: none;">
            <b>Copy successful!!!!</b>
            <p id="showURL"></p>
        </div>

        @csrf
        @method('PUT')
   
        <div class="row mt-4">
            <div class="col">
                <h3 class="mb-5">Información General</h3>
            </div>
            
            <div class="col-12 col-sm-6 col-md-5">
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="file_cv" id="file_cv" accept="application/msword, application/pdf, .doc, .docx">
                        <label class="custom-file-label" for="file_cv">UPLOAD CV (max 2M)</label>
                    </div>
                    @if( $expert->file_path != '' )
                    <div class="input-group-append">
                        <a href="{{ $expert->file_path }}" download class="btn btn-outline-secondary">DOWNLOAD</a>
                    </div>
                    @endif
                </div>
            </div>
            
        </div>
        <div class="form-row">
            <div class="form-group col-12 col-sm-4">
                <label for="fullname">Nombre</label>
                <input type="text" name="fullname" id="fullname" class="form-control" value="{{$expert->fullname}}">
            </div>
            <div class="form-group col-12 col-sm-4">
                <label for="email_address">Email</label>
                <input type="text" name="email_address" class="form-control" id="email_address" value="{{$expert->email_address}}" required>
            </div>
            <div class="form-group col-6 col-sm-2">
                <label for="identification_number">DNI/CE/Pasaporte</label>
                <input type="text" name="identification_number" class="form-control" id="identification_number" value="{{$expert->identification_number}}">
            </div>
            <div class="form-group col-6 col-sm-2">
                <label for="birthday">Fecha de nacimiento</label>
                <input type="text" name="birthday" class="form-control date" id="birthday" value="{{$expert->birthday}}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-12 col-sm-4">
                <label for="education">Universidad/Instituto</label>
                <input type="text" name="education" class="form-control" id="education" value="{{$expert->education}}">
            </div>
            <div class="form-group col-12 col-sm-4">
                <label for="english_education">¿Donde aprendió inglés?</label>
                <select name="english_education" class="form-control" id="english_education">
                    <option value=""></option> 
                    <option value="self" {{ ($expert->english_education=="self")? "selected" : "" }}>Independiente</option> 
                    <option value="academy" {{ ($expert->english_education=="academy")? "selected" : "" }}>ICPNA, Británico, Idiomas Católica</option>
                    <option value="university" {{ ($expert->english_education=="university")? "selected" : "" }}>Universidad o instituto</option>
                </select>
            </div>
            <div class="form-group col-2">
                <label for="phone">Teléfono/Celular</label>
                <input type="text" name="phone" class="form-control" id="phone" value="{{$expert->phone}}" phone>
            </div>
            <div class="form-group col-2">
                <label for="last_info_update">Última actualización</label>
                <input type="text" name="last_info_update" class="form-control date" id="last_info_update" value="{{$expert->last_info_update}}">
            </div>
        </div>
        <div class="form-row"> 
            <div class="form-group col-12 col-sm-4 col-lg-6">
                <label for="address">País - Ciudad</label>
                <input type="text" name="address" class="form-control" id="address" value="{{$expert->address}}">
            </div>
            <div class="form-group col-6 col-sm-4 col-lg-3">
                <label for="salary">Expectativa salarial</label>
                <input type="text" name="salary" class="form-control" id="salary" value="{{$expert->salary}}">
            </div>
            <div class="form-group col-6 col-sm-4 col-lg-3">
                <label for="availability">Disponibilidad</label>
                <input type="text" name="availability" class="form-control date" id="availability" value="{{$expert->availability}}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-6 col-sm">
                <label for="linkedin">Linkedin</label>
                <input type="text" name="linkedin" class="form-control" id="linkedin" value="{{$expert->linkedin}}">
            </div>
            <div class="form-group col-6 col-sm">
                <label for="github">Github</label>
                <input type="text" name="github" class="form-control" id="github" value="{{$expert->github}}">
            </div>
            <div class="form-group col-6 col-sm">
                <label for="instagram">Instagram</label>
                <input type="text" name="instagram" class="form-control" id="instagram" value="{{$expert->instagram}}">
            </div>
            <div class="form-group col-6 col-sm">
                <label for="facebook">Facebook</label>
                <input type="text" name="facebook" class="form-control" id="facebook" value="{{$expert->facebook}}">
            </div>
            <div class="form-group col-6 col-sm">
                <label for="twitter">Twitter</label>
                <input type="text" name="twitter" class="form-control" id="twitter" value="{{$expert->twitter}}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-12 col-sm-4">
                <label for="other_knowledge">¿Qué otros conocimientos tiene?</label>
                <input type="text" name="other_knowledge" class="form-control" id="other_knowledge" value="{{$expert->other_knowledge}}">
            </div>
            <div class="form-group col-12 col-sm-4">
                <label for="wish_knowledge">¿Qué le gustaría aprender?</label>
                <input type="text" name="wish_knowledge" class="form-control" id="wish_knowledge" value="{{$expert->wish_knowledge}}">
            </div>
            <div class="form-group col">
                <label for="focus">Has tenido mayor experience en:</label>
                <select name="focus" class="form-control" id="focus">
                    <option value=""></option> 
                    <option value="fullstack" {{ ($expert->focus=="fullstack")? "selected" : "" }}>Fullstack</option>
                    <option value="backend" {{ ($expert->focus=="backend")? "selected" : "" }}>Backend</option>
                    <option value="frontend" {{ ($expert->focus=="frontend")? "selected" : "" }}>Frontend</option>
                    <option value="mobile" {{ ($expert->focus=="mobile")? "selected" : "" }}>Mobile</option>
                    <option value="devops" {{ ($expert->focus=="devops")? "selected" : "" }}>DevOps</option>
                </select>
            </div>
        </div>
        <h3>Evaluaciones</h3>
        <div class="form-row">
            <div class="form-group col-4 col-sm-3">
                <label for="assessment1">Evaluado en</label>
                <input type="text" name="assessment1" class="form-control" id="assessment1" value="{{$expert->assessment1}}">
            </div>
            <div class="form-group col-8 col-sm">
                <label for="result1">Resultado</label>
                <input type="text" name="result1" class="form-control" id="result1" value="{{$expert->result1}}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-4 col-sm-3">
                <label for="assessment2">Evaluado en</label>
                <input type="text" name="assessment2" class="form-control" id="assessment2" value="{{$expert->assessment2}}">
            </div>
            <div class="form-group col-8 col-sm">
                <label for="result2">Resultado</label>
                <input type="text" name="result2" class="form-control" id="result2" value="{{$expert->result2}}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-4 col-sm-3">
                <label for="assessment3">Evaluado en</label>
                <input type="text" name="assessment3" class="form-control" id="assessment3" value="{{$expert->assessment3}}">
            </div>
            <div class="form-group col-8 col-sm">
                <label for="result3">Resultado</label>
                <input type="text" name="result3" class="form-control" id="result3" value="{{$expert->result3}}">
            </div>
        </div>
    @foreach($technologies as $categoryid => $category)
        <h3>{{$category[0]}}</h3>
        <br>
        @foreach($category[1] as $key => $value)
        <fieldset class="form-group">
            <div class="form-row">
                <legend class="col-form-label col-5 col-md-3 pt-0">{{$value}}</legend>
                <div class="col-7 col-md-9">
                    <div class="form-check-inline">
                        <input class="form-check-input" type="radio" name="{{$key}}" id="{{$key}}u" value="unknown" {{ ($expert->$key=="unknown")? "checked" : "" }}>
                        <label class="form-check-label" for="{{$key}}u">No lo manejo</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input" type="radio" name="{{$key}}" id="{{$key}}b" value="basic" {{ ($expert->$key=="basic")? "checked" : "" }}>
                        <label class="form-check-label" for="{{$key}}b">Básico</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input" type="radio" name="{{$key}}" id="{{$key}}i" value="intermediate" {{ ($expert->$key=="intermediate")? "checked" : "" }}>
                        <label class="form-check-label" for="{{$key}}i">Intermedio</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input" type="radio" name="{{$key}}" id="{{$key}}a" value="advanced" {{ ($expert->$key=="advanced")? "checked" : "" }}>
                        <label class="form-check-label" for="{{$key}}a">Avanzado</label>
                    </div>
                </div>
            </div>
        </fieldset>
        @endforeach
    @endforeach
    
    <button type="submit" class="btn btn-primary">Editar</button>
    </div>
   
    </form>
@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.date').datetimepicker({
                format: "{{ config('app.date_format_javascript') }}",
                locale: "en"
            });
        });
    </script>
    <script>
        $('#file_cv').on('change',function(ev){
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(ev.target.files[0].name);
        });
        // $('[data-toggle="tooltip"]').tooltip({ trigger : 'click' });

        $('#url-generate').on('click', function (ev) {
            ev.preventDefault();
            $.ajax({
                type:'GET',
                url:'/developer/edit/signed/'+ $(this).data("expert"),
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){
                    $('#showURL').html(data);
                    
                    var el = document.createElement("textarea");
                    el.value = data;
                    el.style.position = 'absolute';                 
                    el.style.left = '-9999px';
                    el.style.top = '0';
                    el.setSelectionRange(0, 99999);
                    el.setAttribute('readonly', ''); 
                    document.body.appendChild(el);
                    
                    el.focus();
                    el.select();

                    var success = document.execCommand('copy')
                    if(success){
                        $(".alert").slideDown(200, function() {
                                
                        });
                    }
                    setTimeout(() => {
                        $(".alert").slideUp(500, function() {
                            document.body.removeChild(el);
                        });
                    }, 4000);

                    // $("#urlGeneration").modal();
                }
            });
        });

    </script>
@endsection