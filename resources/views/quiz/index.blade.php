@extends('layouts.app' , ['controller' => 'position'])

@section('styles')

<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap-table/bootstrap-table.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.css') }}"/>
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

<style>

</style>
@endsection
 
@section('content')
@if($recruit->raven_overall == null)
<div id="welcome-view">
    <div class="row justify-content-sm-center">
        <div class="col-12">
            <h2 class="text-center">TEST PSICOLÓGICO <br> FULLTIMEFORCE</h2>
        </div>
        <div class="col-10">
            <h5>INDICACIONES</h5>
            <p>Ahora pasaremos a realizar una breve prueba, la cual tiene como objetivo evaluar tu capacidad de razonamiento lógico. Te mostraré una serie de láminas en power point, en donde habrá una serie de figuras incompletas que tienes que observar atentamente. Abajo de estas, tendrás distintas opciones de respuesta. Lo que tienes que hacer es simplemente decir que opción piensas que completaría la figura. No te preocupes, seguramente van a haber ejercicios que te parecerán más fáciles y otros más difíciles, esto es totalmente natural. Si no tienes ninguna duda, continuemos.</p>
            <p>Tendras un tiempo límite para hacer ello de 60 min.</p>
        </div>
        <div class="col-10">
            <h5>ITEM DE EJEMPLO</h5>
            <img src="{{asset('workat/quiz_assets/quiz_example.png')}}" height=350>
        </div>
    </div>
    <div class="row justify-content-sm-center">
        <div class="col-1">
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
            <div class="col-md-7 col-sm-12 text-right">
                <img id="question_img" src="{{asset('workat/quiz_assets/1441512.jpg')}}" height=600>
            </div>
            <div class="col-md-5 col-sm-12">
                <div class="m-md-5">
                    <ul style="list-style-type:none; padding:0;">
                        <li><input type="radio" name="q" value="1"> 1</li>
                        <li><input type="radio" name="q" value="2"> 2</li>
                        <li><input type="radio" name="q" value="3"> 3</li>
                        <li><input type="radio" name="q" value="4"> 4</li>
                        <li><input type="radio" name="q" value="5"> 5</li>
                        <li><input type="radio" name="q" value="6"> 6</li>
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
    <div class="col-12">
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
        success:function(data){
            console.log("Quiz Start");
            console.log(data);

            var timeInSeconds = 60 * 60; //60 minutes,
            display = $('#time');
            startTimer(timeInSeconds, display);

            $("#question_img").attr('src',"{{asset('workat/quiz_assets/')}}"+"/"+data.img)

            $("#welcome-view").html("");
            $("#questionaire").show();
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
        success:function(data){
            if(data.status == 'continue'){
                console.log("next question");
                console.log(data);
                $("#question_img").attr('src',"{{asset('workat/quiz_assets/')}}"+"/"+data.img);
                $("[name='q']").prop('checked', false);
                if(data.curr_question >= 60){
                    button.html('Terminar');
                }
            }else{
                console.log("quiz ended");
                console.log(data);
                // $("#questionaire").html('<h4>¡Muchas gracias por participar! Nos estaremos poniendo en contacto contigo cuanto tengamos los resultados.</h4>');
            }
        }
    });
});

function startTimer(duration, display) {
    var timer = duration, minutes, seconds;
    setInterval(function () {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.html(minutes + ":" + seconds);

        if (--timer < 0) {
            timer = duration;
        }
    }, 1000);
}

function getFormData($form){
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};

    $.map(unindexed_array, function(n, i){
        indexed_array[n['name']] = n['value'];
    });

    return indexed_array;
}

</script>

@endsection