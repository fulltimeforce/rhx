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
                                <option value="">Select option</option>
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
        <div class="col-12">
            <table class="table row-border order-column" id="table-logs-fill"> 
            </table>
        </div>

    </div>
    

@endsection

@section('javascript')

<script type="text/javascript" src="{{ asset('/bootstrap-table/bootstrap-table.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function (ev) {

        $('#date').datetimepicker({
            format: "{{ config('app.date_format_javascript') }}",
            locale: "en"
        });
        $('#date').val( moment().format("{{ config('app.date_format_javascript') }}") )


        var $_logs = {!! $logs !!};

        var a_columns = [
            {
                field: 'id',
                title: "Actions",
                valign: 'middle',
                clickToSelect: false,
                formatter : function(value,row,index) {
                    var _buttons = '';
                    
                    if( "{{ Auth::id() }}" == row.user_id || "{{ Auth::user()->role->id }}" == 1){
                        _buttons += '<a class="badge badge-primary log-edit" data-id="'+row.id+'" href="#">Edit</a>';
                    
                        _buttons += '  <a class="badge badge-danger log-delete" data-id="'+row.id+'" href="#">Delete</a>';
                    }
                    return _buttons;
                },
                class: 'frozencell'
            },
            {   field: 'date', title: 'Date', class: 'frozencell'  },
            {   field: 'user.name', title: 'Recruiter' , class: 'frozencell'   },
            {   field: 'expert', title: 'Name' , class: 'frozencell'  },
            {   field: 'position.name', title: 'Position'   },
            {   field: 'info', title: 'Info'   },
            {   field: 'contact', title: 'Contact', width: 150 , widthUnit: 'px' , formatter: function(value,row,index) { return html_select_contact( row.id , value ) }   },
            {   field: 'filter', title: 'Filter' , formatter: function(value,row,index) { return html_check_filter( row.id , row ) }  },
            {   field: 'schedule', title: 'Schedule' , formatter: function(value,row,index) { return html_select_schedule( row.id , value ) }  },
            {   field: 'evaluate', title: 'Evaluate' , formatter: function(value,row,index) { return html_check_evaluate( row.id , row ) }  },
            {   field: 'platform', title: 'Platform' , formatter: function(value,row,index){ return row.platform ? {!! json_encode($platforms) !!}.filter(f => f.value == row.platform)[0].label : ''; }   },
            {   field: 'link', title: 'Link'   },

        ];

        function update_table_logs(){
            $("#table-logs-fill").bootstrapTable('destroy').bootstrapTable({
                height: 500,
                pagination: true,
                sidePagination: "server",
                columns: a_columns,
                fixedColumns: true,
                fixedNumber: 4,
                theadClasses: 'table-dark',
                showExtendedPagination: true,
                uniqueId: 'id',
                pageSize: 25,
                totalNotFilteredField: 'totalNotFiltered',
                url : "{{ route('recruiter.listlogs') }}",
                queryParams : function(params){
                    var offset = params.offset;
                    var limit = params.limit;
                    var page = (offset / limit) + 1;
                    return {'offset': offset,'limit':params.limit,'page' : page};
                }

            });
        }
        update_table_logs();
        

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

                    update_table_logs();
                    // $("#table-logs-fill").bootstrapTable('insertRow', {index: 0, row: data});

                    $_logs.push({
                        id      : data.id,
                        expert: data.expert,
                        info: data.info,
                        position:{
                            id: data.position_id,
                            name: data.position_id != null ? {!! $positions !!}.filter(f => f.id == data.position_id)[0].name : null
                        },
                        date   : data.date,
                        position_id : data.position_id,
                        platform : data.platform,
                        link : data.link,
                        filter: "-",
                        called: "-",
                        scheduled: "-",
                        attended: "-",
                        approve: "-",
                        created_at : data.created_at
                    });

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

                    var index = $_logs.findIndex( f => f.id == data.id);
                    console.log(index, "dddddd");
                    $_logs[index].expert = data.expert;
                    $_logs[index].info = data.info;
                    $_logs[index].date = data.date;
                    $_logs[index].position_id = data.position_id;
                    $_logs[index].position = {
                        name : data.position_id != null ? {!! $positions !!}.filter(f => f.id == data.position_id)[0].name : null ,
                        id: data.position_id
                    };
                    $_logs[index].platform = data.platform;
                    $_logs[index].link = data.link;
                    update_table_logs();    
                    // $("#table-logs-fill").bootstrapTable('updateByUniqueId', {id: data.id, row: data }).
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
                    update_table_logs();
                    
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

            if( val == null ){
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

            if( val == null ){
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

        function html_select_contact( _id , _contact){
            var html = '';
            html += '<div class="form-group">';
            html += '    <select class="form-control form-dropdown" data-name="contact" data-id="'+_id+'" style="width: 200px;">';
            html += '        <option value="" >Select option</option>';
            html += '        <option value="contacted" '+(_contact=='contacted'? 'selected' : '' )+'>Contactado</option>';
            html += '        <option value="not respond" '+(_contact=='not respond'? 'selected' : '' )+'>No Responde</option>';
            html += '        <option value="dont want" '+(_contact=='dont want'? 'selected' : '' )+'>No desea</option>';
            html += '        <option value="not available" '+(_contact=='not available'? 'selected' : '' )+'>No disponible</option>';
            html += '        <option value="num email incorrect" '+(_contact=='num email incorrect'? 'selected' : '' )+'>Num/Email incorrecto</option>';
            html += '        <option value="submitted form" '+(_contact=='submitted form'? 'selected' : '' )+'>Form enviado</option>';
            html += '        <option value="filled form" '+(_contact=='filled form'? 'selected' : '' )+'>Form llenado</option>';
            html += '    </select>';
            html += '</div>';
            return html;
        }

        function html_check_filter( _id, _filter ){
            var html = '';
            html += '<a href="#" class="badge chk-filter badge-'+ ( _filter.cv == null ? 'secondary' : (_filter.cv == 'approved' ? 'success' : 'danger') ) +'" data-value="'+_filter.cv+'" data-name="cv" data-id="'+_id+'">CV</a>\n';
            html += '<a href="#" class="badge chk-filter badge-'+ ( _filter.experience == null ? 'secondary' : (_filter.experience == 'approved' ? 'success' : 'danger') ) +'" data-value="'+_filter.experience+'" data-name="experience" data-id="'+_id+'">Experience</a>\n';
            html += '<a href="#" class="badge chk-filter badge-'+ ( _filter.communication == null ? 'secondary' : (_filter.communication == 'approved' ? 'success' : 'danger') ) +'" data-value="'+_filter.communication+'" data-name="communication" data-id="'+_id+'">Communication</a>\n';
            html += '<a href="#" class="badge chk-filter badge-'+ ( _filter.english == null ? 'secondary' : (_filter.english == 'approved' ? 'success' : 'danger') ) +'" data-value="'+_filter.english+'" data-name="english" data-id="'+_id+'">English</a>';
            return html;
        }

        function html_select_schedule( _id , _schedule){
            var html = '';
            html += '<div class="form-group">';
            html += '    <select class="form-control form-dropdown" data-name="schedule" data-id="'+_id+'" style="width: 150px;">';
            html += '        <option value="">Select option</option>';
            html += '        <option value="scheduled" '+( _schedule=='scheduled'? 'selected' : '' )+'>Agendado</option>';
            html += '        <option value="dont want" '+( _schedule=='dont want'? 'selected' : '' )+'>No puede/desea</option>';
            html += '    </select>';
            html += '</div>';
            return html;
        }

        function html_check_evaluate( _id , _evaluate){
            var html = '';
            html += '<a href="#" class="badge chk-evaluate badge-'+ ( _evaluate.commercial == null ? 'secondary' : (_evaluate.commercial == 'approved' ? 'success' : ( _evaluate.commercial == 'not approved' ? 'danger' : 'warning' ) ) ) +'" data-value="'+_evaluate.commercial+'" data-name="commercial" data-id="'+_id+'">Commercial</a>\n';
            html += '<a href="#" class="badge chk-evaluate badge-'+ ( _evaluate.technique == null ? 'secondary' : (_evaluate.technique == 'approved' ? 'success' : ( _evaluate.technique == 'not approved' ? 'danger' : 'warning' ) ) ) +'" data-value="'+_evaluate.technique+'" data-name="technique" data-id="'+_id+'">Technical</a>\n';
            html += '<a href="#" class="badge chk-evaluate badge-'+ ( _evaluate.psychology == null ? 'secondary' : (_evaluate.psychology == 'approved' ? 'success' : ( _evaluate.psychology == 'not approved' ? 'danger' : 'warning' ) ) ) +'" data-value="'+_evaluate.psychology+'" data-name="psychology" data-id="'+_id+'">Psychological</a>\n';
            
            return html;
        }

    });
</script>

@endsection