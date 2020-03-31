@extends('layouts.app' , ['controller' => 'position'])

@section('styles')

<!-- <link rel="stylesheet" type="text/css" href="{{ asset('/datatable/dataTables.min.css') }}"/> -->
<link rel="stylesheet" type="text/css" href="{{ asset('/datatable/jquery.dataTables.min.css') }}"/>
<!-- <link rel="stylesheet" type="text/css" href="{{ asset('/datatable/css/dataTables.bootstrap4.min.css') }}"/> -->
<link rel="stylesheet" type="text/css" href="{{ asset('/datatable/css/fixedColumns.dataTables.min.css') }}"/>

<!-- <link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap-table/bootstrap-table.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.css') }}"/> -->

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

a.badge-success.focus, 
a.badge-success:focus,
a.badge-secondary.focus, 
a.badge-secondary:focus,
a.badge-danger.focus, 
a.badge-danger:focus,
a.badge-warning.focus, 
a.badge-warning:focus{
    box-shadow: none;
}
</style>
@endsection
 
@section('content')
    <div class="row">
        <div class="col">
            <h1>Logs</h1>
            @auth
            
            @endauth
        </div>
    </div>
   
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    
    <div class="row">
        <div class="col-12">
            <form action="" name="new-log" id="new-log">
            <table class="table" >
                <tr>
                    <td >
                        <div class="form-group">
                            <label for="expert">Name</label>
                            <input type="expert" name="expert" id="name" class="form-control">
                            <input type="hidden" name="id" id="log-id">
                            
                        </div>
                    </td>
                    <td >
                        <div class="form-group" style="position: relative;">
                            <label for="date">Date</label>
                            <input type="text" name="date" id="date" class="form-control" data-toggle="datetimepicker" data-target="#date">
                        </div>
                        
                    </td>
                    <td>
                        <div class="form-group">
                            <label for="position">Positions</label>
                            <select id="position" class="form-control" name="position_id" >
                                @foreach($positions as $pid => $position)
                                    <option value="{{ $position->id }}">{{ $position->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </td>

                    <td>
                        <div class="form-group" id="btn-form-save">
                            <button type="button" id="save" class="btn btn-success">SAVE</button>
                        </div>
                        <div class="form-group" id="btn-form-edit" style="display:none;">
                            <button type="button" id="edit" class="btn btn-success">Edit</button>
                            <button type="button" id="clear" class="btn btn-info">Clear</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group" style="position: relative;">
                            <label for="info">Phone/Email</label>
                            <input type="text" name="info" id="info" class="form-control">
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <label for="link">Link</label>
                            <input type="text" name="link" id="link" class="form-control">
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <label for="platform">Platform</label>

                            <select name="platform" id="platform" class="form-control" >
                                @foreach($platforms as $pid => $platform)
                                
                                    <option value="{{$platform->value}}">{{$platform->label}}</option>

                                @endforeach
                            </select>
                        </div>
                    </td>
                    <td></td>
                </tr>
            </table>
            </form>
        </div>
        <!-- <div class="col-12">

            <table class="table row-border order-column" id="table-logs-fill">
                    
            </table>
        </div> -->
        <div class="col-12">
            <table class="table row-border order-column" id="table-logs">
                <thead class="thead-dark">
                    <tr>
                    <th>Actions</th>
                    <th>Date</th>
                    <th>Recruiter</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Info</th>
                    <th style="width: 150px;">Contact</th>
                    <th>Filter</th>
                    <th style="width: 150px;">Schedule</th>
                    <th>Evaluate</th>
                    <th>Platform</th>
                    <th>Link</th>
                    <th>Created at</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($logs as $pid => $log)
                    
                    <tr id="row-{{ $log->id }}" >
                        <td style="background-color: #fafafa;">
                            @if( Auth::id() == $log->user_id || Auth::user()->role->id == 1  )
                                <a class="badge badge-primary log-edit" data-id="{{ $log->id }}" href="#">Edit</a>
                            @endif
                            
                            @if( Auth::id() == $log->user_id || Auth::user()->role->id == 1 )
                                <a class="badge badge-danger log-delete" data-id="{{ $log->id }}" href="#">Delete</a>
                            @endif
                        </td>
                        <td style="background-color: #fafafa;">{{ $log->date }}</td>
                        <td style="background-color: #fafafa;">{{ $log->user->name }}</td>
                        <td style="background-color: #fafafa;">{{ $log->expert }}</td>

                        <td>{{ $log->position->name }}</td>
                        <td>{{ $log->info }}</td>
                        <td>
                            <div class="form-group">
                                <select class="form-control form-dropdown" data-name="contact" data-id="{{ $log->id }}">
                                    <option value="">Select option</option>
                                    <option value="contacted" {{ $log->contact == 'contacted' ? 'selected' : '' }} >Contactado</option>
                                    <option value="not respond" {{ $log->contact == 'not respond' ? 'selected' : '' }} >No Responde</option>
                                    <option value="dont want" {{ $log->contact == 'dont want' ? 'selected' : '' }} >No desea</option>
                                    <option value="not available" {{ $log->contact == 'not available' ? 'selected' : '' }} >No disponible</option>
                                    <option value="num email incorrect" {{ $log->contact == 'num email incorrect' ? 'selected' : '' }} >Num/Email incorrecto</option>
                                    <option value="submitted form" {{ $log->contact == 'submitted form' ? 'selected' : '' }} >Form enviado</option>
                                    <option value="filled form" {{ $log->contact == 'filled form' ? 'selected' : '' }} >Form llenado</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <a href="#" class="badge chk-filter badge-{{ $log->cv == '' ? 'secondary' : ($log->cv == 'approved' ? 'success' : 'danger') }}" data-value="{{ $log->cv }}" data-name="cv" data-id="{{ $log->id }}">CV</a>
                            <a href="#" class="badge chk-filter badge-{{ $log->experience == '' ? 'secondary' : ($log->experience == 'approved' ? 'success' : 'danger') }}" data-value="{{ $log->experience }}" data-name="experience" data-id="{{ $log->id }}">Experience</a>
                            <a href="#" class="badge chk-filter badge-{{ $log->communication == '' ? 'secondary' : ($log->communication == 'approved' ? 'success' : 'danger') }}" data-value="{{ $log->communication }}" data-name="communication" data-id="{{ $log->id }}">Communication</a>
                            <a href="#" class="badge chk-filter badge-{{ $log->english == '' ? 'secondary' : ($log->english == 'approved' ? 'success' : 'danger') }}" data-value="{{ $log->english }}" data-name="english" data-id="{{ $log->id }}">English</a>
                        </td>
                        <td>
                            <div class="form-group">
                                <select class="form-control form-dropdown" data-name="schedule" data-id="{{ $log->id }}">
                                    <option value="">Select option</option>
                                    <option value="scheduled" {{ $log->schedule == 'scheduled' ? 'selected' : '' }}>Agendado</option>
                                    <option value="dont want" {{ $log->schedule == 'dont want' ? 'selected' : '' }}>No puede/desea</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <a href="#" class="badge chk-evaluate badge-{{ $log->commercial == '' ? 'secondary' : ($log->commercial == 'approved' ? 'success' : ( $log->commercial == 'not approved' ? 'danger' : 'warning' ) ) }}" data-value="{{ $log->commercial }}" data-name="commercial" data-id="{{ $log->id }}">Commercial</a>
                            <a href="#" class="badge chk-evaluate badge-{{ $log->technique == '' ? 'secondary' : ($log->technique == 'approved' ? 'success' : ( $log->commercial == 'not approved' ? 'danger' : 'warning' )) }}" data-value="{{ $log->technique }}" data-name="technique" data-id="{{ $log->id }}">Technique</a>
                            <a href="#" class="badge chk-evaluate badge-{{ $log->psychology == '' ? 'secondary' : ($log->psychology == 'approved' ? 'success' : ( $log->commercial == 'not approved' ? 'danger' : 'warning' )) }}" data-value="{{ $log->psychology }}" data-name="psychology" data-id="{{ $log->id }}">Psychology</a>
                        </td>
                        <td>{{ !is_null($log->platform)? collect($platforms)->firstWhere('value' , $log->platform)->label : '' }}  </td>
                        <td>{{ $log->link }}</td>
                        <td>{{ $log->created_at }}</td>
                    </tr>
                    
                @endforeach
                </tbody>
                <!-- <div class="SliderSwitch"><label for="approve-"><input class="ck-form" value="1" data-id="" id="approve-" name="approve" type="checkbox"  /><div class="SliderSwitch__container"><div class="fas SliderSwitch__toggle"></div></div></label></div> -->
            </table>
        </div>
    </div>
    

@endsection

@section('javascript')

<script type="text/javascript" src="{{ asset('/datatable/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/datatable/js/dataTables.fixedColumns.min.js') }}"></script>

<!-- <script type="text/javascript" src="{{ asset('/bootstrap-table/bootstrap-table.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.js') }}"></script> -->

<script type="text/javascript">
    $(document).ready(function (ev) {

        $('#date').datetimepicker({
            format: "{{ config('app.date_format_javascript') }}",
            locale: "en"
        });
        $('#date').val( moment().format("{{ config('app.date_format_javascript') }}") )


        var $_logs = {!! $logs !!};

        // $("#table-logs-fill").bootstrapTable('destroy').bootstrapTable({
        //     height: 500,
        //     pagination: true,
        //     sidePagination: "server",
        //     columns: [
        //         {
        //             field: 'id',
        //             title: "Actions",
        //             valign: 'middle',
        //             clickToSelect: false,
        //             formatter : function(value,row,index) {
        //                 return '<button class=\'btn btn-primary \' pageName="'+row.expert+'" pageDetails="'+row.id+'"  >Edit</button> ';
        //             }
        //         },
        //         {
        //             field: 'expert', title: 'Name'
        //         },
        //         {
        //             field: 'date', title: 'Date'
        //         },
        //     ],
        //     pageNumber: 2,
        //     showExtendedPagination: true,
        //     totalNotFilteredField: 'totalNotFiltered',
        //     url : "{{ route('recruiter.listlogs') }}",
        //     queryParams : function(params){
        //         var offset = params.offset;
        //         var limit = params.limit;
        //         var page = (offset / limit) + 1;
        //         return {'offset': offset,'limit':params.limit,'page' : page};
        //     }

        // });

        var table = $('#table-logs').DataTable({
            "order": [[ 12, "desc" ]],
            scrollY: "500px",
            scrollX: true,
            searching: false,
            fixedColumns: {
                leftColumns: 4
            },
            // ordering: false,
        });

        var column = table.column( 12 );

        column.visible(false);

        $("#save").on('click', function(ev){
            
            $.ajax({
                type:'POST',
                url: "{{ route('recruiter.save') }}",
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:  $("#new-log input , #new-log select").serialize() ,
                success:function(data){
                    console.log(data);
                    // return;
                    if(data.type == 'create'){
                        var _buttons = '<a class="badge badge-primary log-edit" data-id="'+data.data.id+'" href="#">Edit</a>';
                        
                        _buttons += '  <a class="badge badge-danger log-delete" data-id="'+data.data.id+'" href="#">Delete</a>';
                        
                        table.row.add([
                            _buttons,
                            data.data.date,
                            data.data.user_name,
                            data.data.expert,
                            {!! $positions !!}.filter(f => f.id == data.data.position_id)[0].name,
                            data.data.info,
                            html_select_contact( data.data.id ),
                            html_check_filter( data.data.id ),
                            html_select_schedule( data.data.id ),
                            html_check_evaluate( data.data.id ),
                            {!! json_encode($platforms) !!}.filter(f => f.value == data.data.platform)[0].label ,
                            data.data.link,
                            data.data.created_at
                        ]).node().id = "row-"+data.data.id;
                        table.draw(false);  
                        $_logs.push({
                            id      : data.data.id,
                            expert: data.data.expert,
                            info: data.data.info,
                            position:{
                                id: data.data.position_id,
                                name: {!! $positions !!}.filter(f => f.id == data.data.position_id)[0].name
                            },
                            date   : data.data.date,
                            position_id : data.data.position_id,
                            platform : data.data.platform,
                            link : data.data.link,
                            filter: "-",
                            called: "-",
                            scheduled: "-",
                            attended: "-",
                            approve: "-",
                            created_at : data.data.created_at
                        });
                    }

                    // clean
                    $("#name").val('').focus();
                    $("#info").val('');
                    $("#date").val( moment().format("{{ config('app.date_format_javascript') }}") );
                    $("#link").val('');
                    $("#log-id").val('');
                    $("#expert_id").val('');
                    $("#log-id").val('');
                }
            });

        });

        $('table').on('change' , '.ck-form' , function(ev){
            var id = $(this).data("id");
            var attr = $(this).attr("name");
            var val = $(this).is(':checked') ? 1 : 0;
            var data_post = {};
            data_post["id"] = id;
            data_post[attr] = val;
            console.log(data_post);
            $.ajax({
                type:'POST',
                url: "{{ route('recruiter.update') }}",
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data_post,
                success:function(data){

                }
            });
        });

        $('table').on('click' , '.log-edit' , function(ev){
            ev.preventDefault();
            var id = $(this).data("id");
            var log = $_logs.filter( f => f.id == id);
            console.log( log );
            if(log.length > 0){
                $("#log-id").val(log[0].id);
                $("#name").val(log[0].expert);
                $("#info").val(log[0].info);
                $("#date").val(log[0].date);
                $("#position").val(log[0].position_id);
                $("#platform").val(log[0].platform);
                $("#link").val(log[0].link);

                $("#btn-form-save").hide();
                $("#btn-form-edit").show();
            }
        });

        $("#clear").on('click' , function(ev){
            $("#log-id").val('');
            $("#name").val('');
            $("#info").val('');
            $("#date").val(moment().format("{{ config('app.date_format_javascript') }}"));
            
            $("#link").val('');

            $("#btn-form-save").show();
            $("#btn-form-edit").hide();
        });

        $("#edit").on('click' , function(ev){
            $.ajax({
                type:'POST',
                url: "{{ route('recruiter.update') }}",
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:  $("#new-log input , #new-log select").serialize() ,
                success:function(data){
                    console.log(data);
                    // return;
                    
                    var index = $_logs.findIndex( f => f.id == data.id);
                    console.log(index, "dddddd");
                    $_logs[index].expert = data.expert;
                    $_logs[index].info = data.info;
                    $_logs[index].date = data.date;
                    $_logs[index].position_id = data.position_id;
                    $_logs[index].position = {
                        name : {!! $positions !!}.filter(f => f.id == data.position_id)[0].name,
                        id: data.position_id
                    };
                    $_logs[index].platform = data.platform;
                    $_logs[index].link = data.link;

                    $('#row-'+ data.id + ' td:nth-child(2)').html( data.date ? data.date : '' );
                    $('#row-'+ data.id + ' td:nth-child(4)').html( data.expert );
                    $('#row-'+ data.id + ' td:nth-child(5)').html( data.position_id ? {!! $positions !!}.filter(f => f.id == data.position_id)[0].name : '' );
                    $('#row-'+ data.id + ' td:nth-child(6)').html( data.info );

                    $('#row-'+ data.id + ' td:nth-child(11)').html( data.platform ? {!! json_encode($platforms) !!}.filter(f => f.value == data.platform)[0].label : '' );
                    $('#row-'+ data.id + ' td:nth-child(12)').html( data.link? data.link : '' );
                        
                    
                    // clean
                    $("#name").val('').focus();
                    $("#info").val('');
                    $("#date").val(moment().format("{{ config('app.date_format_javascript') }}"));
                    $("#link").val('');
                    $("#log-id").val('');
                    $("#expert_id").val('');
                    $("#log-id").val('');
                }
            });
        });


        $("table").on('click' , '.log-delete' , function(ev){
            ev.preventDefault();
            var id = $(this).data("id");
            $.ajax({
                type:'POST',
                url: "{{ route('recruiter.delete') }}",
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:  { id: id} ,
                success:function(data){
                    console.log(data);
                    // return;
                    $("#row-"+id).addClass("remove-row");
                    table.rows( '.remove-row' ).remove().draw();
                    
                }
            });
        });

        $("table").on('click' , '.chk-filter' , function(ev){
            ev.preventDefault();
            var id = $(this).data("id");
            var attr = $(this).data("name");
            var val = $(this).data('value');

            $(this)
                .removeClass('badge-success')
                .removeClass('badge-secondary')
                .removeClass('badge-danger');

            if( val == '' ){
                $(this).addClass('badge-danger');
                $(this).data('value' , 'not approved')
                val = 'not approved';
            }else if( val == 'not approved' ){

                $(this).addClass('badge-success');
                $(this).data('value' , 'approved')
                val = 'approved';
            }else if( val == 'approved' ){

                $(this).addClass('badge-secondary');
                $(this).data('value' , '')
                val = '';
            }

            var data_post = {};
            data_post["id"] = id;
            data_post[attr] = val;
            console.log(data_post);
            $.ajax({
                type:'POST',
                url: "{{ route('recruiter.update') }}",
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data_post,
                success:function(data){

                }
            });

        });

        $('table').on('change' , '.form-dropdown' , function(ev){
            var id = $(this).data("id");
            var attr = $(this).data("name");
            var val = $(this).val();
            var data_post = {};
            data_post["id"] = id;
            data_post[attr] = val;
            console.log(data_post);
            $.ajax({
                type:'POST',
                url: "{{ route('recruiter.update') }}",
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data_post,
                success:function(data){

                }
            });
        });


        $("table").on('click' , '.chk-evaluate' , function(ev){
            ev.preventDefault();
            var id = $(this).data("id");
            var attr = $(this).data("name");
            var val = $(this).data('value');

            $(this)
                .removeClass('badge-success')
                .removeClass('badge-secondary')
                .removeClass('badge-danger')
                .removeClass('badge-warning');

            if( val == '' ){
                $(this).addClass('badge-success');
                $(this).data('value' , 'approved')
                val = 'approved';
            }else if( val == 'approved' ){

                $(this).addClass('badge-danger');
                $(this).data('value' , 'not approved')
                val = 'not approved';
            }else if( val == 'not approved' ){

                $(this).addClass('badge-warning');
                $(this).data('value' , 'not show up')
                val = 'not show up';
            }else if( val == 'not show up' ){

                $(this).addClass('badge-secondary');
                $(this).data('value' , '')
                val = '';
            }


            var data_post = {};
            data_post["id"] = id;
            data_post[attr] = val;
            console.log(data_post);
            $.ajax({
                type:'POST',
                url: "{{ route('recruiter.update') }}",
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data_post,
                success:function(data){

                }
            });

        });

        function html_select_contact( _id ){
            var html = '';
            html += '<div class="form-group">';
            html += '    <select class="form-control form-dropdown" data-name="contact" data-id="'+_id+'">';
            html += '        <option value="">Select option</option>';
            html += '        <option value="contacted" >Contactado</option>';
            html += '        <option value="not respond" >No Responde</option>';
            html += '        <option value="dont want" >No desea</option>';
            html += '        <option value="not available" >No disponible</option>';
            html += '        <option value="num email incorrect" >Num/Email incorrecto</option>';
            html += '        <option value="submitted form" >Form enviado</option>';
            html += '        <option value="filled form" >Form llenado</option>';
            html += '    </select>';
            html += '</div>';
            return html;
        }

        function html_check_filter( _id ){
            var html = '';
            html += '<a href="#" class="badge chk-filter badge-secondary" data-value="" data-name="cv" data-id="'+_id+'">CV</a>';
            html += '<a href="#" class="badge chk-filter badge-secondary" data-value="" data-name="experience" data-id="'+_id+'">Experience</a>';
            html += '<a href="#" class="badge chk-filter badge-secondary" data-value="" data-name="communication" data-id="'+_id+'">Communication</a>';
            html += '<a href="#" class="badge chk-filter badge-secondary" data-value="" data-name="english" data-id="'+_id+'">English</a>';
            return html;
        }

        function html_select_schedule( _id ){
            var html = '';
            html += '<div class="form-group">';
            html += '    <select class="form-control form-dropdown" data-name="schedule" data-id="'+_id+'">';
            html += '        <option value="">Select option</option>';
            html += '        <option value="scheduled" >Agendado</option>';
            html += '        <option value="dont want">No puede/desea</option>';
            html += '    </select>';
            html += '</div>';
            return html;
        }

        function html_check_evaluate( _id ){
            var html = '';
            html += '<a href="#" class="badge chk-evaluate badge-secondary" data-value="" data-name="commercial" data-id="'+_id+'">Commercial</a>';
            html += '<a href="#" class="badge chk-evaluate badge-secondary" data-value="" data-name="technique" data-id="'+_id+'">Technique</a>';
            html += '<a href="#" class="badge chk-evaluate badge-secondary" data-value="" data-name="psychology" data-id="'+_id+'">Psychology</a>';
            
            return html;
        }

    });
</script>

@endsection