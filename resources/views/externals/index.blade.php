@extends('layouts.app' , ['controller' => 'position'])

@section('styles')

<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap-table/bootstrap-table.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.css') }}"/>
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

<style>
  #overlay {
    background-color: rgba(0, 0, 0, 0.45);
    z-index: 999;
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    display: none;
  }

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
  .tech{
      display: inline-block;
      padding: 5px;
      
      border-radius: 5px;
      margin-right: 5px;
      margin-bottom: 5px;
  }
  .tech_adv{
      background-color: #536afc;
      color: white;
  }
  .tech_int{
      background-color: #e8ff63;
      color: black;
  }
  .tech_bsc{
      background-color: gray;
      color: white;
  }
  #list-expert-audios{
      background-color: #03132e;
      padding: 5px;
      text-align: center;
  }
  .info-speed-audio{
      font-size: 12px;
      margin-bottom: 5px;
  }
  .expert-audio{
      margin: 5px 5px 5px 5px;
  }
  #new-recruit-form td{
      border:none;
  }
  #add-file-label{
    align-items: start!important;
    justify-content: start!important;
  }
  .ttip {
    position: relative;
    display: inline-block;
  }
  .ttip .ttiptext {
    font-size: 9px;
    visibility: hidden;
    width: 120px;
    background-color: #555;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 5px 0;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    margin-left: -60px;
    opacity: 0;
    transition: opacity 0.3s;
  }

  .ttip .ttiptext::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #555 transparent transparent transparent;
  }

  .ttip:hover .ttiptext {
    visibility: visible;
    opacity: 1;
  }
</style>
@endsection
 
@section('content')

      <!--======================================================================================================================  
    ==================================================EXPERT INFORMATION MODAL================================================    
    =======================================================================================================================-->
    <div class="modal" id="info-expert" tabindex="-1" role="dialog" aria-labelledby="interviews-expertLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="interviews-expertLabel"><span class="show_expert_name">{expert Name}</span> - INFO</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Basic Info -->
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="font-weight-bold">Name</label>
                            <h5 class="show_expert_name"></h5>
                        </div>
                        <div class="col-sm-6">
                            <label class="font-weight-bold">Email</label>
                            <h5 class="show_expert_email"></h5>
                        </div>
                    </div>
                    <hr/>
                    <!-- Additional info -->
                    <div class="row">
                        <div class="col-6 col-sm-2">
                            <label class="font-weight-bold">Age</label>
                            <p class="show_expert_age"></p>
                        </div>
                        <div class="col-6 col-sm-3">
                            <label class="font-weight-bold">Phone</label>
                            <p class="show_expert_phone"></p>
                        </div>
                        <div class="col-6 col-sm-3">
                            <label class="font-weight-bold">Availability</label>
                            <p class="show_expert_availability"></p>
                        </div>
                        <div class="col-6 col-sm-2">
                            <label class="font-weight-bold">Salary</label>
                            <p class="show_expert_salary"></p>
                        </div>
                        <div class="col-6 col-sm-2">
                            <label class="font-weight-bold">FCE</label>
                            <p class="show_expert_fce"></p>
                        </div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-sm-6">
                        <label class="font-weight-bold">Persona Ambiente</label>
                        <select class="form-control show_expert_crit_1" data-crit="1">
                        </select>
                      </div>
                      <div class="col-12 col-sm-6">
                        <label class="font-weight-bold">Autoconfianza</label>
                        <select class="form-control show_expert_crit_2" data-crit="2">
                        </select>
                      </div>
                    </div>

                    <hr/>
                    
                    <!-- Links -->
                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <label class="font-weight-bold">Links</label>
                            <p>
                                <a class="show_expert_github" href="#"><button class="btn btn-primary">Github</button></a>
                                <a class="show_expert_linkedin" href="#"><button class="btn btn-primary">LinkedIn</button></a>
                            </p>
                        </div>
                        <div id="list-expert-audios" class="col-12 col-sm-8 dark-player">
                            <div class="row"></div>
                        </div>
                    </div>
                    <hr/>
                    <!-- English Proficiency -->
                    <div class="row">
                        <div class="col-12">
                            <h5>English</h5>
                        </div>
                        <div class="col-md-4">
                            <label class="font-weight-bold">Speaking</label>
                            <div class="progress">
                                <div class="progress-bar show_expert_eng_speak" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" ></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="font-weight-bold">Writing</label>
                            <div class="progress">
                                <div class="progress-bar show_expert_eng_write" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" ></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="font-weight-bold">Reading</label>
                            <div class="progress">
                                <div class="progress-bar show_expert_eng_read" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" ></div>
                            </div>
                        </div>
                    </div>
                    <!-- Technologies -->
                    <hr/>
                    <div class="row">
                        <div class="col-12">
                            <h5>Techonologies</h5>
                        </div>
                        <hr>
                        <div class="col-12">
                            <h6>Advanced</h6>
                            <p class="show_expert_adv_tech"></p>
                        </div>
                        <div class="col-12">
                            <h6>Intermediate</h6>
                            <p class="show_expert_int_tech"></p>
                        </div>
                        <div class="col-12">
                            <h6>Basic</h6>
                            <p class="show_expert_bsc_tech"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-6">
                          <button class="btn btn-primary btn-update-expert" data-id="" style="width:100%;">Save</button>
                        </div>
                        <div class="col-3">
                          <button class="btn btn-outline-secondary btn-prev-expert" data-id="" data-index=""><</button>
                        </div>
                        <div class="col-3">
                          <button class="btn btn-outline-secondary btn-next-expert" data-id="" data-index="">></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

      <!-- NEW EXTERNAL MODAL -->

      <div class="modal fade" id="new_external_modal" tabindex="-1" role="dialog" aria-labelledby="create-external" aria-hidden="true">
          <div class="modal-dialog" role="document">
          </div>
      </div>

      <div class="row">
          <!--
            PROGRESS BAR SECTION
          -->
          <div class="col-12 mb-3">
            <div class="progress">
              <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
          <!--
          SAVE POSTULANT FORM
          -->
          <div class="col-12 text-right p-2 pr-3">
              <a id="new_external_btn" class="btn btn-success" href="#">New External</a>
          </div>
          
          <!--
          TOTAL RECORDS SECTION
          -->
          <div class="col-8 text-left">
            <p>Records: <span id="count-recruit"></span></p>
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
              <table class="table row-border order-column" id="list-externals" data-toggle="list-externals"> 
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
      var _idMap = [];
      var _page = 1;
      
      var search_name = "{{ $search_name }}";

      var _user =  "{{ $_user }}";
      var _hand =  null;
      var _auto =  null;

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
              'user' : user,
              'hand' : hand,
              'auto' : auto,
          };

          $.ajax({
              type:'GET',
              url: '{{ route("externals.list") }}',
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
                  for (var i = 0; _dataRows.length > i ; i++) {
                    _idMap.push(_dataRows[i].id);
                  }
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
            }, "Page" , "{{ route('externals.menu') }}" + '?'+ $.param(
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
            { 
              field: 'fullname', 
              title: "Postulant",  
              class: 'frozencell recruit-fullname',
              formatter: function(value, rowData, index){
                var cell = '';
                cell += '<a href="#" class="btn-show" data-id="'+rowData.recruit_id+'" data-name="'+rowData.fullname+'" data-index="'+index+'">'+rowData.fullname+'</a>';
                return cell;
              }
            },
            { field: 'agent_name', title: "Agent", width: 120, class: 'frozencell recruit-agent-name'},
            { field: 'position_name', title: "Position", width: 75 , class: 'frozencell recruit-position-name'},
            { field: 'phone_number', title: "Phone", width: 75 , class: 'frozencell recruit-phone-number'},
            { field: 'email_address', title: "E-mail", width: 75 , class: 'frozencell recruit-email-address'},
            {
              field: 'test_status',
              title: "Test",
              width: 75,
              class: 'frozencell',
              formatter : function(value,rowData,index) { 
                  var actions = "";
                  if(rowData.test_status == 0){
                    actions = '<span>---</span>';
                  }else{
                    var min = Math.min(rowData.completeness_score,rowData.code_score,rowData.design_score,rowData.technologies_score,rowData.readme_score);
                    actions = "<div class='ttip'>"
                    + rankString(min)
                    + "<span class='ttiptext'>"
                    + "Completeness: "+rankInitial(rowData.completeness_score)+"<br>"
                    + "Clean Code: "+rankInitial(rowData.code_score)+"<br>"
                    + "Design Quality: "+rankInitial(rowData.design_score)+"<br>"
                    + "Technologies: "+rankInitial(rowData.technologies_score)+"<br>"
                    + "Readme: "+rankInitial(rowData.readme_score)
                    + "</span>"
                    + "</div>";
                  }
                  return actions;
                },
            },
            {
              field: 'file_path', 
              title: "CV",
              width: 50,
              class: 'frozencell',
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
            },
        ];
        
        //SET TABLE PROPERTIES
        $("#list-externals").bootstrapTable('destroy').bootstrapTable({
            height: undefined,
            columns: columns,
            data: data,
            theadClasses: 'table-dark',
            uniqueId: 'id'
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
                        "Page" , "{{ route('externals.menu') }}" + '?'+ $.param({   
                          'rows' : 50,
                          'page' : 1,
                          'name' : $('#search-history-name').val(),
                          'user' : $("#recruiter-action").children("option:selected").val(),
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

          $.ajax({
              type:'POST',
              data: {id: recruit_id, rp_id: rp_id},
              url: '{{route("external.save.popup")}}',
              headers: {
                'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success:function(data){
                console.log(data);
                $("#new_external_modal > .modal-dialog").html(data);
                $("#new_external_modal").modal();
              }
          });
        });

        //SHOW RECRUIT INFOMATION MODAL
        $("table tbody").on("click", "a.btn-show",function(ev){
            ev.preventDefault();
            var recruitId = $(this).attr("data-id");
            var index = $(this).attr("data-index");
            $.ajax({
                type:"POST",
                url: '{{ route("experts.btn.show") }}',
                data:{id: recruitId},
                headers: {
                  'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){
                    var recruit = data.recruit;
                    var age = "-";

                    if(recruit.birthday){
                        var date = new Date(recruit.birthday).getTime();
                        var now = Date.now();

                        var age_time = new Date(now-date);
                        age = Math.abs(age_time.getUTCFullYear() - 1970);
                    }
                    
                    $(".show_expert_name").html(recruit.fullname)
                    $(".show_expert_email").html(recruit.email_address);
                    $(".show_expert_age").html(age);
                    $(".show_expert_phone").html(recruit.phone_number);
                    $(".show_expert_availability").html(recruit.availability);
                    $(".show_expert_salary").html((recruit.type_money == 'sol' ? 'S/' : '$') + ' ' +(recruit.salary!=null?recruit.salary:0));
                    $(".show_expert_fce").html(recruit.fce_overall);
                    $("a.show_expert_linkedin").attr("href",(recruit.linkedin!=undefined?recruit.linkedin:"#"));
                    $("a.show_expert_linkedin").html((recruit.linkedin!=undefined?'<button class="btn btn-primary">Linkedin</button>':''));
                    $("a.show_expert_github").attr("href",(recruit.github!=undefined?recruit.github:"#"));
                    $("a.show_expert_github").html((recruit.github!=undefined?'<button class="btn btn-primary">Github</button>':''));
                    $(".show_expert_eng_speak").css("width",(recruit.english_speaking=="advanced"?"100%":recruit.english_speaking=="intermediate"?"70%":recruit.english_speaking=="basic"?"30%":"0%"));
                    $(".show_expert_eng_speak").html(recruit.english_speaking);

                    $(".show_expert_eng_write").html(recruit.english_writing);
                    $(".show_expert_eng_write").css("width",(recruit.english_writing=="advanced"?"100%":recruit.english_writing=="intermediate"?"70%":recruit.english_writing=="basic"?"30%":"0%"));

                    $(".show_expert_eng_read").html(recruit.english_reading);
                    $(".show_expert_eng_read").css("width",(recruit.english_reading=="advanced"?"100%":recruit.english_reading=="intermediate"?"70%":recruit.english_reading=="basic"?"30%":"0%"));
                    
                    var audioHtml='';
                    if(recruit.audio_path){
                      audioHtml+='<div class="col-12"><div class="expert-audio" data-index="'+index+'">';
                      audioHtml+='<p style="color:white; text-align: left">Audio 1</p>'
                      audioHtml += '<a href="#" class="mr-1 btn btn-light info-speed-audio" data-speed="1">x1.00</a><a href="#" class="mr-1 btn btn-light info-speed-audio" data-speed="1.25">x1.25</a> <a href="#" class="mr-1 btn btn-light info-speed-audio" data-speed="1.5">x1.5</a> <a href="#" class="mr-1 btn btn-light info-speed-audio" data-speed="1.75">x1.75</a> <a href="#" class="mr-1 btn btn-light info-speed-audio" data-speed="2">x2.0</a>'
                      audioHtml += '<audio id="info-audio-player-'+index+'" src="'+recruit.audio_path+'" controls></audio></td>';
                      audioHtml+='</div></div>';
                    }
                    $("#list-expert-audios>.row").html(audioHtml);

                    var crit1Html = "";
                    crit1Html += '<option value="" '+(recruit.crit_1 == null ? 'selected':'')+'>None</option>';
                    crit1Html += '<option value="excellent" '+(recruit.crit_1 == 'excellent' ? 'selected':'')+'>Excellent</option>';
                    crit1Html += '<option value="efficient" '+(recruit.crit_1 == 'efficient' ? 'selected':'')+'>Efficient</option>';
                    crit1Html += '<option value="inefficient" '+(recruit.crit_1 == 'inefficient' ? 'selected':'')+'>Inefficient</option>';
                    crit1Html += '<option value="lower" '+(recruit.crit_1 == 'lower' ? 'selected':'')+'>Lower than expected</option>';

                    $(".show_expert_crit_1").html(crit1Html);

                    var crit2Html = "";
                    crit2Html += '<option value="" '+(recruit.crit_2 == null ? 'selected':'')+'>None</option>';
                    crit2Html += '<option value="excellent" '+(recruit.crit_2 == 'excellent' ? 'selected':'')+'>Excellent</option>';
                    crit2Html += '<option value="efficient" '+(recruit.crit_2 == 'efficient' ? 'selected':'')+'>Efficient</option>';
                    crit2Html += '<option value="inefficient" '+(recruit.crit_2 == 'inefficient' ? 'selected':'')+'>Inefficient</option>';
                    crit2Html += '<option value="lower" '+(recruit.crit_2 == 'lower' ? 'selected':'')+'>Lower than expected</option>';
                    $(".show_expert_crit_2").html(crit2Html);

                    var adv_tech = [];
                    var int_tech = [];
                    var bsc_tech = [];
                    for(i=0;data.advanced.length > i; i++){
                        var span = '<span class="tech tech_adv">'+data.advanced[i]+'</span>';
                        adv_tech.push(span);
                    }
                    for(i=0;data.intermediate.length > i; i++){
                        var span = '<span class="tech tech_int">'+data.intermediate[i]+'</span>';
                        int_tech.push(span);
                    }
                    for(i=0;data.basic.length > i; i++){
                        var span = '<span class="tech tech_bsc">'+data.basic[i]+'</span>';
                        bsc_tech.push(span);
                    }
                    $(".show_expert_adv_tech").html(adv_tech);
                    $(".show_expert_int_tech").html(int_tech);
                    $(".show_expert_bsc_tech").html(bsc_tech);
                    $(".btn-update-expert").attr("data-id",recruit.id);
                    $(".btn-prev-expert").attr("data-id",recruit.id).attr("data-index",index);
                    $(".btn-next-expert").attr("data-id",recruit.id).attr("data-index",index);

                    $("#info-expert").modal();
                }
            });
        });
      }

      $("#new_external_btn").on('click',function(ev){
        ev.preventDefault();
        $.ajax({
          type:'POST',
          data: {},
          url: '{{route("external.save.popup")}}',
          headers: {
            'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success:function(data){
            $("#new_external_modal > .modal-dialog").html(data);
            $("#new_external_modal").modal();
          },
        });
      });

      $("#new_external_modal").on('click','#save_recruit',function(ev){
        ev.preventDefault();
        console.log("verify form content...");
        $("#new-recruit-form").submit();
      })


      //==========AUDIO SPEED BUTTON FUNCTION
      $("body").on('click' , 'a.speed-audio' , function(ev){
          ev.preventDefault();
          var speed = $(this).data("speed");
          var index = $(this).parent().parent().data("audio");
          console.log( parseFloat( speed ) , speed )
          document.getElementById("audio-player-"+index).playbackRate = parseFloat(speed);
      })

      //==========SHOW EXPERT - PREV BUTTON FUNCTION
      $("#info-expert").on("click",".btn-prev-expert",function(ev){
        ev.preventDefault();
        var id = $(this).attr("data-id");
        var index = $(this).attr("data-index");
        index = parseInt(index) - 1;
        var prev = getPrevId(id);
        if(prev != "-"){
          loadModalExpert(prev, index);
        }
      });

      //==========SHOW EXPERT - NEXT BUTTON FUNCTION
      $("#info-expert").on("click",".btn-next-expert",function(ev){
        ev.preventDefault();
        var id = $(this).attr("data-id");
        var index = $(this).attr("data-index");
        index = parseInt(index) + 1;
        var next = getNextId(id);
        if(next!="-"){
          loadModalExpert(next, index);
        }
      });

      //==========SHOW EXPERT - PREV BUTTON FUNCTION (ARROW)
      $("#info-expert").on("keydown",function(ev){
        if(ev.keyCode == 37){
          var id = $(".btn-prev-expert").attr("data-id");
          var index = $(".btn-prev-expert").attr("data-index");
          index = parseInt(index) - 1;
          var prev = getPrevId(id);
          if(prev != "-"){
            loadModalExpert(prev, index);
          }
        }
      });

      //==========SHOW EXPERT - NEXT BUTTON FUNCTION (ARROW)
      $("#info-expert").on("keydown",function(ev){
        if(ev.keyCode == 39){
          var id = $(".btn-next-expert").attr("data-id");
          var index = $(".btn-next-expert").attr("data-index");
          index = parseInt(index) + 1;
          var next = getNextId(id);
          if(next!="-"){
            loadModalExpert(next, index);
          }
        }      
      });

      //==========NEXT/PREV MODAL - AUDIO SPEED BUTTON FUNCTION
      $("#info-expert").on('click' , 'a.info-speed-audio' , function(ev){
          ev.preventDefault();
          var speed = $(this).data("speed");
          var index = $(this).parent().data("index");
          console.log( parseFloat( speed ) , speed )
          document.getElementById("info-audio-player-"+index).playbackRate = parseFloat(speed);
      })

      //==========UPDATE EXPERT INFORMATION ON MODAL
      $("#info-expert").on('click' , 'button.btn-update-expert' , function(ev){
        ev.preventDefault();
        var id = $(this).attr("data-id");        
        var crit_1 = $(".show_expert_crit_1").val();
        var crit_2 = $(".show_expert_crit_2").val();
        var data = {
          id: id,
          crit_1: crit_1,
          crit_2: crit_2,
        };

        $.ajax({
          type:"POST",
          url: '{{ route("experts.popup.edit") }}',
          data: data,
          headers: {
            'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success:function(data){
            console.log("success");
            location.reload();
          },
        });
      }); 

      function rankString(rank){
        if(rank < 0 || rank > 5){
          return "INVALID SCORE";
        }
        var ranks = ['Fail', 'Trainee', 'Junior', 'Middle', 'Senior', 'Rockstar'];
        return ranks[rank];
      }
      function rankInitial(rank){
        if(rank < 0 || rank > 5){
          return "??";
        }
        var ranks = ['F', 'T', 'J', 'M', 'S', 'R'];
        return ranks[rank];
      }

      //==========NEXT/PREV MODAL - LOAD EXPERT INFORMATION FUNCTION
      function loadModalExpert(id, index){
        $.ajax({
          type:"POST",
          url: '{{ route("experts.btn.show") }}',
          data:{id: id},
          headers: {
              'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success:function(data){
              setInfoModal(data);
          }
        });
      }

      //==========GET NEXT ID AUXILIARY FUNCTION
      function getNextId(id){
        var currIdFound = false;
        for (var i = 0; i < _idMap.length; i++) {
          if(currIdFound){
            return _idMap[i];
          }
          if (_idMap[i]==id) {
            currIdFound = true;
          }
        }
        return "-";
      }

      //==========GET PREV ID AUXILIARY FUNCTION
      function getPrevId(id){
        for (var i = 0; i < _idMap.length; i++) {
          if (_idMap[i]==id && _idMap[0] != id) {
            i--;
            return _idMap[i];
          }else{
            if(i == _idMap.length-1){
              return "-";
            }
          }
        }
      }

      //==========POPULATE INFO ON MODAL AUXILIARY FUNCTION
      function setInfoModal(data){
        var recruit = data.recruit;
        var age = "-";

        if(recruit.birthday){
            var date = new Date(recruit.birthday).getTime();
            var now = Date.now();

            var age_time = new Date(now-date);
            age = Math.abs(age_time.getUTCFullYear() - 1970);
        }

        $(".show_expert_name").html(recruit.fullname)
        $(".show_expert_email").html(recruit.email_address);
        $(".show_expert_age").html(age);
        $(".show_expert_phone").html(recruit.phone_number);
        $(".show_expert_availability").html(recruit.availability);
        $(".show_expert_salary").html((recruit.type_money == 'sol' ? 'S/' : '$')+' '+(recruit.salary!=null?recruit.salary:0));
        $(".show_expert_fce").html(recruit.fce_overall);
        $("a.show_expert_linkedin").attr("href",(recruit.linkedin!=undefined?recruit.linkedin:"#"));
        $("a.show_expert_linkedin").html((recruit.linkedin!=undefined?'<button class="btn btn-primary">Linkedin</button>':''));
        $("a.show_expert_github").attr("href",(recruit.github!=undefined?recruit.github:"#"));
        $("a.show_expert_github").html((recruit.github!=undefined?'<button class="btn btn-primary">Github</button>':''));
        $(".show_expert_eng_speak").css("width",(recruit.english_speaking=="advanced"?"100%":recruit.english_speaking=="intermediate"?"70%":recruit.english_speaking=="basic"?"30%":"0%"));
        $(".show_expert_eng_speak").html(recruit.english_speaking);

        $(".show_expert_eng_write").html(recruit.english_writing);
        $(".show_expert_eng_write").css("width",(recruit.english_writing=="advanced"?"100%":recruit.english_writing=="intermediate"?"70%":recruit.english_writing=="basic"?"30%":"0%"));

        $(".show_expert_eng_read").html(recruit.english_reading);
        $(".show_expert_eng_read").css("width",(recruit.english_reading=="advanced"?"100%":recruit.english_reading=="intermediate"?"70%":recruit.english_reading=="basic"?"30%":"0%"));
        
        var html='';
        if(recruit.audio_path){
                html+='<div class="col-12"><div class="expert-audio" data-index="'+index+'">';
                html+='<p style="color:white; text-align: left">Audio 1</p>'
                html += '<a href="#" class="mr-1 btn btn-light info-speed-audio" data-speed="1">x1.00</a><a href="#" class="mr-1 btn btn-light info-speed-audio" data-speed="1.25">x1.25</a> <a href="#" class="mr-1 btn btn-light info-speed-audio" data-speed="1.5">x1.5</a> <a href="#" class="mr-1 btn btn-light info-speed-audio" data-speed="1.75">x1.75</a> <a href="#" class="mr-1 btn btn-light info-speed-audio" data-speed="2">x2.0</a>'
                html += '<audio id="info-audio-player-'+index+'" src="'+recruit.audio_path+'" controls></audio></td>';
                html+='</div></div>';
        }
        $("#list-expert-audios>.row").html(html);

        var crit1Html = "";
        crit1Html += '<option value="" '+(recruit.crit_1 == null ? 'selected':'')+'>None</option>';
        crit1Html += '<option value="excellent" '+(recruit.crit_1 == 'excellent' ? 'selected':'')+'>Excellent</option>';
        crit1Html += '<option value="efficient" '+(recruit.crit_1 == 'efficient' ? 'selected':'')+'>Efficient</option>';
        crit1Html += '<option value="inefficient" '+(recruit.crit_1 == 'inefficient' ? 'selected':'')+'>Inefficient</option>';
        crit1Html += '<option value="lower" '+(recruit.crit_1 == 'lower' ? 'selected':'')+'>Lower than expected</option>';

        $(".show_expert_crit_1").html(crit1Html);

        var crit2Html = "";
        crit2Html += '<option value="" '+(recruit.crit_2 == null ? 'selected':'')+'>None</option>';
        crit2Html += '<option value="excellent" '+(recruit.crit_2 == 'excellent' ? 'selected':'')+'>Excellent</option>';
        crit2Html += '<option value="efficient" '+(recruit.crit_2 == 'efficient' ? 'selected':'')+'>Efficient</option>';
        crit2Html += '<option value="inefficient" '+(recruit.crit_2 == 'inefficient' ? 'selected':'')+'>Inefficient</option>';
        crit2Html += '<option value="lower" '+(recruit.crit_2 == 'lower' ? 'selected':'')+'>Lower than expected</option>';
        $(".show_expert_crit_2").html(crit2Html);

        var adv_tech = [];
        var int_tech = [];
        var bsc_tech = [];
        for(i=0;data.advanced.length > i; i++){
            var span = '<span class="tech tech_adv">'+data.advanced[i]+'</span>';
            adv_tech.push(span);
        }
        for(i=0;data.intermediate.length > i; i++){
            var span = '<span class="tech tech_int">'+data.intermediate[i]+'</span>';
            int_tech.push(span);
        }
        for(i=0;data.basic.length > i; i++){
            var span = '<span class="tech tech_bsc">'+data.basic[i]+'</span>';
            bsc_tech.push(span);
        }
        $(".show_expert_adv_tech").html(adv_tech);
        $(".show_expert_int_tech").html(int_tech);
        $(".show_expert_bsc_tech").html(bsc_tech);
        $(".btn-update-expert").attr("data-id",recruit.id);
        $(".btn-prev-expert").attr("data-id",recruit.id).attr("data-index",index);
        $(".btn-next-expert").attr("data-id",recruit.id).attr("data-index",index);
      }

        $(document).on("keydown", "form", function(event) { 
            return event.key != "Enter";
        });

        $('.change-money').on('click' , function(ev){
            ev.preventDefault();
            var type = $(this).data("money");
            var label = $(this).html();
            $(this).parent().parent().find('button').html(label);
            $("#type_money").val(type);
        })

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
                        'name' : _text,
                        'user' : $("#recruiter-action").children("option:selected").val(),
                        'hand' : $("#handmade-toggle").prop('checked'),
                        'auto' : $("#auto-toggle").prop('checked'),
                };
                $(".lds-ring").show();
                $.ajax({
                    type:'GET',
                    url: '{{ route("externals.list") }}',
                    data: $.param(data),
                    headers: {
                        'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(data){

                        let _data = JSON.parse(data);
                        _before_rows = _data.total;
                        for (var i = 0; _dataRows.length > i ; i++) {
                          _idMap.push(_dataRows[i].id);
                        }
                        $("#list-externals").bootstrapTable('append', _data.rows );
                        
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

    //FILE INPUT CHANGE NAME FUNCTION
    $("#new_external_modal").on('change','#file_path',function(ev){
      console.log("file changed");
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

      $("#list-externals").empty();
      $(".lds-ring").show();

      var params = {
          'rows' : 50,
          'page' : 1,
          'name' : '',
          'user' : select_value,
          'auto' : auto_option.prop('checked'),
          'hand' : handmade_option.prop('checked'),
      };

      $.ajax({
          type:'GET',
          url: '{{ route("externals.list") }}',
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