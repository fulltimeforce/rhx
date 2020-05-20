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
@keyframes lds-ring {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

</style>
@endsection
 
@section('content')
    <div class="row">
        <div class="col">
            <h1 class="d-inline-block">Logs (<span id="count-logs"></span>)</h1>
            
        </div>
    </div>
   
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="modal fade" id="listexperts" tabindex="-1" role="dialog" aria-labelledby="listexpertsLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="listexpertsLabel">Select Expert</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row mb-3">
                <div class="col">
                    <div class="form-group">
                        <input type="hidden" id="log-id-modal">
                        <input type="text" class="form-control" id="search-experts" placeholder="Search">
                    </div>  
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <table id="list-experts"></table>
                </div>
            </div>
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>

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
                    <input type="hidden" id="delete-audio-log">
                    <input type="hidden" id="delete-audio-log-type">
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


    <!-- ============================ NOTES ============================-->
    <div class="modal fade" id="noteLogModal" tabindex="-1" role="dialog" aria-labelledby="noteLogModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="noteLogModalLabel"><span id="log-name"></span> - <span id="type-name" class="text-capitalize"></span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12" id="section-note-date">
                    <label for="">Date</label>
                    <input type="text" class="form-control" name="" id="date_note" data-toggle="datetimepicker" data-target="#date_note">
                </div>
                <div class="col-12">
                    <label for="">Note</label>
                    <textarea id="note-log" cols="30" rows="10" class="form-control"></textarea>
                    <input type="hidden" id="log_id_note">
                    <input type="hidden" id="log_type_note">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="saveNote">Save</button>
        </div>
        </div>
    </div>
    </div>
    
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
                                <option value="">None</option>
                                @foreach($positions as $pid => $position)
                                    <option value="{{ $position->id }}">{{ $position->name }}</option>
                                @endforeach
                            </select>
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
                        <div class="form-group" id="btn-form-save">
                            <button type="button" id="save" class="btn btn-success">SAVE</button>
                        </div>
                        <div class="form-group" id="btn-form-edit" style="display:none;">
                            <button type="button" id="edit" class="btn btn-success">Edit</button>
                            <button type="button" id="clear" class="btn btn-info">Clear</button>
                        </div>
                    </td>
                </tr>
            </table>
            </form>
        </div>

        <div class="col-6">
            <h5>Records: </h5>
        </div>
        
        <div class="col-6 text-right">
            <div class="form-group d-inline-block" style="max-width: 300px;">
                <input type="text" placeholder="Search By Expert" class="form-control" id="search-column-name">
            </div>
            <button class="btn btn-primary" id="search-log" type="button" style="vertical-align: top;">Buscar</button>
        </div>
        <div class="col-12 text-center mb-5">
            <table class="table row-border order-column" id="table-logs-fill"> 
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
        $('.lds-ring').hide();
        $('#date').datetimepicker({
            format: "{{ config('app.date_format_javascript') }}",
            locale: "en"
        });
        $('#date').val( moment().format("{{ config('app.date_format_javascript') }}") )

        $('#date_note').datetimepicker({
            format: "{{ config('app.date_format_javascript') }}",
            locale: "en"
        });

        var $_logs = {!! $logs !!};

        var search = "{{ $s }}";
        $('#search-column-name').val( search );
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
            {   field: 'contact', title: 'Contact', width: 150 , widthUnit: 'px' , formatter: function(value,row,index) { return html_select_contact( row.id , value , row.experts) }   },
            {   field: 'filter', title: 'Filter' , formatter: function(value,row,index) { return html_check_filter( row.id , row ) }  },
            // {   field: 'schedule', title: 'Schedule' , formatter: function(value,row,index) { return html_select_schedule( row.id , value ) }  },
            {   field: 'evaluate', title: 'Evaluate' , formatter: function(value,row,index) { return html_check_evaluate( row.id , row ) }  },
            {   field: 'platform', title: 'Platform' , formatter: function(value,row,index){ return row.platform ? {!! json_encode($platforms) !!}.filter(f => f.value == row.platform)[0].label : ''; }   },
            {   field: 'link', title: 'Link' , formatter: function(value,row,index){ 
                var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
                    '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
                    '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
                    '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
                    '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
                    '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
                if( !!pattern.test(value) ){
                    return '<a href="'+value+'" target="_blank">'+value+'</a>';
                }else{
                    return value;
                }
             }  },

        ];

        function table_list_experts( search_name ){
            $("#list-experts").bootstrapTable('destroy').bootstrapTable({
                height: 500,
                pagination: true,
                sidePagination: "server",
                columns: [
                    { field: 'fullname', title: "Expert" },
                    { field: 'id', title: "Actions" , formatter: function(value,rowData,index){
                        
                        return '<a class="badge badge-primary btn-select-expert" data-expert="'+rowData.id+'" href="#">Select</a>\n';
                    } },
                ],
                showExtendedPagination: true,
                totalNotFilteredField: 'totalNotFiltered',
                url : "{{ route('expert.listtbootstrap') }}",
                theadClasses: 'table-dark',
                uniqueId: 'id',
                pageSize: 50,
                queryParams : function(params){
                    var offset = params.offset;
                    var limit = params.limit;
                    var page = (offset / limit) + 1;
                    return {
                        'offset': offset,
                        'rows':params.limit,
                        'page' : page , 
                        'basic': [] , 
                        'intermediate': [] ,
                        'advanced' : [],
                        'name' : search_name
                    };
                }

            });
        }
        
        var _records = 50;
        var _total_records = 0;
        var _count_records = 0;

        var loading = false;
        var scroll_previus = 0;
        var _page = 1;

        function update_table_logs( _data ){
            $("#table-logs-fill").bootstrapTable('destroy').bootstrapTable({

                columns: a_columns,
                fixedColumns: true,
                fixedNumber: 4,
                theadClasses: 'table-dark',
                // showExtendedPagination: true,
                uniqueId: 'id',
                // pageSize: 25,
                data: _data,   

            });
        }

        function ajax_logs( _text ){
            
            var data = {
                    'limit': _records,
                    'page' : _page , 
                    'name' : _text
            };
            $('.lds-ring').show();
            $.ajax({
                type:'GET',
                url: '{{ route("recruiter.listlogs") }}',
                data: $.param(data),
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function( _data ){

                    update_table_logs( _data.rows );
                    _total_records = _data.total;
                    $("#count-logs").html(_data.total)
                    _count_records = _count_records + _data.rows.length;
                    $('.lds-ring').hide();
                }
            });
        }

        ajax_logs( search );

        $(window).on('scroll', function (e){
            
            if($(window).scrollTop() + $(window).height() == $(document).height()) {
                if( _count_records < _total_records ){
                    _page++;
                    var _text = $('#search-column-name').val();
                    var data = {
                            'limit': _records,
                            'page' : _page , 
                            'name' : _text
                    };
                    $('.lds-ring').show();
                    $.ajax({
                        type:'GET',
                        url: '{{ route("recruiter.listlogs") }}',
                        data: $.param(data),
                        headers: {
                            'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success:function(_data){

                            $("#table-logs-fill").bootstrapTable('append', _data.rows )
                            
                            _count_records = _count_records + _data.rows.length;
                            $('.lds-ring').hide();
                            
                        }
                    });
                }
            }
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

        $("#search-log").on('click' , function(){
            var text = $('#search-column-name').val();
            _page = 1;
            _count_records = 0;
            
            search = text;
            window.history.replaceState({
                edwin: "Fulltimeforce"
                }, "Page" , "{{ route('recruiter.log') }}" + '?'+ $.param(
                    {   
                        s : search , 
                    }
                    )
                );
            _page = 1;
            _count_records = 0;
            location.reload();

            // ajax_logs( text );
            is_jqgrid = true;
        });

        $("#search-column-name").keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                var text = $('#search-column-name').val();
                _page = 1;
                _count_records = 0;
                
                search = text;
                window.history.replaceState({
                    edwin: "Fulltimeforce"
                    }, "Page" , "{{ route('recruiter.log') }}" + '?'+ $.param(
                        {   
                            s : search , 
                        }
                        )
                    );
                _page = 1;
                _count_records = 0;
                location.reload();

                // ajax_logs( text );
                is_jqgrid = true;
            }
        })


        $('#search-experts').on( 'keyup', delay(function (ev) {
            var text = $(this).val();

            table_list_experts( text );

        } , 500 ));

        

        $("#save").on('click', function(ev){
            $('#search-column-name').val( '' );
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
                    _page = 1;
                    _count_records = 0;
                    ajax_logs( '' );

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
                    
                    _page = 1;
                    _count_records = 0;
                    ajax_logs( '' );    
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
                    _page = 1;
                    _count_records = 0;
                    ajax_logs( '' );
                    
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
                $(this).data('value' , null)
                val = null;
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

        var previus;
        var select_expert = false;
        $('table')
        .on('focus' , '.form-dropdown' , function(ev){
            previus = this.value;
        })
        .on('change' , '.form-dropdown' , function(ev){
            var id = $(this).data("id");
            var attr = $(this).data("name");
            var val = $(this).val();
            var data_post = {};
            data_post["id"] = id;
            data_post[attr] = val;
            console.log(data_post);
            if( attr == 'contact' && val == 'filled form' ){
                select_expert = false;
                $('#listexperts').modal();
                $("#log-id-modal").val(id);
                
            }else{
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
            }
            
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
                $(this).data('value' , null)
                val = null;
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

        function html_select_contact( _id , _contact, _experts){
            var html = '';
            if( _contact == 'filled form' && _experts.length > 0 ){
                for (let index = 0; index < _experts.length; index++) {
                    let params = $.param({
                        'search' : true,
                        'basic': "" , 
                        'intermediate': "" ,
                        'advanced' : "",
                        'name' : _experts[index].fullname
                    });
                    html += '<p><a href="'+ '{{ route("experts.home") }}' +'?'+params+'" target="_blank">'+_experts[index].fullname+'</a>   <a href="#" class="text-danger remove-expert-log" data-log="'+_id+'" data-expert="'+_experts[index].id+'" ><i class="far fa-times-circle"></i></a></p>';
                }
            }else{
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
            }
            
            return html;
        }

        function html_check_filter( _id, _filter ){
            var html = '';
            html += '<div class="btn-group" role="group">';
            html += '<a href="#" class="badge chk-filter badge-'+ ( _filter.cv == null ? 'secondary' : (_filter.cv == 'approved' ? 'success' : 'danger') ) +'" data-value="'+_filter.cv+'" data-name="cv" data-id="'+_id+'">CV</a>\n';
            html += '<a href="#" class="badge badge-primary check-note" data-name="cv" data-id="'+_id+'"><i class="fas fa-pen"></i></a>';
            html += '</div>';
            
            
            
            html += '<div class="btn-group" role="group">';
            html += '<a href="#" class="badge chk-filter badge-'+ ( _filter.experience == null ? 'secondary' : (_filter.experience == 'approved' ? 'success' : 'danger') ) +'" data-value="'+_filter.experience+'" data-name="experience" data-id="'+_id+'">Experience</a>\n';
            html += '<a href="#" class="badge badge-primary check-note" data-name="experience" data-id="'+_id+'"><i class="fas fa-pen"></i></a>';
            html += '</div>';

            html += '<div class="btn-group" role="group">';
            html += '<a href="#" class="badge chk-filter badge-'+ ( _filter.communication == null ? 'secondary' : (_filter.communication == 'approved' ? 'success' : 'danger') ) +'" data-value="'+_filter.communication+'" data-name="communication" data-id="'+_id+'">Communication</a>\n';
            html += '<a href="#" class="badge badge-primary check-note" data-name="communication" data-id="'+_id+'"><i class="fas fa-pen"></i></a>';
            html += '</div>';

            html += '<div class="btn-group" role="group">';
            html += '<a href="#" class="badge chk-filter badge-'+ ( _filter.english == null ? 'secondary' : (_filter.english == 'approved' ? 'success' : 'danger') ) +'" data-value="'+_filter.english+'" data-name="english" data-id="'+_id+'">English</a>';
            html += '<a href="#" class="badge badge-primary check-note" data-name="english" data-id="'+_id+'"><i class="fas fa-pen"></i></a>';
            html += '</div>';

            
            
            // html += '<label class="badge badge-'+ (_filter.filter_audio == null? 'secondary' : 'success' ) +'" for="audio-upload-'+_id+'">UPLOAD AUDIO</label>';
            
            html += '<div class="btn-group mt-2 btn-upload-audio '+( _filter.filter_audio == null ? '' : 'd-none')+'" role="group" data-id="'+_id+'" data-type="filter"> ';
            html += '<label class="badge badge-secondary" for="audio-upload-filter-'+_id+'">Upload Audio</label>';
            html += '<input type="file" class="custom-file-input audio-upload" id="audio-upload-filter-'+_id+'" data-type="filter" data-id="'+_id+'" style="display:none;" >';
            html += '</div>';
        
        
            html += '<div class="btn-group btn-show-audio '+( _filter.filter_audio != null ? '' : 'd-none')+'" role="group" data-id="'+_id+'" data-type="filter">';
            html += '<a href="#" class="badge badge-success show-audio" data-audio="'+_filter.filter_audio+'" data-type="filter" data-id="'+_id+'">Show Audio</a>';
            html += '<a href="#" class="badge badge-primary confirmation-upload-delete" data-type="filter" data-id="'+_id+'"><i class="fas fa-trash"></i></a>';
            html += '</div>';

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
            html += '<div class="btn-group" role="group">';
            html += '<a href="#" class="badge chk-evaluate badge-'+ ( _evaluate.commercial == null ? 'secondary' : (_evaluate.commercial == 'approved' ? 'success' : ( _evaluate.commercial == 'not approved' ? 'danger' : 'warning' ) ) ) +'" data-value="'+_evaluate.commercial+'" data-name="commercial" data-id="'+_id+'">Commercial</a>\n';
            html += '<a href="#" class="badge badge-primary check-note" data-name="commercial" data-id="'+_id+'"><i class="fas fa-pen"></i></a>';
            html += '</div>';

            html += '<div class="btn-group" role="group">';
            html += '<a href="#" class="badge chk-evaluate badge-'+ ( _evaluate.technique == null ? 'secondary' : (_evaluate.technique == 'approved' ? 'success' : ( _evaluate.technique == 'not approved' ? 'danger' : 'warning' ) ) ) +'" data-value="'+_evaluate.technique+'" data-name="technique" data-id="'+_id+'">Technical</a>\n';
            html += '<a href="#" class="badge badge-primary check-note" data-name="technique" data-id="'+_id+'"><i class="fas fa-pen"></i></a>';
            html += '</div>';

            html += '<div class="btn-group" role="group">';
            html += '<a href="#" class="badge chk-evaluate badge-'+ ( _evaluate.psychology == null ? 'secondary' : (_evaluate.psychology == 'approved' ? 'success' : ( _evaluate.psychology == 'not approved' ? 'danger' : 'warning' ) ) ) +'" data-value="'+_evaluate.psychology+'" data-name="psychology" data-id="'+_id+'">Psychological</a>\n';
            html += '<a href="#" class="badge badge-primary check-note" data-name="psychology" data-id="'+_id+'"><i class="fas fa-pen"></i></a>';
            html += '</div>';

            html += '<div class="btn-group mt-2 btn-upload-audio '+( _evaluate.evaluate_audio == null ? '' : 'd-none')+'" role="group" data-id="'+_id+'" data-type="evaluate"> ';
            html += '<label class="badge badge-secondary" for="audio-upload-evaluate-'+_id+'">Upload Audio</label>';
            html += '<input type="file" class="custom-file-input audio-upload" id="audio-upload-evaluate-'+_id+'" data-type="evaluate" data-id="'+_id+'" style="display:none;" >';
            html += '</div>';
        
        
            html += '<div class="btn-group btn-show-audio '+( _evaluate.evaluate_audio != null ? '' : 'd-none')+'" role="group" data-id="'+_id+'" data-type="evaluate">';
            html += '<a href="#" class="badge badge-success show-audio" data-audio="'+_evaluate.evaluate_audio+'" data-id="'+_id+'" data-type="evaluate">Show Audio</a>';
            html += '<a href="#" class="badge badge-primary confirmation-upload-delete" data-type="evaluate" data-id="'+_id+'"><i class="fas fa-trash"></i></a>';
            html += '</div>';

            return html;
        }
        
        $('#listexperts').on('click' , '.btn-select-expert' , function(ev){
            ev.preventDefault();
            var expert_id = $(this).data("expert");
            var log_id = $("#log-id-modal").val();
            select_expert = false;
            $.ajax({
                type:'POST',
                url: "{{ route('expert.log.union') }}",
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    expert_id : expert_id,
                    log_id: log_id
                },
                success:function(data){
                    select_expert = true;
                    $("#listexperts").modal('hide');
                    location.reload();
                }
            });
        });

        $('#listexperts').on('hidden.bs.modal', function (e) {
            console.log("fffff");
            var id = $("#log-id-modal").val();
            if(!select_expert){
                $('.form-dropdown[data-id="'+id+'"]').val(previus);
            }
            $("#search-experts").val('');
            $("#log-id-modal").val('');
            $("#list-experts").bootstrapTable('destroy');
        });

        $("table").on('click' , '.check-note' , function(ev){
            ev.preventDefault();
            var id = $(this).data("id");
            var type = $(this).data("name");

            var data_post = {};
            data_post["log_id"] = id;
            data_post["type"] = type;
            console.log(data_post);
            $.ajax({
                type:'POST',
                url: "{{ route('log.note') }}",
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data_post,
                success:function(data){
                    console.log(data)
                    $('#date_note').val( moment().format("{{ config('app.date_format_javascript') }}") );
                    if(data){
                        $("#note-log").val( data.note );
                        $('#date_note').val( data.date == null?  moment().format("{{ config('app.date_format_javascript') }}") : data.date )
                    }
                    var index = $_logs.findIndex( f => f.id == id);
                    $("#log_id_note").val( id );
                    $("#log_type_note").val( type );
                    $("#log-name").html( $_logs[index].expert );
                    $("#section-note-date").hide();
                    $("#type-name").html(type);
                    if( type == 'commercial' || type == 'technique' || type == 'psychology' ){
                        $("#section-note-date").show();
                    }
                    $("#noteLogModal").modal();
                    
                }
            });

        });

        $('table').on('click' , '.remove-expert-log', function(ev){
            ev.preventDefault();
            var log = $(this).data("log");
            var expert = $(this).data("expert");
            let data_post = {
                log : log,
                expert: expert
            };
            $.ajax({
                type:'POST',
                url: "{{ route('recruiterlog.expert.delete') }}",
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data_post,
                success:function(data){
                    console.log(data)
                    location.reload();

                }
            });

        });

        $("#saveNote").on('click' , function(){
            var data_post = {
                log_id: $("#log_id_note").val(),
                type: $("#log_type_note").val(),
                note: $("#note-log").val(),
                date: $("#date_note").val(),
            };
            $.ajax({
                type:'POST',
                url: "{{ route('log.note.save') }}",
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data_post,
                success:function(data){
                    $("#noteLogModal").modal('hide');
                }
            });
        });

        $('#noteLogModal').on('hidden.bs.modal', function (e) {
            $("#log_id_note").val( '' );
            $("#log_type_note").val( '' );
            $("#log-name").html( '' );
            $("#note-log").val( '' );
        });

        $('body').on('change' , '.audio-upload' , function(ev){
            // ev.preventDefault();
            var file = this.files[0];
            var id = $(this).data("id");
            var type = $(this).data("type");

            var data_post = {};
            data_post["log_id"] = id;
            data_post["type"] = type;
            
            var _this = $(this);
            var _formData = new FormData();
            _formData.append('file', file);
            _formData.append('log_id', id);
            _formData.append('type', type);

            $.ajax({
                type:'POST',
                url: "{{ route('recruiter.upload.audio') }}",
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                contentType: false,
                cache: false,
                processData: false,
                data: _formData,
                success:function(data){
                    console.log(data)
                    $('.btn-upload-audio[data-id="'+id+'"][data-type="'+type+'"]').addClass("d-none");
                    $('.btn-show-audio[data-id="'+id+'"][data-type="'+type+'"]').removeClass("d-none");
                    $('.show-audio[data-id="'+id+'"][data-type="'+type+'"]').attr("data-audio" , data.file)
                    
                }
            });
        })

        $("body").on('click' , '.confirmation-upload-delete' , function(ev){
            ev.preventDefault();
            var id = $(this).data("id");
            var type = $(this).data("type");

            $("#delete-audio-log").val(id);
            $("#delete-audio-log-type").val(type);

            $("#delete-audio").modal();

        })

        $('#delete-audio').on('hidden.bs.modal', function (e) {
            $("#delete-audio-log").val("");
            $("#delete-audio-log-type").val("");
        })

        $("#deleteAudio").on('click' , function(){

            $.ajax({
                type:'POST',
                url: "{{ route('recruiter.delete.audio') }}",
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    log_id : $("#delete-audio-log").val(),
                    type: $("#delete-audio-log-type").val()
                },
                success:function(data){
                    console.log(data)
                    var id = $("#delete-audio-log").val();
                    var type = $("#delete-audio-log-type").val();
                    $('.btn-upload-audio[data-id="'+id+'"][data-type="'+type+'"]').removeClass("d-none");
                    $('.btn-show-audio[data-id="'+id+'"][data-type="'+type+'"]').addClass("d-none");
                    $("#delete-audio").modal('hide');
                }
            });

        });

        $('body').on('click' , '.show-audio' ,function(ev){
            ev.preventDefault();
            var audio = $(this).data("audio");
            var h = "{{ route('home') }}";
            $("#audio-play").attr("src" , audio);
            $("#show-audio").modal();
        })

        $('#show-audio').on('hidden.bs.modal', function (e) {
            $("#audio-play").attr("src" , "");
        })

    });
</script>

@endsection