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
    
    @if( isset($position) )
    <div class="row mt-5 mb-5">
        <div class="col-12">
            <div class="icon-position d-inline-block">
                <i class="svg-icon svg-icon-{{$position->icon}}"></i>
            </div>
            <div class="body-position d-inline-block">
                <h2>{{ $position->name }}</h2>
                @guest
                <!--<p>Requisitos:</p>
                <p>{!! nl2br($position->description) !!}</p>-->
                @endguest
                @auth
                <h2>New Expert</h2>
                @endauth
            </div>
        </div>
    </div>
    @endif
    
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

    <form action="{{ route('recruit.save.link') }}" id="postulant-form" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col mb-5">
                <h3 class="">Información General</h3>
            </div>
            
        </div>
        <div class="form-row">
            <div class="form-group col-12 col-sm-6">
                <label for="fullname">Nombre completo *</label>
                <input type="text" name="fullname" id="fullname" class="form-control" value="" required> 
            </div>
            <div class="form-group col-12 col-sm-6">
                <label for="email_address">Correo electrónico *</label>
                <input type="text" name="email_address" class="form-control" id="email_address" value="" required >
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-12 col-sm-4">
                <label for="phone_number">Teléfono/Celular *</label>
                <input type="text" name="phone_number" class="form-control" id="phone_number" value="" required>
            </div>
            <div class="form-group col-12 col-sm-8">
                <label for="profile_link">Perfil Profesional (Link)</label>
                <input type="text" name="profile_link" class="form-control" id="profile_link" value="">
            </div>
        </div>
        
        <div class="form-row mb-4">
            <div class="col-10">
                <b>Adjunta tu CV aquí</b>
                <div class="custom-file mt-2">
                    <input type="file" class="custom-file-input" name="file_path" id="file_path" accept="application/msword, application/pdf, .doc, .docx">
                    <label class="custom-file-label" for="file_path">Aún no has cargado ningún archivo (max 2M)</label>
                </div>
            </div>
            <div class="col-2 text-center pt-4">
                <i class="svg-icon svg-icon-small svg-icon-upload"></i>
            </div>
        </div>
        <input type="hidden" name="platform" id="platform" value="workat">
        @if( isset($position) )
        <input type="hidden" name="position_id" id="position_id" value="{{$position->id}}">
        @endif
        <button type="submit" id="btn-save" class="btn btn-primary">Guardar</button>
        </div>
    
    </form>
@endsection
</section>
</div>


@section('javascript')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#btn-save').on('click',function(ev){
                ev.preventDefault();
                var cv = $('#file_path').val();
                var link = $('#profile_link').val();
                if(!cv && !link){
                    alert('You need to complete your LINK or CV. One at least.')
                }else{
                    $('#postulant-form').submit();
                }
            });
            
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