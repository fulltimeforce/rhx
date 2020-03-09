@extends('layouts.app' , ['controller' => 'positions-expert'])

@section('styles')
<style>
caption{
    caption-side: top !important;
    width: max-content !important;
    border: 1px solid;
    margin-bottom: 1.5rem;
}

</style>
@endsection
 
@section('content')

    <div class="row">
        <div class="col-lg-12 mt-5 mb-5">
            <div class="float-left">
                <h2>Show applicants</h2>
            </div>
            <div class="float-right">
                <a class="btn btn-primary" href="{{ url('/') }}"> Back</a>
            </div>
        </div>
    </div>
   
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
        base_path: $_url_ajax + 'tablefilter/',
        paging: {
            results_per_page: ['Records: ', [10, 25, 50, 100]]
        },
        themes: [{
            name: 'transparent'
        }]
    };
    var tf = new TableFilter('allexperts',tfConfig);
    tf.init();
    </script>   
@endsection