@extends('layouts.app', ['controller' => 'expert-create'])
@section('styles')
<style>
#showURL{
    word-break: break-all;
}
.txt-description {
    white-space: pre-line;
}
</style>
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12 mt-5 mb-5">
        <div class="float-left">
            @isset($positionName)
            <h2>Apply to {{ $positionName }}</h2>
            @endisset
            @empty($positionName)
            @auth
            <h2>New Expert</h2>
            @endauth
            @endempty
            
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

<form action="{{ route('experts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col mb-5">
            <h3 class="">Información General</h3>
            @if ($expert->id != '')
            <span>Actualiza tu información</span> 
            @endif
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="custom-file">
                <input type="file" class="custom-file-input" name="file_cv" id="file_cv" accept="application/msword, application/pdf, .doc, .docx">
                <label class="custom-file-label" for="file_cv">UPLOAD CV (max 2M)</label>
            </div>
        </div>
    </div>
    @isset($signed )
    <div class="row">
        <div class="col">
            <input type="hidden" name="signed" value="{{ $signed }}">
            <input type="hidden" name="logid" value="{{ $log }}">
        </div>
    </div>
    @endisset
    <div class="form-row">
        <div class="form-group col-12 col-sm-4">
            <label for="fullname">Nombre completo</label>
            <input type="text" name="fullname" id="fullname" class="form-control" value="{{ $expert->fullname }}" required> 
        </div>
        <div class="form-group col-12 col-sm-4">
            <label for="email_address">Email</label>
            <input type="text" name="email_address" class="form-control" id="email_address" value="{{ $expert->email_address }}" required >
        </div>
        <div class="form-group col-6 col-sm-2">
            <label for="identification_number">DNI/CE/Pasaporte</label>
            <input type="text" name="identification_number" class="form-control" id="identification_number" value="{{ $expert->identification_number }}">
        </div>
        <div class="form-group col-6 col-sm-2">
            <label for="birthday">Fecha de nacimiento</label>
            <input type="text" name="birthday" class="form-control date" id="birthday" data-toggle="datetimepicker" data-target="#birthday" value="{{ $expert->birthday }}">
        </div>
        
    </div>
    <div class="form-row">
        <div class="form-group col-12 col-sm-4">
            <label for="education">Universidad/Instituto - Carrera</label>
            <input type="text" name="education" class="form-control" id="education" value="{{ $expert->education }}">
        </div>
        <div class="form-group col-12 col-sm-4">
            <label for="english_education">¿Donde aprendiste inglés?</label>
            <select name="english_education" class="form-control" id="english_education" value="{{ $expert->english_education }}">
                <option value=""></option> 
                <option value="self">Independiente</option> 
                <option value="academy">ICPNA, Británico, Idiomas Católica</option>
                <option value="university">Universidad o instituto</option>
            </select>
        </div>
        <div class="form-group col">
            <label for="phone">Teléfono/Celular</label>
            <input type="text" name="phone" class="form-control" id="phone" value="{{ $expert->phone }}" required>
        </div>
        <div class="form-group col">
            <label for="last_info_update">Última actualización</label>
            @auth
            {!! Form::text('last_info_update',date('m/d/Y'),['class'=>'form-control date','disabled'=>'disabled']) !!}
            @else
            {!! Form::text('last_info_update',date('m/d/Y'),['class'=>'form-control date']) !!}
            @endauth
        </div>
    </div>
    <div class="form-row"> 
        <div class="form-group col-12 col-sm-4 col-lg-6">
            <label for="address">País - Ciudad</label>
            <input type="text" name="address" class="form-control" id="address" value="{{ $expert->address }}">
            
        </div>
        <div class="form-group col-6 col-sm-4 col-lg-3">
            <label for="salary">Expectativa salarial</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">S/</button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item change-money" data-money="sol" href="#">S/</a>
                        <a class="dropdown-item change-money" data-money="dolar" href="#">$</a>
                    </div>
                </div>
                <input type="hidden" name="type_money" value="sol" id="type_money">
                <input type="number" min="0" name="salary" class="form-control" id="salary" value="{{ $expert->salary }}">
            </div>
        </div>
        <div class="form-group col-6 col-sm-4 col-lg-3">
            <label for="availability">Disponibilidad</label>
            {!! Form::text('availability', date('m/d/Y'), ['class'=>'form-control date','id'=>'availability', 'data-toggle'=> 'datetimepicker', 'data-target'=> '#availability']) !!}
            
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group col-6 col-sm">
            <label for="linkedin">Linkedin</label>
            <input type="text" name="linkedin" class="form-control" id="linkedin" value="{{ $expert->linkedin }}">
        </div>
        <div class="form-group col-6 col-sm">
            <label for="github">Github</label>
            <input type="text" name="github" class="form-control" id="github" value="{{ $expert->github }}">
        </div>
        <div class="form-group col-6 col-sm">
            <label for="instagram">Instagram</label>
            <input type="text" name="instagram" class="form-control" id="instagram" value="{{ $expert->instagram }}">
        </div>
        <div class="form-group col-6 col-sm">
            <label for="facebook">Facebook</label>
            <input type="text" name="facebook" class="form-control" id="facebook" value="{{ $expert->facebook }}">
        </div>
        <div class="form-group col-6 col-sm">
            <label for="twitter">Twitter</label>
            <input type="text" name="twitter" class="form-control" id="twitter" value="{{ $expert->twitter }}">
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group col-12 col-sm-4">
            <label for="other_knowledge">¿Qué otros conocimientos tienes?</label>
            <input type="text" name="other_knowledge" class="form-control" id="other_knowledge" value="{{ $expert->other_knowledge }}">
        </div>
        <div class="form-group col-12 col-sm-4">
            <label for="wish_knowledge">¿Qué te gustaría aprender?</label>
            <input type="text" name="wish_knowledge" class="form-control" id="wish_knowledge" value="{{ $expert->wish_knowledge }}">
        </div>
        <div class="form-group col">
            <label for="focus">Has tenido mayor experience en:</label>
            <select name="focus" class="form-control" id="focus" value="{{ $expert->focus }}">
                <option value=""></option> 
                <option value="fullstack">Fullstack</option>
                <option value="backend">Backend</option>
                <option value="frontend">Frontend</option>
                <option value="mobile">Mobile</option>
                <option value="devops">DevOps</option>
                <option value="game">Games</option>
            </select>
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
    
    <div class="row mb-3">
        <div class="col-12">
            <h3>Portfolio</h3>
        </div>
        <div class="col-12">
            <div class="form-row mb-4">
                <div class="col-12 col-md-8">
                    <label for="portfolio-link">Link</label>
                    <input type="text" class="form-control" id="portfolio-link">
                </div>
                <div class="col-12 col-md-8 mb-2">
                    <label for="portfolio-description">Description</label>
                    <textarea id="portfolio-description" class="form-control" rows="7"></textarea>
                </div>
                <div class="col-12">
                    <button class="btn btn-success" type="button" id="add-portfolio">Add</button>
                </div>
            </div>
        </div>
        <div class="col-12" id="list-portfolio" >
            <div class="card mb-2" >
                <div class="card-header">
                    <h5 class="card-title d-inline"><a href=":link">:link</a></h5>
                    <input type="hidden" name="link[]" value=":link">
                    <button type="button" class="btn btn-danger float-right delete-portfolio"><i class="fas fa-trash" aria-hidden="true"></i></button>
                </div>
                <div class="card-body">
                    <p class="txt-description">:description</p>
                    <input type="hidden" name="description[]" value=":description">
                </div>
            </div>
        </div>
    </div>
    
    <input type="hidden" name="position" value="{{$positionId}}">
    
    <button type="submit" class="btn btn-primary">Submit</button>
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
            var card = $("#list-portfolio").html();
            $("#list-portfolio").html('');
            $('#add-portfolio').on('click' ,function(ev){
                
                var link = $("#portfolio-link").val();
                var description = $("#portfolio-description").val();

                if( link != '' && description != '' ){
                    var html = card;
                    html = html.replace(/:link/gi , link);
                    html = html.replace(/:description/gi , description);
                    
                    $("#list-portfolio").append(html);
                    $("#portfolio-link").val('');
                    $("#portfolio-description").val('');
                }
            });
            $("#list-portfolio").on('click' , '.delete-portfolio' ,function(){
                $(this).parent().parent().slideUp('slow' , function(){
                    $(this).remove();
                })
            })
            $('.change-money').on('click' , function(ev){
                ev.preventDefault();
                var type = $(this).data("money");
                console.log(type);
                var label = $(this).html();
                $(this).parent().parent().find('button').html(label);
                $("#type_money").val(type);
            })
        });
    </script>

    <script>
        $('#file_cv').on('change',function(ev){
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(ev.target.files[0].name);
        });


    </script>
@endsection