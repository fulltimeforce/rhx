@extends('layouts.app' , ['controller' => 'experts'])

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
  .tab-fce{
      display: none;
  }
  .tab-fce.fce-active{
      display: flex;
  }
</style>
@endsection

@section('content')

<div class="row">
    <div class="col">
        <h1>Sales</h1>
    </div>
</div>    
<div class="row mb-4">
    <div class="col">
            <p>Result: <span id="count-sale"></span></p>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <table id="list-sales"></table>
        </div>
        <div class="col-12 text-center">
            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
        </div>
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

    function ajax_sales(page){
        $(".lds-ring").show();
        var params = {
            'rows': _records,
            'page' : page ,
        };
        $.ajax({
            type:'GET',
            url: '{{ route("sales.list") }}',
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
                $("#count-sale").html( _count_records );
                _dataRows = _data.rows;
                tablebootstrap_filter( _data.rows );
                if( page == 1 ) $("html, body").animate({ scrollTop: 0 }, "slow");
                $(".lds-ring").hide();
            }
        });
    }

    function tablebootstrap_filter( data ){
        var columns = [
            {
              field: 'id', 
              title: "Accion",
              width: 50,
              formatter : function(value,rowData,index) {                        
                var actions = '<a class="badge badge-success btn-link-sale" data-id="'+rowData.id+'" data-index="'+index+'" href="'+rowData.url+'" target="_blank">Go to Link</a>\n';
                    if(rowData.status == "enabled"){
                      actions += '<a class="badge badge-danger btn-switch-status-sale" href="#" data-jobid="'+rowData.job_id+'" data-id="'+rowData.id+'" data-status="'+rowData.status+'">Disable</a>\n';
                    }else{
                      actions += '<a class="badge badge-warning btn-switch-status-sale" href="#" data-jobid="'+rowData.job_id+'" data-id="'+rowData.id+'" data-status="'+rowData.status+'>Restore</a>\n';
                    }
                    actions = actions.replace(/:id/gi , rowData.id);
                    return actions;
                },
              class: 'frozencell',
            },
            { field: 'title', title: "Title", width: 200 , class: 'frozencell'},
            { field: 'payment_range', title: "Payment", width: 75 , class: 'frozencell'},
            { field: 'freelancers_qty', title: "Freelancers", width: 75, class: 'frozencell'},
        ];
        
        $("#list-sales").bootstrapTable('destroy').bootstrapTable({
            height: undefined,
            columns: columns,
            data: data,
            theadClasses: 'table-dark',
            uniqueId: 'id'
        });
        // =================== DELETE

        $("table tbody").on('click', 'a.btn-switch-status-sale' , function(ev){
          ev.preventDefault();
          var id = $(this).data("id");
          var status = $(this).data("status");
          var job_id = $(this).data("jobid");
          var confirmed = confirm("Are you sure you want to "+ (status=="enabled"?"disable":"enable") +": "+job_id+"?");
          console.log(id)
          console.log(status)
          console.log(job_id)
          if(confirmed){
            $.ajax({
                type:'POST',
                url: '{{ route("sales.switch") }}',
                data: {id : id, status: status},
                headers: {
                  'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){
                  //$("#list-users").bootstrapTable('removeByUniqueId',id);
                  location.reload();
                }
            });
          }
        });
    }

    ajax_sales(1);

    $(window).on('scroll', function (e){
        console.log( $(window).scrollTop() + $(window).height() , $(document).height() )
        if($(window).scrollTop() + $(window).height() >= $(document).height()) {
            console.log( _count_records , _total_records, _before_rows , _records , "##################" );
            if( _count_records < _total_records && _before_rows == _records ){
                _page++;
                var data = {
                        'offset': _records,
                        'rows': _records,
                        'page' : _page , 
                };
                $(".lds-ring").show();
                $.ajax({
                    type:'GET',
                    url: '{{ route("sales.list") }}',
                    data: $.param(data),
                    headers: {
                        'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(data){

                        let _data = JSON.parse(data);
                        _before_rows = _data.total;
                        $("#list-sales").bootstrapTable('append', _data.rows );
                        
                        _count_records = _count_records + _data.rows.length;
                        $("#count-sale").html( _count_records );
                        $(".lds-ring").hide();
                    }
                });
            }
        }
    });


});


</script>
@endsection