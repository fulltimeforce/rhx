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
</style>
@endsection
 
@section('content')

    <div class="row">
        <div class="col-lg-12 mt-5">
            <div class="float-left">
                <h2>{{ $position->name }} Applicants</h2>
            </div>
            <div class="float-right">
                <a class="btn btn-primary" href="{{ url('/') }}"> Back</a>
            </div>
        </div>
    </div>
   
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <br>


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
                            <span>:result</span>
                            <span class="float-right">:date</span>
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

    <div class="row">
        <div class="col">
            
        </div>
        <div class="col-lg-3 col-md-3 col-sm-4">
            <input type="text" placeholder="Search By Name" class="form-control" id="search-column-name">
        </div>
    </div>
    @if( count($requirements) > 0 )
    <div class="row mt-3">
        <div class="col">
            
            <h5>Requirements:</h5>
            <ul class="row">
                @foreach( $requirements as $rid => $requirement )
                    <li class="col-3">{{ $requirement->name }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
    <div class="row mt-3">
        <div class="col">
            <div class="btn-group" >

                <button class="btn btn-light rd-filter" data-value="null">Commercial</button>
                
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <table id="list-experts"></table>

        </div>
    </div>
@endsection

@section('javascript')


<script type="text/javascript" src="{{ asset('/bootstrap-table/bootstrap-table.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.js') }}"></script>


<script type="text/javascript">

    $(document).ready(function (ev) {

        $('#interview_date').datetimepicker({
            format: "{{ config('app.date_format_javascript') }}",
            locale: "en"
        });

        var a_columns = [
            {
                field: 'id',
                title: "Actions",
                valign: 'middle',
                clickToSelect: false,
                formatter : function(value,rowData,index) {
                    var actions = '';
                    actions += '<a class="badge expert_status badge-'+( rowData.status == 'not interviewed' ? 'secondary' : ( rowData.status == 'disqualified'? 'danger' : 'success' ) )+'  " data-id="'+rowData.id+'" data-value="'+rowData.status+'" href="#">Commercial</a>\n';
                    actions += '<a class="badge badge-primary" href=" '+ "{{ route('experts.edit', ':id' ) }}"+ ' ">Edit</a>\n';
                    actions += rowData.file_path == '' ? '' : '<a class="badge badge-dark text-light" download href="'+ "{{ route('home') }}" + '/'+rowData.file_path+'  ">Download</a>\n';
                    actions += '<a class="badge badge-info btn-position" data-id="'+rowData.id+'" href="#">Positions</a>\n';
                    actions += '<a class="badge badge-warning btn-interviews" href="#" data-id="'+rowData.id+'" data-name="'+rowData.fullname+'">Interviews</a>\n';
                    
                    actions = actions.replace(/:id/gi , rowData.id);

                    return actions;
                },
                width: 100,
                class: 'frozencell'
            },
            { field: 'fullname', title: "Name", width: 150 , class: 'frozencell'}
        ];

        @foreach($current_tech as $categoryid => $category)
            @foreach($category as $techid => $techlabel)
                a_columns.push( { field: '{{$techid}}', title: "{{$techlabel}}", class: 'stickout', align: 'center' } );
            @endforeach
        @endforeach

        a_columns.push({ field: 'email_address', title: "Email" });
        a_columns.push({ field: 'age', title: "Age" });
        a_columns.push({ field: 'phone', title: "Phone" });
        a_columns.push({ field: 'availability', title: "Availability" });
        a_columns.push({ field: 'salary', title: "Salary" ,width: 110 , formatter: function(value,rowData,index){ return value== null ? '-' : "S/. "+value;} });
        a_columns.push({ field: 'linkedin', title: "Linkedin" });
        a_columns.push({ field: 'github', title: "Github" });
        a_columns.push({ field: 'experience', title: "Experience" });

        @foreach($after_tech as $categoryid => $category)
            @foreach($category as $techid => $techlabel)
                a_columns.push( { field: '{{$techid}}', title: "{{$techlabel}}" , width: 110, align: 'center' } );
            @endforeach
        @endforeach

        function list_experts( search_name , _filter ){
            $("#list-experts").bootstrapTable('destroy').bootstrapTable({
                height: 500,
                pagination: true,
                sidePagination: "server",
                columns: a_columns,
                showExtendedPagination: true,
                totalNotFilteredField: 'totalNotFiltered',
                url : "{{ route('positions.experts.list') }}",
                fixedColumns: true,
                fixedNumber: 2,
                theadClasses: 'table-dark',
                uniqueId: 'id',
                pageSize: 25,
                queryParams : function(params){
                    var offset = params.offset;
                    var limit = params.limit;
                    var page = (offset / limit) + 1;
                    return {
                        'offset'        : offset,
                        'rows'          : params.limit,
                        'page'          : page , 
                        'positionId'    : "{{ $positionId }}",
                        'filter'        : _filter,
                        'name'          : search_name
                    };
                }

            });
        }
        
        list_experts('' , '');
        // ================================== POSITIONS =====================

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

        // ================================== SAVE POSITIONS =====================

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

        // ================================== INTERVIEWS =====================

        var template_card_interview = $('#list-interviews').html();
        $('#list-interviews').html('');
        
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

                    var html = '';
                    for (let index = 0; index < interviews.length; index++) {
                        var html_card_interview = template_card_interview;
                        html_card_interview = html_card_interview.replace(/:id/ , interviews[index].id);
                        html_card_interview = html_card_interview.replace(/:id/ , interviews[index].id);
                        html_card_interview = html_card_interview.replace(/:id/ , interviews[index].id);
                        html_card_interview = html_card_interview.replace(':name-type' , interviews[index].type);
                        html_card_interview = html_card_interview.replace(':about' , interviews[index].about);
                        html_card_interview = html_card_interview.replace(':description' , interviews[index].description);
                        var result = interviews[index].result ? 'APPROVE' : 'FAILED';
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
                    $('#form-btn-save').show()
                    $("#interviews-expert").modal();
                }
            });
            
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
            list_experts(text , '');

        } , 500 ));

        $('table').on('click' , '.expert_status' , function(ev){

            ev.preventDefault();
            var expertId = $(this).data("id");
            var status = $(this).data('value');
            var positionId = "{{ $positionId }}";

            $(this)
                .removeClass('badge-light')
                .removeClass('badge-success')
                .removeClass('badge-secondary')
                .removeClass('badge-danger');

            if( status == 'not interviewed' ){

                $(this).addClass('badge-danger');
                $(this).data('value' , 'disqualified')
                status = 'disqualified';

            }else if( status == 'disqualified' ){

                $(this).addClass('badge-success');
                $(this).data('value' , 'qualified')
                status = 'qualified';
            }else if( status == 'qualified' ){

                $(this).addClass('badge-secondary');
                $(this).data('value' , 'not interviewed')
                status = 'not interviewed';
            }

            $.ajax({
                type: 'POST',
                url: '{{ route("positions.expert.status") }}',
                data: {expertId : expertId , positionId : positionId ,status : status},
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(interviews){

                }
            });
        })


        function html_status_expert( _id , _status ){
            var html = '';
            html += '<div class="form-group">';
            html += '    <select name="expert_status" class="form-control expert_status" style="width: 150px;" data-expert="'+_id+'" data-position="{{ $positionId }}">';
            html += '        <option value=""></option>';
            html += '        <option value="filter" '+( _status == 'filter' ? 'selected' : '' ) + '>Filter</option>';
            html += '        <option value="called" '+( _status == 'called' ? 'selected' : '' ) + '>Called</option>';
            html += '        <option value="scheduled" '+( _status == 'scheduled' ? 'selected' : '' ) + '>Scheduled</option>';
            html += '        <option value="attended" '+( _status == 'attended' ? 'selected' : '' ) + '>Attended</option>';
            html += '        <option value="approved" '+( _status == 'approved' ? 'selected' : '' ) + '>Approved</option>';
            html += '        <option value="failed" '+( _status == 'failed' ? 'selected' : '' ) + '>Failed</option>';
            html += '    </select>';
            html += '</div>';

            return html;
        }

        $('.rd-filter').on("click" , function(ev){

            var status = $(this).data('value');

            $(this)
                .removeClass('btn-light')
                .removeClass('btn-success')
                .removeClass('btn-secondary')
                .removeClass('btn-danger');

            if( status == null ){
                $(this).addClass('btn-secondary');
                $(this).data('value' , 'not interviewed')
                status = 'not interviewed';
            }else if( status == 'not interviewed' ){

                $(this).addClass('btn-danger');
                $(this).data('value' , 'disqualified')
                status = 'disqualified';

            }else if( status == 'disqualified' ){

                $(this).addClass('btn-success');
                $(this).data('value' , 'qualified')
                status = 'qualified';
            }else if( status == 'qualified' ){

                $(this).addClass('btn-light');
                $(this).data('value' , null)
                status = '';
            }

            list_experts('' ,  status );
            return;
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