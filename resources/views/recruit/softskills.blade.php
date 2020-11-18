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

.toggle.btn {
    min-width: 8rem;
    min-height: 2.15rem;
}

.count-notif{
  vertical-align: middle;
  margin-left: -8px;
  margin-top: -17px;
  font-size: 13px;
}
</style>
@endsection
 
@section('content')
    <!--
    VIEW MENU
    -->
    <nav class="nav nav-pills nav-fill mb-4">
      <a class="nav-item nav-link nav-item-custom {{$tab == 'postulant' ? 'active' : ''}}" href="{{ route('recruit.menu') }}">Postulantes
        @if ($badge_qty>0)
          <span class="badge badge-pill badge-warning count-notif">{{ $badge_qty }}</span>
        @endif
      </a>
      <a class="nav-item nav-link nav-item-custom {{$tab == 'outstanding' ? 'active' : ''}}" href="{{ route('recruit.outstanding') }}">Perfiles Destacados</a>
      <a class="nav-item nav-link nav-item-custom {{$tab == 'preselected' ? 'active' : ''}}" href="{{ route('recruit.preselected') }}">Pre-Seleccionados</a>
      <a class="nav-item nav-link nav-item-custom {{$tab == 'softskills' ? 'active' : ''}}" href="{{ route('recruit.softskills') }}">Para Evaluar</a>
      <a class="nav-item nav-link nav-item-custom {{$tab == 'selected' ? 'active' : ''}}" href="{{ route('recruit.selected') }}">Seleccionados</a>
    </nav>

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
        BULK ACTIONS SECTION
        -->
        <div class="col-6 text-left">
            <div class="form-group d-inline-block" style="max-width: 300px;">
                <select name="bulk-action" id="bulk-action" class="form-control" >
                    <option value="">-- Bulk Actions --</option> 
                    <option value="approve">Approve</option>
                    <option value="disapprove">Disapprove</option>
                    <!--<option value="trash">Move to Trash</option>-->
              </select>
            </div>
            <button class="btn btn-info" id="bulk-recruit" type="button" style="vertical-align: top;">Apply</button>
        </div>
        <div class="col-6 text-right">
          <div class="form-group d-inline-block" style="max-width:300px;">
            <input type="text" placeholder="Search By Name" class="form-control" id="search-column-name">
          </div>
          <button type="button" class="btn btn-info" id="search" style="vertical-align: top;">Search</button>
        </div>

        <!--
        POSTULANTS TABLE SECTION
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
      
      var search_name = "{{ $search_name }}";

      $("#search-column-name").val( search_name );

      //===================================================================================
      //=====================POSTULANTS TABLE BUILDING FUNCTION============================
      //===================================================================================

      //LOAD POSTULANTS TABLE DATA FUNCTION
      function ajax_recruits(_search_name, page){
          $(".lds-ring").show();

          var params = {
              'rows' : _records,
              'page' : page ,
              'name' : _search_name,
              'tab'  : "{{ $tab }}"
          };

          $.ajax({
              type:'GET',
              url: '{{ route("recruit.list") }}',
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
                  $('input[name="btSelectAll"]').click();
              }
          });
      }
      $('#search').on('click' , function(){
        
        search_name = $('#search-column-name').val();
        
        window.history.replaceState({
            edwin: "Fulltimeforce"
            }, "Page" , "{{ route('recruit.softskills') }}" + '?'+ $.param(
                {name: search_name})
            );
        _page = 1;
        _count_records = 0;
        location.reload();
        
      });

      ajax_recruits(search_name, 1);

      //===================================================================================
      //=====================POSTULANTS TABLE AND ROWS FUNCTIONS===========================
      //===================================================================================

      //BUILD TABLE FUNCTION - ELEMENTS FUNCTIONS
      function tablebootstrap_filter( data ){
        var columns = [
            { 
              field: 'id', 
              valign: 'middle',
              checkbox: true,
              clickToSelect: false,
              width: 20,
              formatter : function(value,rowData,index) {    
                  var actions = '';

                  actions = actions.replace(/:id/gi , rowData.id);
                  return actions;
                },
              class: 'frozencell',
            },
            {
              field: 'created_at', 
              title: "Date",
              width: 50,
              formatter : function(value,rowData,index) { 
                  var aux_date = new Date(rowData.created_at)
                  var actions = (aux_date.getDate())+'/'+(aux_date.getMonth()+1)+'/'+(aux_date.getFullYear())  

                  actions = actions.replace(/:id/gi , rowData.id);
                  return actions;
                },
              class: 'frozencell',
            },
            { field: 'user_name', title: "Recruiter", width: 75 , class: 'frozencell'},
            { field: 'fullname', title: "Postulant", width: 75 , class: 'frozencell'},
            {
              field: 'fce_overall', 
              title: "English",
              width: 50,
              formatter : function(value,rowData,index) { 
                  var actions = rowData.fce_overall;

                  actions += '<input class="bulk-input-value" type="hidden" data-index="'+index+'" data-rpid="'+rowData.rp_id+'" data-recruit-id="'+rowData.recruit_id+'">';

                  return actions;
                },
              class: 'frozencell',
            },
            {
              field: 'pos_id',
              title: "Selected",
              valign: 'middle',
              clickToSelect: false,
              width: 20,
              formatter : function(value,rowData,index) {    
                  var actions = '<a class="badge badge-primary recruit-evaluation" data-evaluation="approve" data-positionid="'+rowData.pos_id+'" data-id="'+rowData.recruit_id+'" data-rpid="'+rowData.rp_id+'" data-fullname="'+rowData.fullname+'" href="#">YES</a>'+
                                ' <a class="badge badge-danger recruit-evaluation" data-evaluation="disapprove" data-positionid="'+rowData.pos_id+'" data-id="'+rowData.recruit_id+'" data-rpid="'+rowData.rp_id+'" data-fullname="'+rowData.fullname+'" href="#">NO</a>'

                  actions = actions.replace(/:id/gi , rowData.id);
                  return actions;
                },
              class: 'frozencell',
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

        //EVALUATE AUDIO - (APPROVE - DISAPPROVE)
        $("table tbody").on('click', 'a.recruit-evaluation' , function(ev){
          ev.preventDefault();
          var id = $(this).data("id");
          var rpid = $(this).data("rpid");
          var fullname = $(this).data("fullname");
          var positionid = $(this).data("positionid");
          var evaluation = $(this).data("evaluation");
          var confirmed = true;

          if(evaluation=="disapprove"){
            var confirmed = confirm("Are you sure you want to "+ (evaluation=="approve"?"APPROVE":"DISAPPROVE") +" this profile?");
          }

          if(confirmed){
            $.ajax({
                type:'POST',
                url: '{{ route("recruit.postulant.evaluation") }}',
                data: {id: id,rpid: rpid,positionid: positionid,evaluation: evaluation,fullname: fullname},
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
      //================================SCROLL FUNCTIONS===================================
      //===================================================================================

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
                        'tab'  : "{{ $tab }}",
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

<script>
  //===================================================================================
  //=====================REGISTERED POSTULANTS BUTTON FUNCTION=========================
  //===================================================================================

  //BULK ACTIONS BUTTON
  $("#bulk-recruit").on('click' , function(){
      var action = $('#bulk-action').val();
      var rp_id_array = [];
      var recruit_id_array = [];

      if(action){
        var checked = $('input[name="btSelectItem"]:checked');

        if(checked.length>0){
            checked.each(function (){
                var checkbox_index = $(this).data("index");
                var rp_id_by_index = $('.bulk-input-value[data-index="'+checkbox_index+'"]').data("rpid");
                var recruit_id_by_index = $('.bulk-input-value[data-index="'+checkbox_index+'"]').data("recruit-id");

                rp_id_array.push(rp_id_by_index)
                recruit_id_array.push(recruit_id_by_index)
            });
            $.ajax({
                type:'POST',
                url: "{{ route('recruit.bulk') }}",
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    action : action,
                    rp_id_array: rp_id_array,
                    recruit_id_array: recruit_id_array,
                    tab: "{{ $tab }}",
                },
                success:function(data){
                  location.reload();
                }
            });
        }else{
          alert('Please, select at least 1 POSTULANT to continue.');
        }

      }else{
        alert('Select a BULK ACTION to continue.');
      }
  });
</script>
@endsection