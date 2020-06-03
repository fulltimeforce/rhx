@extends('layouts.app' , ['controller' => 'experts-edit'])

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
        <button type="submit" class="btn btn-success">Save</button>
        @isset( $position )
        <input type="hidden" name="position" value="{{ $position }}" > 
        @endisset
        
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
                        <input type="file" class="custom-file-input" name="file_cv_update" id="file_cv" accept="application/msword, application/pdf, .doc, .docx">
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
                
                <input type="text" name="birthday" class="form-control date" id="birthday" data-toggle="datetimepicker" data-target="#birthday" value="{{$expert->birthday}}">
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
                <input type="text" name="last_info_update" class="form-control date" id="last_info_update" data-toggle="datetimepicker" data-target="#last_info_update" value="{{$expert->last_info_update}}">
            </div>
        </div>
        <div class="form-row"> 
            <div class="form-group col-12 col-sm-4 col-lg-6">
                <label for="address">País - Ciudad</label>
                <input type="text" name="address" class="form-control" id="address" value="{{$expert->address}}">
            </div>
            <div class="form-group col-6 col-sm-4 col-lg-3">
                <label for="salary">Expectativa salarial</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ $expert->type_money == 'sol' ? 'S/' : '$' }}</button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item change-money" data-money="sol" href="#">S/</a>
                            <a class="dropdown-item change-money" data-money="dolar" href="#">$</a>
                        </div>
                    </div>
                    <input type="hidden" name="type_money" value="{{ $expert->type_money }}" id="type_money">
                    <input type="number" min="0" name="salary" class="form-control" id="salary" value="{{ $expert->salary }}">
                </div>
            
            </div>
            <div class="form-group col-6 col-sm-4 col-lg-3">
                <label for="availability">Disponibilidad</label>
                <select name="availability" id="availability" class="form-control">
                    <option value="Inmediata"   {{ $expert->availability == 'Inmediata' ? 'checked' : '' }}>Inmediata</option>
                    <option value="1 semana"    {{ $expert->availability == '1 semana' ? 'checked' : '' }}>1 semana</option>
                    <option value="2 semanas"   {{ $expert->availability == '2 semanas' ? 'checked' : '' }}>2 semanas</option>
                    <option value="3 semanas"   {{ $expert->availability == '3 semanas' ? 'checked' : '' }}>3 semanas</option>
                    <option value="1 mes o más" {{ $expert->availability == '1 mes o más' ? 'checked' : '' }}>1 mes o más</option>
                </select>
                
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
                    <option value="game" {{ ($expert->focus=="game")? "selected" : "" }}>Games</option>
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
        @foreach($portfolios as $pkey => $portfolio)
            <div class="card mb-1" >
                <div class="card-header">
                    <h5 class="card-title d-inline"><a href="{{ $portfolio->link }}">{{ $portfolio->link }}</a></h5>
                    <input type="hidden" name="link[]" value="{{ $portfolio->link }}">
                    <button type="button" class="btn btn-danger float-right delete-portfolio"><i class="fas fa-trash" aria-hidden="true"></i></button>
                </div>
                <div class="card-body">
                    <p class="txt-description">{{ $portfolio->description }}</p>
                    <input type="hidden" name="description[]" value="{{ $portfolio->description }}">
                </div>
            </div>
        @endforeach
        </div>
        <div class="col-12" id="edit-portfolio" >
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

            var card = $("#edit-portfolio").html();
            $("#edit-portfolio").html('');
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
        // $('[data-toggle="tooltip"]').tooltip({ trigger : 'click' });

        $('#url-generate').on('click', function (ev) {
            ev.preventDefault();
            var url = '{{ route("developer.edit.signed" , ":id") }}';
            url = url.replace( ":id" , $(this).data("expert") );

            $.ajax({
                type:'GET',
                url: url,
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