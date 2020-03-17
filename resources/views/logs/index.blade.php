@extends('layouts.app' , ['controller' => 'position'])

@section('styles')

<link rel="stylesheet" type="text/css" href="{{ asset('/datatable/dataTables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/datatable/jquery.dataTables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/datatable/css/dataTables.bootstrap4.min.css') }}"/>

<style>
    /* The switch - the box around the slider */

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
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <input type="hidden" name="id" id="log-id">
                            <input type="hidden" name="expert_id" id="expert_id">
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <label for="name">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control">
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <label for="position">Positions</label>
                            <select id="position" class="form-control" name="position" >
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
                        <div class="form-group">
                            <button type="button" id="save" class="btn btn-success">SAVE</button>
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
                    <th>Phone</th>
                    <th>Position</th>
                    <th>Plataform</th>
                    <th>Link</th>
                    <th>Form</th>
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
                    <tr id="{{ $log->id }}" >
                        <td>
                            <a href="#" data-id="{{ $log->id }}" class="badge badge-success btn-edit">Edit</a>
                            @if( $log->form == 0 )
                            <a href="#" data-id="{{ $log->id }}" data-toggle="tooltip" data-placement="top" title="Copied..!!" data-expert="{{ $log->expert_id }}" class="badge badge-info btn-link">Link</a>
                            @endif
                            @if( $log->user_id == 0 )
                            <a href="#" data-id="{{ $log->id }}" class="badge badge-primary btn-take">Take</a>
                            @endif
                        </td>
                        <td>{{ $log->expert->fullname }}</td>
                        <td>{{ $log->expert->phone }}</td>
                        <td>{{ $log->position->name }}</td>
                        <td>{{ !is_null($log->platform)? collect($platforms)->firstWhere('value' , $log->platform)->label : '' }}  </td>
                        <td>{{ $log->link }}</td>
                        <td class="text-center">
                            <i class="fas {{ $log->form == 1 ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }} fa-2x"></i>
                        </td>
                        <td>{{ $log->filter }}</td>
                        <td>{{ $log->called }}</td>
                        <td>{{ $log->scheduled }}</td>
                        <td>{{ $log->attended }}</td>
                        <td>{{ $log->approve }}</td>
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
        var $_logs = {!! $logs !!};
        console.log($_logs);
        var url_ajax = '{!! env("APP_URL_AJAX") !!}';

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
                url: "{{ route('logs.updateForm') }}",
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:  $("#new-log input , #new-log select").serialize() ,
                success:function(data){
                    console.log(data);
                    // return;
                    if(data.type == 'create'){
                        var edit = '<a href="#" data-id="'+data.data.id+'" class="badge badge-primary btn-edit">Edit</a>';
                        var link = '    <a href="#" data-toggle="tooltip" data-placement="top" title="Copied..!!" data-id="'+data.data.id+'" data-expert="'+data.data.expert_id+'" class="badge badge-info btn-link">Link</a>';
                        table.row.add([
                            edit + link,
                            data.data.name,
                            data.data.phone,
                            {!! $positions !!}.filter(f => f.id == data.data.position_id)[0].name,
                            {!! json_encode($platforms) !!}.filter(f => f.value == data.data.platform)[0].label ,
                            data.data.link,
                            '<i class="fas fa-times-circle text-danger fa-2x"></i>',
                            '-',
                            '-',
                            '-',
                            '-',
                            '-',
                            data.data.created_at
                        ]).node().id = data.data.id;
                        table.draw(false);  
                        $_logs.push({
                            id      : data.data.id,
                            expert: {
                                fullname : data.data.name,
                                phone: data.data.phone,
                            },
                            position:{
                                id: data.data.position_id,
                                name: {!! $positions !!}.filter(f => f.id == data.data.position_id)[0].name
                            },
                            // phone   : data.data.phone,
                            position_id : data.data.position_id,
                            platform : data.data.platform,
                            link : data.data.link,
                            filter: "-",
                            called: "-",
                            scheduled: "-",
                            attended: "-",
                            approve: "-",
                            expert_id: data.data.expert_id,
                            created_at : data.data.created_at
                        });
                    }else{
                        var index = $_logs.findIndex( f => f.id == data.data.id);
                        console.log(index, "dddddd");
                        $_logs[index].expert = {
                            fullname : data.data.name,
                            phone: data.data.phone,
                        };
                        $_logs[index].expert_id = data.data.expert_id;
                        $_logs[index].position_id = data.data.position_id;
                        $_logs[index].position = {
                            name : {!! $positions !!}.filter(f => f.id == data.data.position_id)[0].name,
                            id: data.data.position_id
                        };
                        $_logs[index].platform = data.data.platform;
                        $_logs[index].link = data.data.link;

                        $('#'+ data.data.id + ' td:nth-child(2)').html( data.data.name );
                        $('#'+ data.data.id + ' td:nth-child(3)').html( data.data.phone ? data.data.phone : '' );
                        $('#'+ data.data.id + ' td:nth-child(4)').html( data.data.position_id ? {!! $positions !!}.filter(f => f.id == data.data.position_id)[0].name : '' );
                        $('#'+ data.data.id + ' td:nth-child(5)').html( data.data.platform ? {!! json_encode($platforms) !!}.filter(f => f.value == data.data.platform)[0].label : '' );
                        $('#'+ data.data.id + ' td:nth-child(6)').html( data.data.link? data.data.link : '' );
                        
                    }
                    // clean
                    $("#name").val('').focus();
                    $("#phone").val('');
                    $("#link").val('');
                    $("#log-id").val('');
                    $("#expert_id").val('');
                    $("#log-id").val('');
                }
            });
        });

        $('table').on('click', '.btn-edit', function(ev){
            ev.preventDefault();
            var id = $(this).data("id");
            var log = $_logs.filter( f => f.id == id);

            if(log.length > 0){
                $("#log-id").val(log[0].id);
                $("#name").val(log[0].expert.fullname);
                $("#phone").val(log[0].expert.phone);
                $("#position").val(log[0].position_id);
                $("#platform").val(log[0].platform);
                $("#link").val(log[0].link);
                $("#expert_id").val(log[0].expert_id);

            }

        });

        $('table').on('click', '.btn-link', function(ev){
            ev.preventDefault();
            var expert = $(this).data("expert");
            var logId = $(this).data("id");

            var _this = this;
            var url = '{{ route("developer.edit.signed" , ["expertId" => ":expertId" ]) }}';
            url = url.replace(":expertId" , expert);
            
            $.ajax({
                type: 'GET',
                url: url,
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
               
                success:function(data){

                    var el = document.createElement("textarea");
                    el.value = data;
                    el.style.top = '0';
                    el.setSelectionRange(0, 99999);
                    el.setAttribute('readonly', ''); 
                    _this.appendChild(el);
                    el.focus();
                    el.select();
                    var success = document.execCommand('copy')
                    _this.removeChild(el);

                }

            });

        });

        $('table').on('click', '.btn-take', function(ev){
            ev.preventDefault();
            var _id = $(this).data("id");
            var _this = $(this);
            $.ajax({
                type: 'POST',
                url: "{{ route('logs.takeUser') }}",
                data: { id: _id },
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){
                    _this.remove();
                }

            });
        });

    });
</script>

@endsection