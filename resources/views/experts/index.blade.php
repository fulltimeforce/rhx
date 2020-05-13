@extends('layouts.app' , ['controller' => 'experts'])

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
</style>
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <h1>Experts ({{ $experts }})</h1>
        </div>
        <div class="col text-right">
            
            <a class="btn btn-info" id="url-generate" href="#">Generate URL</a>
        </div>
    </div>
    <div class="alert alert-warning alert-dismissible mt-3" role="alert" style="display: none;">
        <b>Copy successful!!!!</b>
        <p id="showURL"></p>
    </div>
    <!--  
        /*========================================== MODALS ==========================================*/
    -->
    <!--  
        /*========================================== POSITONS BY EXPERT ==========================================*/
    -->
    <div class="modal fade" id="positionsExpert" tabindex="-1" role="dialog" aria-labelledby="positionsExpertLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="positionsExpertLabel">ASSIGNED POSITIONS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <form action="" id="form-positions">
                <input type="hidden" name="expertId-p" id="expertId-p" value="">
                <ul class="list-group" id="list-positions">
                    <li class="list-group-item d-flex justify-content-between align-items-center">Cras justo odio <div ><input type="checkbox"></div></li>
                </ul>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="save-positions" class="btn btn-primary">Save</button>
            </div>
            
        </div>
    </div>
    </div>
    <!--  
        /*========================================== INTERVIEWS BY EXPERT ==========================================*/
    -->
    <div class="modal" id="interviews-expert" tabindex="-1" role="dialog" aria-labelledby="interviews-expertLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="interviews-expertLabel"><span id="interview_expert_name">{expert Name}</span> - INTERVIEWS</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row mb-4">
                <div class="col" id="list-interviews">
                    
                </div>
            </div>
            
        </div>
        
        </div>
    </div>
    </div>
    <!--  
        /*========================================== FORM ==========================================*/
    -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <br>
    
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
    <div class="row mb-4">
        <div class="col">
            <p>Result: <span id="count-expert"></span></p>
        </div>
        <div class="col text-right">
            <div class="form-group d-inline-block" style="max-width: 300px;">
                <input type="text" placeholder="Search By Name" class="form-control" id="search-column-name">
            </div>
            <button type="button" class="btn btn-success" id="search" style="vertical-align: top;">Search</button>
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

        var _dataRows = [];

        var search_name = "{{ $name }}";

        $("#search-column-name").val( search_name );

        function ajax_experts( basic , intermediate , advanced , _search_name , page){
            $(".lds-ring").show();
            var params = {
                'rows': _records,
                'page' : page , 
                'basic': basic.join(',') , 
                'intermediate': intermediate.join(',') ,
                'advanced' : advanced.join(','),
                'name' : _search_name
            };
            $.ajax({
                type:'GET',
                url: '{{ route("expert.listtbootstrap") }}',
                data: $.param(params),
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){

                    let _data = JSON.parse(data)
                    _total_records = _data.total;
                    _count_records = _count_records + _data.rows.length;
                    $("#count-expert").html( _count_records );
                    _dataRows = _data.rows;
                    tablebootstrap_filter( _data.rows , basic , intermediate , advanced );
                    if( page == 1 ) $("html, body").animate({ scrollTop: 0 }, "slow");
                    $(".lds-ring").hide();
                }
            });
        }

        function tablebootstrap_filter( data ,a_keys_basic , a_keys_inter , a_keys_advan ){
            
            var a_keys_filter = a_keys_basic.concat( a_keys_inter, a_keys_advan );

            console.log(a_keys_filter);

            var columns = [
                {
                    field: 'id',
                    title: "Actions",
                    valign: 'middle',
                    align: 'left',
                    clickToSelect: false,
                    formatter : function(value,rowData,index) {
                        var actions = '<a class="badge badge-primary" href=" '+ "{{ route('experts.edit', ':id' ) }}"+ ' ">Edit</a>\n';
                        actions += rowData.file_path == '' ? '' : '<a class="badge badge-dark text-light" download href="'+ "{{ route('home') }}" + '/'+rowData.file_path+'  ">Download</a>\n';
                        actions += '<a class="badge badge-info btn-position" data-id="'+rowData.id+'" href="#">Positions</a>\n';
                        actions += '<a class="badge badge-secondary btn-interviews" href="#" data-id="'+rowData.id+'" data-name="'+rowData.fullname+'">Interviews</a>\n';
                        actions += '<a class="badge badge-danger btn-delete-expert" data-id="'+rowData.id+'" href="#">Delete</a>';
                        if( rowData.resume == null){
                            actions += '<span class="badge badge-secondary" >Resume</span>\n';
                        }else{
                            actions += '<span class="badge badge-success" >Resume</span>\n';
                        }
                        
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
                { field: 'age', title: "Age" },
                { field: 'phone', title: "Phone" },
                { field: 'availability', title: "Availability" },
                { field: 'salary', title: "Salary" ,width: 110 , formatter: function(value,rowData,index){ return value== null ? '-' : (rowData.type_money == 'sol' ? 'S/' : '$') + ' ' +value;} },
                { field: 'linkedin', title: "Linkedin" },
                { field: 'github', title: "Github" },
                { field: 'experience', title: "Experience" },
            ];

            @foreach($technologies as $categoryid => $category)
                @foreach($category[1] as $techid => $techlabel)
                    
                    if ( a_keys_filter.filter(f => f=='{{$techid}}').length > 0 ){
                        columns.push( { field: '{{$techid}}', title: "{{$techlabel}}", class: 'stickout' } );
                    }else{
                        columns_temp.push( { field: '{{$techid}}', title: "{{$techlabel}}" , width: 110, align: 'center' } );
                    }
                @endforeach
            @endforeach

            $("#list-experts").bootstrapTable('destroy').bootstrapTable({
                height: undefined,
                columns: columns.concat(columns_info, columns_temp),
                data: data,
                fixedColumns: true,
                fixedNumber: 2,
                theadClasses: 'table-dark',
                uniqueId: 'id'
            });

            // =================== DELETE

            $("table tbody").on('click', 'a.btn-delete-expert' , function(ev){
                ev.preventDefault();
                var id = $(this).data("id");
                $.ajax({
                    type:'POST',
                    url: '{{ route("experts.deleteExpert") }}',
                    data: {expertId : id},
                    headers: {
                        'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(data){

                        $("#list-experts").bootstrapTable('removeByUniqueId',id);
                    }
                });

            });
            // =============== POSITIONS

            $("table tbody").on('click', 'a.btn-position' , function(ev){
                ev.preventDefault();
                var id = $(this).data("id");
                $('#expertId-p').val('');
                $.ajax({
                    type:'POST',
                    url: '{{ route("positions.enabled") }}',
                    data: {expertId : id},
                    headers: {
                        'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(data){

                        $('#expertId-p').val(id);
                        $("#list-positions").html('');
                        var html = '';
                        for (let index = 0; index < data.length; index++) {
                            html += '<li class="list-group-item d-flex justify-content-between align-items-center">:name <div ><input type="checkbox" name="positions[]" value="'+data[index].id+'" '+ (data[index].active == 1? 'checked' : '') +' ></div></li>';
                            html = html.replace(':name' , data[index].name);
                        }
                        $("#list-positions").html(html);
                        $("#positionsExpert").modal();
                    }
                });
                
            });

            // =============== LIST INTERVIEWS

            $("table tbody").on("click" , "a.btn-interviews" , function(ev){
                ev.preventDefault();
                var expertId = $(this).data("id");
                var expertName = $(this).data("name");
                
                $("#interview_expert_name").html( expertName );
                $("#interview_expert_id").val(expertId);
                $.ajax({
                    type: 'POST',
                    url: '{{ route("interviews.recruiterlog") }}',
                    data: {expertId : expertId },
                    headers: {
                        'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(interviews){
                        console.log(interviews);
                        var html = '';
                        for (let index = 0; index < interviews.length; index++) {
                            html += card_interviews( interviews[index] )
                        }
                        $("#list-interviews").html(html);
                        $("#interviews-expert").modal();
                    }
                });
                
            });

        }

        function card_interviews( _interview ){
            var html = '';
            html += '<div class="card mb-4">';
            html += '    <div class="card-header">';
            html += '        <div class="row">';
            html += '            <div class="col">';
            html += '                <h4 class="text-uppercase txt-type"><span>'+( (_interview.position == null)? "GENERAL" : _interview.position.name )+'</span> - <span>'+_interview.date+'</span></h4>';
            html += '            </div>';
            html += '        </div>';
            html += '    </div>';
            html += '    <div class="card-body">';
            for (let index = 0; index < _interview.notes.length; index++) {
                
                html += '<div class="card mb-3">';
                html += '    <div class="card-header">';
                html += '        <div class="row">';
                var _class = '';
                switch( _interview.notes[index].type ){
                    case 'commercial': 
                    case 'technique': 
                    case 'psychology': 
                        _class =  _interview.notes[index].type_value == null ? 'secondary' : (_interview.notes[index].type_value == 'approved' ? 'success' : ( _interview.notes[index].type_value == 'not approved' ? 'danger' : 'warning' ) ) 
                    break;
                    case 'cv': 
                    case 'experience': 
                    case 'communication': 
                    case 'english': 
                        _class =  _interview.notes[index].type_value == null ? 'secondary' : (_interview.notes[index].type_value == 'approved' ? 'success' : 'danger') 
                    break;
                }
                html += '            <div class="col">';
                html += '                <span class="text-uppercase badge badge-'+_class+'">'+ _interview.notes[index].type +'</span>';
                html += '            </div>';
                html += '        </div>';
                html += '    </div>';
                
                html += '    <div class="card-body">';
                html += '        <p class="card-text txt-description ">'+ _interview.notes[index].note +'</p>';
                html += '    </div>';
                html += '</div>';
            }
                    
            html += '    </div>';
            html += '</div>';
            return html;
        }

        var loading = false;
        var scroll_previus = 0;
        var _page = 1;
        
        // =============================== LAZY LOADING SCROLL ================================= 

        $("#list-experts").on('scroll-body.bs.table' , function(e, arg1){
            console.log(e);
            
            var _height = $(e.target).height();
            var _positionScroll = $("#list-experts").bootstrapTable('getScrollPosition');
            var _diff = 491;

        });

        $(window).on('scroll', function (e){
            
            if($(window).scrollTop() + $(window).height() == $(document).height()) {

                if( _count_records < _total_records ){
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
                            'name' : _text
                    };
                    $(".lds-ring").show();
                    $.ajax({
                        type:'GET',
                        url: '{{ route("expert.listtbootstrap") }}',
                        data: $.param(data),
                        headers: {
                            'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success:function(data){

                            console.log(data);
                            let _data = JSON.parse(data);
                            $("#list-experts").bootstrapTable('append', _data.rows );
                            
                            _count_records = _count_records + _data.rows.length;
                            $("#count-expert").html( _count_records );
                            $(".lds-ring").hide();
                        }
                    });
                }
            }
        });

        // ================================================================================

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
            
            ajax_experts( basic , intermediate , advanced , search_name , 1);
            
            
        @endif

        $(".search-level").select2({
            ajax: {
                url: "{{ route('expert.technologies') }}",
                dataType: 'json',
                data: function (params) {
                    var query = {
                        search: params.term,
                    }
                    // Query parameters will be ?search=[term]&type=public
                    return query;
                },
                processResults: function (data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: data
                    };
                }

            }
        });
        
        
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
                        $(".alert").slideDown(200, function() {
                            
                        });
                    }
                    setTimeout(() => {
                        $(".alert").slideUp(500, function() {
                            document.body.removeChild(el);
                        });
                    }, 4000);  
                }
            });
        });

        var a_basic_level = [];
        var a_intermediate_level = [];
        var a_advanced_level = [];

        var is_jqgrid = false;


        $('#search').on('click' , function(){
            
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
                        name: search_name
                    }
                    )
                );
            _page = 1;
            _count_records = 0;
            location.reload();
            // ajax_experts( a_basic_level, a_intermediate_level , a_advanced_level , search_name , 1);
            
        });

        $("#search-column-name").keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
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
                            name: search_name
                        }
                        )
                    );
                _page = 1;
                _count_records = 0;
                location.reload();
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

        // ===================== SHOW POSITIONS =====================

        $("#save-positions").on('click' , function(ev){
            
            var positionsIDs = [];
            $('#form-positions input[type="checkbox"]:checked').each( function(){
                positionsIDs.push($(this).val());
            } );
            var id = $('#expertId-p').val();

            $.ajax({
                type:'POST',
                url: '{{ route("positions.experts.attach") }}',
                data: {expertId : id, positions : positionsIDs},
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){

                    $("#positionsExpert").modal('hide');
                    
                }
            });

        });

        var template_card_interview = $('#list-interviews').html();
        $('#list-interviews').html('');

        $('table').on('click' , '.copy-link' , function(ev){
            var data = $(this).data("info");
            ev.preventDefault();
            var el = document.createElement("textarea");
            el.value = data;
            el.style.top = '0';
            el.setSelectionRange(0, 99999);
            el.setAttribute('readonly', ''); 
            this.appendChild(el);
            el.focus();
            el.select();
            var success = document.execCommand('copy');
            console.log(success, "dsdsdsdsdsdsdsdsdsds");
            this.removeChild(el);

        })

        $('#interview_date').datetimepicker({
            format: "{{ config('app.date_format_javascript') }}",
            locale: "en",
            defaultDate : new Date()
        });

        $("#clear-interview").on('click' , function(){

            $("#interview_type").val('');
            $("#interview_date").val( moment().format("{{ config('app.date_format_javascript') }}") );
            $("#about").val('');
            $("#interview_description").val('');
            $("#interview_result").prop('checked', false);
            
            $("#interview_id").val( '' );

            $('#form-btn-edit').hide();
            $('#form-btn-save').show();

        });

    });
 
</script>   
@endsection