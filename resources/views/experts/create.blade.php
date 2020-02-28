@extends('layouts.app')
  
@section('content')
<div class="row">
    <div class="col-lg-12 mt-5 mb-5">
        <div class="float-left">
            @auth
            <h2>New Expert</h2>
            @endauth
            
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

<form action="{{ route('experts.store') }}" method="POST">
    @csrf
    <h3 class="mb-6">Información General</h3>
    <div class="form-row">
        <div class="form-group col">
            <label for="fullname">Nombre completo</label>
            <input type="text" name="fullname" id="fullname" class="form-control" value="{{ $expert->fullname }}"> 
        </div>
        <div class="form-group col">
            <label for="email_address">Email</label>
            <input type="text" name="email_address" class="form-control" id="email_address" value="{{ $expert->email_address }}" required >
        </div>
        <div class="form-group col-2">
            <label for="identification_number">DNI/CE/Pasaporte</label>
            <input type="text" name="identification_number" class="form-control" id="identification_number" value="{{ $expert->identification_number }}">
        </div>
        <div class="form-group col-2">
            <label for="birthday">Fecha de nacimiento</label>
            <input type="text" name="birthday" class="form-control date" id="birthday" value="{{ $expert->birthday }}">
        </div>
        
    </div>
    <div class="form-row">
        <div class="form-group col-4">
            <label for="education">Universidad/Instituto - Carrera</label>
            <input type="text" name="education" class="form-control" id="education" value="{{ $expert->education }}">
        </div>
        <div class="form-group col-4">
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
            <input type="text" name="phone" class="form-control" id="phone" value="{{ $expert->phone }}">
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
        <div class="form-group col">
            <label for="address">País - Ciudad</label>
            <input type="text" name="address" class="form-control" id="address" value="{{ $expert->address }}">
            
        </div>
        <div class="form-group col-3">
            <label for="salary">Expectativa salarial</label>
            <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="validationTooltipUsernamePrepend">S/.</span>
            </div>
            <input type="number" min="0" name="salary" class="form-control" id="salary" value="{{ $expert->salary }}">
            </div>
        </div>
        <div class="form-group col-3">
            <label for="availability">Disponibilidad</label>
            <input type="text" name="availability" class="form-control date" id="availability" value="{{ $expert->availability }}">
        </div>
    </div>
    @auth
    <div class="form-row">
        <div class="form-group col">
            <label for="linkedin">Linkedin</label>
            <input type="text" name="linkedin" class="form-control" id="linkedin" value="{{ $expert->linkedin }}">
        </div>
        <div class="form-group col">
            <label for="github">Github</label>
            <input type="text" name="github" class="form-control" id="github" value="{{ $expert->github }}">
        </div>
        <div class="form-group col">
            <label for="instagram">Instagram</label>
            <input type="text" name="instagram" class="form-control" id="instagram" value="{{ $expert->instagram }}">
        </div>
        <div class="form-group col">
            <label for="facebook">Facebook</label>
            <input type="text" name="facebook" class="form-control" id="facebook" value="{{ $expert->facebook }}">
        </div>
        <div class="form-group col">
            <label for="twitter">Twitter</label>
            <input type="text" name="twitter" class="form-control" id="twitter" value="{{ $expert->twitter }}">
        </div>
    </div>
    @endauth
    <div class="form-row">
        <div class="form-group col">
            <label for="other_knowledge">¿Qué otros conocimientos tienes?</label>
            <input type="text" name="other_knowledge" class="form-control" id="other_knowledge" value="{{ $expert->other_knowledge }}">
        </div>
        <div class="form-group col">
            <label for="wish_knowledge">¿Qué te gustaría aprender?</label>
            <input type="text" name="wish_knowledge" class="form-control" id="wish_knowledge" value="{{ $expert->wish_knowledge }}">
        </div>
        <div class="form-group col">
            <label for="focus">¿En cuál de las siguientes áreas tienes mayor experiencia?</label>
            <select name="focus" class="form-control" id="focus" value="{{ $expert->focus }}">
                <option value=""></option> 
                <option value="fullstack">Fullstack</option>
                <option value="backend">Backend</option>
                <option value="frontend">Frontend</option>
                <option value="mobile">Mobile</option>
                <option value="devops">DevOps</option>
            </select>
        </div>
    </div>
    @auth
    <h3>Evaluaciones</h3>
    <div class="form-row">
        <div class="form-group col-3">
            <label for="assessment1">Evaluado en</label>
            <input type="text" name="assessment1" class="form-control" id="assessment1" value="{{ $expert->assessment1 }}">
        </div>
        <div class="form-group col">
            <label for="result1">Resultado</label>
            <input type="text" name="result1" class="form-control" id="result1" value="{{ $expert->result1 }}">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-3">
            <label for="assessment2">Evaluado en</label>
            <input type="text" name="assessment2" class="form-control" id="assessment2" value="{{ $expert->assessment2 }}">
        </div>
        <div class="form-group col">
            <label for="result2">Resultado</label>
            <input type="text" name="result2" class="form-control" id="result2" value="{{ $expert->result2 }}">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-3">
            <label for="assessment3">Evaluado en</label>
            <input type="text" name="assessment3" class="form-control" id="assessment3" value="{{ $expert->assessment3 }}">
        </div>
        <div class="form-group col">
            <label for="result3">Resultado</label>
            <input type="text" name="result3" class="form-control" id="result3" value="{{ $expert->result3 }}">
        </div>
    </div>
    @endauth
    @foreach($technologies as $categoryid => $category)
        <h3>{{$category[0]}}</h3>
        <br>
        @foreach($category[1] as $key => $value)
        <fieldset class="form-group">
            <div class="form-row">
                <legend class="col-form-label col-3 pt-0">{{$value}}</legend>
                <div class="col-9">
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
        });
    </script>
@endsection