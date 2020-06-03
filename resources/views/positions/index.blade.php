@extends('layouts.app' , ['controller' => 'position'])

@section('styles')


<!-- <link rel="stylesheet" type="text/css" href="{{ asset('/datatable/jquery.dataTables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/datatable/css/fixedColumns.dataTables.min.css') }}"/> -->

<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap-table/bootstrap-table.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.css') }}"/>

<style>

.section-positions{
    margin-top: -7rem !important;
    padding: 0 7rem;
}
.section-positions .card-position{
    padding-top: 13px;
    padding-bottom: 13px;
}
.section-positions .card-position a{
    color: #000000;

}
.section-positions .card-position{
    box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.15);
    border-radius: 15px;
}
.card-position .position-icon{
    vertical-align: top;
}
.card-position .position-body h4{
    color: #000000;
    font-family: "OpenSans-Bold";
    margin-bottom: 1.35rem;
}
.card-position .position-body p{
    font-size: 13px;
    
}
.card-position .position-body{
    width: calc( 100% - 64px );
    padding-top: 15px;
    padding-left: 10px;
    padding-right: 10px;
}

@media (max-width: 992px) {
    .banner-home{
        padding: 4rem 5rem 8rem;
    }
    .section-positions {
        margin-top: -5rem !important;
        padding: 0px 1rem;
    }
}
@media (max-width: 576px) {
    .banner-home {
        padding: 3rem 1rem 3rem;
    }
    .section-positions {
        margin-top: -3rem !important;
        padding: 0px 1rem;
    }
}
/*************** The slider ***********/
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
#callFilter.modal.show .modal-dialog{
    /* max-width: 98%; */
}
.bootstrap-datetimepicker-widget.dropdown-menu{
    min-width: 292px;
}
</style>

@endsection

@section('content')
    @auth
    <div class="row">
        <div class="col">
            <h1>Careers</h1>
        </div>
        <div class="col">
            <a class="btn btn-secondary float-right" href="{{ route('positions.create') }}">New Position</a>
        </div>
    </div>
    @endauth
   
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <br>
    <div class="row row-cols-1 ">
    @auth
    <div class="col mb-4">
        <table id="table-positions">
        </table>
    </div>
    @endauth
    @guest

    <div class="mb-4">
        <section class="text-center banner-home">
            <h4>La familia #FULLTIMEFORCE sigue creciendo y nos encontramos en la búsqueda de los mejores talentos</h4>
            <div class="form-group">
                <input type="text" id="search-position" placeholder="Buscar puesto" class="form-control">
                <i class="svg-icon svg-icon-search icons-addon-input"></i>
            </div>
        </section>
    </div>
    <section class="section-positions">
        <div class="row">
        @foreach($positions as $pid => $position)
        <div class="col-12 col-sm-6 col-lg-4 mb-4">
            <section class="card card-position ">
                <a href="#" data-position="{{$pid}}" data-positionid="{{$position->id}}" class="btn-apply-expert">
                    <div class="position-icon d-inline-block">
                        <i class="svg-icon svg-icon-{{$position->icon}}"></i>
                    </div>
                    <div class="position-body d-inline-block">
                        <h4>{{$position->name}}</h4>
                        <p>Haz <b>Click Aquí</b> para ver los requeriminetos</p>
                    </div> 
                </a>
            </section>
        </div>
        @endforeach
        </div>
    </section>

    @endguest
    
    </div>
@endsection

@section('javascript')

<!-- <script type="text/javascript" src="{{ asset('/datatable/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/datatable/js/dataTables.fixedColumns.min.js') }}"></script> -->

<script type="text/javascript" src="{{ asset('/bootstrap-table/bootstrap-table.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function (ev) {
        $('[data-toggle="tooltip"]').tooltip({
            trigger : 'click'
        });
        $("#search-position + i").on("click" , function(){
            window.history.replaceState({
                edwin: "Fulltimeforce"
                }, "Page" , "{{ route('home') }}" + '?'+ $.param(
                    {   s : $("#search-position").val() , 
                    }
                    )
                );

            location.reload();
        })
        $("#table-positions").bootstrapTable('destroy').bootstrapTable({

            columns: [
                { field: 'name', title: "Position" },
                { field: 'id', title: "Actions" , width: 400 , formatter: function(value,rowData,index){
                    
                        var actions = '<a href="'+ "{{ route('positions.edit', ':id') }}" +'" class="btn btn-success card-link">Edit</a>';
                        actions += '<a href="'+ "{{ route('positions.experts', ':id') }}" +'" class="btn btn-info card-link">Show applicants</a>';
                        actions += '<a href="#" class="btn btn-primary card-link btn-copy-slug" title="Copied" data-toggle="tooltip" data-placement="top"  data-url="'+ rowData.slug +'">Copy URL</a>';
                        actions = actions.replace(/:id/gi , rowData.id);
                        return actions;
                    
                    } 
                }
            ],
            
            url : "{{ route('positions.listpositions') }}",
            theadClasses: 'table-dark',
            uniqueId: 'id',
            
            

        });

        $('[data-toggle="tooltip"]').on('shown.bs.tooltip', function () {
            setTimeout(() => {
                $('[data-toggle="tooltip"]').tooltip('hide')
            }, 2000);
        });

        $(".btn-apply-expert").on('click',function(ev){
            ev.preventDefault();
            var position = $(this).data("position");
            var positionId = $(this).data("positionid");
            var email = "";
            // var email = $("input[name='email_"+position+"']").val();
            // if( !isEmail(email) ){
            //     $("input[name='email_"+position+"']").focus();
            //     $("input[name='email_"+position+"']").addClass('is-invalid');
            // }else{
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
            // }
            
        })

        $("table").on('click' , '.btn-copy-slug',function(ev){
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
            scrollY: "500px",           
            lengthMenu: [[150 -1], [150 ,"All"]],
            
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
                    $("#table-call-filter tbody").html( html_table_body_call(data.data) );

                    table_call = $('#table-call-filter').DataTable(table_call_options);

                    $("#callFilter").modal();

                    $('.time-picker-input').datetimepicker({
                        format: "{{ config('app.date_format_js_datetime') }}",
                        locale: "en",
                        showClose : true,
                        toolbarplacement: 'top',
                        icons:{
                            time: "far fa-clock",
                        }
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
        
        $('table').on( 'keyup , change.datetimepicker', '.input-req-log' , function (ev) {
            console.log("dddddd");
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
                'SALARY EXPECTATION','NOTES','INTERVIEW'
            ];
            // var all_requirements = requirements.map(m => {return {}})
            // fixed_requirement_start.map( (m,i) => { requirements.splice( i ,0 , {name: m , log}) });
            var count = 1;
            for (let i = 0; i < requirements.length; i++) {
                html += '<tr>';
                html += '<td style="background-color: #fafafa;">'+requirements[i].name+'</td>';
                for (let j = 0; j < requirements[i].logs.length; j++) {
                    var _class = (requirements[i].id === 6)? 'time-picker-input' : '';
                    html += '<td><div class="form-group" style="position: relative;">';
                    if( _class != ''){
                        html += '<input type="text" class="form-control input-req-log '+_class+'" data-toggle="datetimepicker" id="time-'+requirements[i].id+'-'+j+'" data-target="#time-'+requirements[i].id+'-'+j+'" data-req="'+requirements[i].id+'" data-log="'+requirements[i].logs[j].applicant_id+'" value="'+requirements[i].logs[j].description+'" />';
                    }else{
                        html += '<textarea class="form-control input-req-log " data-req="'+requirements[i].id+'" data-log="'+requirements[i].logs[j].applicant_id+'" >'+requirements[i].logs[j].description+'</textarea>';
                    }
                    
                    html += '</div></td>';
                }
                count++;
                html += '</tr>';
            }
            
            return html;
        }

    });
</script>

@endsection