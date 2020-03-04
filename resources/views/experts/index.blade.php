@extends('layouts.app' , ['controller' => 'experts'])

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
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

    <!-- Modal -->
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
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">Cras justo odio <div ><input type="checkbox"></div></li>
                    
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
            
        </div>
    </div>
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
            <select multiple id="basic_level" name="basic_level[]" class="form-control search-level basic"></select>
        </div>
        <div class="form-group">
            <label for="intermediate_level">Intermediate</label>
            <select multiple id="intermediate_level" name="intermediate_level[]" class="form-control search-level intermediate"></select>
        </div>
        <div class="form-group">
            <label for="advanced_level">Advanced</label>
            <select multiple id="advanced_level" name="advanced_level[]" class="form-control search-level advanced"></select>
        </div>
        <div class="form-group text-right">
            <button type="button" class="btn btn-success" id="search">Search</button>
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

                            <button type="button" data-id="{{ $expert->id }}" class="badge badge-info btn-position">Positions</button>

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

<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script type="text/javascript" src="{{ asset('/datatable/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/datatable/js/dataTables.fixedColumns.min.js') }}"></script>


<script type="text/javascript">
        
        $(document).ready(function () {

            // jQuery(".search-level").tokenize2({
            //     dataSource: "{{ action('ExpertController@techs') }}",
            // });
            
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

            @if(isset($basic))
                console.log("ddddddddddddd")
                var basic = [];
                @foreach ($basic as $techid => $techlabel)
                    // $(".search-level.basic").trigger('tokenize:tokens:add', ['{{$techid}}', '{{$techlabel}}', true]);
                    console.log('{{$techid}}');
                    basic.push('{{$techid}}');
                @endforeach
                $(".search-level.basic").val( basic).trigger('change');
            @endif
            @if(isset($intermediate))
                @foreach ($intermediate as $techid => $techlabel)
                    // $(".search-level.intermediate").trigger('tokenize:tokens:add', ['{{$techid}}', '{{$techlabel}}', true]);
                @endforeach
            @endif
            @if(isset($advanced))
                @foreach ($advanced as $techid => $techlabel)
                    // $(".search-level.advanced").trigger('tokenize:tokens:add', ['{{$techid}}', '{{$techlabel}}', true]);
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

            $("#allexperts").on('click',".btn-position" , function(){
                var id = $(this).data("id");
                $.ajax({
                    type:'POST',
                    url: '{{ route("positions.enabled") }}',
                    data: {expertId : id},
                    headers: {
                        'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(data){
                        console.log(data);
                        for (let index = 0; index < array.length; index++) {
                            var html = '<li class="list-group-item d-flex justify-content-between align-items-center">:name <div ><input type="checkbox"></div></li>';
                            const element = array[index];
                            
                        }
                        $("#positionsExpert").modal();
                    }
                });
                
            });

            $('#search').on('click' , function(){

                var a_basic_level = $(".search-level.basic").val();
                var a_intermediate_level = $(".search-level.intermediate").val();
                var a_advanced_level = $(".search-level.advanced").val(); 
                
                $.ajax({
                    type: 'POST',
                    url: '{{ route("experts.filter") }}',
                    data: {basic_level : a_basic_level , intermediate_level : a_intermediate_level , advanced_level : a_advanced_level },
                    headers: {
                        'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(data){
                        table.destroy();
                        var html = '';
                        for (let index = 0; index < data.length; index++) {

                            html += html_table_row(data[index]);
                        }
                        
                        $("#allexperts tbody").html('');
                        $("#allexperts tbody:first").html(html);


                        table = $("#allexperts").DataTable( options );
                        // 
                    }
                });
            });

            function html_table_row(data){
                var html = '';
                html += '<tr>';
                html += '<td>';
                html += '<form action="'+ "{{ route('experts.destroy', ':id' ) }}"+ '" method="POST">';
        
                // html += '        <a class="badge badge-info" href=" '+ "{{ route('experts.show', ':id') }}" + '">Show</a>';
        
                html += '        <a class="badge badge-primary" href="'+ "{{ route('experts.edit', ':id') }}" + '">Edit</a>';

                if( data.file_path != '' ){
                    html += '   <a href="'+data.file_path+'" download class="badge badge-dark text-light">DOWNLOAD</a>';
                }
                
                html += '        <button type="button" data-id="'+data.id+'" class="badge badge-info btn-position">Positions</button>';

                html = html.replace(/:id/gi , data.id);

                html += '<input type="hidden" name="_token" value="{{csrf_token()}}" /> ';
                html += '<input type="hidden" name="_method" value="DELETE" /> ';
                
                // ('delete')
                html += '        <button type="submit" class="badge badge-danger">Delete</button>';
                html += '    </form>';
                html += '</td>';
                html += '<td>'+data.fullname+'</td>';
                html += '<td>'+data.email_address+'</td>';
                html += '<td>'+data.birthday+'</td>';
                html += '<td>'+data.phone+'</td>';
                html += '<td>'+data.availability+'</td>';
                html += '<td>'+data.salary+'</td>';
                @foreach($technologies as $categoryid => $category)
                    @foreach($category[1] as $techid => $techlabel)
                    // console.log( '{{$techid}}' ,'{{$techlabel}}' )
                    html += '<td>'+data['{{$techid}}']+'</td>';
                    @endforeach
                @endforeach
                html += '</tr>';
                return html;
            }


        });

        
        
    </script>   
@endsection