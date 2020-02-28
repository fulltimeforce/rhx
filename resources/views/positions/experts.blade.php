@extends('layouts.app')
 
@section('content')
    <h1 class="mb-5">Show applicants</h1>
   
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <br>
    
    <div class="row">
        <div class="col">
            <table class="table table-bordered" id="allexperts">
                <tr>

                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Edad</th>
                    <th>Teléfono</th>
                    <th>Disponibilidad</th>
                    <th>Salario</th>
                    @foreach($technologies as $categoryid => $category)
                        @foreach($category[1] as $techid => $techlabel)
                            <th>{{$techlabel}}</th>
                        @endforeach
                    @endforeach
                </tr>
                @foreach ($experts as $expert)
                <tr>
                    
                    <td>{{ $expert->fullname }}</td>
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
            </table>
        </div>
    </div>
@endsection

@section('javascript')
<script type="text/javascript" src="{{ asset('/tokenize2/tokenize2.min.js') }}"></script>
<script type="text/javascript">
        
    var tfConfig = {
        alternate_rows: true,
        highlight_keywords: true,
        responsive: true,
        rows_counter: true,
        popup_filters: true,
        base_path: '/tablefilter/'
    };
    var tf = new TableFilter('allexperts',tfConfig);
    tf.init();
    </script>   
@endsection