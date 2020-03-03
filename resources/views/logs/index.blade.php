@extends('layouts.app' , ['controller' => 'position'])

@section('styles')

<link rel="stylesheet" type="text/css" href="{{ asset('/datatable/dataTables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/datatable/css/dataTables.bootstrap4.min.css') }}"/>

<style>
    /* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

/* Hide default HTML checkbox */
.switch input {display:none;}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input.default:checked + .slider {
  background-color: #444;
}
input.primary:checked + .slider {
  background-color: #2196F3;
}
input.success:checked + .slider {
  background-color: #8bc34a;
}
input.info:checked + .slider {
  background-color: #3de0f5;
}
input.warning:checked + .slider {
  background-color: #FFC107;
}
input.danger:checked + .slider {
  background-color: #f44336;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
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
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control">
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <label for="positions">Positions</label>
                            <select id="positions" class="form-control" name="positions" >
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
            <table class="table" id="table-logs">
                <thead>
                    <tr>
                    <th>Actions</th>
                    <th>Name</th>
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
                    <tr>
                        <td></td>
                        <td>{{ $log->name }}</td>
                        <td>{{ $log->position->name }}</td>
                        <td>{{ collect($platforms)->firstWhere('value' , $log->platform)->label   }}  </td>
                        <td>{{ $log->link }}</td>
                        <td>
                            <label class="switch ">
                                <input type="checkbox" name="form" id="form" class="primary">
                                <span class="slider"></span>
                            </label>
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

        var url_ajax = '{!! env("APP_URL_AJAX") !!}';

        // var tfConfig = {
        //     alternate_rows: true,
        //     highlight_keywords: true,
        //     responsive: true,
        //     rows_counter: true,
        //     popup_filters: true,
        //     base_path: url_ajax + 'tablefilter/',
        //     paging: {
        //         results_per_page: ['Records: ', [10, 25, 50, 100]]
        //     },
        //     themes: [{
        //         name: 'transparent'
        //     }]
        // };
        // var tf = new TableFilter('table-logs',tfConfig);
        // tf.init();

        var table = $('#table-logs').DataTable({
            "order": [[ 11, "desc" ]],
            scrollY: "500px",
            scrollX: true,
        });

        var column = table.column( 11 );

        column.visible(false);

        $("#save").on('click', function(ev){
            
            $.ajax({
                type:'POST',
                url: url_ajax + 'logs/store',
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:  $("#new-log input , #new-log select").serialize() ,
                success:function(data){
                    console.log(data);
                    table.row.add([
                        '',
                        data.name,
                        {!! $positions !!}.filter(f => f.id == data.positions)[0].name,
                        data.platform,
                        data.link,
                        0,
                        '-',
                        '-',
                        '-',
                        '-',
                        '-',
                        data.created_at
                    ]).draw(true);
                }
            });
        });

    });
</script>

@endsection