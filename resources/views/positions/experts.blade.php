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
    POSTULANT POSITION MODAL SECTION
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

    <div class="row">
        <!--
        TOP BUTTON AND TITLE SECTION 
        -->
        <div class="col-12 text-left align-top">
            <h2 class="d-inline">{{ $position->name }} Applicants</h2>
        </div>

        <!--
        TOTAL RECORDS SECTION
        -->

        <div class="col-12 text-right">
            <!--
            SEARCH BY NAME SECTION 
            -->
            <div class="form-group d-inline-block" style="max-width: 300px;">
                <input type="text" placeholder="Search By Name" class="form-control" id="search-column-name">
            </div>
            <!--
            BACK BUTTON SECTION 
            -->
            <a class="btn btn-primary align-top" href="{{ url('/') }}"> Back</a>
        </div>

        <!--
        POSTULANTS TABLE SECTION
        -->
        <div class="col-12 text-center mb-5">
            <table class="table row-border order-column" id="list-experts" data-toggle="list-experts"> 
            </table>
        </div>
    </div>

    
@endsection

@section('javascript')


<script type="text/javascript" src="{{ asset('/bootstrap-table/bootstrap-table.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.js') }}"></script>


<script type="text/javascript">

    $(document).ready(function (ev) {
        
        var a_columns = [
            {
                field: 'id',
                title: "Actions",
                valign: 'middle',
                clickToSelect: false,
                formatter : function(value,rowData,index) {
                    var actions = '';
                    actions += '<a class="badge badge-primary" href=" '+ "{{ route('experts.btn.edit', ':id' ) }}"+ ' ">Edit</a>\n';
                    actions += ( rowData.file_path ) ? '<a class="badge badge-dark text-light" target="_blank" download href="'+rowData.file_path+'  ">Download</a>\n' : '';
                    actions += '<a class="badge badge-info btn-position" data-id="'+rowData.id+'" href="#">Positions</a>\n';
                    
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
        a_columns.push({ field: 'age', title: "Age" ,width: 110 , formatter: function(value,rowData,index){
            var date = new Date(rowData.birthday).getTime();
            var now = Date.now();

            var age_time = new Date(now-date);
            var age = Math.abs(age_time.getUTCFullYear() - 1970);

            var actions = (!rowData.birthday) ? '-' : age;

            return actions;
        }});
        a_columns.push({ field: 'phone_number', title: "Phone" });
        a_columns.push({ field: 'availability', title: "Availability" });
        a_columns.push({ field: 'salary', title: "Salary" ,width: 110 , formatter: function(value,rowData,index){ return value== null ? '-' : "S/. "+value;} });
        a_columns.push({ field: 'linkedin', title: "Linkedin" ,width: 110 , formatter: function(value,rowData,index){
            return rowData.linkedin!=undefined?'<a class="badge badge-success" target="_blank" href="'+rowData.linkedin+'">Go to Linkedin Link</a>':'-';
        }});
        a_columns.push({ field: 'github', title: "Github" ,width: 110 , formatter: function(value,rowData,index){
            return rowData.github!=undefined?'<a class="badge badge-success" target="_blank" href="'+rowData.github+'">Go to Github Link</a>':'-';
        }});

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
        
        //==========================CALL POSITIONS MODAL FUNCTION
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

        //==========================SAVE POSITIONS FUNCTION
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

        //==========================SEARCH BY NAME FUNCTION
        $('#search-column-name').on( 'keyup', delay(function (ev) {

            var text = $(this).val();
            list_experts(text , '');

        } , 500 ));
    });
</script>   
@endsection