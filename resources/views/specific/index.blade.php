@extends('layouts.app' , ['controller' => 'position'])

@section('styles')

<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap-table/bootstrap-table.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.css') }}"/>

<style>
    /* The switch - the box around the slider */
.SliderSwitch {
  max-width: 600px;
  margin-left:auto;
  margin-right: auto;
  text-align: center;
}

.SliderSwitch input{
  visibility: hidden;
  display: inline-block;
  width: 1px;
  height: 1px;
}

.SliderSwitch label{
  font-family: Helvetica, Arial, sans-serif;
  pointer: cursor;
}

.SliderSwitch__container{
  background-color: #FF0000;
  height: 20px;
  display: inline-block;
  width: 50px;
  border-radius: 10px;
  position: relative;
  vertical-align: middle;
  box-shadow: inset 0px 0px 3px 1px rgba(0,0,0,0.3);
  margin-left: 10px;
  
  transition: background-color 300ms ease-in-out;
}

.SliderSwitch__toggle {
  display: block;
  height: 24px;
  width: 24px;
  border-radius: 12px;
  background-color: white;
  border: 1px solid #DDD;
  position: absolute;
  top: -2px;
  left: -2px;
  box-shadow: 0px 0px 3px rgba(0,0,0,0.2);
  cursor: pointer;
  
  transition: left 300ms ease-in-out;
}

.SliderSwitch__toggle:after {
  content: '\f00d';
  font-size: 12px;
  color: #FF4136;
  display: block;
  position: absolute;
  top: 50%;
  margin-top: -6px;
  left: 0;
  width: 100%;
  text-align: center;
  
  transition: color 300ms ease-in-out;
  
}

input:checked + .SliderSwitch__container{
  background-color: #01FF70;
}

input:checked + .SliderSwitch__container .SliderSwitch__toggle {
  left: calc( 100% - 20px );
}

input:checked + .SliderSwitch__container .SliderSwitch__toggle:after {
  content: '\f00c';
  color: #2ECC40;
}
table.dataTable thead .sorting:before,
table.dataTable thead .sorting:after{
    content: '';
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
a.badge-primary.focus, 
a.badge-primary:focus{
    box-shadow: none;
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
.nav-item-custom {
    border: 1px solid rgba(86, 61, 124, .2);
}

@keyframes lds-ring {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.button-disabled {
  cursor: not-allowed;
}

.tech{
    display: inline-block;
    padding: 5px;
    border-radius: 5px;
    margin-right: 5px;
    margin-bottom: 5px;
}

.tech_bas {
    background-color: #539eef;
    color: white;
}

.tech_int {
    background-color: #eca95c;
    color: white;
}

.tech_adv {
    background-color: #ff6969;
    color: white;
}

</style>
@endsection
 
@section('content')
    <!--
    ERROR - SUCCESS MESSAGE SECTION
    -->
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

        <!--
        TOTAL RECORDS SECTION
        -->
        <div class="col-12">
          <p>Records: <span id="count-recruit"></span></p>
        </div>
        
        <!--
        NEW SPECIFIC POSITION BUTTON SECTION
        -->
        <div class="col-6 text-left">
            <a href="{{ route('specific.create') }}" class="btn btn-info" id="create-specific-position" type="button" style="vertical-align: top;">New Specific Position</a>
        </div>

        <!--
        SEARCH BY NAME SECTION
        -->
        <div class="col-6 text-right">
            <div class="form-group d-inline-block" style="max-width: 300px;">
                <input type="text" placeholder="Search By Name" class="form-control" id="search-column-name">
            </div>
            <button class="btn btn-primary" id="search-recruit" type="button" style="vertical-align: top;">Search</button>
        </div>

        <!--
        SPECIFIC POSITIONS TABLE SECTION
        -->
        <div class="col-12 text-center mb-5">
            <table class="table row-border order-column" id="list-recruits" data-toggle="list-recruits"> 
            </table>
            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
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
        
        var search_name = "{{ $s }}";

        $("#search-column-name").val( search_name );

        //===================================================================================
        //=====================SPECIFICS TABLE BUILDING FUNCTION=============================
        //===================================================================================

        //LOAD SPECIFICS TABLE DATA FUNCTION
        function ajax_recruits(_search_name, page){
            $(".lds-ring").show();

            var params = {
                'rows' : _records,
                'page' : page ,
                'name' : _search_name,
            };

            $.ajax({
                type:'GET',
                url: '{{ route("specific.list") }}',
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
                    $("#count-recruit").html( _count_records );
                    _dataRows = _data.rows;
                    tablebootstrap_filter( _data.rows );
                    if( page == 1 ) $("html, body").animate({ scrollTop: 0 }, "slow");
                    $(".lds-ring").hide();
                }
            });
        }

        ajax_recruits(search_name, 1);

        //===================================================================================
        //=====================SPECIFICS TABLE AND ROWS FUNCTIONS============================
        //===================================================================================

        //BUILD TABLE FUNCTION - ELEMENTS FUNCTIONS
        function tablebootstrap_filter( data ){
            var columns = [
                {
                    field: 'id', 
                    title: "Name",
                    align: 'left',
                    clickToSelect: false,
                    width: 350,
                    formatter : function(value,rowData,index) {    
                        var actions = rowData.name;
                        return actions;
                        },
                    class: 'frozencell',
                },
                {
                    title: "Basic",
                    align: 'left',
                    clickToSelect: false,
                    width: 350,
                    formatter : function(value,rowData,index) {    
                        var actions = '';
                        rowData.technology_basic.forEach(element => {
                            actions += '<span class="tech tech_bas">'+element+'</span>';
                        });
                        return actions;
                        },
                    class: 'frozencell',
                },
                {
                    title: "Intermediate",
                    align: 'left',
                    clickToSelect: false,
                    width: 350,
                    formatter : function(value,rowData,index) {    
                        var actions = '';
                        rowData.technology_inter.forEach(element => {
                            actions += '<span class="tech tech_int">'+element+'</span>';
                        });
                        return actions;
                        },
                    class: 'frozencell',
                },
                {
                    title: "Advanced",
                    align: 'left',
                    clickToSelect: false,
                    width: 350,
                    formatter : function(value,rowData,index) {    
                        var actions = '';
                        rowData.technology_advan.forEach(element => {
                            actions += '<span class="tech tech_adv">'+element+'</span>';
                        });
                        return actions;
                        },
                    class: 'frozencell',
                },
                {
                    field: 'created_at', 
                    title: "Actions",
                    width: 50,
                    formatter : function(value,rowData,index) { 
                        var actions = '<a class="badge badge-primary specific-position-edit" href=" '+ "{{ route('specific.edit', ':id' ) }}"+ '">Edit</a>'+
                                    ' <a class="badge badge-danger specific-position-delete" data-id="'+rowData.id+'" href="#">Delete</a>'+
                                    ' <a class="badge badge-warning specific-position-result" href=" '+ "{{ route('specific.show.applicants', ':id' ) }}"+ '">Show Result</a>';

                        actions = actions.replace(/:id/gi , rowData.id);
                        return actions;
                        },
                    class: 'frozencell recruit-created-at',
                },
            ];
            
            //SET TABLE PROPERTIES
            $("#list-recruits").bootstrapTable('destroy').bootstrapTable({
                height: undefined,
                columns: columns,
                data: data,
                theadClasses: 'table-dark',
                uniqueId: 'id'
            });

            //DELETE SPECIFIC POSITION FUNCTION
            $("table tbody").on('click', 'a.specific-position-delete' , function(ev){
                ev.preventDefault();
                var specific_position_id = $(this).data("id");

                var confirmed = confirm("Are you sure you want to DELETE this specific position?");

                if(confirmed){
                    $.ajax({
                        type:'POST',
                        url: '{{ route("specific.delete") }}',
                        data: {specific_position_id: specific_position_id},
                        headers: {
                            'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                            success:function(data){
                            location.reload();
                        }
                    });
                }
            });
        }

        //===================================================================================
        //=========================SCROLL AND SEARCH FUNCTIONS===============================
        //===================================================================================

        //SEARCH BY NAME BUTTON FUNCTION
        $('#search-recruit').on('click' , function(){
            search_name = $('#search-column-name').val();
            
            window.history.replaceState({
                edwin: "Fulltimeforce"
                }, "Page" , "{{ route('specific.menu') }}" + '?'+ $.param(
                    {   search : true , 
                        name: search_name
                    }
                    )
                );
            _page = 1;
            _count_records = 0;
            location.reload();
        });

        //SCROLL LOADING ROWS FUNCTION
        $(window).on('scroll', function (e){
            console.log( $(window).scrollTop() + $(window).height() , $(document).height() )
            if($(window).scrollTop() + $(window).height() >= $(document).height()) {
                console.log( _count_records , _total_records, _before_rows , _records , "##################" );
                if( _count_records < _total_records && _before_rows == _records ){
                    _page++;
                    var _text = $('#search-column-name').val();
                    var data = {
                            'offset': _records,
                            'rows': _records,
                            'page' : _page , 
                            'name' : _text,
                    };
                    $(".lds-ring").show();
                    $.ajax({
                        type:'GET',
                        url: '{{ route("recruit.list") }}',
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