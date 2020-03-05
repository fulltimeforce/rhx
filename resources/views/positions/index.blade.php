@extends('layouts.app' , ['controller' => 'position'])

@section('styles')


<link rel="stylesheet" type="text/css" href="{{ asset('/datatable/jquery.dataTables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/datatable/css/fixedColumns.dataTables.min.css') }}"/>

@endsection

@section('content')
    <div class="row">
        <div class="col">
            <h1>Careers</h1>
            @auth
            <a class="btn btn-secondary float-right" href="{{ route('positions.create') }}">New Position</a>
            @endauth
        </div>
    </div>

    <!-- MODAL FILTER -->
    <div class="modal fade" id="filterPosition" tabindex="-1" role="dialog" aria-labelledby="filterPositionLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="filterPositionLabel">FILTER CVs - <span id="position-name"></span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-5 col-12">
                    <p>REQUIREMENTS</p>
                    <ul class="list-group" id="requirements-list">
                        <li class="list-group-item">Cras justo odio</li>
                        <li class="list-group-item">Dapibus ac facilisis in</li>
                        <li class="list-group-item">Morbi leo risus</li>
                        <li class="list-group-item">Porta ac consectetur ac</li>
                        <li class="list-group-item">Vestibulum at eros</li>
                    </ul>
                </div>
                <div class="col-sm-7 col-12">
                    <p>APPLICANTS</p>
                    <table class="table" id="table-applicants">
                        <thead>
                            <tr>
                                <th>NAME</th>
                                <th>PHONE</th>
                                <th>APPROVED</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
        </div>
        </div>
    </div>
    </div>

    <!-- MODAL CALL FILTER -->
    <div class="modal fade" id="callFilter" tabindex="-1" role="dialog" aria-labelledby="callFilterLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header text-center">
            <h5 class="modal-title" id="callFilterLabel">CALL FILTERED - <span id="position-name-call">{positionName}</span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col col-sm">
                    <table class="table" id="table-call-filter">
                        <thead>
                            <tr>
                                <th>REQUIREMENTS</th> 
                                <th></th> 
                                <th><p>{phone}</p><p>{name}</p></th> 
   
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>WORKING STATUS</td>
                                <td></td>

                            </tr>
                            <tr>
                                <td>AVAILABILITY</td>
                                <td></td>
 
                            </tr>
                            <tr>
                                <td>ENGLISH LEVEL</td>
                                <td></td>

                            </tr>


                            <tr>
                                <td>SALARY EXPERCTATION</td>
                                <td></td>

                            </tr>
                            <tr>
                                <td>NOTES</td>
                                <td></td>

                            </tr>
                            <tr>
                                <td>INTERVIEW</td>
                                <td></td>

                            </tr>
                        </tbody>   
                    </table>

                </div>

            </div>
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
    <div class="row row-cols-1 ">
    
    @foreach($positions as $pid => $position)
    <div class="col mb-4">
        <div class="card">
            <div class="card-header" data-toggle="collapse" href="#position-{{$position->id}}" role="button" aria-expanded="true" aria-controls="position-{{$position->id}}">
                <h4>{{$position->name}}</h4>
            </div>
            <div class="card-body">
                <div class="card-text collapse show" id="position-{{$position->id}}">{!! nl2br($position->description) !!}</div>
            </div>
            <div class="card-footer">
                @guest
                <div class="row">
                    <div class="offset-sm-8 col-sm-4 offset-4 col-8">
                        <div class="input-group">
                            <input type="email" name="email_{{$pid}}"  placeholder="Enter your email to apply" class="form-control d-inline">
                            <div class="input-group-append">
                                <a href="#" data-position="{{$pid}}" data-positionid="{{$position->id}}" class="btn btn-outline-primary float-right btn-apply-expert">Apply!</a> 
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <a href="{{ route('positions.edit', $position->id) }}" class="btn btn-success card-link">Edit</a>
                <a href="{{ route('positions.experts', $position->id) }}" class="btn btn-info card-link">Show applicants</a>
                <a href="#" data-position="{{ $position->id }}" class="btn btn-warning card-link btn-position-filter">Filter</a>
                <a href="#" data-position="{{ $position->id }}" class="btn btn-dark card-link btn-call-filter">Call</a>
                <a href="#" class="btn btn-primary card-link btn-copy-slug" title="Copied" data-toggle="tooltip" data-placement="top"  data-url="{{ $position->slug }}">Copy URL</a>
                @endguest
            </div>
        </div>
    </div>
    @endforeach
    
    </div>
@endsection

@section('javascript')

<script type="text/javascript" src="{{ asset('/datatable/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/datatable/js/dataTables.fixedColumns.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function (ev) {
        $('[data-toggle="tooltip"]').tooltip({
            trigger : 'click'
        })

        $('[data-toggle="tooltip"]').on('shown.bs.tooltip', function () {
            setTimeout(() => {
                $('[data-toggle="tooltip"]').tooltip('hide')
            }, 2000);
        })
        $(".btn-apply-expert").on('click',function(ev){
            ev.preventDefault();
            var position = $(this).data("position");
            var positionId = $(this).data("positionid");
            console.log(positionId , "sssssssssssss");
            var email = $("input[name='email_"+position+"']").val();
            if( !isEmail(email) ){
                $("input[name='email_"+position+"']").focus();
                $("input[name='email_"+position+"']").addClass('is-invalid');
            }else{
                $("input[name='email_"+position+"']").removeClass('is-invalid');
                $.ajax({
                    type:'POST',
                    url:'/expert/validate',
                    headers: {
                        'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data:{email:email,positionId: positionId},
                    success:function(data){
                        
                        window.location = data;

                    }
                });
            }
            
        })

        $(".btn-copy-slug").on('click',function(ev){
            ev.preventDefault();
            var el = document.createElement("textarea");
            el.value = "{{ route('home') }}" + '/position/'+$(this).data("url");
            
            el.style.position = 'absolute';                 
            el.style.left = '-9999px';
            el.style.top = '0';
            el.setSelectionRange(0, 99999);
            el.setAttribute('readonly', ''); 
            document.body.appendChild(el);
            
            el.focus();
            el.select();

            var success = document.execCommand('copy')
            document.body.removeChild(el);

        });

        function isEmail(email) {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return regex.test(email);
        }
        
        $(".btn-position-filter").on('click',function(ev){
            ev.preventDefault();
            var position = $(this).data('position');
            var url = '{{ route("logs.position", ":id") }}';
            url = url.replace(':id', position);
            $.ajax({
                type:'GET',
                url:  url,
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){

                    $('#position-name').html(data.position.name);
                    var list_req_html = '';
                    $("#requirements-list").html('');
                    for (let index = 0; index < data.requirements.length; index++) {
                        var reqs_html = '<li class="list-group-item">:name</li>';
                        reqs_html = reqs_html.replace(':name' , data.requirements[index].name );
                        list_req_html += reqs_html;
                    }
                    $("#requirements-list").html(list_req_html);

                    $("#table-applicants tbody").html('');
                    var table_applicants_html = '';
                    for (let index = 0; index < data.logs.length; index++) {
                        var applicants_html = '<tr><td>:name</td><td>:phone</td><td> <input type="checkbox" data-id=":id" name="filter[]" class="ck-approve"> </td></tr>';
                        applicants_html = applicants_html.replace( ':name' , data.logs[index].name );  
                        applicants_html = applicants_html.replace( ':id' , data.logs[index].id );  
                        table_applicants_html += applicants_html
                    }
                    $("#table-applicants tbody").html(table_applicants_html);
                    $("#table-applicants").DataTable({
                        
                        searching : false,
                        lengthChange : false,
                        paging : false,
                        pageLength : 50,
                        info : false
                    });

                    $("#filterPosition").modal()
                    console.log(data,"eeee");

                }
            });
        });
        
        //

        

        $("table").on('change','.ck-approve' , function(){
            var ck = $(this).is(':checked') ? 'yes' : 'no';
            var id = $(this).data("id");
            $.ajax({
                type:'POST',
                url: "{{ route('logs.updateForm') }}",
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:  { id: id, filter: ck } ,
                success:function(data){
                    console.log(data, "---------------------");
                    
                    <td><input type="text" class="form-control"></td>
                }

            });
        });


        //

        var table_call = $('#table-call-filter').DataTable({
            scrollY: "700px",           
            
            lengthMenu: [[150 -1], [150 ,"All"]],
            lengthChange : false,
            paging : false,
            pageLength : 150,
            info : false,
            scrollX: true,
            scrollCollapse: true,
            // fixedColumns: {
            //     leftColumns: 2
            // },
            // "order": [[ 0, "desc" ]],
            dom: "Bfrtip",
            searching : false,
        });

        $("table .sorting_desc").trigger('click')
            
        $(".btn-call-filter").on('click' , function(ev){
            ev.preventDefault();
            $("#callFilter").modal();

            var position = $(this).data('position');
            var url = '{{ route("logs.position", ":id") }}';
            url = url.replace(':id', position);
            $.ajax({
                type:'GET',
                url:  url,
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){
                    $("#position-name-call").html(data.position.name);
                }
            })
        });

    });
</script>

@endsection