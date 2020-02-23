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
   <!-- Modal -->
    <div class="modal fade" id="urlGeneration" tabindex="-1" role="dialog" aria-labelledby="urlGenerationLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="urlGenerationLabel">URL Generation</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <p id="showURL"></p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                    <th>CV</th>
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
        
                            @csrf
                            @method('DELETE')
            
                            <button type="submit" class="badge badge-danger">Delete</button>
                        </form>
                    </td>
                    <td>{{ $expert->fullname }}</td>
                    <td>{{ $expert->email_address }}</td>
                    <td>{{ $expert->birthday }}</td>
                    <td>{{ $expert->phone }}</td>
                    <td>
                        @if($expert->file_path != '')
                            <a href="{{ $expert->file_path }}" download class="btn btn-dark text-light">DOWNLOAD</a>
                        @endif
                    </td>
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
                        $("#urlGeneration").modal();
                    }
                });
            })

        });
        $('#urlGeneration').on('hidden.bs.modal', function (e) {
            $('#showURL').html('');
        })
        var tfConfig = {
            alternate_rows: true,
            highlight_keywords: true,
            responsive: true,
            rows_counter: true,
            popup_filters: true,
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