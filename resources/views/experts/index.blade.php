@extends('layouts.app' , ['controller' => 'experts'])

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<!-- <link rel="stylesheet" type="text/css" href="{{ asset('/datatable/jquery.dataTables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/datatable/css/fixedColumns.dataTables.min.css') }}"/> -->

<!-- <link rel="stylesheet" type="text/css" href="{{ asset('/jqGrid/css/ui.jqgrid.css') }}"/> -->

<link rel="stylesheet" type="text/css" href="{{ asset('/jqGrid/css/ui.jqgrid-bootstrap4.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/jqGrid/plugins/jQuery.jqGrid.fontAwesome4.css') }}"/>
 
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

</style>
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <h1>Experts</h1>
        </div>
        <div class="col text-right">
            <!-- <a class="btn btn-primary" href="{{ route('experts.create') }}">New Expert</a> -->
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
            <h5 class="modal-title" id="interviews-expertLabel">INTERVIEWS - <span id="interview_expert_name">{expert Name}</span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div >
            <div class="row mb-4">
                <div class="col" id="list-interviews">
                    <div class="card mb-4" id="interview-:id">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-uppercase txt-type">:name-type</h5>
                                </div>
                                <div class="col text-right">
                                    <a href="#" class="btn btn-danger btn-delete-interview" data-id=":id">Delete</a>
                                    <a href="#" class="btn btn-primary btn-edit-interview" data-id=":id">Edit</a>
                                </div>
                            </div>

                        </div>
                        <div class="card-body">
                            <b class="mb-2 txt-about">:about</b>
                            <p class="card-text txt-description">:description</p>
                        </div>
                        <div class="card-footer text-muted">
                            <span class="txt-result">:result</span>
                            <span class="float-right txt-date">:date</span>
                        </div>
                    </div>
                </div>
            </div>

            <form action="" id="interviews-form" class="row">
                <div class="col-5">
                    <div class="form-group">
                        <label for="">Type</label>
                        <select class="form-control" name="type" id="interview_type">
                            <option value="technical">Technical</option>
                            <option value="psychological">Psychological</option>
                            <option value="commercial">Commercial</option>
                            <option value="client">Client</option>
                        </select>
                        <input type="hidden" name="expert_id" id="interview_expert_id">
                        <input type="hidden" name="id" id="interview_id">
                    </div>
                    <div class="form-group">
                        <label for="">Result</label>
                        <div class="slider">
                            <input type="checkbox" name="result" class="slider-checkbox" id="interview_result" >
                            <label class="slider-label" for="interview_result">
                            <span class="slider-inner"></span>
                            <span class="slider-circle"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Date</label>
                        <input type="text" class="form-control" name="date" id="interview_date" data-toggle="datetimepicker" data-target="#interview_date">
                    </div>
                </div>
                <div class="col-7">
                    <div class="form-group">
                        <label for="">About</label>
                        <input type="text" class="form-control" name="about" id="about">
                    </div>
                    <div class="form-group">
                        <label for="">Description</label>
                        <textarea name="description" id="interview_description" class="form-control" rows="10"></textarea>
                    </div>
                </div>
            </form>
            </div>
            <div class="row mb-4">
                <div class="col">
                    <div class="form-group" id="form-btn-save">
                        <button class="btn btn-success" id="save-interview">SAVE</button>
                    </div>
                    <div class="form-group" id="form-btn-edit" style="display: none;">
                        <button class="btn btn-success" id="edit-interview">EDIT</button>
                        <button class="btn btn-info" id="clear-interview">CLEAR</button>
                    </div>
                    
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
    <form action="{{ route('experts.filter') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="basic_level">Basic</label>
            <select multiple id="basic_level" name="basic_level[]" class="form-control search-level basic" size="1"></select>
        </div>
        <div class="form-group">
            <label for="intermediate_level">Intermediate</label>
            <select multiple id="intermediate_level" name="intermediate_level[]" class="form-control search-level intermediate" size="1"></select>
        </div>
        <div class="form-group">
            <label for="advanced_level">Advanced</label>
            <select multiple id="advanced_level" name="advanced_level[]" class="form-control search-level advanced" size="1"></select>
        </div>
        <div class="form-group text-right">
            <button type="button" class="btn btn-success" id="search">Search</button>
        </div>
    
    </form>
    <div class="row mb-4">
        <div class="col">
            <h5>Experts: {{ $experts }} <span id="filter-count-expert" style="display: none;">- Filter : <span class="count-expert"></span></span></h5>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-4">
            <input type="text" placeholder="Search By Name" class="form-control" id="search-column-name">
        </div>
    </div>
    <div class="row">
        <div class="col">
            @component('layouts.components.spiner')
            @endcomponent
            
            <table id="list2"></table>
            <div id="pager2"></div>
            
        </div>
    </div>

    <div class="row">
        <div class="col">
            
        </div>
    </div>
@endsection

@section('javascript')

<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<!-- <script type="text/javascript" src="{{ asset('/datatable/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/datatable/js/dataTables.fixedColumns.min.js') }}"></script> -->

<script type="text/javascript" src="{{ asset('/jqGrid/js/i18n/grid.locale-en.js') }}"></script>
<script type="text/javascript" src="{{ asset('/jqGrid/js/jquery.jqGrid.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/jqGrid/plugins/jQuery.jqGrid.fontAwesome4.js') }}"></script>

<script type="text/javascript">
    $.jgrid.defaults.styleUI = 'Bootstrap4';
    $(document).ready(function () {

        $("#loader-spinner").hide();

        function jqGridExperts_filter( a_keys_basic , a_keys_inter , a_keys_advan , _is_jqgrid , search_name ){

            var url = '{{ route("expert.list" , array( "basic" => ":basic", "intermediate" => ":inter", "advanced" => ":advan" , "name" => ":search_name" ) ) }}';
            
            $("#loader-spinner").show();
            
            var q_basic = a_keys_basic? a_keys_basic.join(',') : '';
            var q_inter = a_keys_inter? a_keys_inter.join(',') : '';
            var q_advan = a_keys_advan? a_keys_advan.join(',') : '';

            url = url.replace("%3Abasic" , q_basic);
            url = url.replace("%3Ainter" , q_inter);
            url = url.replace("%3Aadvan" , q_advan);

            url = url.replace("%3Asearch_name" , search_name);


            url = url.replace(/amp;/gi , "");

            var a_keys_filter = a_keys_basic.concat( a_keys_inter, a_keys_advan );

            var a_colModels = [
                {name:'action', index:'action' , width: 100 ,frozen : true , classes : 'frozencell'},
                {name:'fullname', index:'fullname' , frozen : true , classes : 'frozencell'},
                {name:'id', index:'id' , hidden: true },
                {name:'file_path', index:'file_path' , hidden: true}
            ];
            var a_colModels_temp = [];
            var a_colModels_info = [
                {name:'email_address', index:'email_address'},
                {name:'age', index:'age' , width: 60},
                {name:'phone', index:'phone' , width: 110},
                {name:'availability', index:'availability'},
                {name:'salary', index:'salary' , width: 110},
                {name:'linkedin', index:'linkedin'},
                {name:'github', index:'github'},
                {name:'focus', index:'focus'},
            ];

            //titles
            var a_colNames = ['Action' , 'Name' , 'ID' , 'File'];
            var a_colNames_temp = [];
            var a_colNames_info = ['Email','Age','Phone','Availability','Salary','Linkedin','Github','Experience'];


            @foreach($technologies as $categoryid => $category)
                @foreach($category[1] as $techid => $techlabel)
                    // a_keys_filter.filter(f => f=='{{$techid}}').length > 
                    if ( a_keys_filter.filter(f => f=='{{$techid}}').length > 0 ){
                        a_colNames.push( "{{$techlabel}}");
                        a_colModels.push( {name:'{{$techid}}', index:'{{$techid}}' , classes : 'stickout' , width: 110 , align : 'center'} );
                    }else{
                        a_colNames_temp.push( "{{$techlabel}}");
                        a_colModels_temp.push( {name:'{{$techid}}', index:'{{$techid}}' , width: 110 ,align : 'center'});
                    }
                @endforeach
            @endforeach

            console.log( url , "URL------");
            console.log( a_colNames.concat(a_colNames_info, a_colNames_temp) , "names------");
            console.log( a_colModels.concat(a_colModels_info, a_colModels_temp) , "models------");

            // return;
            
            
            if(_is_jqgrid) $.jgrid.gridUnload("#list2"); // jQuery("#list2").jqGrid('GridDestroy');
            
            jQuery("#list2").jqGrid("initFontAwesome").jqGrid({
                url: url,
                datatype: "json",
                colNames: a_colNames.concat(a_colNames_info, a_colNames_temp),
                colModel: a_colModels.concat(a_colModels_info, a_colModels_temp),
                rowNum: 25,
                rowList: [25,50,100],
                pager: '#pager2',
                sortname: 'id',
                regional: 'es',
                viewrecords: true,
                sortorder: "desc",
                height: '500',
                autowidth:true,
                shrinkToFit:false,
                forceFit:true,
                gridComplete: function(){
                    var grid = jQuery("#list2");
                    var ids = grid.jqGrid('getDataIDs');
                    for (var i = 0; i < ids.length; i++) {

                        var rowId = ids[i];
                        var rowData = jQuery('#list2').jqGrid ('getRowData', rowId);
                        console.log(rowData, "rowData")
                        var actions = '<a class="badge badge-primary" href=" '+ "{{ route('experts.edit', ':id' ) }}"+ ' ">Edit</a>\n';
                        actions += rowData.file_path == '' ? '' : '<a class="badge badge-dark text-light" download href="'+ "{{ route('home') }}" + '/'+rowData.file_path+'  ">Download</a>\n';
                        actions += '<a class="badge badge-info btn-position" data-id="'+rowData.id+'" href="#">Positions</a>\n';
                        actions += '<a class="badge badge-secondary btn-interviews" href="#" data-id="'+rowData.id+'" data-name="'+rowData.fullname+'">Interviews</a>\n';
                        actions += '<a class="badge badge-danger btn-delete-expert" data-id="'+rowData.id+'" href="#">Delete</a>';
                        
                        actions = actions.replace(/:id/gi , rowData.id);
                        grid.jqGrid('setRowData', rowId, { action: actions });
                    }

                    $("#loader-spinner").hide();

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

                                $('#list2').jqGrid('delRowData',id);
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
                            url: '{{ route("interviews.expert") }}',
                            data: {expertId : expertId },
                            headers: {
                                'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success:function(interviews){
                                console.log(interviews);
                                var html = '';
                                for (let index = 0; index < interviews.length; index++) {
                                    var html_card_interview = template_card_interview;
                                    html_card_interview = html_card_interview.replace(/:id/ , interviews[index].id);
                                    html_card_interview = html_card_interview.replace(/:id/ , interviews[index].id);
                                    html_card_interview = html_card_interview.replace(/:id/ , interviews[index].id);
                                    html_card_interview = html_card_interview.replace(':name-type' , interviews[index].type);
                                    html_card_interview = html_card_interview.replace(':about' , interviews[index].about);
                                    html_card_interview = html_card_interview.replace(':description' , interviews[index].description.replace(/↵/g, '<br>'));
                                    var result = interviews[index].result ? 'APPROVED' : 'FAILED';
                                    html_card_interview = html_card_interview.replace(':result' , result);
                                    var _date = new Date(interviews[index].date);
                                    html_card_interview = html_card_interview.replace(':date' , moment( interviews[index].date ).format("{{ config('app.date_format_javascript') }}") );
                                    html += html_card_interview
                                }

                                $('#list-interviews').html(html);

                                $("#interview_type").val('');
                                $("#interview_date").val( moment().format("{{ config('app.date_format_javascript') }}") );
                                $("#about").val('');
                                $("#interview_description").val('');
                                $("#interview_result").prop('checked', false);
                                
                                $("#interview_id").val( '' );

                                $('#form-btn-edit').hide();
                                $('#form-btn-save').show();

                                $("#interviews-expert").modal();
                            }
                        });
                        
                    });



                    $('.oi-caret-right').addClass('fas').addClass('fa-angle-right')
                        .removeClass('oi').removeClass('oi-caret-right');
                    
                    $('.oi-media-step-forward').addClass('fas').addClass('fa-angle-double-right')
                        .removeClass('oi').removeClass('oi-media-step-forward');

                    $('.oi-caret-left').addClass('fas').addClass('fa-angle-left')
                        .removeClass('oi').removeClass('oi-caret-left');

                    $('.oi-media-step-backward').addClass('fas').addClass('fa-angle-double-left')
                        .removeClass('oi').removeClass('oi-media-step-backward');
                }

            });
            jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false});
            jQuery("#list2").jqGrid('setFrozenColumns');

            $("#loader-spinner").hide();
            
        }

        var options = {
            lengthMenu: [[50, 100, 150, -1], [50, 100, 150, "All"]],
            scrollY: "500px",
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 2
            },
            // searching: false
            // dom: "Bfrtip",
        }

        var table;

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
            $("#filter-count-expert").hide();
            $('#search-column-name').val('');
            a_basic_level = $(".search-level.basic").val();
            a_intermediate_level = $(".search-level.intermediate").val();
            a_advanced_level = $(".search-level.advanced").val(); 
            
            $("#loader-spinner").show();
            


            jqGridExperts_filter( a_basic_level, a_intermediate_level , a_advanced_level , is_jqgrid , '' );
            is_jqgrid = true;
            // if(table) table.destroy();
            
            // $.ajax({
            //     type: 'POST',
            //     url: '{{ route("experts.filter") }}',
            //     data: {basic_level : a_basic_level , intermediate_level : a_intermediate_level , advanced_level : a_advanced_level },
            //     headers: {
            //         'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     },
            //     success:function(data){

            //         print_table_experts( data , a_basic_level.concat(a_intermediate_level , a_advanced_level) );

            //     }
            // });
        });

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

        $('#search-column-name').on( 'keyup', delay(function (ev) {
            
            var text = $(this).val();
            // if( text .length >= 3){
            //     if(table){
            //         table.columns( 1 ).search(
            //             this.value
            //         ).draw();
            //     }
            // }else{
            //     if(table){
            //         table.columns( 1 ).search(
            //             ''
            //         ).draw();
            //     }
            // }

            $("#loader-spinner").show();

            jqGridExperts_filter( [], [] , [] , is_jqgrid , text );
            is_jqgrid = true;

            // if(table) table.destroy();

            // $.ajax({
            //     type: 'POST',
            //     url: '{{ route("experts.search") }}',
            //     data: { name : text },
            //     headers: {
            //         'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     },
            //     success:function(data){

            //         print_table_experts( data , a_basic_level.concat(a_intermediate_level , a_advanced_level) );

            //     }
            // });

        } , 500 ));

        function print_table_experts( data , techonologies_level ){

            $("#filter-count-expert").show();
            $("#filter-count-expert .count-expert").html( data.length ) ;
            var html = '';
            $("#allexperts thead").html('');
            $("#allexperts thead").html( html_table_head( techonologies_level ) );
            
            for (let index = 0; index < data.length; index++) {

                html += html_table_row(data[index] , techonologies_level );
            }
            
            $("#allexperts tbody").html('');
            $("#allexperts tbody:first").html(html);

            $("#loader-spinner").hide();
            
            table = $("#allexperts").DataTable( options );
        }

        function html_table_head(a_keys){

            var html = '';
            html += '<tr>';
            var columns = [];
            var info = '';
            html += '<th>Action</th>';
            html += '<th style="width: 200px;">Name</th>';
            info += '<th>Email</th>';
            info += '<th>Age</th>';
            info += '<th>Phone</th>';
            info += '<th>Availability</th>';
            info += '<th>Salary</th>';
            info += '<th>Linkedin</th>';
            info += '<th>Github</th>';
            info += '<th>Experience</th>';
            var temp = '';
            var rows = '';
            @foreach($technologies as $categoryid => $category)
                @foreach($category[1] as $techid => $techlabel)
                    columns.push({title: "{{$techlabel}}" });
                    if ( a_keys.filter(f => f=='{{$techid}}').length > 0 ){
                        rows += '<th>'+"{{$techlabel}}"+'</th>';
                    }else{
                        temp += '<th>'+"{{$techlabel}}"+'</th>';
                    }
                @endforeach
            @endforeach
            html += rows + info + temp;
            html += '</tr>';
            return html;
        }

        function html_table_row(data , a_keys){
            var html = '';
            html += '<tr>';
            html += '<td>';
            html += '<form action="'+ "{{ route('experts.destroy', ':id' ) }}"+ '" method="POST">';
    
            html += '        <a class="badge badge-primary" href="'+ "{{ route('experts.edit', ':id') }}" + '">Edit</a>';

            if( data.file_path != '' ){
                html += '   <a href="'+data.file_path+'" download class="badge badge-dark text-light">DOWNLOAD</a>';
            }
            
            html += '        <a href="#" data-id="'+data.id+'" class="badge badge-info btn-position">Positions</a>';

            // if( data.log_id == '' || data.log_id == null ){
            //     html += '        <a href="#" data-id="'+data.id+'" class="badge badge-dark btn-log">Log</a>';
            // }

            html += '       <a class="badge badge-secondary btn-interviews" data-id="'+data.id+'" data-name="'+data.fullname+'" href="#">Interviews</a>';

            html = html.replace(/:id/gi , data.id);

            html += '<input type="hidden" name="_token" value="{{csrf_token()}}" /> ';
            html += '<input type="hidden" name="_method" value="DELETE" /> ';
            
            // ('delete')
            html += '        <button type="submit" class="badge badge-danger">Delete</button>';
            html += '    </form>';
            html += '</td>';
            html += '<td style="background-color: #fafafa;width: 200px;">'+data.fullname+'</td>';
            var info = '';
            var temp = '';
            var rows = '';
            @foreach($technologies as $categoryid => $category)
                @foreach($category[1] as $techid => $techlabel)
                
                var _text =  (data['{{$techid}}'] == null)? '' : data['{{$techid}}'] ;
                if ( a_keys.filter(f => f=='{{$techid}}').length > 0 ){
                    rows += '<td class="stickout">'+_text+'</td>';
                }else{

                    temp += '<td>'+_text+'</td>';
                }
                
                @endforeach
            @endforeach

            info += '<td>'+((data.email_address==null)? "" : data.email_address)+'</td>';
            info += '<td>'+((data.age==null)? "": data.age)+'</td>';
            info += '<td>'+((data.phone==null)? "": data.phone)+'</td>';
            info += '<td>'+((data.availability==null)?"":data.availability)+'</td>';
            info += '<td>'+((data.salary==null)?"":data.salary)+'</td>';
            info += '<td>'+((data.linkedin==null)?"":"<a href='#' class='btn btn-sm btn-info copy-link' data-info='"+data.linkedin+"'>Link</a>")+'</td>';
            info += '<td>'+((data.github==null)?"": "<a href='#' class='btn btn-sm btn-info copy-link' data-info='"+data.github+"'>Link</a>" )+'</td>';
            info += '<td class="text-capitalize">'+((data.focus==null)?"":data.focus)+'</td>';

            html += rows + info + temp;
            html += '</tr>';
            return html;
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

        
        $("#save-interview").on("click"  , function(ev){
            ev.preventDefault();
            
            $.ajax({
                type: 'POST',
                url: '{{ route("interviews.save") }}',
                data: $("form#interviews-form").serialize(),
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){

                    // 
                    var html_card_interview = template_card_interview;
                    html_card_interview = html_card_interview.replace(/:id/ , data.id);
                    html_card_interview = html_card_interview.replace(/:id/ , data.id);
                    html_card_interview = html_card_interview.replace(/:id/ , data.id);
                    html_card_interview = html_card_interview.replace(':name-type' , data.type);
                    html_card_interview = html_card_interview.replace(':about' , data.about);
                    html_card_interview = html_card_interview.replace(':description' , data.description);
                    var result = data.result ? 'APPROVED' : 'FAILED';
                    html_card_interview = html_card_interview.replace(':result' , result);
                    var _date = new Date(data.date);
                    html_card_interview = html_card_interview.replace(':date' , moment(data.date).format("{{ config('app.date_format_javascript') }}") );
                    $('#list-interviews').append(html_card_interview);
                    $("#interview_description").val('');
                    $("#about").val('');
                    $("#interview_date").val( moment().format("{{ config('app.date_format_javascript') }}") );
                }
            });
            
        });

        $('#list-interviews').on('click' , '.btn-delete-interview' , function(ev){
            ev.preventDefault();
            var id = $(this).data("id");
            var _this = $(this);
            $.ajax({
                type: 'POST',
                url: '{{ route("interviews.delete") }}',
                data: {id : id},
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){
                    
                    _this.parent().parent().parent().parent().slideUp('slow' , function(){
                        $(this).remove();
                    })
                    
                }
            });
        });

        $("#list-interviews").on('click' , '.btn-edit-interview' , function(ev){
            ev.preventDefault();
            var id = $(this).data("id");
            var _this = $(this);

            $.ajax({
                type: 'POST',
                url: '{{ route("interviews.edit") }}',
                data: {id : id},
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){
                    
                    $("#interview_type").val( data.type );
                    $("#interview_date").val( moment(data.date).format("{{ config('app.date_format_javascript') }}") );
                    $("#about").val( data.about );
                    $("#interview_description").val(data.description);
                    $("#interview_result").prop('checked', data.result == 1? true : false);  // Checks the box
                    $("#interview_expert_id").val( data.expert_id );
                    $("#interview_id").val( data.id );

                    $('#form-btn-edit').show();
                    $('#form-btn-save').hide();
                }
            });
        });

        $("#edit-interview").on('click' , function(){
            $.ajax({
                type: 'POST',
                url: '{{ route("interviews.update") }}',
                data: $("form#interviews-form").serialize(),
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){
                    
                    console.log(data)

                    $('#form-btn-edit').hide();
                    $('#form-btn-save').show();

                    $("#interview-"+data.id).find(".txt-type").html(data.type);
                    $("#interview-"+data.id).find(".txt-about").html(data.about);
                    $("#interview-"+data.id).find(".txt-description").html(data.description);
                    $("#interview-"+data.id).find(".txt-date").html( moment(data.date).format("{{ config('app.date_format_javascript') }}") );
                    $("#interview-"+data.id).find(".txt-result").html(data.result == 1 ? 'APPROVED' : 'FAILED' );


                    $("#interview_type").val('');
                    $("#interview_date").val( moment().format("{{ config('app.date_format_javascript') }}") );
                    $("#about").val('');
                    $("#interview_description").val('');
                    $("#interview_result").prop('checked', false);
                    
                    $("#interview_id").val( '' );
                    
                }
            });
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

        // $('table').on('click' , '.btn-log' , function(ev){
        //     ev.preventDefault();
        //     var id = $(this).data("id");
        //     var $_this = $(this);
        //     $.ajax({
        //         type: 'POST',
        //         url: '{{ route("experts.log") }}',
        //         data: {id : id},
        //         headers: {
        //             'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         success:function(data){
                    
        //             console.log(data , "______________data");
        //             if(data) $_this.remove();
                    
        //         }
        //     });

        // });

    });
 
</script>   
@endsection