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
    DELETE AUDIO MODAL
    -->
    <div class="modal fade" id="delete-audio" tabindex="-1" role="dialog" aria-labelledby="delete-audioLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="delete-audioLabel">Delete audio</h5>
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

    <!--
    SHOW AUDIO MODAL
    -->
    <div class="modal fade" id="show-audio" tabindex="-1" role="dialog" aria-labelledby="show-audioLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    
                    <audio src="" controls autoplay id="audio-play"></audio>
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
    SHOW TEXT BLOCK MODAL
    -->
    <div class="modal fade" id="show_block" tabindex="-1" role="dialog" aria-labelledby="show-blockLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="show-blockLabel">NOTES - <span id="show_block_name"></span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
              <div class="form-group">
                <label for="show_block_textarea" class="col-form-label">Take notes:</label>
                <textarea class="form-control" id="show_block_textarea" style="height: 300px;"></textarea>
                <input type="hidden" id="show_block_id">
                <input type="hidden" id="show_block_rpid">
                <input type="hidden" id="show_block_fullname">
                <input type="hidden" id="show_block_positionId">
              </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="show_block_save">Save</button>
        </div>
        </div>
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
      var _idMap = [];
      var _page = 1;
      
      var search_name = "{{ $search_name }}";

      $("#search-column-name").val( search_name );

      $('#show_block').on('shown.bs.modal', function() {
        $('#show_block_textarea').focus();
      })   

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
            }, "Page" , "{{ route('recruit.preselected') }}" + '?'+ $.param(
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
            { field: 'user_name', title: "Recruiter", width: 200 , class: 'frozencell'},
            { 
              field: 'fullname', 
              title: "Postulant",  
              width: 350,
              class: 'frozencell recruit-fullname',
              formatter: function(value, rowData, index){
                var cell = '';
                cell += '<a href="#" class="btn-show" data-id="'+rowData.recruit_id+'" data-name="'+rowData.fullname+'" data-index="'+index+'">'+rowData.fullname+'</a>';
                return cell;
              }
            },
            {
              field: 'audio_path', 
              title: "Zoom Audio",
              width: 50,
              formatter : function(value,rowData,index) { 
                  var actions = '';

                  actions += '<div class="btn-group mt-2 btn-upload-audio '+( rowData.audio_path == null ? '' : 'd-none')+'" data-recruitid="'+rowData.recruit_id+'" data-positionid="'+rowData.pos_id+'"> ';
                  actions += '<label class="badge badge-secondary" for="audio-upload-evaluate-'+rowData.recruit_id+'">Upload Audio</label>';
                  actions += '<input type="file" class="custom-file-input audio-upload" id="audio-upload-evaluate-'+rowData.recruit_id+'" data-recruitid="'+rowData.recruit_id+'" data-positionid="'+rowData.pos_id+'" style="display:none;" >';
                  actions += '</div>';

                  actions += '<input class="bulk-input-value" type="hidden" data-index="'+index+'" data-rpid="'+rowData.rp_id+'" data-recruit-id="'+rowData.recruit_id+'">';
              
                  actions += '<div class="btn-group btn-show-audio '+( rowData.audio_path != null ? '' : 'd-none')+'" data-recruitid="'+rowData.recruit_id+'" data-positionid="'+rowData.pos_id+'">';
                  actions += '<a href="#" class="badge badge-success show-audio" data-audio="'+rowData.audio_path+'" data-recruitid="'+rowData.recruit_id+'" data-positionid="'+rowData.pos_id+'">Show Audio</a>';
                  actions += '<a href="#" class="badge badge-primary confirmation-upload-delete" data-recruitid="'+rowData.recruit_id+'" data-positionid="'+rowData.pos_id+'"><i class="fas fa-trash"></i></a>';
                  actions += '</div>';

                  actions = actions.replace(/:id/gi , rowData.id);
                  return actions;
                },
              class: 'frozencell',
            },
            {
              field: 'crit_1', 
              title: "Persona Ambiente",
              width: 50,
              formatter : function(value,rowData,index) { 
                  var actions = '';

                  actions += '<select class="form-control recruit-crit" data-crit="1" data-positionid="'+rowData.pos_id+'" data-id="'+rowData.recruit_id+'">';
                  actions += '<option value="" '+(rowData.crit_1 == null ? 'selected':'')+'>None</option>';
                  actions += '<option value="excellent" '+(rowData.crit_1 == 'excellent' ? 'selected':'')+'>Excellent</option>';
                  actions += '<option value="efficient" '+(rowData.crit_1 == 'efficient' ? 'selected':'')+'>Efficient</option>';
                  actions += '<option value="inefficient" '+(rowData.crit_1 == 'inefficient' ? 'selected':'')+'>Inefficient</option>';
                  actions += '<option value="lower" '+(rowData.crit_1 == 'lower' ? 'selected':'')+'>Lower than expected</option>';
                  actions += '</select>'

                  return actions;
                },
              class: 'frozencell',
            },
            {
              field: 'crit_2', 
              title: "Autoconfianza",
              width: 50,
              formatter : function(value,rowData,index) { 
                  var actions = '';

                  actions += '<select class="form-control recruit-crit" data-crit="2" data-positionid="'+rowData.pos_id+'" data-id="'+rowData.recruit_id+'">';
                  actions += '<option value="" '+(rowData.crit_2 == null ? 'selected':'')+'>None</option>';
                  actions += '<option value="excellent" '+(rowData.crit_2 == 'excellent' ? 'selected':'')+'>Excellent</option>';
                  actions += '<option value="efficient" '+(rowData.crit_2 == 'efficient' ? 'selected':'')+'>Efficient</option>';
                  actions += '<option value="inefficient" '+(rowData.crit_2 == 'inefficient' ? 'selected':'')+'>Inefficient</option>';
                  actions += '<option value="lower" '+(rowData.crit_2 == 'lower' ? 'selected':'')+'>Lower than expected</option>';
                  actions += '</select>'
                  
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

                actions += '<a id="hide-tech-link-'+rowData.rp_id+'" class="badge badge-success button-disabled text-white '+( rowData.tech_qtn == 'filled' ? '' : 'd-none')+'" data-index="'+index+'" data-id="'+rowData.recruit_id+'" disabled>Completed</a>\n';

                return actions;
                },
              class: 'frozencell',
            },
            {
              field: 'pos_id',
              title: "Evaluation",
              valign: 'middle',
              clickToSelect: false,
              width: 20,
              formatter : function(value,rowData,index) {    
                  var actions = '<a class="badge badge-primary recruit-audio" data-audio="approve" data-positionid="'+rowData.pos_id+'" data-id="'+rowData.recruit_id+'" data-rpid="'+rowData.rp_id+'" data-fullname="'+rowData.fullname+'" href="#">YES</a>'+
                                ' <a class="badge badge-danger recruit-audio" data-audio="disapprove" data-positionid="'+rowData.pos_id+'" data-id="'+rowData.recruit_id+'" data-rpid="'+rowData.rp_id+'" data-fullname="'+rowData.fullname+'" href="#">NO</a>'+
                                ' <a class="badge badge-warning crit-notes" data-positionid="'+rowData.pos_id+'" data-id="'+rowData.recruit_id+'" data-rpid="'+rowData.rp_id+'" data-fullname="'+rowData.fullname+'" href="#"><i class="fas fa-book"></i></a>'

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
        $("table tbody").on('click', 'a.recruit-audio' , function(ev){
          ev.preventDefault();
          var id = $(this).data("id");
          var rpid = $(this).data("rpid");
          var fullname = $(this).data("fullname");
          var positionid = $(this).data("positionid");
          var audio = $(this).data("audio");
          var confirmed = true;

          if(audio=="disapprove"){
            var confirmed = confirm("Are you sure you want to "+ (audio=="approve"?"APPROVE":"DISAPPROVE") +" this profile?");
          }

          if(confirmed){
            $.ajax({
                type:'POST',
                url: '{{ route("recruit.postulant.audio") }}',
                data: {id: id,rpid: rpid,positionid: positionid,audio: audio,fullname: fullname},
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

        //EVALUATION NOTES FUNCTION
        $("table tbody").on('click', 'a.crit-notes' , function(ev){
          ev.preventDefault();
          var id = $(this).data("id");
          var rpid = $(this).data("rpid");
          var fullname = $(this).data("fullname");
          var positionid = $(this).data("positionid");
          var tab = "{{ $tab }}";

          $.ajax({
              type: 'POST',
              url: '{{ route("recruit.get.position.notes") }}',
              data: {id : id,rpid: rpid,fullname: fullname,positionid: positionid,tab: tab},
              headers: {
                  'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success:function(data){
                let _data = JSON.parse(data)

                $("#show_block_id").val(id);
                $("#show_block_rpid").val(rpid);
                $("#show_block_fullname").val(fullname);
                $("#show_block_positionid").val(positionid);

                $("#show_block_textarea").val(_data.notes);
                $("#show_block_name").html(fullname);
                $('#show_block').modal();             
              }
          });
        });

        $("#show_block_save").on('click', function(ev){
          ev.preventDefault();
          var id = $("#show_block_id").val();
          var rpid = $("#show_block_rpid").val();
          var fullname = $("#show_block_fullname").val();
          var positionid = $("#show_block_positionid").val();
          var textarea = $("#show_block_textarea").val();
          var tab = "{{ $tab }}";

          $.ajax({
              type: 'POST',
              url: '{{ route("recruit.update.position.notes") }}',
              data: {id : id,rpid: rpid,fullname: fullname,positionid: positionid,tab: tab,textarea: textarea},
              headers: {
                  'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success:function(data){
                $('#show_block').modal('hide');      
              }
          });
        });

        //EVALUATE POSTULANT CRIT_1 - CRIT_2
        $("table tbody").on('change', 'select.recruit-crit' , function(ev){
          ev.preventDefault();
          var recruit_id = $(this).data("id");
          var positionid = $(this).data("positionid");
          var crit = $(this).data("crit");
          var option = $(this).find(":selected").val();

          $.ajax({
              type:'POST',
              url: '{{ route("recruit.postulant.crit.evaluation") }}',
              data: {recruit_id : recruit_id,positionid: positionid,crit: crit,option: option},
              headers: {
                'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success:function(data){
                console.log('Modified');
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

                    $("#info-expert").modal();
                }
            });
        });

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
                        for (var i = 0; _dataRows.length > i ; i++) {
                          _idMap.push(_dataRows[i].id);
                        }
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

  //UPLOAD AUDIO FUNCTION - INCLUDING PROGRESS BAR
  $('body').on('change' , '.audio-upload' , function(ev){
      var file = this.files[0];
      var recruit_id = $(this).data("recruitid");
      var position_id = $(this).data("positionid");
      var bar = $('.progress-bar');

      var _formData = new FormData();
      _formData.append('file', file);
      _formData.append('recruit_id', recruit_id);
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
          url: "{{ route('recruit.postulant.upload.audio') }}",
          headers: {
              'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          contentType: false,
          cache: false,
          processData: false,
          data: _formData,
          success:function(data){
              $('.btn-upload-audio[data-recruitid="'+recruit_id+'"][data-positionid="'+position_id+'"]').addClass("d-none");
              $('.btn-show-audio[data-recruitid="'+recruit_id+'"][data-positionid="'+position_id+'"]').removeClass("d-none");
              $('.show-audio[data-recruitid="'+recruit_id+'"][data-positionid="'+position_id+'"]').attr("data-audio" , data.file);
              bar.width('0%');
          }
      });
  })

  //SET VALUES FOR AUDIO FILE DELETE MODAL
  $("body").on('click' , '.confirmation-upload-delete' , function(ev){
      ev.preventDefault();
      var recruit_id = $(this).data("recruitid");
      var position_id = $(this).data("positionid");

      $("#delete-audio-rp-id").val(recruit_id);
      $("#delete-audio-position-id").val(position_id);

      $("#delete-audio").modal();

  })

  //SET VALUES FOR AUDIO FILE DELETE MODAL (NULL)
  $('#delete-audio').on('hidden.bs.modal', function (e) {
    $("#delete-audio-rp-id").val("");
    $("#delete-audio-position-id").val("");
  })

  //DELETE AUDIO FILE FUNCTION
  $("#deleteAudio").on('click' , function(){
      $.ajax({
          type:'POST',
          url: "{{ route('recruit.postulant.delete.audio') }}",
          headers: {
              'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: {
              recruit_id : $("#delete-audio-rp-id").val(),
              position_id: $("#delete-audio-position-id").val()
          },
          success:function(data){
              var rp_id = $("#delete-audio-rp-id").val();
              var position_id = $("#delete-audio-position-id").val();
              $('.btn-upload-audio[data-recruitid="'+rp_id+'"][data-positionid="'+position_id+'"]').removeClass("d-none");
              $('.btn-show-audio[data-recruitid="'+rp_id+'"][data-positionid="'+position_id+'"]').addClass("d-none");
              $("#delete-audio").modal('hide');
          }
      });

  });

  //SET VALUES FOR AUDIO FILE PLAY MODAL
  $('body').on('click' , '.show-audio' ,function(ev){
      ev.preventDefault();
      var audio = $(this).data("audio");
      var h = "{{ route('home') }}";
      $("#audio-play").attr("src" , audio);
      $("#show-audio").modal();
  })

  //SET VALUES FOR AUDIO FILE PLAY MODAL (NULL)
  $('#show-audio').on('hidden.bs.modal', function (e) {
      $("#audio-play").attr("src" , "");
  })

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