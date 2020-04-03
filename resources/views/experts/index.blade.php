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

</style>
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <h1>Experts</h1>
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
            <table id="list-experts"></table>
        </div>
    </div>
    
@endsection

@section('javascript')

<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script type="text/javascript" src="{{ asset('/bootstrap-table/bootstrap-table.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.js') }}"></script>

<script type="text/javascript">
    
    $(document).ready(function () {

        $('#interview_date').datetimepicker({
            format: "{{ config('app.date_format_javascript') }}",
            locale: "en"
        });

        function tablebootstrap_filter( a_keys_basic , a_keys_inter , a_keys_advan , _is_jqgrid , search_name ){
            
            var a_keys_filter = a_keys_basic.concat( a_keys_inter, a_keys_advan );

            var columns = [
                {
                    field: 'id',
                    title: "Actions",
                    valign: 'middle',
                    clickToSelect: false,
                    formatter : function(value,rowData,index) {
                        var actions = '<a class="badge badge-primary" href=" '+ "{{ route('experts.edit', ':id' ) }}"+ ' ">Edit</a>\n';
                        actions += rowData.file_path == '' ? '' : '<a class="badge badge-dark text-light" download href="'+ "{{ route('home') }}" + '/'+rowData.file_path+'  ">Download</a>\n';
                        actions += '<a class="badge badge-info btn-position" data-id="'+rowData.id+'" href="#">Positions</a>\n';
                        actions += '<a class="badge badge-secondary btn-interviews" href="#" data-id="'+rowData.id+'" data-name="'+rowData.fullname+'">Interviews</a>\n';
                        actions += '<a class="badge badge-danger btn-delete-expert" data-id="'+rowData.id+'" href="#">Delete</a>';
                        
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
                { field: 'salary', title: "Salary" ,width: 110 , formatter: function(value,rowData,index){ return value== null ? '-' : "S/. "+value;} },
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
                height: 500,
                pagination: true,
                sidePagination: "server",
                columns: columns.concat(columns_info, columns_temp),
                showExtendedPagination: true,
                totalNotFilteredField: 'totalNotFiltered',
                url : "{{ route('expert.listtbootstrap') }}",
                fixedColumns: true,
                fixedNumber: 2,
                theadClasses: 'table-dark',
                uniqueId: 'id',
                pageSize: 50,
                queryParams : function(params){
                    var offset = params.offset;
                    var limit = params.limit;
                    var page = (offset / limit) + 1;
                    var q_basic = a_keys_basic? a_keys_basic.join(',') : '';
                    var q_inter = a_keys_inter? a_keys_inter.join(',') : '';
                    var q_advan = a_keys_advan? a_keys_advan.join(',') : '';
                    return {
                        'offset': offset,
                        'rows':params.limit,
                        'page' : page , 
                        'basic': q_basic , 
                        'intermediate': q_inter ,
                        'advanced' : q_advan,
                        'name' : search_name
                    };
                }

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

        }

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
            
            tablebootstrap_filter( a_basic_level, a_intermediate_level , a_advanced_level , is_jqgrid , '');
            
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

            tablebootstrap_filter( [], [] , [] , is_jqgrid , text );
            is_jqgrid = true;

        } , 500 ));


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

    });
 
</script>   
@endsection