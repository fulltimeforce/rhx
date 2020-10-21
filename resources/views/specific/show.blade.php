@extends('layouts.app' , ['controller' => 'positions-expert'])

@section('styles')

<!-- <link rel="stylesheet" type="text/css" href="{{ asset('/datatable/jquery.dataTables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/datatable/css/fixedColumns.dataTables.min.css') }}"/> -->

<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap-table/bootstrap-table.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.css') }}"/>

<style>
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

.dataTables_filter{
    display: none;
}

td.stickout{
    background-color: yellow;
}
td.frozencell{
    background-color : #fafafa;
}
a.badge-success.focus, 
a.badge-success:focus,
a.badge-secondary.focus, 
a.badge-secondary:focus,
a.badge-danger.focus, 
a.badge-danger:focus,
a.badge-warning.focus, 
a.badge-warning:focus,
a.badge-light.focus, 
a.badge-light:focus{
    box-shadow: none;
}
.txt-description{
    white-space: pre-line;
}

.basic-background{
    background-color: #96c4f3;
}

.inter-background{
    background-color: #ffde8a;
}

.advan-background{
    background-color: #f98677;
}
</style>
@endsection
 
@section('content')

    <div class="row mt-5 mb-5">
        <div class="col">
            <h2 class="d-inline">{{ $specific_position->name }} - Matches</h2>
        </div>
        <div class="col text-right">
            <a class="btn btn-primary align-top" href="{{ route('specific.menu') }}"> Back</a>
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

    <div class="col-12">
        <p>Records: <span id="count-postulants"></span></p>
    </div>

    <div class="row mt-3">
        <div class="col">
            <table id="list-postulants"></table>

        </div>
    </div>
@endsection

@section('javascript')


<script type="text/javascript" src="{{ asset('/bootstrap-table/bootstrap-table.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.js') }}"></script>


<script type="text/javascript">

$(document).ready(function (ev) {
        
        $(".lds-ring").hide();

        var _records = 50;
        var _total_records = 0;
        var _count_records = 0;

        var _before_rows = 0;

        var _dataRows = [];
        var _page = 1;

        function ajax_recruits(page){
            $(".lds-ring").show();

            var params = {
                'rows' : _records,
                'page' : page ,
                'spcposId' : "{{ $specific_position->id }}",
            };

            $.ajax({
                type:'GET',
                url: '{{ route("specific.show.list") }}',
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
                    $("#count-postulants").html( _count_records );
                    _dataRows = _data.rows;
                    tablebootstrap_filter( _data.rows, _data.positionId );
                    if( page == 1 ) $("html, body").animate({ scrollTop: 0 }, "slow");
                    $(".lds-ring").hide();
                }
            });
        }

        function tablebootstrap_filter( data, positionId ){
            var columns = [
                {
                    field: 'id',
                    title: "Actions",
                    align: 'center',
                    clickToSelect: false,
                    width: 20,
                    formatter : function(value,rowData,index) {    
                        var actions = '<a class="badge badge-primary specific-position-apply" data-id="'+rowData.id+'" data-positionId="'+ positionId +'" data-index="'+index+'" href="#">Apply</a>';
                        return actions;
                        },
                    class: 'frozencell',
                },
                {
                    field: 'fullname', 
                    title: "Name",
                    align: 'center',
                    clickToSelect: false,
                    width: 50,
                    formatter : function(value,rowData,index) {    
                        var actions = '<div class="text-left">'+rowData.fullname+'</div>';
                        return actions;
                        },
                    class: 'frozencell',
                }
            ];

            columns.push( { field: 'crit_1', title: "Person - Environment", align: 'center', width: 20, formatter : function(value,rowData,index) { 
                        var actions = '-'
                        if(rowData.crit_1 == 'excellent'){actions = 'Excellent'}
                        if(rowData.crit_1 == 'efficient'){actions = 'Efficient'}
                        if(rowData.crit_1 == 'inefficient'){actions = 'Inefficient'}
                        if(rowData.crit_1 == 'lower'){actions = 'Lower than expected'}
                        return actions;
            } } );
            columns.push( { field: 'crit_2', title: "Self - confidence", align: 'center', width: 20, formatter : function(value,rowData,index) { 
                        var actions = '-'
                        if(rowData.crit_2 == 'excellent'){actions = 'Excellent'}
                        if(rowData.crit_2 == 'efficient'){actions = 'Efficient'}
                        if(rowData.crit_2 == 'inefficient'){actions = 'Inefficient'}
                        if(rowData.crit_2 == 'lower'){actions = 'Lower than expected'}
                        return actions;
            } } );
            columns.push( { field: 'fce_overall', title: "English", align: 'center', width: 20, formatter : function(value,rowData,index) {
                        var actions = '-';
                        if(rowData.fce_overall){actions = rowData.fce_overall}
                        return actions;
            } } );

            @foreach($a_basic as $key => $basic)
                columns.push( { title: '{{$basic}} (B)', align: 'center', width: 20, formatter : function(value,rowData,index) {
                            var actions = '-';
                            if(rowData['{{$key}}']){actions = '<div class="text-center">'+rowData['{{$key}}']+'</div>';}
                            return actions;
                } } );
            @endforeach

            @foreach($a_inter as $key => $inter)
                columns.push( { title: '{{$inter}} (I)', align: 'center', width: 20, formatter : function(value,rowData,index) {
                            var actions = '-';
                            if(rowData['{{$key}}']){actions = '<div class="text-center">'+rowData['{{$key}}']+'</div>';}
                            return actions;
                } } );
            @endforeach

            @foreach($a_advan as $key => $advan)
                columns.push( { title: '{{$advan}} (A)', align: 'center', width: 20, formatter : function(value,rowData,index) {
                            var actions = '-';
                            if(rowData['{{$key}}']){actions = '<div class="text-center">'+rowData['{{$key}}']+'</div>';}
                            return actions;
                } } );
            @endforeach
            
            $("#list-postulants").bootstrapTable('destroy').bootstrapTable({
                height: undefined,
                columns: columns,
                showExtendedPagination: true,
                data: data,
                theadClasses: 'table-dark',
                fixedColumns: true,
                fixedNumber: 2,
                uniqueId: 'id'
            });

            $("table tbody").on('click', 'a.specific-position-apply' , function(ev){
                ev.preventDefault();
                var recruit_id = $(this).data("id");
                var position_id = $(this).data("positionid");

                $.ajax({
                    type:'POST',
                    url: '{{ route("specific.apply") }}',
                    data: {recruit_id : recruit_id,position_id: position_id},
                    headers: {
                        'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(data){
                        location.reload();
                    }
                });
            });
        }

        ajax_recruits(1);

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
                            'spcposId' : "{{ $specific_position->id }}",
                    };
                    $(".lds-ring").show();
                    $.ajax({
                        type:'GET',
                        url: '{{ route("specific.show.list") }}',
                        data: $.param(data),
                        headers: {
                            'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success:function(data){

                            let _data = JSON.parse(data);
                            _before_rows = _data.total;
                            $("#list-postulants").bootstrapTable('append', _data.rows );
                            
                            _count_records = _count_records + _data.rows.length;
                            $("#count-postulants").html( _count_records );
                            $(".lds-ring").hide();
                        }
                    });
                }
            }
        });

    });
</script>
</script>   
@endsection