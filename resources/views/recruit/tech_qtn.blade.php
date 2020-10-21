@extends('layouts.app', ['controller' => 'expert-create'])
@section('styles')
<style>
#showURL{
    word-break: break-all;
}
.txt-description {
    white-space: pre-line;
}
.section-form{
    margin-top: -7rem !important;
    padding: 0 15px;
    background-color: #FFFFFF;
    max-width: 1200px;
    width: 100%;
    margin: auto;
    box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.15);
    border-radius: 15px; 
}
input.form-control,
input.custom-file-input + label,
select.form-control,
textarea.form-control{
    border: 1px solid #39A8DF;
}
.btn.btn-outline-secondary.dropdown-toggle{
    border: 1px solid #39A8DF;
    background-color: #39A8DF;
    color: #FFFFFF;
}

.nav-tabs-technologies li{
    width: 14.28%;
}
.nav-tabs-technologies li a.nav-link{
    white-space: nowrap;
    border: 0;
    border-radius: 0;
    color: #FFF;
    font-size: 14px;
    padding: .7rem 0 0.7rem 0;
    text-align: center;
    
}
.nav-tabs-technologies li a.nav-link span{
    border-right: 1px solid rgba(196, 196, 196, 0.17);
    display: block;
}
.nav-tabs-technologies li a.nav-link.active{
    color: #4E6B8B;
    background-color: #FFFFFF;
}
.nav-tabs-technologies li.nav-item{
    background: #4E6B8B;
    
}
.tab-pane{
    padding: 2rem 2rem 5rem;
}
.icon-position{
    vertical-align: top;
}
.body-position{
    width: calc( 100% - 64px );
}
.selection-tech-options{
    color: #000000;
    font-size: 13px;
}
.selection-tech-options a{
    padding: 0 .65rem;
    color: #000000;
    text-decoration: none;
}
.selection-tech-options a:hover{
    font-weight: 600;
}
@media (max-width: 992px) {
    .banner-home{
        padding: 4rem 5rem 8rem;
    }
    .nav-tabs-technologies li{
        width: 33.33%;
    }
}
@media (max-width: 576px) {
    .banner-home {
        padding: 2rem 1rem 6rem;
    }
    .section-form{
        margin-top: -3rem !important;
        margin: 0 10px;
        width: calc( 100% - 20px );
    }
    .tab-pane{
        padding: 1rem 1rem 3rem;
    }
    .nav-tabs-technologies{
        overflow-x: scroll;
        flex-wrap: initial;
        overflow-y: hidden;
    }
    .nav-tabs-technologies li{
        width: auto;
        
    }
    .nav-tabs-technologies li a.nav-link{
        padding-right: 1rem;
        padding-left: 1rem;
    }
}
</style>
@endsection
@section('content')


<div class="row">

    <div class="mb-4 w-100">
        <section class="text-center banner-home w-100">
            <div class="float-right">
                <a class="" href="{{ url('/') }}"> <i class="svg-icon svg-icon-arrow-prev"></i></a>
            </div>
        </section>
    </div>

    <section class="section-form">

        <div class="row mt-5 mb-5">
            <div class="col-12">
                <div class="body-position d-inline-block">
                    <h2>Información General</h2>
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

        @if ($message = Session::get('error'))
            <div class="alert alert-danger">
                <p>{!! $message !!}</p>
            </div>
        @endif

        <form action="{{ route('recruit.save.tech') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="recruit_id" value="{{ $recruit['id'] }}" id="recruit_id">

            <div class="mb-4">
                <p><b>Datos Generales</b></p>
            </div>

            <div class="form-row">
                <div class="form-group col-12 col-sm-4">
                    <label for="fullname">Nombre completo *</label>
                    <input type="text" name="fullname" id="fullname" class="form-control" value="{{ $recruit['fullname'] }}" required> 
                </div>
                <div class="form-group col-12 col-sm-4">
                    <label for="email_address">Correo electrónico *</label>
                    <input type="text" name="email_address" class="form-control" id="email_address" value="{{ $recruit['email_address'] }}" required >
                </div>
                <div class="form-group col-sm-6 col-md-2">
                    <label for="identification_number">DNI/CE/Pasaporte *</label>
                    <input type="text" name="identification_number" class="form-control" id="identification_number" value="{{ $recruit['identification_number'] }}">
                </div>
                <div class="form-group col-sm-6 col-md-2">
                    <label for="birthday">Fecha de nacimiento *</label>
                    <input type="text" name="birthday" class="form-control date" id="birthday" data-toggle="datetimepicker" data-target="#birthday" value="{{ ($recruit['birthday']!=null)? date('m-d-Y', strtotime($recruit['birthday'])):'' }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-12 col-sm-4">
                    <label for="education">Universidad/Instituto - Carrera</label>
                    <input type="text" name="education" class="form-control" id="education" value="{{ $recruit['education'] }}">
                </div>
                <div class="form-group col-12 col-sm-4">
                    <label for="english_education">¿Donde aprendiste inglés?</label>
                    <select name="english_education" class="form-control" id="english_education" value="{{ $recruit['english_education'] }}">
                        <option value=""></option> 
                        <option value="self" {{$recruit['english_education']=='self'?'selected':''}}>Independiente</option> 
                        <option value="academy" {{$recruit['english_education']=='academy'?'selected':''}}>ICPNA, Británico, Idiomas Católica</option>
                        <option value="university" {{$recruit['english_education']=='university'?'selected':''}}>Universidad o instituto</option>
                    </select>
                </div>
                <div class="form-group col">
                    <label for="phone_number">Teléfono/Celular *</label>
                    <input type="text" name="phone_number" class="form-control" id="phone_number" value="{{ $recruit['phone_number'] }}" required>
                </div>
            </div>

            <div class="form-row"> 
                <div class="form-group col-12 col-sm-4">
                    <label for="address">País - Ciudad</label>
                    <input type="text" name="address" class="form-control" id="address" value="{{ $recruit['address'] }}">
                </div>
                <div class="form-group col-12 col-sm-6 col-lg-4">
                    <label for="salary"><b>Expectativa salarial</b></label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ $recruit['type_money'] == 'sol' ? 'S/' : '$' }}</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item change-money" data-money="sol" href="#">S/</a>
                                <a class="dropdown-item change-money" data-money="dolar" href="#">$</a>
                            </div>
                        </div>
                        <input type="hidden" name="type_money" value="{{ $recruit['type_money'] }}" id="type_money">
                        <input type="number" min="0" name="salary" class="form-control" id="salary" value="{{ $recruit['salary'] }}">
                    </div>
                </div>
                <div class="form-group col-12 col-sm-6 col-sm-4 col-lg-4">
                    <label for="availability"><b>Disponibilidad</b></label>
                    <select name="availability" id="availability" class="form-control">
                        <option value="Inmediata" {{$recruit['availability']=='Inmediata'?'selected':''}}>Inmediata</option>
                        <option value="1 semana" {{$recruit['availability']=='1 semana'?'selected':''}}>1 semana</option>
                        <option value="2 semanas" {{$recruit['availability']=='2 semanas'?'selected':''}}>2 semanas</option>
                        <option value="3 semanas" {{$recruit['availability']=='3 semanas'?'selected':''}}>3 semanas</option>
                        <option value="1 mes o más" {{$recruit['availability']=='1 mes o más'?'selected':''}}>1 mes o más</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <p><b>Adjunta tu CV aquí</b></p>
            </div>

            <div class="form-row">
                <div class="form-group col-10 form-inline">
                    <div class="custom-file mt-2 col-10">
                        <input type="file" class="custom-file-input" name="file_path" id="file_path" accept="application/msword, application/pdf, .doc, .docx">
                        <label class="custom-file-label" for="file_path">{{$recruit['file_path']!=''?'Ya existe un archivo, puede modificarlo (max 2M)':'Aún no has cargado ningún archivo (max 2M)'}}</label>
                    </div>
                    @if( $recruit['file_path'] != '' )
                    <div class="input-group-append mt-2 col-2">
                        <a href="{{ $recruit['file_path'] }}" download class="btn btn-outline-secondary">DOWNLOAD</a>
                    </div>
                    @endif
                </div>
                <div class="form-group col-2 text-center pt-2">
                    <i class="svg-icon svg-icon-small svg-icon-upload"></i>
                </div>
            </div>

            <div class="mb-4">
                <p><b>Redes Sociales</b></p>
            </div>

            <div class="form-row">
                <div class="col">
                    <div class="form-group row">
                        <label class="col-12 col-md-3" for="linkedin"><i class="svg-icon svg-icon-low svg-icon-linkedin"></i> Linkedin</label>
                        <div class="col-12 col-md-9">
                            <input type="text" name="linkedin" class="form-control" id="linkedin" value="{{ $recruit['linkedin'] }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12 col-md-3" for="github"><i class="svg-icon svg-icon-low svg-icon-github"></i> Github</label>
                        <div class="col-12 col-md-9">
                            <input type="text" name="github" class="form-control" id="github" value="{{ $recruit['github'] }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12 col-md-3" for="twitter"> <i class="svg-icon svg-icon-low svg-icon-twitter"></i> Twitter</label>
                        <div class="col-12 col-md-9">
                            <input type="text" name="twitter" class="form-control" id="twitter" value="{{ $recruit['twitter'] }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <p><b>Conocimientos y Habilidades</b></p>
            </div>

            <div class="form-row mb-4">
                <div class="form-group col-12 col-sm-4">
                    <label for="other_knowledge">¿Qué otros conocimientos tienes?</label>
                    <input type="text" name="other_knowledge" class="form-control" id="other_knowledge" value="{{ $recruit['other_knowledge'] }}">
                </div>
                <div class="form-group col-12 col-sm-4">
                    <label for="wish_knowledge">¿Qué te gustaría aprender?</label>
                    <input type="text" name="wish_knowledge" class="form-control" id="wish_knowledge" value="{{ $recruit['wish_knowledge'] }}">
                </div>
                <div class="form-group col">
                    <label for="focus">Has tenido mayor experience en:</label>
                    <select name="focus" class="form-control" id="focus">
                        <option value=""></option> 
                        <option value="fullstack" {{$recruit['focus']=='fullstack'?'selected':''}}>Fullstack</option>
                        <option value="backend" {{$recruit['focus']=='backend'?'selected':''}}>Backend</option>
                        <option value="frontend" {{$recruit['focus']=='frontend'?'selected':''}}>Frontend</option>
                        <option value="mobile" {{$recruit['focus']=='mobile'?'selected':''}}>Mobile</option>
                        <option value="devops" {{$recruit['focus']=='devops'?'selected':''}}>DevOps</option>
                        <option value="game" {{$recruit['focus']=='game'?'selected':''}}>Games</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <p><b>Nivel de Conocimientos</b></p>
            </div>
            
            <ul class="nav nav-tabs nav-tabs-technologies" id="myTab" role="tablist">
            @foreach($technologies as $categoryid => $category)
                <li class="nav-item">
                    <a class="nav-link {{ $categoryid == 'english'? 'active' : '' }}" id="profile-tab_{{$categoryid}}" data-toggle="tab" href="#content__{{$categoryid}}" role="tab" aria-controls="content__{{$categoryid}}" aria-selected="false"><span>{{$category[0]}}</span></a>
                </li>
                @foreach($category[1] as $key => $value)
                @endforeach
            @endforeach
            </ul>

            <div class="tab-content" id="myTabContent">
            @foreach($technologies as $categoryid => $category)
                <div class="tab-pane fade {{ $categoryid == 'english'? 'show active' : '' }}" id="content__{{$categoryid}}" role="tabpanel" aria-labelledby="content__{{$categoryid}}-tab"> 
                    <div class="row mb-4">
                        <div class="col text-right selection-tech-options">
                            <span><b>Todas:</b></span>
                            <a href="#" class="select-all" data-val="unknown" data-cat="{{$categoryid}}">No lo manejo</a>
                            <a href="#" class="select-all" data-val="basic" data-cat="{{$categoryid}}">Básico</a>
                            <a href="#" class="select-all" data-val="intermediate" data-cat="{{$categoryid}}">Intermedio</a>
                            <a href="#" class="select-all" data-val="advanced" data-cat="{{$categoryid}}">Avanzado</a>
                        </div>
                    </div>
                    <div class="form-row">
                    @foreach($category[1] as $key => $value)
                        <div class="col-12 col-md-4 mb-3">
                            <label for="">{{$value}}</label>
                            <select name="{{$key}}" id="{{$key}}" class="form-control selection-tech" data-cat="{{$categoryid}}">
                                <option value="unknown" {{$recruit[$key]=='unknown'?'selected':''}}>No lo manejo</option>
                                <option value="basic" {{$recruit[$key]=='basic'?'selected':''}}>Básico</option>
                                <option value="intermediate" {{$recruit[$key]=='intermediate'?'selected':''}}>Intermedio</option>
                                <option value="advanced" {{$recruit[$key]=='advanced'?'selected':''}}>Avanzado</option>
                            </select>
                        </div>
                    @endforeach
                    </div>
                </div>
            @endforeach
            </div>

            <div class="mb-4">
                <p><b>Portafolio</b></p>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <div class="form-row mb-4">
                        <div class="col-12 col-md-8 mb-3">
                            <label for="portfolio-link">Introducir dirección URL</label>
                            <input type="text" class="form-control" id="portfolio-link">
                        </div>
                        <div class="col-12 col-md-8 mb-2">
                            <label for="portfolio-description">Introducir descripción del proyecto</label>
                            <textarea id="portfolio-description" class="form-control" rows="5"></textarea>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-transparent" type="button" id="add-portfolio">Agregar</button>
                        </div>
                    </div>
                </div>
                <div class="col-12" id="list-portfolio" >
                @foreach($portfolios as $pkey => $portfolio)
                    <div class="card mb-2" >
                        <div class="card-header">
                            <h5 class="card-title d-inline"><a href="{{ $portfolio['link'] }}">{{ $portfolio['link'] }}</a></h5>
                            <input type="hidden" name="link[]" value="{{ $portfolio['link'] }}">
                            <button type="button" class="btn btn-danger float-right delete-portfolio"><i class="fas fa-trash" aria-hidden="true"></i></button>
                        </div>
                        <div class="card-body">
                            <p class="txt-description">{{ $portfolio['description'] }}</p>
                            <input type="hidden" name="description[]" value="{{ $portfolio['description'] }}">
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
            
            <button type="submit" class="btn btn-primary">Guardar</button>
        
        </form>
    @endsection
    </section>
</div>


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

            $('.select-all').on('click', function(ev){
                ev.preventDefault();
                var val = $(this).data('val');
                var cat = $(this).data('cat');
                $(".selection-tech[ data-cat='"+cat+"']").val(val);
            })
        });
    </script>

    <script>
        $('#file_path').on('change',function(ev){
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(ev.target.files[0].name);
        });


    </script>
@endsection