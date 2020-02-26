@extends('layouts.app' , ['controller' => 'experts'])

@section('styles')
<style>
caption{
    caption-side: top !important;
    width: max-content !important;
    border: 1px solid;
    margin-bottom: 1.5rem;
}
#showURL{
    word-break: break-all;
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
            <table class="table table-bordered" id="allexperts">
                <tr>
                    <th>Acción</th>
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
                    <td>
                        <form action="{{ route('experts.destroy',$expert->id) }}" method="POST">
        
                            <a class="badge badge-info" href="{{ route('experts.show',$expert->id) }}">Show</a>
            
                            <a class="badge badge-primary" href="{{ route('experts.edit',$expert->id) }}">Edit</a>

                            @if($expert->file_path != '')
                                <a href="{{ $expert->file_path }}" download class="badge badge-dark text-light">DOWNLOAD</a>
                            @endif

                            @csrf
                            @method('DELETE')
            
                            <button type="submit" class="badge badge-danger">Delete</button>
                        </form>
                    </td>
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

        
        var count_cols = $("#allexperts tr:first th").length;
        var cols_filter = {};
        var labels_filter = [];
        var values_filter = [];
        for (let index = 0; index < count_cols ; index++) {
            cols_filter['col_'+index] = '';
            if( index > 6) {
                cols_filter['col_'+index] = 'select'
                labels_filter.push(['basic', 'intermediate', 'advanced']);
                values_filter.push(['basic', 'intermediate', 'advanced']);
            }
        }
        var tfConfig = {
            alternate_rows: true,
            responsive: true,
            rows_counter: true,
            loader: true,
            filters_row_index: 1,
            paging: {
                results_per_page: ['Records: ', [10, 25, 50, 100]]
            },
            
            themes: [{
                name: 'transparent'
            }],
            col_types: ['string']
            
        };
        var new_tfConfig = Object.assign(tfConfig , cols_filter);
        new_tfConfig = Object.assign( new_tfConfig , { 
            custom_options : {
                cols : [ ...Array(count_cols).keys() ].filter( f => f>6) ,
                texts : labels_filter,
                values : labels_filter,
                sorts: [false]
            }
        })
        
        
        var tf = new TableFilter('allexperts',new_tfConfig);
        tf.init();

        
    </script>   
@endsection