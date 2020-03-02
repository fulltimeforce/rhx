@extends('layouts.app' , ['controller' => 'experts'])

@section('styles')

<link rel="stylesheet" type="text/css" href="{{ asset('/datatable/jquery.dataTables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/datatable/css/fixedColumns.dataTables.min.css') }}"/>


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
</style>
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <h1>Experts</h1>
        </div>
        <div class="col text-right">
            <a class="btn btn-primary" href="{{ route('experts.create') }}">New Expert</a>
            <a class="btn btn-info" id="url-generate" href="#">Generate URL</a>
        </div>
    </div>
    <div class="alert alert-warning alert-dismissible mt-3" role="alert" style="display: none;">
        <b>Copy successful!!!!</b>
        <p id="showURL"></p>
    </div>
   
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <br>
    <form action="{{ route('experts.filter') }}" method="POST">
        @csrf
        <!--<div class="form-row">
        @foreach($technologies as $categoryid => $category)
                <div class="form-group col-3">
                <h4>{{$category[0]}}</h4>
                @foreach($category[1] as $techid => $techlabel)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="{{$techid}}" name="{{$categoryid}}" id="{{$techid}}">
                        <label class="form-check-label" for="{{$techid}}">{{$techlabel}}</label>
                    </div>
                @endforeach
                </div>
        @endforeach
        </div>-->
        <div class="form-group">
            <label for="basic_level">Basic</label>
            <select multiple type="text" id="basic_level" name="basic_level[]" class="form-control search-level basic"></select>
        </div>
        <div class="form-group">
            <label for="intermediate_level">Intermediate</label>
            <select multiple type="text" id="intermediate_level" name="intermediate_level[]" class="form-control search-level intermediate"></select>
        </div>
        <div class="form-group">
            <label for="advanced_level">Advanced</label>
            <select multiple type="text" id="advanced_level" name="advanced_level[]" class="form-control search-level advanced"></select>
        </div>
        <div class="form-group text-right">
            <button type="submit" class="btn btn-success">Search</button>
        </div>
    
    </form>

    <div class="row">
        <div class="col">
            <table class="table table-bordered row-border order-column" id="allexperts">
            <thead class="thead-dark">
                <tr>
                    <th data-col="action" >Acción</th>
                    <th data-col="name" style="width: 200px;">Nombre</th>
                    <th data-col="email">Email</th>
                    <th data-col="age">Edad</th>
                    <th data-col="phone">Teléfono</th>
                    <th data-col="availability">Disponibilidad</th>
                    <th data-col="salary">Salario</th>
                    @foreach($technologies as $categoryid => $category)
                        @foreach($category[1] as $techid => $techlabel)
                            <th data-col="{{ $techid }}">{{$techlabel}}</th>
                        @endforeach
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($experts as $expert)
                <tr>
                    <td>
                        <form action="{{ route('experts.destroy',$expert->id) }}" method="POST">
        
                            <!-- <a class="badge badge-info" href="{{ route('experts.show',$expert->id) }}">Show</a> -->
            
                            <a class="badge badge-primary" href="{{ route('experts.edit',$expert->id) }}">Edit</a>

                            @if($expert->file_path != '')
                                <a href="{{ $expert->file_path }}" download class="badge badge-dark text-light">DOWNLOAD</a>
                            @endif

                            @csrf
                            @method('DELETE')
            
                            <button type="submit" class="badge badge-danger">Delete</button>
                        </form>
                    </td>
                    <td style="background-color: #fafafa;">{{ $expert->fullname }}</td>
                    <td>{{ $expert->email_address }}</td>
                    <td>{{ $expert->birthday }}</td>
                    <td>{{ $expert->phone }}</td>
                    <td>{{ $expert->availability }}</td>
                    <td>{{ $expert->salary }}</td>
                    @foreach($technologies as $categoryid => $category)
                        @foreach($category[1] as $techid => $techlabel)
                        <td>{{ $expert->$techid }}</td>
                        @endforeach
                    @endforeach
                </tr>
                @endforeach
            <tbody>
            </table>
        </div>
    </div>
@endsection

@section('javascript')
<script type="text/javascript" src="{{ asset('/tokenize2/tokenize2.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('/datatable/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/datatable/js/dataTables.fixedColumns.min.js') }}"></script>


<script type="text/javascript">
        
        $(document).ready(function () {

            jQuery(".search-level").tokenize2({
                dataSource: "{{ action('ExpertController@techs') }}",
            });
            @if(isset($basic))
                @foreach ($basic as $techid => $techlabel)
                    $(".search-level.basic").trigger('tokenize:tokens:add', ['{{$techid}}', '{{$techlabel}}', true]);
                @endforeach
            @endif
            @if(isset($intermediate))
                @foreach ($intermediate as $techid => $techlabel)
                    $(".search-level.intermediate").trigger('tokenize:tokens:add', ['{{$techid}}', '{{$techlabel}}', true]);
                @endforeach
            @endif
            @if(isset($advanced))
                @foreach ($advanced as $techid => $techlabel)
                    $(".search-level.advanced").trigger('tokenize:tokens:add', ['{{$techid}}', '{{$techlabel}}', true]);
                @endforeach
            @endif

            $('#url-generate').on('click', function (ev) {

                ev.preventDefault();
                $.ajax({
                    type:'GET',
                    url:'/applicant/register/signed',
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
        });

        var options = {
            
            lengthMenu: [[50, 100, 150, -1], [50, 100, 150, "All"]],
            
            scrollY: "500px",
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 2
            },
            searching: false
            // dom: "Bfrtip",
        }

        var table = $("#allexperts").DataTable( options );

        $( table.table().container() ).on( 'click', 'tbody td:not(:first-child)', function (e) {
            console.log("ddddddddddd");
            // editor.inline( this );
        } );
        
    </script>   
@endsection