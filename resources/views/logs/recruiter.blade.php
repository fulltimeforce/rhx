@extends('layouts.app' , ['controller' => 'position'])

@section('styles')

<link rel="stylesheet" type="text/css" href="{{ asset('/datatable/dataTables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/datatable/jquery.dataTables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/datatable/css/dataTables.bootstrap4.min.css') }}"/>

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
                    <td>
                        <div class="form-group">
                            <label for="expert">Name</label>
                            <input type="expert" name="expert" id="name" class="form-control">
                            <input type="hidden" name="id" id="log-id">
                            
                        </div>
                    </td>
                    <td>
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
        <div class="col-12">
            <table class="table row-border order-column" id="table-logs">
                <thead class="thead-dark">
                    <tr>
                    <th>Actions</th>
                    <th>Name</th>
                    <th>Recruiter</th>
                    <th>Date</th>
                    <th>Position</th>
                    <th>Plataform</th>
                    <th>Link</th>
                    <th>Filter</th>
                    <th>Called</th>
                    <th>Scheduled</th>
                    <th>Attended</th>
                    <th>Approve</th>
                    <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($logs as $pid => $log)
                    
                    <tr id="row-{{ $log->id }}" >
                        <td>
                            @if( Auth::id() == $log->user_id || Auth::user()->role->id == 1  )
                                <a class="badge badge-primary log-edit" data-id="{{ $log->id }}" href="#">Edit</a>
                            @endif
                            
                            @if( Auth::id() == $log->user_id || Auth::user()->role->id == 1 )
                                <a class="badge badge-danger log-delete" data-id="{{ $log->id }}" href="#">Delete</a>
                            @endif
                        </td>
                        <td>{{ $log->expert }}</td>
                        <td>{{ $log->user->name }}</td>
                        <td>{{ $log->date }}</td>
                        <td>{{ $log->position->name }}</td>
                        <td>{{ !is_null($log->platform)? collect($platforms)->firstWhere('value' , $log->platform)->label : '' }}  </td>
                        <td>{{ $log->link }}</td>
                        <td>
                            <div class="SliderSwitch"><label for="filter-{{ $log->id }}"><input class="ck-form" value="1" data-id="{{ $log->id }}" id="filter-{{ $log->id }}" name="filter" type="checkbox" {{ $log->filter == 1 ? 'checked' : '' }} /><div class="SliderSwitch__container"><div class="fas SliderSwitch__toggle"></div></div></label></div>
                        </td>
                        <td>
                            <div class="SliderSwitch"><label for="called-{{ $log->id }}"><input class="ck-form" value="1" data-id="{{ $log->id }}" id="called-{{ $log->id }}" name="called" type="checkbox" {{ $log->called == 1 ? 'checked' : '' }} /><div class="SliderSwitch__container"><div class="fas SliderSwitch__toggle"></div></div></label></div>
                        <td>
                            <div class="SliderSwitch"><label for="scheduled-{{ $log->id }}"><input class="ck-form" value="1" data-id="{{ $log->id }}" id="scheduled-{{ $log->id }}" name="scheduled" type="checkbox" {{ $log->scheduled == 1 ? 'checked' : '' }} /><div class="SliderSwitch__container"><div class="fas SliderSwitch__toggle"></div></div></label></div>
                        <td>
                            <div class="SliderSwitch"><label for="attended-{{ $log->id }}"><input class="ck-form" value="1" data-id="{{ $log->id }}" id="attended-{{ $log->id }}" name="attended" type="checkbox" {{ $log->attended == 1 ? 'checked' : '' }} /><div class="SliderSwitch__container"><div class="fas SliderSwitch__toggle"></div></div></label></div>
                        </td>
                        <td>
                            <div class="SliderSwitch"><label for="approve-{{ $log->id }}"><input class="ck-form" value="1" data-id="{{ $log->id }}" id="approve-{{ $log->id }}" name="approve" type="checkbox" {{ $log->approve == 1 ? 'checked' : '' }} /><div class="SliderSwitch__container"><div class="fas SliderSwitch__toggle"></div></div></label></div>
                        </td>
                        <td>{{ $log->created_at }}</td>
                    </tr>
                    
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    

@endsection

@section('javascript')

<script type="text/javascript" src="{{ asset('/datatable/jquery.dataTables.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function (ev) {

        $('#date').datetimepicker({
            format: "{{ config('app.date_format_javascript') }}",
            locale: "en"
        });
        $('#date').val( moment().format("{{ config('app.date_format_javascript') }}") )


        var $_logs = {!! $logs !!};

        var table = $('#table-logs').DataTable({
            "order": [[ 12, "desc" ]],
            scrollY: "500px",
            scrollX: true,
            searching: false,
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
                            data.data.expert,
                            data.data.user_name,
                            data.data.date,
                            {!! $positions !!}.filter(f => f.id == data.data.position_id)[0].name,
                            {!! json_encode($platforms) !!}.filter(f => f.value == data.data.platform)[0].label ,
                            data.data.link,
                            '<div class="SliderSwitch"><label for="filter-'+data.data.id+'"><input class="ck-form" value="1" data-id="'+data.data.id+'" id="filter-'+data.data.id+'" name="filter" type="checkbox" /><div class="SliderSwitch__container"><div class="fas SliderSwitch__toggle"></div></div></label></div>',
                            '<div class="SliderSwitch"><label for="called-'+data.data.id+'"><input class="ck-form" value="1" data-id="'+data.data.id+'" id="called-'+data.data.id+'" name="called" type="checkbox" /><div class="SliderSwitch__container"><div class="fas SliderSwitch__toggle"></div></div></label></div>',
                            '<div class="SliderSwitch"><label for="scheduled-'+data.data.id+'"><input class="ck-form" value="1" data-id="'+data.data.id+'" id="scheduled-'+data.data.id+'" name="scheduled" type="checkbox" /><div class="SliderSwitch__container"><div class="fas SliderSwitch__toggle"></div></div></label></div>',
                            '<div class="SliderSwitch"><label for="attended-'+data.data.id+'"><input class="ck-form" value="1" data-id="'+data.data.id+'" id="attended-'+data.data.id+'" name="attended" type="checkbox" /><div class="SliderSwitch__container"><div class="fas SliderSwitch__toggle"></div></div></label></div>',
                            '<div class="SliderSwitch"><label for="approve-'+data.data.id+'"><input class="ck-form" value="1" data-id="'+data.data.id+'" id="approve-'+data.data.id+'" name="approve" type="checkbox" /><div class="SliderSwitch__container"><div class="fas SliderSwitch__toggle"></div></div></label></div>',
                            data.data.created_at
                        ]).node().id = "row-"+data.data.id;
                        table.draw(false);  
                        $_logs.push({
                            id      : data.data.id,
                            expert: data.data.expert,
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
                    $_logs[index].date = data.date;
                    $_logs[index].position_id = data.position_id;
                    $_logs[index].position = {
                        name : {!! $positions !!}.filter(f => f.id == data.position_id)[0].name,
                        id: data.position_id
                    };
                    $_logs[index].platform = data.platform;
                    $_logs[index].link = data.link;

                    $('#'+ data.id + ' td:nth-child(2)').html( data.expert );
                    $('#'+ data.id + ' td:nth-child(3)').html( data.date ? data.date : '' );
                    $('#'+ data.id + ' td:nth-child(4)').html( data.position_id ? {!! $positions !!}.filter(f => f.id == data.position_id)[0].name : '' );
                    $('#'+ data.id + ' td:nth-child(5)').html( data.platform ? {!! json_encode($platforms) !!}.filter(f => f.value == data.platform)[0].label : '' );
                    $('#'+ data.id + ' td:nth-child(6)').html( data.link? data.link : '' );
                        
                    
                    // clean
                    $("#name").val('').focus();
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


    });
</script>

@endsection