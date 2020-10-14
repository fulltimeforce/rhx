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
            <input type="hidden" name="recruit_id" value="{{$recruit['id']}}" id="recruit_id">
            <div class="mb-4">
                <p><b>Datos Generales</b></p>
            </div>
            <div class="tab-content mb-4">
                <div class="form-group col-sm-6 col-md-2">
                    <label for="identification_number">DNI/CE/Pasaporte (*)</label>
                    <input type="text" name="identification_number" class="form-control" id="identification_number" value="">
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
                                <option value="unknown">No lo manejo</option>
                                <option value="basic">Básico</option>
                                <option value="intermediate">Intermedio</option>
                                <option value="advanced">Avanzado</option>
                            </select>
                        </div>
                    @endforeach
                    </div>
                </div>
            @endforeach
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

            $('.select-all').on('click', function(ev){
                ev.preventDefault();
                var val = $(this).data('val');
                var cat = $(this).data('cat');
                $(".selection-tech[ data-cat='"+cat+"']").val(val);
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