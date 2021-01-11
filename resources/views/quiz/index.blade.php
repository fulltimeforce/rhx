@extends('layouts.quiz' , ['controller' => 'position'])

@section('styles')

<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap-table/bootstrap-table.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.css') }}"/>
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

<style>
.demo-success{
    color: blue;
}
.demo-failure{
    color: red;
}
#overlay {
    background-color: rgba(0, 0, 0, 0.45);
    z-index: 999;
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    display: none;
}
</style>
@endsection
 
@section('content')
<div id="welcome-view">
    @if($recruit->raven_status == null)
    <div class="row justify-content-sm-center">
        <div class="col-12 mt-3">
            <h2 class="text-center">TEST PSICOLÓGICO <br> FULLTIMEFORCE</h2>
            <div class="text-center">
                <img src="https://fulltimeforce.com/wp-content/themes/ftf-2020/images/fulltimeforce-logo.svg" alt="Fulltimeforce Logo" id="logo">
            </div>
        </div>
        <div class="col-10 mt-3">
            <h5><strong>INDICACIONES</strong></h5>
            <div class="card">
                <div class="card-body">
                    <p>Ahora pasaremos a realizar una breve prueba, la cual tiene como objetivo evaluar tu capacidad de razonamiento lógico. Te mostraré una serie de láminas en power point, en donde habrá una serie de figuras incompletas que tienes que observar atentamente. Abajo de estas, tendrás distintas opciones de respuesta. Lo que tienes que hacer es simplemente decir que opción piensas que completaría la figura. No te preocupes, seguramente van a haber ejercicios que te parecerán más fáciles y otros más difíciles, esto es totalmente natural. Si no tienes ninguna duda, continuemos.</p>
                    <p><strong>Tendras un tiempo límite para hacer ello de 60 min.</strong></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-sm-center mt-3">
        <div class="col-10">
            <h5><strong>ITEM DE EJEMPLO</strong></h5>
        </div>
        <div class="col-10">
            <div class="row">
                <div class="col-md-7 col-sm-12 text-right">
                    <img src="{{asset('workat/quiz_assets/quiz_example.png')}}" height=350>
                </div>
                <div class="col-5">
                    <form id="demo_form" action="#">
                        <ul id="demo_options" style="list-style-type:none; padding:0;">
                            <li><input type="radio" name="demo" value="1"> 1</li>
                            <li><input type="radio" name="demo" value="2"> 2</li>
                            <li><input type="radio" name="demo" value="3"> 3</li>
                            <li><input type="radio" name="demo" value="4"> 4</li>
                            <li><input type="radio" name="demo" value="5"> 5</li>
                            <li><input type="radio" name="demo" value="6"> 6</li>
                        </ul>
                        <button class="btn btn-secondary demo_button">Validar</button>
                        <span id="demo_message"></span>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-12 mt-2 mb-5 text-center">
           <button id="begin-test" class="btn btn-primary">¡Empezemos!</button>
        </div>
    </div>
</div>
<div id="questionaire" style="display: none">
    <form id="question_form">
        <div class="row">
            <div class="col-12 text-center">
                <p>Te quedan <span id="time">60:00</span> minutos!</p>
            </div>
            <div id="q_img" class="col-md-7 col-sm-12 text-right">
                <img id="question_img" src="{{asset('workat/quiz_assets/1441512.jpg')}}" height=600>
            </div>
            <div class="col-md-5 col-sm-12">
                <div class="m-md-5">
                    <ul id="quiz_options" style="list-style-type:none; padding:0;">
                        <li><input type="radio" name="q" value="1"> 1</li>
                    </ul>
                    <button class="btn btn-primary next_button">Continuar</button>
                </div>
            </div>
            <input type="hidden" name="rcn" value="{{$recruit->id}}">
        </div>
    </form>
</div>
<input type="hidden" name="csrf-token" value="{{ csrf_token() }}">
@else
<div class="row">
    <div class="col-12 mt-3">
        <h4 class="text-center">Ya se han registrado sus respuestas. Por favor espere que estan siendo evaluadas</h4>
    </div>
</div>
@endif
@endsection
@section('javascript')

<script type="text/javascript" src="{{ asset('/bootstrap-table/bootstrap-table.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

<script type="text/javascript">
$(".demo_button").on('click',function(e){
    e.preventDefault();
    var form = getFormData($("#demo_form"));
    var message = $("#demo_message");
    if(form['demo'] == '2'){
        message.removeClass('demo-failure');
        message.addClass('demo-success');
        message.html("¡Correcto!");
    }else{
        message.addClass('demo-failure');
        message.removeClass('demo-success');
        message.html("¡Incorrecto!");
    }
});

$("#begin-test").on('click',function(e){
    e.preventDefault();
    $.ajax({
        type: 'POST',
        url: '{{ route("recruit.quiz.start") }}',
        data: {rcn: $("[name='rcn']").val()},
        headers: {
            'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend:function(){
            $("#overlay").show();
        },
        success:function(data){
            if(data.status == 'continue'){
                var timeInSeconds = 60 * 60;
                display = $('#time');
                startTimer(timeInSeconds, display);
                $("#q_img").html("<img id='question_img' src='{{asset('workat/quiz_assets/')}}"+"/"+data.img+"' height=600>");

                // $("#question_img").attr('src',"{{asset('workat/quiz_assets/')}}"+"/"+data.img)
                loadOptions(data.curr_question);
                $("#welcome-view").html("");
                $("#questionaire").show();
            }            
        },
        complete: function(){
            $("#overlay").hide();
        }
    });
});

$(document).on('click','.next_button',function(e){
    e.preventDefault();
    var form = getFormData($("#question_form"));
    var button = $(this);
    // console.log(form);
    $.ajax({
        type: 'POST',
        url: '{{ route("recruit.quiz.continue") }}',
        data: {rcn: form['rcn'], answer: form['q'] },
        headers: {
            'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend:function(){
            $("#overlay").show();
        },
        success:function(data){
            if(data.status == 'continue'){
                $("#q_img").html("<img id='question_img' src='{{asset('workat/quiz_assets/')}}"+"/"+data.img+"' height=600>");
                // $("#question_img").attr('src',"{{asset('workat/quiz_assets/')}}"+"/"+data.img);
                $("[name='q']").prop('checked', false);
                loadOptions(data.curr_question);
                if(data.curr_question >= 60){
                    button.html('Terminar');
                }
            }else{
                console.log("quiz ended");
                console.log(data);
                $("#questionaire").html('<div class="row"><div class="col-12"><h4 class="text-center">¡Muchas gracias por participar! <br> Nos estaremos poniendo en contacto contigo cuanto tengamos los resultados.</h4></div></div>');
            }
        },
        complete: function(){
            $("#overlay").hide();
        }
    });
});

function startTimer(duration, display) {
    var timer = duration, minutes, seconds;
    var interval = setInterval(function () {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.html(minutes + ":" + seconds);

        if (--timer < 0) {
            timer = 0;
            console.log("ended");
            clearInterval(interval);
            $.ajax({
                type: 'POST',
                url: '{{ route("recruit.quiz.end") }}',
                data: {rcn: $("[name='rcn']").val()},
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){
                    $("#questionaire").html('<div class="row"><div class="col-12"><h4 class="text-center">¡Muchas gracias por participar! <br> Nos estaremos poniendo en contacto contigo cuanto tengamos los resultados.</h4></div></div>');
                }
            });
        }
    }, 1000);
}

function getFormData(form){
    var unindexed_array = form.serializeArray();
    var indexed_array = {};

    $.map(unindexed_array, function(n, i){
        indexed_array[n['name']] = n['value'];
    });

    return indexed_array;
}

function loadOptions(number){
    var ul = $("#quiz_options");
    var options = "";
    var amount = number < 25 ? 6:8;
    for (let i = 1; i <= amount; i++) {
        options += '<li><input type="radio" name="q" value="'+i+'"> '+i+'</li>';
    }
    ul.html(options);
}

window.onbeforeunload = function() {
    return "Preventing leave quiz";
}

</script>

@endsection