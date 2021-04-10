@extends('layouts.app' , ['controller' => 'position'])

@section('styles')

<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap-table/bootstrap-table.min.css') }}"/>
 
<style>
  caption{
      /* caption-side: top !important; */
      width: max-content !important;
      border: 1px solid;
      margin-bottom: 1.5rem;
  }
  #showURL{
      word-break: break-all;
  }
  #allexperts tbody tr td:nth-child(2){
      text-transform: capitalize;
  }
  .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
  .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover{
      border: 1px solid #007bff;
      color: #FFF !important;
      background: #007bff;
  }
  .dataTables_wrapper .dataTables_paginate .paginate_button:hover{
      background-color: #e9ecef;
      background: #e9ecef;
      border: 1px solid #dee2e6;
      color: #0056b3;
  }

  .slider {
    border: none;
    position: relative;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    width: 125px;
  }

  .slider-checkbox {
    display: none;
  }

  .slider-label {
    border: 0;
    border-radius: 20px;
    cursor: pointer;
    display: block;
    overflow: hidden;
  }

  .slider-inner {
    display: block;
    margin-left: -100%;
    transition: margin 0.3s ease-in 0s;
    width: 200%;
  }

  .slider-inner:before,
  .slider-inner:after {
    box-sizing: border-box;
    display: block;
    float: left;
    font-family: sans-serif;
    font-size: 14px;
    font-weight: bold;
    height: 30px;
    line-height: 30px;
    padding: 0;
    width: 50%;
  }

  .slider-inner:before {
    background-color: #007bff;
    color: #fff;
    content: "APPROVED";
    padding-left: .75em;
  }

  .slider-inner:after {
    background-color: #dc3545;
    color: #FFF;
    content: "FAILED";
    padding-right: .75em;
    text-align: right;
  }

  .slider-circle {
    background-color: #FFF;
    border: 0;
    border-radius: 20px;
    bottom: 0;
    display: block;
    margin: 5px;
    position: absolute;
    right: 91px;
    top: 0;
    transition: all 0.3s ease-in 0s; 
    width: 20px;
  }

  .slider-checkbox:checked + .slider-label .slider-inner {
    margin-left: 0;
  }

  .slider-checkbox:checked + .slider-label .slider-circle {
    background-color: #FFFFFF;
    right: 0; 
  }
  td.stickout{
      background-color: yellow;
  }
  td.frozencell{
      background-color : #fafafa;
  }
  .dataTables_filter{
      display: none;
  }
  .txt-description{
      white-space: pre-line;
  }
  .ui-jqgrid .ui-jqgrid-btable tbody tr.jqgrow td,
  .ui-jqgrid .ui-jqgrid-htable thead th div{
      white-space: normal;
  }
  .ui-jqgrid .table-bordered th.ui-th-ltr{
      color: #fff;
      background-color: #343a40;
  }
  .select2-container{
      width: 100% !important;
  }

  .lds-ring {
    display: inline-block;
    position: relative;
    width: 80px;
    height: 80px;
  }
  .lds-ring div {
    box-sizing: border-box;
    display: block;
    position: absolute;
    width: 64px;
    height: 64px;
    margin: 8px;
    border: 8px solid #17a2b8;
    border-radius: 50%;
    animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
    border-color: #17a2b8 transparent transparent transparent;
  }
  .lds-ring div:nth-child(1) {
    animation-delay: -0.45s;
  }
  .lds-ring div:nth-child(2) {
    animation-delay: -0.3s;
  }
  .lds-ring div:nth-child(3) {
    animation-delay: -0.15s;
  }
  @keyframes lds-ring {
    0% {
      transform: rotate(0deg);
    }
    100% {
      transform: rotate(360deg);
    }
  }

  .btn-group>.badge:not(:last-child):not(.dropdown-toggle){
      border-top-right-radius: 0;
      border-bottom-right-radius: 0;
  }
  .btn-group>.badge:not(:first-child){
      border-top-left-radius: 0;
      border-bottom-left-radius: 0;
  }
  .btn-group>.badge:not(:first-child) {
      border-top-left-radius: 0;
      border-bottom-left-radius: 0;
  }
  .btn-group>.badge{
      height: 21px;
  }
  .btn-group>.badge.badge-primary{
      font-size: 9px;
  }
  .btn-group>.badge.badge-primary i.fas:before{
      vertical-align: -webkit-baseline-middle;
  }
  main{
      position: relative;
  }
  .buble-audio{
      position: fixed;
      padding: .7rem;
      z-index: 2;
      background: #FFFFFF;
      right: 15px;
      bottom: 16px;
      max-width: 350px;
      width: 100%;
      border: 1px solid #000;
      font-size: 14px;
  }
  .section-audio{
      position: relative;
  }
  .buble-audio p{
      margin-bottom: 3px;
  }
  .section-audio .close-audio{
      position: absolute;
      right: -12px;
      top: -25px;
      background: #FFF;
      z-index: 4;
      font-size: 24px;
      line-height: 1;
      border-radius: 27px;
  }
  .speed-audio{
      font-size: 12px;
      margin-bottom: 5px;
  }
  .tab-test{
      display: none;
  }
  .tab-test.test-active{
      display: flex;
  }
</style>
@endsection

@section('content')

<!--  
        /*========================================== TEST ==========================================*/
    -->
<div class="modal fade" id="testRecruit" tabindex="-1" role="dialog" aria-labelledby="testRecruitLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="testRecruitLabel">TEST - <span id="test_recruit_name"></span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <input type="hidden" id="recruit_index">
                <form class="col" id="form_test">
                    <input type="hidden" id="recruit_id" name="recruit_id">
                    <div class="row tab-test test-active" data-tab="1">
                        <div class="col-12 mb-4">
                            <h3>Completeness</h3>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="test_completeness">Program does what is expected and contains lots of improvements from the specifications</label>
                                <input type="radio" class="col-3" name="completeness_score" value="5">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="test_completeness">Program does what is expected and contains improvements from the specifications</label>
                                <input type="radio" class="col-3" name="completeness_score" value="4">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="test_completeness">Program always works correctly and meets the specifications</label>
                                <input type="radio" class="col-3" name="completeness_score" value="3">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="test_completeness">Minor details of the program specification are violated, program functions correctly in most inputs</label>
                                <input type="radio" class="col-3" name="completeness_score" value="2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="test_completeness">Significant details of specification are violated, or the program often exhibits incorrect behavior.</label>
                                <input type="radio" class="col-3" name="completeness_score" value="1">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="test_completeness">Program does not compile/run, or errors occur on input similar to sample.</label>
                                <input type="radio" class="col-3" name="completeness_score" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="row tab-test" data-tab="2">
                        <div class="col-12 mb-4">
                            <h3>Clean code</h3>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="code_score">The code has been created using all five SOLID principles in mind from start to finish</label>
                                <input type="radio" class="col-3" name="code_score" value="5">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="code_score">We can verify the existence of all five SOLID principles but not throughout all the code</label>
                                <input type="radio" class="col-3" name="code_score" value="4">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="code_score">We can verify the existence of three or more SOLID principles in the code</label>
                                <input type="radio" class="col-3" name="code_score" value="3">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="code_score">We can verify the existence of only two or less SOLID principles in the code. Perhaps added without real knowledge of their existence.</label>
                                <input type="radio" class="col-3" name="code_score" value="2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="code_score">We cannot find any of the SOLID principles in the code</label>
                                <input type="radio" class="col-3" name="code_score" value="1">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="code_score">The code is a mess / unreadable without proper explanation/documentation or sometimes even harcoded </label>
                                <input type="radio" class="col-3" name="code_score" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="row tab-test" data-tab="3">
                        <div class="col-12 mb-4">
                            <h3>Design quality</h3>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="design_score">The solution meets all five criteria</label>
                                <input type="radio" class="col-3" name="design_score" value="5">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="design_score">The solutions meets all criteria but Simplicity</label>
                                <input type="radio" class="col-3" name="design_score" value="4">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="design_score">The solution meets three criteria including Robustness</label>
                                <input type="radio" class="col-3" name="design_score" value="3">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="design_score">The solution meets two criteria</label>
                                <input type="radio" class="col-3" name="design_score" value="2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="design_score">The solution meets one criteria</label>
                                <input type="radio" class="col-3" name="design_score" value="1">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="design_score">The solution meets no criteria</label>
                                <input type="radio" class="col-3" name="design_score" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="row tab-test" data-tab="4">
                        <div class="col-12 mb-4">
                            <h3>Technologies</h3>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="technologies_score">The solution uses correctly testing, architecture, microservices/containerization, cloud services, design patterns, several cutting-edge technologies/frameworks</label>
                                <input type="radio" class="col-3" name="technologies_score" value="5">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="technologies_score">The solution uses correctly microservices/containerization, cloud services, several cutting-edge technologies</label>
                                <input type="radio" class="col-3" name="technologies_score" value="3">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="technologies_score">The solution uses correctly cloud services and several cutting-edge technologies</label>
                                <input type="radio" class="col-3" name="technologies_score" value="2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="technologies_score">The solution uses correctly one or more cutting-edge technologies</label>
                                <input type="radio" class="col-3" name="technologies_score" value="1">
                            </div>
                        </div>
                    </div>
                    <div class="row tab-test" data-tab="5">
                        <div class="col-12 mb-4">
                            <h3>Readme</h3>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="readme_score">Indications to run the app are clear. If needed, the dev has pointed under what conditions the app will work.</label>
                                <input type="radio" class="col-3" name="readme_score" value="5">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="readme_score">Indications to run the app are clear. </label>
                                <input type="radio" class="col-3" name="readme_score" value="3">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="readme_score">Indications are a little incomplete or unclear</label>
                                <input type="radio" class="col-3" name="readme_score" value="2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="readme_score">No Readme added</label>
                                <input type="radio" class="col-3" name="readme_score" value="0">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <p class="mb-5 text-danger" id="error-test-form" style="display: none;">*You must select all options</p>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" id="prev-test" class="btn btn-primary" data-test="1" style="display: none;">Prev</button>
            <button type="button" id="nest-test" class="btn btn-primary" data-test="1">Next</button>
            <button type="button" id="save-test" class="btn btn-success" style="display: none;">Save</button>
        </div>
        
    </div>
</div>
</div>

    

<div class="row">
    <div class="col">
        <h1>TECHNICAL TEST</h1>
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

@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

<div class="row">
    <div class="col">
            <p>Result: <span id="count-recruits"></span></p>
        </div>
        <div class="col text-right">
            
            <div class="form-group d-inline-block" style="max-width: 300px;">
                <input type="text" placeholder="Search By Name" class="form-control" id="search-column-name">
            </div>
            <button type="button" class="btn btn-success" id="search" style="vertical-align: top;">Search</button>
        </div>
    </div>

    <div class="col-12 mb-5">
        <table class="table row-border order-column" id="list-recruits" data-toggle="list-recruits"> 
        </table>
        <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
    </div>
</div>
@endsection

@section('javascript')

<script type="text/javascript" src="{{ asset('/bootstrap-table/bootstrap-table.min.js') }}"></script>

<script type="text/javascript">

    $(document).ready(function () {
        $(".lds-ring").hide();

        var _records = 50;
        var _total_records = 0;
        var _count_records = 0;

        var _before_rows = 0;

        var _dataRows = [];
        var _page = 1;

        var search_name = "{{ $name }}";

        $("#search-column-name").val( search_name );

        function ajax_experts(_search_name , page){
            $(".lds-ring").show();
            var params = {
                'rows': _records,
                'page' : page , 
                'name' : _search_name,
            };
            $.ajax({
                type:'GET',
                url: '{{ route("recruit.test.list") }}',
                data: $.param(params),
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){
                    let _data = JSON.parse(data)
                    _total_records = _data.totalNotFiltered;
                    _before_rows = _data.total;
                    _count_records = _count_records + _data.rows.length;
                    $("#count-recruits").html( _count_records );
                    _dataRows = _data.rows;
                    tablebootstrap_filter( _data.rows);
                    if( page == 1 ) $("html, body").animate({ scrollTop: 0 }, "slow");
                    $(".lds-ring").hide();
                }
            });
        }

        function tablebootstrap_filter( data ){
            var columns = [
                {
                    field: 'id',
                    title: "Actions",
                    valign: 'middle',
                    align: 'left',
                    clickToSelect: false,
                    formatter : function(value,rowData,index) {
                        var actions = "";
                        
                        actions += '<a class="badge badge-info btn-test" data-id="'+rowData.id+'" data-index="'+index+'" href="#">Evaluate</a>\n';
                        actions += '<a class="badge badge-danger btn-fail" data-id="'+rowData.id+'" data-name="'+rowData.fullname+'" data-index="'+index+'" href="#">Fail</a>';
                        
                        actions = actions.replace(/:id/gi , rowData.id);

                        return actions;
                    },
                    width: 100,
                    class: 'frozencell'
                },
                { field: 'fullname', title: "Name", width: 150 , class: 'frozencell'},
                {
                    field: 'sent_at',
                    title: 'Sent At',
                    width: 100,
                    class: 'frozencell',
                    formatter: function(value, rowData, index){
                        if(rowData.sent_at == null){
                            return "-";
                        }
                        var d = new Date(rowData.sent_at);
                        var dateString =
                                d.getUTCFullYear() + "-" +
                                ("0" + (d.getUTCMonth()+1)).slice(-2) + "-" +
                                ("0" + d.getUTCDate()).slice(-2);
                        return dateString;
                    }
                }
            ];
            
            $("#list-recruits").bootstrapTable('destroy').bootstrapTable({
                height: undefined,
                columns: columns,
                data: data,
                theadClasses: 'table-dark',
                uniqueId: 'id'
            });
            // ========================================
            
            $("table tbody").on('click' , 'a.btn-test' , function(ev){
                ev.preventDefault();
                var recruit_id = $(this).attr("data-id");
                var index = $(this).attr("data-index");
                $("input:radio").prop('checked', false);
                $.ajax({
                    type: 'POST',
                    url: '{{ route("recruit.test.call") }}',
                    data: {recruit_id : recruit_id },
                    headers: {
                        'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(data){
                        let _data = JSON.parse(data)
                        var html='';

                        $("input:radio[name=completeness_score]").filter('[value='+_data.recruit.completeness_score+']').prop('checked', true);
                        $("input:radio[name=code_score]").filter('[value='+_data.recruit.code_score+']').prop('checked', true);
                        $("input:radio[name=design_score]").filter('[value='+_data.recruit.design_score+']').prop('checked', true);
                        $("input:radio[name=technologies_score]").filter('[value='+_data.recruit.technologies_score+']').prop('checked', true);
                        $("input:radio[name=readme_score]").filter('[value='+_data.recruit.readme_score+']').prop('checked', true);

                        $("#error-test-form").hide();

                        $('#recruit_index').val(index);
                        $('#recruit_id').val(recruit_id);

                        $("#test_recruit_name").html(_data.recruit.fullname);
                        
                        $(".tab-test").removeClass("test-active");
                        $(".tab-test[data-tab='1']").addClass("test-active");
                        $("#prev-test").attr("data-test", 1).hide();
                        $("#nest-test").attr("data-test", 1).show();
                        $("#save-test").hide();
                        $('#testRecruit').modal();
                    }
                });
            });

            $("table tbody").on('click','a.btn-fail', function(ev){
                ev.preventDefault();
                var recruit_id = $(this).attr("data-id");
                var name = $(this).data("name");
                var fail = confirm("You are about to FAIL the recruit: "+name);
                if(fail){
                    $.ajax({
                        type:'POST',
                        data:{id:recruit_id},
                        url:'{{ route("recruit.test.fail") }}',
                        headers: {
                            'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success:function(data){
                            if(data.stats = "success"){
                                location.reload();
                            }
                        },
                    });
                }
            });

        }

        $("#prev-test").on('click' , function(ev){
            var tab = $(this).attr("data-test");
            $("#nest-test").attr("data-test", tab);
            $(".tab-test").removeClass("test-active");
            var max = $(".tab-test").length;
            
            $(".tab-test[data-tab='"+tab+"']").addClass("test-active");
            if(tab == 1){
                $(this).hide();
            }else{
                
                $("#nest-test").show();
            }
            tab = parseInt(tab) - 1;
            $(this).attr("data-test", tab);
            $("#save-test").hide();
        });

        $("#nest-test").on('click', function(ev){
            var tab = $(this).attr("data-test");
            $("#prev-test").attr("data-test", tab);
            tab = parseInt(tab) + 1;
            $(".tab-test").removeClass("test-active");
            
            $(".tab-test[data-tab='"+tab+"']").addClass("test-active");
            var max = $(".tab-test").length;
            if(tab == max){
                $(this).hide();
                $("#save-test").show();
            }else{
                $("#save-test").hide();
                $("#prev-test").show();
            }
            $(this).attr("data-test", tab);
        })

        $("#save-test").on('click', function(){
            $("#error-test-form").hide();
            var max = $('#form_test').serializeArray();
            var count = $(".tab-test").length;
            if( max.length < (count + 1) ){
                $("#error-test-form").show();
                return true;
            }
            
            $.ajax({
                type: 'POST',
                url: '{{ route("recruit.test.save") }}',
                data: $('#form_test').serialize(),
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){
                    location.reload();
                }
            });
        })


        $('#search').on('click' , function(){
            
            search_name = $('#search-column-name').val();
            
            window.history.replaceState({
                edwin: "Fulltimeforce"
                }, "Page" , "{{ route('recruit.test.menu') }}" + '?'+ $.param(
                    {   search : true , 
                        name: search_name
                    }
                    )
                );
            _page = 1;
            _count_records = 0;
            location.reload();
        });

        $("body").on('click' , 'a.speed-audio' , function(ev){
            ev.preventDefault();
            var speed = $(this).data("speed");
            var index = $(this).parent().parent().data("audio");
            console.log( parseFloat( speed ) , speed )
            document.getElementById("audio-player-"+index).playbackRate = parseFloat(speed);
        })

        ajax_experts(search_name , 1);

        $(window).on('scroll', function (e){
            console.log("SCROLL TOP: "+$(window).scrollTop()+"|| WINDOW HEIGHT: "+$(window).height()+"|| DOCUMENT HEIGHT: "+$(document).height());
            console.log( $(window).scrollTop() + $(window).height() , $(document).height()-1 )
            if($(window).scrollTop() + $(window).height() >= $(document).height()-1) {
                console.log( _count_records , _total_records, _before_rows , _records , "##################" );
                console.log(_count_records < _total_records && _before_rows == _records);
                console.log("PAGE: "+_page);
                if( _count_records < _total_records && _before_rows == _records ){
                    _page++;
                    var _text = $('#search-column-name').val();
                    var data = {
                            'offset': _records,
                            'rows': _records,
                            'page' : _page , 
                            'name' : _text,
                            'audio': 1
                    };
                    $(".lds-ring").show();
                    $.ajax({
                        type:'GET',
                        url: '{{ route("recruit.test.list") }}',
                        data: $.param(data),
                        headers: {
                            'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success:function(data){

                            let _data = JSON.parse(data);
                            _before_rows = _data.total;
                            $("#list-recruits").bootstrapTable('append', _data.rows );
                            
                            _count_records = _count_records + _data.rows.length;
                            $("#count-recruits").html( _count_records );
                            $(".lds-ring").hide();
                        }
                    });
                }
            }
        });

    });
</script>
@endsection