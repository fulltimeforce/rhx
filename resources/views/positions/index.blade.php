@extends('layouts.app' , ['controller' => 'position'])

@section('styles')


<link rel="stylesheet" type="text/css" href="{{ asset('/datatable/jquery.dataTables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/datatable/css/fixedColumns.dataTables.min.css') }}"/>

<style>
/* The slider */

.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

/* Hide default HTML checkbox */
.switch input {display:none;}

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
  border-radius: 20px;
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
  border-radius: 20px;
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

.dataTables_scrollHeadInner, .dataTables_scrollHeadInner table {
    width: 100% !important;
}
</style>

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
                <div class="col col-sm" style="height: 800px;">
                    <table class="table row-border order-column" id="table-call-filter" style="height: 800px;width: 100%;">
                        <thead class="table-dark">
                            
                        </thead>
                        <tbody>
                            
                        </tbody>   
                    </table>

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="save-req-applicants">Save changes</button>
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
            
            var email = $("input[name='email_"+position+"']").val();
            if( !isEmail(email) ){
                $("input[name='email_"+position+"']").focus();
                $("input[name='email_"+position+"']").addClass('is-invalid');
            }else{
                $("input[name='email_"+position+"']").removeClass('is-invalid');
                $.ajax({
                    type:'POST',
                    url: "{{ route('experts.validate') }}",
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

        var table_applicants;
        
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
                    if(table_applicants) table_applicants.destroy();

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
                        var applicants_html = '<tr><td>:name</td><td> <label class="switch"> <input type="checkbox" data-id=":id" name="filter[]" class="primary ck-approve" :checked> <span class="slider"></span> </label> </td></tr>';
                        applicants_html = applicants_html.replace( ':name' , data.logs[index].expert.fullname );  
                        applicants_html = applicants_html.replace( ':id' , data.logs[index].id );
                        if( data.logs[index].filter == 'yes' ){
                            applicants_html = applicants_html.replace( ':checked' , 'checked' );
                        }else{
                            applicants_html = applicants_html.replace( ':checked' , '' );
                        }  
                        table_applicants_html += applicants_html;
                    }
                    $("#table-applicants tbody").html(table_applicants_html);
                    table_applicants = $("#table-applicants").DataTable({
                        
                        searching : false,
                        lengthChange : false,
                        paging : false,
                        pageLength : 50,
                        info : false
                    });

                    $("#filterPosition").modal();

                }
            });
        });
        
        //

        $("table").on('change','.ck-approve' , function(){
            var ck = $(this).is(':checked') ? 'yes' : 'no';
            var id = $(this).data("id");
            $.ajax({
                type:'POST',
                url: "{{ route('logs.approveFilter') }}",
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:  { id: id, filter: ck } ,
                success:function(data){
                    // console.log(data, "---------------------");
                }

            });
        });

        //
        var table_call_options = {
            scrollY: "700px",           
            lengthMenu: [[150 -1], [150 ,"All"]],
            lengthChange : false,
            paging : false,
            pageLength : 150,
            info : false,
            scrollX: true,
            scrollCollapse: true,
            ordering: false,
            fixedColumns: {
                leftColumns: 2
            },
            // "order": [[ 0, "desc" ]],
            // dom: "Bfrtip",
            searching : false,
        };

        var table_call;
            
        $(".btn-call-filter").on('click' , function(ev){
            ev.preventDefault();
            var positionId = $(this).data('position');
            var position = {!! json_encode($positions) !!}.filter(f => f.id == positionId);
            var url = '{{ route("logs.position", ":id") }}';
            url = url.replace(':id', positionId);
            $.ajax({
                type:'post',
                url:  '{{ route("logs.requirementByLog") }}',
                data: {positionId : positionId},
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){
                    
                    if(table_call) table_call.destroy();
                    $("#position-name-call").html( position[0].name );

                    $("#table-call-filter thead").html('');
                    $("#table-call-filter thead").html( html_table_head_call(data.logs) );

                    
                    $("#table-call-filter tbody").html('');
                    $("#table-call-filter tbody").html(html_table_body_call(data.data));

                    table_call = $('#table-call-filter').DataTable(table_call_options);

                    $("#callFilter").modal();

                    $('.time-picker-input').datetimepicker({
                        format: "{{ config('app.date_format_javascript') }}",
                        locale: "en",
                        showClose : true
                    });
                }
            })
        });
        
        var content_req = {};
        $("#save-req-applicants").on('click' , function(ev){
            console.log(content_req);
            $.ajax({
                type:'POST',
                url:  '{{ route("logs.saveReqApplict") }}',
                data: {data : JSON.stringify(content_req)},
                dataType: "json",
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){
                    console.log(data, "****************");

                    // $("#callFilter").modal('hide');
                }
            })
        });
        
        $('table').on( 'keyup , dp.change', '.input-req-log' , function (ev) {
            var req = $(this).data("req");
            var log = $(this).data("log");
            var value = $(this).val();

            if( !(("req-"+req) in content_req) ){
                content_req["req-"+req] = {};
            }
            content_req["req-"+req][log] = {
                description : value
            }

            console.log(content_req , "########################");
        });

        function html_table_head_call(logs){
            var html = '';
            html += '<tr>';
            html += '<th class="align-middle">REQUIREMENTS</th>';
            html += '<th class="align-middle"></th>';
            for (let index = 0; index < logs.length; index++) {
                html += '<th><p>'+logs[index].expert.fullname+'</p><p>'+logs[index].expert.phone+'</p></th>';
            }
            html += '</tr>';
            return html;
        }

        function html_table_body_call(requirements){
            var html = '';
            var fixed_requirement_start = [
                'WORKING STATUS','AVAILABILITY','ENGLISH LEVEL'
            ];
            var fixed_requirement_end = [
                'SALARY EXPERCTATION','NOTES','INTERVIEW'
            ];
            // var all_requirements = requirements.map(m => {return {}})
            // fixed_requirement_start.map( (m,i) => { requirements.splice( i ,0 , {name: m , log}) });
            console.log(requirements);

            for (let i = 0; i < requirements.length; i++) {
                html += '<tr>';
                html += '<td style="background-color: #fafafa;">'+requirements[i].name+'</td>';
                html += '<td></td>';
                for (let j = 0; j < requirements[i].logs.length; j++) {
                    var _class = (requirements[i].id === 6)? 'time-picker-input' : '';
                    html += '<td><div class="form-group" style="position: relative;"><input type="text" class="form-control input-req-log '+_class+'" data-req="'+requirements[i].id+'" data-log="'+requirements[i].logs[j].applicant_id+'" value="'+requirements[i].logs[j].description+'" /></div></td>';
                }
                html += '</tr>';
            }
            
            return html;
        }

    });
</script>

@endsection