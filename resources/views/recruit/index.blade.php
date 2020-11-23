@extends('layouts.app' , ['controller' => 'position'])

@section('styles')

<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap-table/bootstrap-table.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.css') }}"/>
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

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

  .basic-background{
      color: #fff;
      background-color: #96c4f3;
      border-color: #96c4f3;
  }

  .inter-background{
      color: #fff;
      background-color: #deb038;
      border-color: #deb038;
  }

  .advan-background{
      color: #fff;
      background-color: #f98677;
      border-color: #f98677;
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
      HISTORY MODAL
      -->
      <div class="modal fade" id="search-table" tabindex="-1" role="dialog" aria-labelledby="search-tableLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="search-tableLabel">Search Result: </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                <div class="col-12">
                  <p>Records: <span id="count-search"></span></p>
                </div>
                <div class="col-12 text-center mb-5">
                    <table class="table row-border order-column" id="list-search" data-toggle="list-search"> 
                    </table>
                    <div id="search-ring" class="lds-ring"><div></div><div></div><div></div><div></div></div>
                </div>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
          </div>
      </div>
      </div>

      <!--
      REGISTERED MODAL
      -->
      <div class="modal fade" id="registered-table" tabindex="-1" role="dialog" aria-labelledby="registered-tableLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="registered-tableLabel">Search Result: </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                <div class="col-12">
                  <p>Records: <span id="count-registered"></span></p>
                </div>
                <div class="col-12 text-center mb-5">
                    <table class="table row-border order-column" id="list-registered" data-toggle="list-registered"> 
                    </table>
                    <div id="registered-ring" class="lds-ring"><div></div><div></div><div></div><div></div></div>
                </div>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
          </div>
      </div>
      </div>

      <!--
      TOP OPTIONS
      -->
      <div class="row">
        <!--<div class="col-6 text-left">
            <button class="btn inter-background" id="registered-recruit" type="button" style="vertical-align: top;">Registered Postulants</button>
            <button class="btn btn-danger" id="pasar-filas" type="button" style="vertical-align: top;">Pasar Filas</button>
        </div>-->

        <div class="col-12 text-right">
          <div class="form-group d-inline-block" style="max-width: 300px;">
              <input type="text" placeholder="Search By Name (*)" class="form-control" id="search-history-name">
          </div>
          <button class="btn advan-background" id="search-recruit" type="button" style="vertical-align: top;">History</button>
        </div>
      </div>

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
              <p>{!! $message !!}</p>
          </div>
      @endif

      @if ($message = Session::get('warning'))
          <div class="alert alert-warning">
              <p>{!! $message !!}</p>
          </div>
      @endif

      <!--
      DELETE CV MODAL
      -->
      <div class="modal fade" id="delete-audio" tabindex="-1" role="dialog" aria-labelledby="delete-audioLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="delete-audioLabel">Delete CV File</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col">
                      Are you sure you want to delete this file?
                      <input type="hidden" id="delete-audio-rp-id">
                      <input type="hidden" id="delete-audio-position-id">
                  </div>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="deleteAudio">Delete</button>
          </div>
          </div>
      </div>
      </div>

      <div class="row">

          <!--
          SAVE POSTULANT FORM
          -->
          <div class="col-12">
            <form name="new-recruit" id="new-recruit" action="{{ route('recruit.save') }}" method="POST" enctype="multipart/form-data">@csrf
              <input type="hidden" name="file_path" id="file_path" value="" class="form-control">
              <input type="hidden" name="recruit_id" id="recruit_id" class="form-control">
              <input type="hidden" name="rp_id" id="rp_id" class="form-control">
              <input type="hidden" name="index" id="index" class="form-control">
              <table class="table" >
                  <tr>
                      <td>
                          <div class="form-group">
                              <label for="fullname">Name *</label>
                              <input type="text" name="fullname" id="fullname" class="form-control">
                          </div>
                      </td>
                      <td>
                          <div class="form-group">
                              <label for="position_id">Positions *</label>
                              <select id="position_id" class="form-control" name="position_id" >
                                  <option value="">None</option>
                                  @foreach($positions as $pid => $position)
                                      <option value="{{ $position->id }}">{{ $position->name }}</option>
                                  @endforeach
                              </select>
                          </div>
                      </td>
                      <td>
                          <div class="form-group">
                              <label for="platform">Platform *</label>
                              <select name="platform" id="platform" class="form-control">
                                  <option value="">None</option>
                                  @foreach($platforms as $pid => $platform)
                                      <option value="{{$platform->value}}">{{$platform->label}}</option>
                                  @endforeach
                              </select>
                          </div>
                      </td>
                      <td>
                          <div class="form-group" style="position: relative;">
                              <label for="phone_number">Phone</label>
                              <input type="text" name="phone_number" id="phone_number" class="form-control">
                          </div>
                      </td>
                      <td>
                          <div class="form-group" style="position: relative;">
                              <label for="email_address">Email</label>
                              <input type="text" name="email_address" id="email_address" class="form-control">
                          </div>
                      </td>
                      <td>
                          <div class="form-group">
                              <label for="profile_link">Link</label>
                              <input type="text" name="profile_link" id="profile_link" class="form-control">
                          </div>
                      </td>
                      <td>
                          <div class="form-group" id="btn-form-save">
                            <label>&nbsp;</label>
                            <button type="submit" id="save_recruit" class="btn btn-success form-control">Save</button>
                          </div>
                      </td>
                      <td>
                          <div class="form-group d-none" id="btn-form-update">
                            <label>&nbsp;</label>
                            <button type="submit" id="update_recruit" class="btn btn-success form-control">Update</button>
                          </div>
                      </td>
                      <td>
                          <div class="form-group d-none" id="btn-form-cancel">
                            <label>&nbsp;</label>
                            <button type="submit" id="cancel_recruit" class="btn btn-danger form-control">Cancel</button>
                          </div>
                      </td>
                  </tr>
              </table>
            </form>
          </div>

          <!--
          PROGRESS BAR SECTION
          -->
          <div class="col-12 mb-3">
            <div class="progress">
              <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>

          <!--
          POSTULANT TECHNICAL QUESTIONARY URL COPY SECTION
          -->
          <div class="col-12 mb-3">
            <div class="alert alert-warning alert-dismissible mt-3 col-12" role="alert" style="display: none;">
                <b>Copy successful!!!!</b>
                <p id="showURL"></p>
            </div>
          </div>

          <!--
          TOTAL RECORDS SECTION
          -->
          <div class="col-12">
            <p>Records: <span id="count-recruit"></span></p>
          </div>
          
          <!--
          BULK ACTIONS SECTION
          -->
          <div class="col-8 text-left">
              <div class="form-group d-inline-block" style="max-width: 300px;">
                  <select name="bulk-action" id="bulk-action" class="form-control" >
                      <option value="">-- Bulk Actions --</option>
                      <option value="approve">Approve</option>
                      <option value="disapprove">Disapprove</option>
                      <option value="trash">Move to Trash</option>
                </select>
              </div>
              <button class="btn btn-info" id="bulk-recruit" type="button" style="vertical-align: top;">Apply</button>
              |
              <div class="form-group d-inline-block" style="max-width: 300px;">
                  <select name="recruiter-action" id="recruiter-action" class="form-control filter-element col-xs-8" >
                      <option value="">-- Recruiter --</option>
                      @foreach($users as $uid => $user)
                          <option value="{{$user->id}}" {{($_user==$user->id)?'selected':''}}>{{$user->name}}</option>
                      @endforeach
                </select>
              </div>
              |
              <input type="checkbox" {{$_auto=='false'?'':'checked'}} {{$_user==''?'':'disabled'}} data-toggle="toggle" data-on="Auto" data-off="Auto" data-onstyle="primary" data-offstyle="secondary" class="filter-element col-xs-8" id="auto-toggle" name="auto-toggle">
              <input type="checkbox" {{$_hand=='false'?'':'checked'}} {{$_user==''?'':'disabled'}} data-toggle="toggle" data-on="Manual" data-off="Manual" data-onstyle="primary" data-offstyle="secondary" class="filter-element col-xs-8" id="handmade-toggle" name="handmade-toggle">
          </div>
          <div class="col-4 text-right">
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
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

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

      var _user =  "{{ $_user }}";
      var _hand =  "{{ $_hand }}";
      var _auto =  "{{ $_auto }}";

      $("#search-column-name").val( search_name );

      if(_user){
        $("#auto-toggle").bootstrapToggle('disable');

        $("#handmade-toggle").bootstrapToggle('disable');

      }

      //===================================================================================
      //=====================POSTULANTS TABLE BUILDING FUNCTION============================
      //===================================================================================

      //LOAD POSTULANTS TABLE DATA FUNCTION
      window.ajax_recruits = function ajax_recruits(_search_name, page, user, hand, auto){
          $(".lds-ring").show();

          var params = {
              'rows' : _records,
              'page' : page,
              'name' : _search_name,
              'tab'  : "{{ $tab }}",
              'user' : user,
              'hand' : hand,
              'auto' : auto,
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
                  let _data = JSON.parse(data);
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
            }, "Page" , "{{ route('recruit.menu') }}" + '?'+ $.param(
                {name: search_name})
            );
        _page = 1;
        _count_records = 0;
        location.reload();
        
    });

      ajax_recruits(search_name, 1, _user, _hand, _auto);

      //===================================================================================
      //=====================POSTULANTS TABLE AND ROWS FUNCTIONS===========================
      //===================================================================================

      //BUILD TABLE FUNCTION - ELEMENTS FUNCTIONS
      window.tablebootstrap_filter = function tablebootstrap_filter( data ){
        var columns = [
            { 
              field: 'id', 
              valign: 'middle',
              checkbox: true,
              clickToSelect: false,
              width: 20,
              formatter : function(value,rowData,index) {    
                  var actions = '';
                  return actions;
                },
              class: 'frozencell',
            },
            {
              field: 'recruit_id', 
              title: "Accion",
              valign: 'middle',
              clickToSelect: false,
              width: 20,
              formatter : function(value,rowData,index) {    
                  var actions = '<a class="badge badge-primary recruit-edit" data-id="'+rowData.recruit_id+'" data-rpid="'+rowData.rp_id+'" data-index="'+index+'" href="#">Edit</a>'+
                                ' <input class="bulk-input-value" type="hidden" data-index="'+index+'" data-rpid="'+rowData.rp_id+'" data-recruit-id="'+rowData.recruit_id+'">'+
                                ' <a class="badge badge-danger recruit-delete" data-rpid="'+rowData.rp_id+'" data-positionid="'+rowData.pos_id+'" data-id="'+rowData.recruit_id+'" href="#">Delete</a>';
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
                  return actions;
                },
              class: 'frozencell recruit-created-at',
            },
            { field: 'user_name', title: "Recruiter", width: 75 , class: 'frozencell recruit-user-name'},
            { field: 'fullname', title: "Postulant", width: 75 , class: 'frozencell recruit-fullname'},
            {
              field: 'profile_link', 
              title: "Link",
              width: 50,
              formatter : function(value,rowData,index) { 
                var actions = '';

                actions += '<a id="show-recruit-link" class="badge badge-success btn-link-recruit '+( rowData.profile_link != null ? '' : 'd-none')+'" data-index="'+index+'" href="'+rowData.profile_link+'" target="_blank">Go to Link</a>\n';

                actions += '<a id="hide-recruit-link" class="badge badge-secondary button-disabled btn-link-recruit '+( rowData.profile_link == null ? '' : 'd-none')+'" data-index="'+index+'" disabled>No Link</a>\n';

                return actions;
                },
              class: 'frozencell',
            },
            { field: 'position_name', title: "Position", width: 75 , class: 'frozencell recruit-position-name'},
            { field: 'phone_number', title: "Phone", width: 75 , class: 'frozencell recruit-phone-number'},
            { field: 'email_address', title: "E-mail", width: 75 , class: 'frozencell recruit-email-address'},
            {
              field: 'file_path', 
              title: "CV",
              width: 50,
              formatter : function(value,rowData,index) {    
                var actions = '';

                actions += '<div class="btn-group mt-2 btn-upload-cv '+( rowData.file_path == null ? '' : 'd-none')+'" data-id="'+rowData.rp_id+'" data-positionid="'+rowData.recruit_id+'"> ';
                actions += '<label class="badge badge-secondary" for="cv-upload-evaluate-'+rowData.rp_id+'">Upload CV File</label>';
                actions += '<input type="file" class="custom-file-input cv-upload" id="cv-upload-evaluate-'+rowData.rp_id+'" data-id="'+rowData.rp_id+'" data-positionid="'+rowData.recruit_id+'" style="display:none;" >';
                actions += '</div>';

                actions += '<div class="btn-group btn-show-cv '+( rowData.file_path != null ? '' : 'd-none')+'" data-id="'+rowData.rp_id+'" data-positionid="'+rowData.recruit_id+'">';
                actions += '<a class="badge badge-success show-cv" href="'+rowData.file_path+'" data-id="'+rowData.rp_id+'" data-positionid="'+rowData.recruit_id+'" target="_blank">Download CV File</a>';
                actions += '<a href="#" class="badge badge-primary confirmation-upload-delete" data-id="'+rowData.rp_id+'" data-positionid="'+rowData.recruit_id+'"><i class="fas fa-trash"></i></a>';
                actions += '</div>';

                return actions;
                },
              class: 'frozencell',
            },
            {
              field: 'tech_qtn',
              title: "Tech Qtn",
              valign: 'middle',
              clickToSelect: false,
              width: 20,
              formatter : function(value,rowData,index) {    
                var actions = '';

                actions += '<a id="show-tech-link-'+rowData.rp_id+'" class="badge badge-warning btn-tech-recruit '+( rowData.tech_qtn != 'filled' ? '' : 'd-none')+'" data-index="'+index+'" data-id="'+rowData.recruit_id+'" href="#">Generate</a>\n';

                actions += '<a id="hide-tech-link-'+rowData.rp_id+'" class="badge badge-secondary button-disabled '+( rowData.tech_qtn == 'filled' ? '' : 'd-none')+'" data-index="'+index+'" data-id="'+rowData.recruit_id+'" disabled>Completed</a>\n';

                return actions;
                },
              class: 'frozencell',
            },
            {
              field: 'pos_id',
              title: "Outstanding",
              valign: 'middle',
              clickToSelect: false,
              width: 20,
              formatter : function(value,rowData,index) {    
                  var actions = '';
                  
                  actions += '<a class="badge badge-primary recruit-outstanding" data-outstanding="approve" data-positionid="'+rowData.pos_id+'" data-id="'+rowData.recruit_id+'" data-rpid="'+rowData.rp_id+'" data-fullname="'+rowData.fullname+'" href="#">YES</a>';
                  actions += ' <a class="badge badge-danger recruit-outstanding" data-outstanding="disapprove" data-positionid="'+rowData.pos_id+'" data-id="'+rowData.recruit_id+'" data-rpid="'+rowData.rp_id+'" data-fullname="'+rowData.fullname+'" href="#">NO</a>'

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

        //EVALUATE OUTSTANDING - (APPROVE - DISAPPROVE)
        $("table tbody").on('click', 'a.recruit-outstanding' , function(ev){
          ev.preventDefault();
          var id = $(this).data("id");
          var rpid = $(this).data("rpid");
          var fullname = $(this).data("fullname");
          var positionid = $(this).data("positionid");
          var outstanding = $(this).data("outstanding");
          var confirmed = true;

          if(outstanding=="disapprove"){
            confirmed = confirm("Are you sure you want to "+ (outstanding=="approve"?"APPROVE":"DISAPPROVE") +" this profile?");
          }
          
          if(confirmed){
            $.ajax({
                type:'POST',
                url: '{{ route("recruit.postulant.outstanding") }}',
                data: {id: id,rpid: rpid,positionid: positionid,outstanding: outstanding,fullname: fullname},
                headers: {
                  'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){
                  window.history.replaceState(
                        {edwin: "Fulltimeforce"}, 
                        "Page" , "{{ route('recruit.menu') }}" + '?'+ $.param({   
                          'rows' : 50,
                          'page' : 1,
                          'name' : $('#search-history-name').val(),
                          'tab'  : "{{ $tab }}",
                          'user' : $("#recruiter-action").children("option:selected").val(),
                          'hand' : $("#handmade-toggle").prop('checked'),
                          'auto' : $("#auto-toggle").prop('checked'),
                        })
                  );
                  location.reload();
                }
            });
          }
        });

        //DELETE POSTULANT - POSITION INFORMATION
        $("table tbody").on('click', 'a.recruit-delete' , function(ev){
          ev.preventDefault();
          var recruit_id = $(this).data("id");
          var position_id = $(this).data("positionid");
          var rp_id = $(this).data("rpid");

          var confirmed = confirm("Are you sure you want to DELETE this profile?");

          if(confirmed){
            $.ajax({
                type:'POST',
                url: '{{ route("recruit.postulant.delete") }}',
                data: {recruit_id : recruit_id,position_id: position_id,rp_id: rp_id},
                headers: {
                  'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){
                  window.history.replaceState(
                        {edwin: "Fulltimeforce"}, 
                        "Page" , "{{ route('recruit.menu') }}" + '?'+ $.param({   
                          'rows' : 50,
                          'page' : 1,
                          'name' : $('#search-history-name').val(),
                          'tab'  : "{{ $tab }}",
                          'user' : $("#recruiter-action").children("option:selected").val(),
                          'hand' : $("#handmade-toggle").prop('checked'),
                          'auto' : $("#auto-toggle").prop('checked'),
                        })
                  );
                  location.reload();
                }
            });
          }
        });
        
        //EDIT POSTULANT - POSITION INFORMATION
        $("table tbody").on('click', 'a.recruit-edit' , function(ev){
          ev.preventDefault();
          var recruit_id = $(this).data("id");
          var rp_id = $(this).data("rpid");
          var index = $(this).data("index");

          $('#btn-form-update').removeClass("d-none");
          $('#btn-form-cancel').removeClass("d-none");
          $('#btn-form-save').addClass("d-none");

          $.ajax({
              type:'POST',
              url: '{{ route("recruit.edit.get") }}',
              data: {recruit_id:recruit_id, rp_id:rp_id},
              headers: {
                'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success:function(data){
                let _data = JSON.parse(data)
                var recruit = _data.recruit[0];
                $('#fullname').val(recruit.fullname);
                $('#position_id').val(recruit.position_id);
                $('#platform').val(recruit.platform);
                $('#phone_number').val(recruit.phone_number);
                $('#email_address').val(recruit.email_address);
                $('#profile_link').val(recruit.profile_link);
                $('#recruit_id').val(recruit.recruit_id);
                $('#rp_id').val(recruit.rp_id);
                $('#index').val(index);
              }
          });
        });

        //CANCEL EDIT POSTULANT - POSITION INFORMATION FUNCTION
        $('#cancel_recruit').on('click' , function(ev){
          ev.preventDefault();
          closeEditProcess();
        });

        //UPDATE POSTULANT - POSITION INFORMATION
        $('#update_recruit').on('click' , function(ev){
          ev.preventDefault();
          var fullname      = $('#fullname').val();
          var position_id   = $('#position_id').val();
          var platform      = $('#platform').val();
          var phone_number  = $('#phone_number').val();
          var email_address = $('#email_address').val();
          var profile_link  = $('#profile_link').val();
          var recruit_id    = $('#recruit_id').val();
          var rp_id         = $('#rp_id').val();
          var index         = $('#index').val();

          var position_id_text = $( "#position_id option:selected" ).text();

          $.ajax({
              type:'POST',
              url: '{{ route("recruit.edit.update") }}',
              data: {fullname:fullname,
                     position_id:position_id,
                     platform:platform,
                     phone_number:phone_number,
                     email_address:email_address,
                     profile_link:profile_link,
                     recruit_id:recruit_id,
                     rp_id:rp_id,},
              headers: {
                'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success:function(data){
                let _data = JSON.parse(data)
                closeEditProcess();
                if(_data.state){
                    $('tr[data-index="'+index+'"]').find('td.recruit-fullname').text(fullname)
                    $('tr[data-index="'+index+'"]').find('td.recruit-position-name').text(position_id_text)
                    $('tr[data-index="'+index+'"]').find('td.recruit-phone-number').text(phone_number)
                    $('tr[data-index="'+index+'"]').find('td.recruit-email-address').text(email_address)
                    if(!profile_link){
                      $('tr[data-index="'+index+'"]').find('a#show-recruit-link').addClass("d-none");
                      $('tr[data-index="'+index+'"]').find('a#show-recruit-link').attr("href" , null);
                      $('tr[data-index="'+index+'"]').find('a#hide-recruit-link').removeClass("d-none");
                    }else{
                      $('tr[data-index="'+index+'"]').find('a#show-recruit-link').removeClass("d-none");
                      $('tr[data-index="'+index+'"]').find('a#show-recruit-link').attr("href" , profile_link);
                      $('tr[data-index="'+index+'"]').find('a#hide-recruit-link').addClass("d-none");
                    }

                    alert('POSTULANT edited successfully')
                }else{
                    alert('Need to complete all (*) fields at least')
                }
              }
          });
        });

        //CANCEL EDIT JQUERY FLOW
        function closeEditProcess(){
          $('#btn-form-update').addClass("d-none");
          $('#btn-form-cancel').addClass("d-none");
          $('#btn-form-save').removeClass("d-none");

          $('#fullname').val("");
          $('#position_id').val("");
          $('#platform').val("");
          $('#phone_number').val("");
          $('#email_address').val("");
          $('#profile_link').val("");
          $('#recruit_id').val("");
          $('#rp_id').val("");
        }

        //GENERATE TECHNICAL QUESTIONARY LINK
        $('.btn-tech-recruit').on('click', function (ev) {
            ev.preventDefault();
            var url = '{{ route("recruit.tech.signed" , ":id") }}';
            url = url.replace( ":id" , $(this).data("id") );
            $.ajax({
                type:'GET',
                url: url,
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){
                    $('#showURL').html(data);
                    
                    var el = document.createElement("textarea");
                    el.value = data;
                    el.style.position = 'absolute';                 
                    el.style.left = '-9999px';
                    el.style.top = '0';
                    el.setSelectionRange(0, 99999);
                    el.setAttribute('readonly', ''); 
                    document.body.appendChild(el);
                    
                    el.focus();
                    el.select();

                    var success = document.execCommand('copy')
                    if(success){
                        $(".alert-dismissible").slideDown(200, function() {
                                
                        });
                    }
                    setTimeout(() => {
                        $(".alert-dismissible").slideUp(500, function() {
                            document.body.removeChild(el);
                        });
                    }, 4000);
                }
            });
        });

      }

      //===================================================================================
      //=====================REGISTERED POSTULANTS BUTTON FUNCTION=========================
      //===================================================================================
      
      //REGISTERED POSTULANTS BUTTON FUNCTION
      $('#registered-recruit').on('click' , function(ev){
        ev.preventDefault();

        $("#registered-table").modal();
        $("#registered-ring").show();

        $("#registered-tableLabel").text("Search Result:");

        $.ajax({
            type:'GET',
            url: '{{ route("recruit.registeredlist") }}',
            headers: {
                'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(data){
                let _data = JSON.parse(data)
                $("#count-registered").html( _data.total );
                registeredtable_button( _data.rows, _data.positions );
                $("#registered-ring").hide();
            }
        });
      });

      //REGISTERED POSTULANTS BUTTON FUNCTION
      $('#pasar-filas').on('click' , function(ev){
        ev.preventDefault();

        $.ajax({
            type:'POST',
            url: '{{ route("recruit.pasarFilas") }}',
            headers: {
                'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(data){
                let _data = JSON.parse(data)
                console.log(_data)
            }
        });
      });
      

      //BUILD TABLE FUNCTION - ELEMENTS FUNCTIONS
      function registeredtable_button( data, positions ){
        var columns = [
            {
              field: 'id', 
              title: "Postulant",
              width: 50,
              formatter : function(value,rowData,index) { 
                var actions = '<div class="text-left">'+rowData.fullname+'</div>';
                return actions;
              },
              class: 'frozencell',
            },
            {
              field: 'fullname', 
              title: "Position",
              width: 50,
              formatter : function(value,rowData,index) { 
                var actions = '';

                actions += '<select class="form-control" id="registered-select-'+index+'" data-index="'+index+'" data-id="'+rowData.id+'">';
                actions += '<option value="">None</option>';

                positions.forEach(element => {                  
                  actions += '<option value="'+element.id+'">'+element.name+'</option>';
                });

                actions += '</select>';

                return actions;
              },
              class: 'frozencell',
            },
            { field: 'identification_number',
              title: "Accion",
              width: 20 ,
              formatter : function(value,rowData,index) { 
                var actions = '<a class="badge badge-primary registered-position-apply" data-id="'+rowData.id+'" data-index="'+index+'" href="#">Apply</a>';
                return actions;
              },
              class: 'frozencell',
            },
        ];

        //SET TABLE PROPERTIES
        $("#list-registered").bootstrapTable('destroy').bootstrapTable({
            height: undefined,
            columns: columns,
            data: data,
            theadClasses: 'table-dark',
            uniqueId: 'id'
        });

        //APPLY EXISTING POSTULANT TO POSITION
        $("table tbody").on('click', 'a.registered-position-apply' , function(ev){
          ev.preventDefault();

          var id    = $(this).data("id");
          var index = $(this).data("index");
          var select = $('#registered-select-'+index);
          var positionId = select.find(":selected").val();

          if(positionId){
            $.ajax({
                type:'POST',
                url: '{{ route("recruit.apply") }}',
                data: {id : id,positionId: positionId},
                headers: {
                  'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){
                  window.history.replaceState(
                        {edwin: "Fulltimeforce"}, 
                        "Page" , "{{ route('recruit.menu') }}" + '?'+ $.param({   
                          'rows' : 50,
                          'page' : 1,
                          'name' : $('#search-history-name').val(),
                          'tab'  : "{{ $tab }}",
                          'user' : $("#recruiter-action").children("option:selected").val(),
                          'hand' : $("#handmade-toggle").prop('checked'),
                          'auto' : $("#auto-toggle").prop('checked'),
                        })
                  );
                  location.reload();
                }
            });
          }else{
            alert('You MUST select a position.')
          }            
        });

      }

      //===================================================================================
      //=====================SEARCH HISTORY BUTTON FUNCTION================================
      //===================================================================================

      //SEARCH POSTULANTS HISTORY BUTTON FUNCTION
      $('#search-recruit').on('click' , function(ev){
        ev.preventDefault();
        search_name = $('#search-history-name').val();

        if(search_name){
          $("#search-table").modal();
          $("#search-ring").show();

          $("#search-tableLabel").text("Search Result: " + search_name);

          var params = {
              'name' : search_name,
          };

          $.ajax({
              type:'GET',
              url: '{{ route("recruit.searchlist") }}',
              data: $.param(params),
              headers: {
                  'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success:function(data){
                  let _data = JSON.parse(data)
                  $("#count-search").html( _data.total );
                  searchtable_button( _data.rows );
                  $("#search-ring").hide();
              }
          });
        }
      });

      //BUILD TABLE FUNCTION - ELEMENTS FUNCTIONS
      function searchtable_button( data ){
        var columns = [
            {
              field: 'id', 
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
            { field: 'fullname', title: "Postulant", class: 'frozencell'},
            { field: 'position_name', title: "Position", width: 75 , class: 'frozencell'},
            { field: 'reached', title: "Stage Reached", width: 75 , class: 'frozencell'},
            { 
              field: 'status', 
              title: "Status", 
              width: 75,
              class: 'frozencell',
              formatter : function(value,rowData,index) {    
                  var actions = '';
                  var status = '';
                  var badge = '';
                  switch(rowData.status){
                    case 'approve':
                      if(rowData.reached == 'Seleccionados'){
                        status = "SELECTED"
                        badge = 'badge-success';
                      }else{
                        status = 'IN EVALUATION';
                        badge = 'badge-primary';
                      }
                      break;
                    case 'disapprove':
                      status = 'DISAPPROVED';
                      badge = 'badge-danger';
                      break;
                  }
                  actions += '<span class="badge '+ badge + ' recruit-status" data-outstanding="approve" data-id="'+rowData.recruit_id+'">'+status+
                            '</span>';

                  return actions;
                },
            },
            { field: 'status', 
              title: "Accion", 
              width: 75 , 
              class: 'frozencell',
              formatter: function(value, rowData, index){
                var actions = '';
                var section_redirect = "";
                switch(rowData.reached){
                  case 'Postulantes':
                    section_redirect = '{{route("recruit.menu")}}?name='+rowData.fullname;
                    break;
                  case 'Perfiles Destacados':
                    section_redirect = '{{route("recruit.outstanding")}}?name='+rowData.fullname;
                    break;
                  case 'Pre-Seleccionados':
                    section_redirect = '{{route("recruit.preselected")}}?name='+rowData.fullname;
                    break;
                  case 'Para Evaluar':
                    section_redirect = '{{route("recruit.softskills")}}?name='+rowData.fullname;
                    break;
                  case 'Seleccionados':
                    section_redirect = '{{route("recruit.selected")}}?name='+rowData.fullname;
                    break;
                }
                if(rowData.status == 'disapprove'){
                  // section_redirect = '{{route("experts.beta")}}?search=true&name='+rowData.fullname;
                  section_redirect = '{{route("experts.home")}}?search=true&name='+rowData.fullname;
                }
                actions +='<a class="btn btn-success" href="'+section_redirect+'">View</a>';
                return actions;
              }
            },
        ];
        
        //SET TABLE PROPERTIES
        $("#list-search").bootstrapTable('destroy').bootstrapTable({
            height: undefined,
            columns: columns,
            data: data,
            theadClasses: 'table-dark',
            uniqueId: 'id'
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
                var _text = $('#search-history-name').val();
                var data = {
                        'offset': _records,
                        'rows': _records,
                        'page' : _page , 
                        'tab'  : "{{ $tab }}",
                        'name' : _text,
                        'user' : $("#recruiter-action").children("option:selected").val(),
                        'hand' : $("#handmade-toggle").prop('checked'),
                        'auto' : $("#auto-toggle").prop('checked'),
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
                        $("#list-recruits").bootstrapTable('append', _data.rows );
                        
                        _count_records = _count_records + _data.rows.length;
                        $("#count-recruit").html( _count_records );
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

    //UPLOAD FILE FUNCTION - INCLUDING PROGRESS BAR
    $('body').on('change' , '.cv-upload' , function(ev){
        var file = this.files[0];
        var rp_id = $(this).data("id");
        var position_id = $(this).data("positionid");
        var bar = $('.progress-bar');

        var _formData = new FormData();
        _formData.append('file', file);
        _formData.append('rp_id', rp_id);
        _formData.append('position_id', position_id);

        $.ajax({
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = (evt.loaded / evt.total) * 100;
                          bar.width(percentComplete+'%');
                    }
                }, false);
              return xhr;
            },
            type:'POST',
            url: "{{ route('recruit.postulant.upload.cv') }}",
            headers: {
                'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            contentType: false,
            cache: false,
            processData: false,
            data: _formData,
            success:function(data){
                $('.btn-upload-cv[data-id="'+rp_id+'"][data-positionid="'+position_id+'"]').addClass("d-none");
                $('.btn-show-cv[data-id="'+rp_id+'"][data-positionid="'+position_id+'"]').removeClass("d-none");
                $('.show-cv[data-id="'+rp_id+'"][data-positionid="'+position_id+'"]').attr("href" , data.file);
                bar.width('0%');
            }
        });
    })

    //SET VALUES FOR CV FILE DELETE MODAL
    $("body").on('click' , '.confirmation-upload-delete' , function(ev){
        ev.preventDefault();
        var rp_id = $(this).data("id");
        var position_id = $(this).data("positionid");

        $("#delete-audio-rp-id").val(rp_id);
        $("#delete-audio-position-id").val(position_id);

        $("#delete-audio").modal();

    })

    //SET VALUES FOR CV FILE DELETE MODAL (NULL)
    $('#delete-audio').on('hidden.bs.modal', function (e) {
      $("#delete-audio-rp-id").val("");
      $("#delete-audio-position-id").val("");
    })

    //DELETE CV FILE FUNCTION
    $("#deleteAudio").on('click' , function(){
        $.ajax({
            type:'POST',
            url: "{{ route('recruit.postulant.delete.cv') }}",
            headers: {
                'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                rp_id : $("#delete-audio-rp-id").val(),
                position_id: $("#delete-audio-position-id").val()
            },
            success:function(data){
                var rp_id = $("#delete-audio-rp-id").val();
                var position_id = $("#delete-audio-position-id").val();
                $('.btn-upload-cv[data-id="'+rp_id+'"][data-positionid="'+position_id+'"]').removeClass("d-none");
                $('.btn-show-cv[data-id="'+rp_id+'"][data-positionid="'+position_id+'"]').addClass("d-none");
                $("#delete-audio").modal('hide');
            }
        });
    });

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
                    window.history.replaceState(
                        {edwin: "Fulltimeforce"}, 
                        "Page" , "{{ route('recruit.menu') }}" + '?'+ $.param({   
                          'rows' : 50,
                          'page' : 1,
                          'name' : $('#search-history-name').val(),
                          'tab'  : "{{ $tab }}",
                          'user' : $("#recruiter-action").children("option:selected").val(),
                          'hand' : $("#handmade-toggle").prop('checked'),
                          'auto' : $("#auto-toggle").prop('checked'),
                        })
                    );
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

    //FILE INPUT CHANGE NAME FUNCTION
    $('#file_path').on('change',function(ev){
      var fileName = $(this).val();
      $(this).next('.custom-file-label').html(ev.target.files[0].name);
    });


    $('.filter-element').change(function(ev) {
      ev.preventDefault();

      var select_option   = $("#recruiter-action");
      var auto_option     = $("#auto-toggle");
      var handmade_option = $("#handmade-toggle");

      var select_value = select_option.children("option:selected").val();

      if(select_value){

        //ENABLE AND DISABLE FLOW
        auto_option.bootstrapToggle('enable');
        auto_option.bootstrapToggle('off', true);
        auto_option.bootstrapToggle('disable');

        handmade_option.bootstrapToggle('enable');
        handmade_option.bootstrapToggle('on', true);
        handmade_option.bootstrapToggle('disable');
    
      }else{

        auto_option.bootstrapToggle('enable');
        handmade_option.bootstrapToggle('enable');

        if(!auto_option.prop('checked') && !handmade_option.prop('checked')){
          if($(this).attr('id') == 'handmade-toggle'){
            auto_option.bootstrapToggle('on', true);
          }

          if($(this).attr('id') == 'auto-toggle'){
            handmade_option.bootstrapToggle('on', true);
          }
        }
      }

      var _total_records = 0;
      var _count_records = 0;
      var _before_rows = 0;
      var _dataRows = [];

      $("#list-recruits").empty();
      $(".lds-ring").show();

      var params = {
          'rows' : 50,
          'page' : 1,
          'name' : '',
          'tab'  : "{{ $tab }}",
          'user' : select_value,
          'auto' : auto_option.prop('checked'),
          'hand' : handmade_option.prop('checked'),
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
              let _data = JSON.parse(data);
              
              _total_records = _data.totalNotFiltered;
              _before_rows = _data.total;
              _count_records = _count_records + _data.rows.length;
              $("#count-recruit").html( _count_records );
              _dataRows = _data.rows;
              tablebootstrap_filter( _data.rows );
              $("html, body").animate({ scrollTop: 0 }, "slow");
              $(".lds-ring").hide();
              $('input[name="btSelectAll"]').click();
          }
      });
    })
</script>

@endsection