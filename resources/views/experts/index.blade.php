@extends('layouts.app' , ['controller' => 'position'])

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap-table/bootstrap-table.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.css') }}"/>
 
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
    <div class="row">
        <div class="col">
            <h1>Experts ({{ $recruits }})</h1>
        </div>
        <div class="col text-right">
            
            <!--<a class="btn btn-info" id="url-generate" href="#">Generate URL</a>-->
        </div>
    </div>

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

    <div class="alert alert-warning alert-dismissible mt-3" role="alert" style="display: none;">
        <b>Copy successful!!!!</b>
        <p id="showURL"></p>
    </div>

    <!--======================================================================================================================  
    ======================================================EXPERT AUDIO MODAL==================================================
    =======================================================================================================================-->
    <div class="modal fade" id="fceExpert" tabindex="-1" role="dialog" aria-labelledby="fceExpertLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fceExpertLabel">FCE - <span id="fce_expert_name"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" id="expert_index">
                    <table id="list-audios" class="table table-dark mb-5">
                        <thead>
                            <tr>
                                <th><span id="audio_expert_name"></span></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
    </div>
    <!--======================================================================================================================  
    ==================================================EXPERT INFORMATION MODAL================================================    
    =======================================================================================================================-->
    <div class="modal fade" id="notes-expert" tabindex="-1" role="dialog" aria-labelledby="interviews-expertLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class='modal-content'>
            <div class='modal-header'>
            <h5 class='modal-title'>Notes</h5>
            </div>
            <div class='modal-body'>
                <div class='row'>
                    <div class='col-12'>
                        <h5>Evaluation Notes</h5>
                    </div>
                    <div class='col-12'>
                        <textarea class="form-control" id="expert_ev_notes" style="height: 150px;" disabled></textarea>
                    </div>
                </div>
                <hr>
                <div class='row'>
                    <div class='col-12'>
                        <h5>Audio Notes</h5>
                    </div>
                    <div class='col-12'>
                        <textarea class="form-control" id="expert_audio_notes" style="height: 150px;" disabled></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

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
    
    <br>

    <!--======================================================================================================================  
    ==================================================SEARCH EXPERTS FORM=====================================================    
    =======================================================================================================================-->
    <form action="{{ route('experts.filter') }}" class="row" method="POST">
        @csrf
        <div class="form-group col">
            <label for="basic_level">Basic</label>
            <select multiple id="basic_level" name="basic_level[]" class="form-control search-level basic" size="1">
                @foreach( $basic as $tid => $tlabel)
                    <option value="{{ $tid }}" selected > {{ $tlabel }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col">
            <label for="intermediate_level">Intermediate</label>
            <select multiple id="intermediate_level" name="intermediate_level[]" class="form-control search-level intermediate" size="1">
                @foreach( $intermediate as $tid => $tlabel)
                    <option value="{{ $tid }}" selected > {{ $tlabel }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col">
            <label for="advanced_level">Advanced</label>
            <select multiple id="advanced_level" name="advanced_level[]" class="form-control search-level advanced" size="1">
                @foreach( $advanced as $tid => $tlabel)
                    <option value="{{ $tid }}" selected > {{ $tlabel }}</option>
                @endforeach
            </select>
        </div>
    </form>
    <div class="row">
        <div class="col">
            <p>Result: <span id="count-expert"></span></p>
        </div>
        <div class="col text-right">
            <div class="form-group d-inline-block pr-3" style="max-width: 300px;">
                <select class="form-control" id="load-search-profile" name="load-search-profile" value="">
                    <option value="">--Select Search Profile--</option> 
                    @foreach( $search_profiles as $sp_id => $sp_value)
                        <option value="{{ $sp_value['id'] }}" {{($profile == $sp_value['id'])? 'selected' : ''}}>{{ $sp_value['search_name'] }}</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="selection" id="selection" value="{{ $selection }}">
            <btn class="btn {{ $selection == 1 ? 'btn-secondary' : ( $selection == 2 ? 'btn-danger' : ( $selection == 3 ? 'btn-warning' : 'btn-success' ) ) }}" id="change-selected">Selected</btn>
            {{-- <input type="checkbox" name="audio" id="audio">
            <label for="audio">With audio</label> --}}
            <div class="form-group d-inline-block" style="max-width: 300px;">
                <input type="text" placeholder="Search By Name" class="form-control" id="search-column-name">
            </div>
            <button type="button" class="btn btn-success" id="search" style="vertical-align: top;">Search</button>
        </div>
    </div>

    <!--======================================================================================================================  
    ==================================POSITION BULK ACTIONS / SAVE SEARCH PROFILE INPUT=======================================
    =======================================================================================================================-->
    <div class="row mb-4">
        <div class="col-3 text-left">
            <div class="form-group d-inline-block mt-3" style="max-width: 300px;">
                <select name="position-bulk-action" id="position-bulk-action" class="form-control" >
                    @foreach( $positions as $ps_key => $ps_value)
                    <option value="{{ $ps_value['id'] }}"> {{ $ps_value['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-info mt-3" id="position-bulk-recruit" type="button" style="vertical-align: top;">Apply</button>
        </div>
        <div class="col-9 text-right">
            <div class="form-group d-none pr-3 mt-3" style="width: 220px;">
                <select multiple id="save_search_notify" name="save_search_notify[]" class="save-search-notify" size="1" placeholder="Search Notify Options" style="width: 23px;">
                    <option value="popup">Pop-up</option>
                </select>
            </div>
            <div class="form-group d-none pr-3 mt-3">
                <select class="form-control" id="save-search-level" name="save-search-level" value="">
                    <option value="">--Select User Level (*)--</option> 
                    <option value="1">SUPERADMIN</option> 
                    <option value="2">RECRUITER</option>
                </select>
            </div>
            <div class="form-group d-none pr-3 mt-3">
                <input type="text" placeholder="Search Profile Name (*)" class="form-control" id="save-search-name" name="save-search-name">
            </div>
            <div class="form-group d-inline-block mt-3">
                <input type="checkbox" name="save-search" id="save-search">
                <label for="save-search">Save Search</label>
            </div>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-12">
            <table id="list-experts"></table>
        </div>
        <div class="col-12 text-center">
            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
        </div>
    </div>
    
@endsection

@section('javascript')

<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script type="text/javascript" src="{{ asset('/bootstrap-table/bootstrap-table.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.js') }}"></script>

<script type="text/javascript">
    
    $(document).ready(function () {
        $(".lds-ring").hide();
        $('#interview_date').datetimepicker({
            format: "{{ config('app.date_format_javascript') }}",
            locale: "en"
        });

        var _records = 50;
        var _total_records = 0;
        var _count_records = 0;
        var _page = 1;

        var _before_rows = 0;

        var _dataRows = [];
        var _idMap = [];

        var search_name = "{{ $name }}";

        var audios_filter = [];
        var audios_evaluate = [];
        var audios = [];
        var isSearch = false;
        var deepSearch = {{$deep_search}};

        $("#search-column-name").val( search_name );

        function ajax_experts( basic , intermediate , advanced , deep_search, _search_name , page){
            $(".lds-ring").show();
            var params = {
                'rows': _records,
                'page' : page , 
                'basic': basic.join(',') , 
                'intermediate': intermediate.join(',') ,
                'advanced' : advanced.join(','),
                'name' : _search_name,
                'deep_search': deep_search,
                'selection' : $("#selection").val(),
                'audio': $("#audio").is(":checked")
            };
            $.ajax({
                type:'GET',
                url: '{{ route("experts.list.bootstrap") }}',
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
                    $("#count-expert").html( _count_records );
                    _dataRows = _data.rows;
                    console.log(_dataRows)
                    for (var i = 0; _dataRows.length > i ; i++) {
                      _idMap.push(_dataRows[i].id);
                    }
                    tablebootstrap_filter( _data.rows , basic , intermediate , advanced );
                    if( page == 1 ) $("html, body").animate({ scrollTop: 0 }, "slow");
                    $(".lds-ring").hide();
                }
            });
        }

        function tablebootstrap_filter( data ,a_keys_basic , a_keys_inter , a_keys_advan ){
            
            var a_keys_filter = a_keys_basic.concat( a_keys_inter, a_keys_advan );
            var columns = [
                { 
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
                    field: 'id',
                    title: "Actions",
                    valign: 'middle',
                    align: 'left',
                    clickToSelect: false,
                    formatter : function(value,rowData,index) {
                        var actions = '<a class="badge badge-primary" href=" '+ "{{ route('experts.btn.edit', ':id' ) }}"+ ' ">Edit</a>\n';
                        actions += (!rowData.file_path) ? '' : '<a class="badge badge-dark text-light" download href="'+rowData.file_path+'" target="_blank">Download</a>\n';
                        //actions += '<a class="badge badge-secondary btn-interviews" href="#" data-id="'+rowData.id+'" data-name="'+rowData.fullname+'">Interviews</a>\n';
                        actions += '<a class="badge badge-danger btn-delete-expert" data-id="'+rowData.id+'" href="#">Delete</a>';
                        actions += (!rowData.audio_path) ? '' : '<a class="badge badge-info btn-fce" data-id="'+rowData.id+'" data-index="'+index+'" href="#">Audio</a>\n';
                        actions += '<a href="#" class="btn-show badge badge-warning" data-id="'+rowData.id+'" data-name="'+rowData.fullname+'" data-index="'+index+'">Show</a>';
                        actions += '<a href="#" class="badge btn-selection '+ ( rowData.selection == 1 ? 'badge-secondary': ( rowData.selection == 2 ? 'badge-danger' : ( rowData.selection == 3 ? 'badge-warning': 'badge-success') ) )+'" data-id="'+rowData.id+'" data-selection="'+rowData.selection+'" >Selected</a>';
                        actions += '<a href="#" class="badge btn-notes badge-info" data-id="'+rowData.id+'">Notes</a>';
                        actions += '<input class="bulk-input-value" type="hidden" data-index="'+index+'" data-id="'+rowData.id+'" data-fullname="'+rowData.fullname+'">';

                        actions = actions.replace(/:id/gi , rowData.id);

                        return actions;
                    },
                    width: 100,
                    class: 'frozencell'
                },
                { field: 'fullname', title: "Name", width: 150 , class: 'frozencell'}
            ];
            var columns_temp = [];
            var columns_info = [
                { field: 'email_address', title: "Email" },
                { 
                    field: 'birthday', 
                    title: "Age",
                    valign: 'middle',
                    align: 'left',
                    clickToSelect: false,
                    formatter : function(value,rowData,index) {
                        var date = new Date(rowData.birthday).getTime();
                        var now = Date.now();

                        var age_time = new Date(now-date);
                        var age = Math.abs(age_time.getUTCFullYear() - 1970);

                        var actions = (!rowData.birthday) ? '-' : age;

                        return actions;
                    },
                    width: 100
                },
                { field: 'phone_number', title: "Phone" },
                { field: 'availability', title: "Availability" },
                { field: 'salary', title: "Salary" ,width: 110 , formatter: function(value,rowData,index){ return value== null ? '-' : (rowData.type_money == 'sol' ? 'S/' : '$') + ' ' +value;} },
                { field: 'fce_overall', title: "FCE", formatter : function(value,rowData,index) { return rowData.fce_overall == '' ? '-' : '<span title="'+rowData.fce_total+'" >'+rowData.fce_overall+'</span>' } },
                { 
                    field: 'test_status', 
                    title: "Test",
                    width: 100,
                    clickToSelect: false,
                    formatter : function(value,rowData,index) { 
                        var actions = "";
                        if(rowData.test_status == 0){
                            actions = '<span>---</span>';
                        }else{
                            var min = Math.min(rowData.completeness_score,rowData.code_score,rowData.design_score,rowData.technologies_score,rowData.readme_score);
                            actions += "<div class='ttip'>"+ rankString(min) + "<span class='ttiptext'>";
                            switch(rowData.test_status){
                            case 1: 
                                actions += "Completeness: "+rankInitial(rowData.completeness_score)+"<br>"
                                + "Clean Code: "+rankInitial(rowData.code_score)+"<br>"
                                + "Design Quality: "+rankInitial(rowData.design_score)+"<br>"
                                + "Technologies: "+rankInitial(rowData.technologies_score)+"<br>"
                                + "Readme: "+rankInitial(rowData.readme_score);
                                break;
                            case 2: 
                                actions += "Test Failed";
                                break;
                            default: 
                                actions += "-";
                            }
                            actions += "</span></div>";
                        }
                        return actions;
                    },
                },
                { 
                    field: 'linkedin', 
                    title: "Linkedin",
                    valign: 'middle',
                    align: 'left',
                    clickToSelect: false,
                    formatter : function(value,rowData,index) {
                        var actions = (!rowData.linkedin) ? '-' : '<a id="show-recruit-linkedin-link" class="badge badge-success data-index="'+index+'" href="'+rowData.linkedin+'" target="_blank">Go to LinkedIn Link</a>';

                        actions = actions.replace(/:id/gi , rowData.id);

                        return actions;
                    },
                    width: 100
                },
                { 
                    field: 'github', 
                    title: "Github",
                    valign: 'middle',
                    align: 'left',
                    clickToSelect: false,
                    formatter : function(value,rowData,index) {
                        var actions = (!rowData.github) ? '-' : '<a id="show-recruit-github-link" class="badge badge-success data-index="'+index+'" href="'+rowData.github+'" target="_blank">Go to GitHub Link</a>';

                        actions = actions.replace(/:id/gi , rowData.id);

                        return actions;
                    },
                    width: 100
                },
            ];

            @foreach($technologies as $categoryid => $category)
                @foreach($category[1] as $techid => $techlabel)
                    if ( a_keys_filter.filter(f => f=='{{$techid}}').length > 0 ){
                        columns.push( { field: '{{$techid}}', title: "{{$techlabel}}", class: 'stickout' } );
                    }
                    // else{
                    //     columns_temp.push( { field: '{{$techid}}', title: "{{$techlabel}}" , width: 110, align: 'center' } );
                    // }
                @endforeach
            @endforeach
            
            //==========SET TABLE CONFIGURATION VALUES
            $("#list-experts").bootstrapTable('destroy').bootstrapTable({
                height: undefined,
                columns: columns.concat(columns_info, columns_temp),
                data: data,
                fixedColumns: true,
                fixedNumber: 3,
                theadClasses: 'table-dark',
                uniqueId: 'id'
            });

            //==========SHOW EXPERT INFORMATION ON MODAL
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

            //==========DELETE EXPERT DB AND TABLE ROW
            $("table tbody").on('click', 'a.btn-delete-expert' , function(ev){
                ev.preventDefault();
                var id = $(this).data("id");
                $.ajax({
                    type:'POST',
                    url: '{{ route("experts.btn.delete") }}',
                    data: {recruitId : id},
                    headers: {
                        'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(data){

                        $("#list-experts").bootstrapTable('removeByUniqueId',id);
                    }
                });
            });

            //==========UPDATE EXPERT SELECTION VALUE
            $("table tbody").on('click' , 'a.btn-selection' , function(ev){
                ev.preventDefault();
                var recruitId = $(this).attr("data-id");
                var expertSelection = $(this).attr("data-selection");
                var $this = $(this)
                var select = 1;
                switch( parseInt(expertSelection) ){
                    case 1: select = 2;break;
                    case 2: select = 3;break;
                    case 3: select = 4;break;
                    case 4: select = 1;break;
                }

                $.ajax({
                    type: 'POST',
                    url: '{{ route("experts.btn.selection") }}',
                    data: {recruitId : recruitId , selection: select },
                    headers: {
                        'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(data){
                        $this.removeClass("badge-secondary")
                            .removeClass("badge-danger")
                            .removeClass("badge-warning")
                            .removeClass("badge-success");
                        switch( select ){
                            case 1: $this.addClass("badge-secondary");break;
                            case 2: $this.addClass("badge-danger");break;
                            case 3: $this.addClass("badge-warning");break;
                            case 4: $this.addClass("badge-success");break;
                        }
                        $this.attr("data-selection" , select )
                    }
                });
            });

            $("table tbody").on('click' , 'a.btn-notes' , function(ev){
                ev.preventDefault();
                var recruitId = $(this).attr("data-id");
                $.ajax({
                    type: 'POST',
                    url: '{{ route("experts.notes") }}',
                    data: {recruitId : recruitId},
                    headers: {
                        'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(data){
                        var ev_notes = data.evaluation_notes ? data.evaluation_notes: 'No hay notas...';
                        var audio_notes = data.audio_notes ? data.audio_notes: 'No hay notas...';
                        $("#expert_ev_notes").html(ev_notes);
                        $("#expert_audio_notes").html(audio_notes);
                        $("#notes-expert").modal('show');
                    },
                });
            });
            
            //==========OPEN AUDIO MODAL WITH SOURCE
            $("table tbody").on('click' , 'a.btn-fce' , function(ev){
                ev.preventDefault();
                var recruitId = $(this).attr("data-id");
                var index = $(this).attr("data-index");
                $("input:radio").prop('checked', false);
                $.ajax({
                    type: 'POST',
                    url: '{{ route("experts.btn.audio") }}',
                    data: {recruitId : recruitId },
                    headers: {
                        'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(data){
                        $("#list-audios tbody").html('');
                        var html='';

                        if(data.recruit){
                            html += '<tr data-audio="'+index+'">';
                            html += '<td style="text-align: center;">';
                            html += '<a href="#" class="mr-1 btn btn-light speed-audio" data-speed="1">x1.00</a><a href="#" class="mr-1 btn btn-light speed-audio" data-speed="1.25">x1.25</a> <a href="#" class="mr-1 btn btn-light speed-audio" data-speed="1.5">x1.5</a> <a href="#" class="mr-1 btn btn-light speed-audio" data-speed="1.75">x1.75</a> <a href="#" class="mr-1 btn btn-light speed-audio" data-speed="2">x2.0</a>'
                            html += '<audio id="audio-player-'+index+'" src="'+data.recruit[0].audio_path+'" controls></audio></td>';
                            html += '</tr>';
                        }

                        $("#list-audios tbody").html(html);

                        $("#fce_expert_name").html(data.recruit[0].fullname);
                        
                        $('#fceExpert').modal();
                    }
                });
            });
        }

        //==========SELECTED BUTTON FUNCTION
        $("#change-selected").on('click' , function(ev){
            switch( parseInt( $("#selection").val() ) ){
                case 1: $("#selection").val(2);break;
                case 2: $("#selection").val(3);break;
                case 3: $("#selection").val(4);break;
                case 4: $("#selection").val(1);break;
            }
            $(this).removeClass("btn-secondary")
                            .removeClass("btn-danger")
                            .removeClass("btn-warning")
                            .removeClass("btn-success");
            switch( parseInt( $("#selection").val() ) ){
                case 1: $(this).addClass("btn-secondary");break;
                case 2: $(this).addClass("btn-danger");break;
                case 3: $(this).addClass("btn-warning");break;
                case 4: $(this).addClass("btn-success");break;
            }

            search_name = $('#search-column-name').val();
            a_basic_level = $(".search-level.basic").val();
            a_intermediate_level = $(".search-level.intermediate").val();
            a_advanced_level = $(".search-level.advanced").val();
            window.history.replaceState({
                edwin: "Fulltimeforce"
                }, "Page" , "{{ route('experts.home') }}" + '?'+ $.param(
                    {   search : true , 
                        basic: a_basic_level.join(","),
                        intermediate: a_intermediate_level.join(","),
                        advanced: a_advanced_level.join(","),
                        audio: $("#audio").is(":checked"),
                        selection : $("#selection").val(),
                        name: search_name
                    }
                    )
                );
            _page = 1;
            _count_records = 0;
            location.reload();
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

        //==========WINDOW SCROLL FUNCTION
        $(window).on('scroll', function (e){
            console.log( $(window).scrollTop() + $(window).height() , $(document).height() )
            if($(window).scrollTop() + $(window).height() >= $(document).height()) {
                console.log( _count_records , _total_records, _before_rows , _records , "##################" );
                if( _count_records < _total_records && _before_rows == _records ){
                    _page++;
                    let a_basic_level = $(".search-level.basic").val();
                    let a_intermediate_level = $(".search-level.intermediate").val();
                    let a_advanced_level = $(".search-level.advanced").val();
                    var _text = $('#search-column-name').val();
                    var data = {
                            'offset': _records,
                            'rows': _records,
                            'page' : _page , 
                            'basic': _text == '' ? a_basic_level.join(',') : '', 
                            'intermediate': _text == '' ? a_intermediate_level.join(',') : '',
                            'advanced' : _text == '' ? a_advanced_level.join(',') : '',
                            'name' : _text,
                            'selection' : $("#selection").val(),
                            'audio': $("#audio").is(":checked")
                    };
                    $(".lds-ring").show();
                    $.ajax({
                        type:'GET',
                        url: '{{ route("experts.list.bootstrap") }}',
                        data: $.param(data),
                        headers: {
                            'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success:function(data){

                            let _data = JSON.parse(data);
                            _before_rows = _data.total;
                            for (var i = 0; _data.rows.length > i ; i++) {
                              _idMap.push(_data.rows[i].id);
                            }
                            $("#list-experts").bootstrapTable('append', _data.rows );
                            
                            _count_records = _count_records + _data.rows.length;
                            $("#count-expert").html( _count_records );
                            $(".lds-ring").hide();
                        }
                    });
                }
            }
        });

        $("#audio").prop( 'checked' , false )

        //==========SET SEARCH PAGE PROPERTIES
        @if( $audio )
            $("#audio").prop( 'checked' , true )
        @endif

        @if( $search )
            var basic = [];
            @foreach($basic as $tid => $tlabel)
                basic.push( "{{$tid}}" );
            @endforeach
            var intermediate = [];
            @foreach($intermediate as $tid => $tlabel)
                intermediate.push( "{{$tid}}" );
            @endforeach
            var advanced = [];
            @foreach($advanced as $tid => $tlabel)
                advanced.push( "{{$tid}}" );
            @endforeach
            
            ajax_experts( basic , intermediate , advanced , deepSearch , search_name , 1);
        @else

            ajax_experts( [] , [] , [] , deepSearch, '' , 1);
        @endif

        //==========GET SELECT TECHNOLOGIES DATA
        $(".search-level").select2({
            ajax: {
                url: "{{ route('experts.select.technologies') }}",
                dataType: 'json',
                data: function (params) {
                    var query = {
                        search: params.term,
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                }
            }
        });
        
        //==========GENERATE URL FUNCTION
        $('#url-generate').on('click', function (ev) {
            ev.preventDefault();
            $.ajax({
                type:'GET',
                url: "{{ route('applicant.register.signed') }}" ,
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

        //==========SEARCH AND SAVE SEARCH PROFILE FUNCTION
        $('#search').on('click' , function(){
            search       = true;
            basic        = $(".search-level.basic").val().join(",");
            intermediate = $(".search-level.intermediate").val().join(",");
            advanced     = $(".search-level.advanced").val().join(",");
            audio        = $("#audio").is(":checked");
            selection    = $("#selection").val();
            name         = $('#search-column-name').val();

            save_search = $("#save-search").is(":checked");

            search_name           = $("#save-search-name").val();
            search_user_level     = $("#save-search-level").val();
            search_notify_options = $("#save_search_notify").val().join(",");
            
            $.ajax({
                type:'POST',
                url: '{{ route("recruit.store.search.profile") }}',
                data: {search: search,
                       basic: basic,
                       intermediate: intermediate,
                       advanced: advanced,
                       audio: audio,
                       selection: selection,
                       name: name,
                       save_search: save_search,
                       search_notify_options: search_notify_options,
                       search_user_level: search_user_level,
                       search_name: search_name
                },
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){

                    window.history.replaceState(
                        {edwin: "Fulltimeforce"}, 
                        "Page" , "{{ route('experts.home') }}" + '?'+ $.param({   
                            search : search , 
                            basic: basic,
                            intermediate: intermediate,
                            advanced: advanced,
                            audio: audio,
                            selection: selection,
                            name: name
                        })
                    );
                    _page = 1;
                    _count_records = 0;
                    location.reload();

                }
            });
            
        });

        //==========SEARCH AND SAVE SEARCH PROFILE FUNCTION USING ENTER KEY
        $("#search-column-name").keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                search       = true;
                basic        = $(".search-level.basic").val().join(",");
                intermediate = $(".search-level.intermediate").val().join(",");
                advanced     = $(".search-level.advanced").val().join(",");
                audio        = $("#audio").is(":checked");
                selection    = $("#selection").val();
                name         = $('#search-column-name').val();

                save_search = $("#save-search").is(":checked");

                search_name           = $("#save-search-name").val();
                search_user_level     = $("#save-search-level").val();
                search_notify_options = $("#save_search_notify").val().join(",");

                $.ajax({
                    type:'POST',
                    url: '{{ route("recruit.store.search.profile") }}',
                    data: {search: search,
                        basic: basic,
                        intermediate: intermediate,
                        advanced: advanced,
                        audio: audio,
                        selection: selection,
                        name: name,
                        save_search: save_search,
                        search_notify_options: search_notify_options,
                        search_user_level: search_user_level,
                        search_name: search_name
                    },
                    headers: {
                        'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(data){

                        window.history.replaceState(
                            {edwin: "Fulltimeforce"}, 
                            "Page" , "{{ route('experts.home') }}" + '?'+ $.param({   
                                search : search , 
                                basic: basic,
                                intermediate: intermediate,
                                advanced: advanced,
                                audio: audio,
                                selection: selection,
                                name: name
                            })
                        );
                        _page = 1;
                        _count_records = 0;
                        location.reload();

                    }
                });
            }
        })

        function delay(callback, ms) {
            var timer = 0;
            return function() {
                var context = this, args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function () {
                callback.apply(context, args);
                }, ms || 0);
            };
        }
    });
 
</script>  

<script type="text/javascript">

    //==========SAVE SEARCH PROFILE CHECKBOX FUNCTION
    $("#save-search").on('click' , function(ev){
        var checked_value = $(this).is(":checked");

        if(checked_value){
            $("#save-search-name").parent().addClass("d-inline-block");
            $("#save-search-level").parent().addClass("d-inline-block");
            $("#save_search_notify").parent().addClass("d-inline-block");

            $("#save-search-name").parent().removeClass("d-none");
            $("#save-search-level").parent().removeClass("d-none");
            $("#save_search_notify").parent().removeClass("d-none");

            $('.save-search-notify').select2({
                placeholder: " Search Notify Options",
            });
        }else{
            $("#save-search-name").parent().removeClass("d-inline-block");
            $("#save-search-level").parent().removeClass("d-inline-block");
            $("#save_search_notify").parent().removeClass("d-inline-block");

            $("#save-search-name").parent().addClass("d-none");
            $("#save-search-level").parent().addClass("d-none");
            $("#save_search_notify").parent().addClass("d-none");
        }
    });

    //==========LOAD SEARCH PROFILE
    $("#load-search-profile").on('change' , function(ev){
        var selectId = $(this).val();

        if(selectId){

            $.ajax({
                type:'POST',
                url: '{{ route("recruit.load.search.profile") }}',
                data: {selectId : selectId},
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){
                    let _data = JSON.parse(data);
                    window.history.replaceState(
                        {edwin: "Fulltimeforce"}, 
                        "Page" , "{{ route('experts.home') }}" + '?'+ $.param({   
                            search : _data.search_profile.search , 
                            basic: _data.search_profile.basic,
                            intermediate: _data.search_profile.intermediate,
                            advanced: _data.search_profile.advanced,
                            audio: _data.search_profile.audio,
                            selection: _data.search_profile.selection,
                            name: _data.search_profile.name,
                            profile: selectId
                        })
                    );
                    _page = 1;
                    _count_records = 0;
                    location.reload();                    
                }
            });
        }
    });

    //==========BULK ACTIONS BUTTON
    $("#position-bulk-recruit").on('click' , function(){
        var action = $('#position-bulk-action').val();
        var position_name = $('#position-bulk-action option:selected').text();
        var expert_id_array = [];
        var expert_name_array = [];

        var checked = $('input[name="btSelectItem"]:checked');

        if(checked.length>0){
            checked.each(function (){
                var checkbox_index = $(this).data("index");
                var expert_id       = $('.bulk-input-value[data-index="'+checkbox_index+'"]').data("id");
                var expert_fullname = $('.bulk-input-value[data-index="'+checkbox_index+'"]').data("fullname");

                if(!expert_id_array.includes(expert_id)){
                    expert_id_array.push(expert_id)    
                }

                if(!expert_name_array.includes(expert_fullname)){
                    expert_name_array.push(expert_fullname)
                }
            });

            $.ajax({
                type:'POST',
                url: "{{ route('recruit.position.bulk') }}",
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    action : action,
                    expert_id_array: expert_id_array,
                    expert_name_array: expert_name_array,
                    position_name: position_name,
                },
                success:function(data){
                    location.reload();
                }
            });
        }else{
            alert('Please, select at least 1 EXPERT to continue.');
        }
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
    
</script>  

@endsection