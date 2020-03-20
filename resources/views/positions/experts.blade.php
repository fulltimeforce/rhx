@extends('layouts.app' , ['controller' => 'positions-expert'])

@section('styles')

<link rel="stylesheet" type="text/css" href="{{ asset('/datatable/jquery.dataTables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/datatable/css/fixedColumns.dataTables.min.css') }}"/>
<style>
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

.slider {
  border: none;
  position: relative;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  width: 125px;
}

.slider-checkbox {
  display: none;
}

.slider-label {
  border: 0;
  border-radius: 20px;
  cursor: pointer;
  display: block;
  overflow: hidden;
}

.slider-inner {
  display: block;
  margin-left: -100%;
  transition: margin 0.3s ease-in 0s;
  width: 200%;
}

.slider-inner:before,
.slider-inner:after {
  box-sizing: border-box;
  display: block;
  float: left;
  font-family: sans-serif;
  font-size: 14px;
  font-weight: bold;
  height: 30px;
  line-height: 30px;
  padding: 0;
  width: 50%;
}

.slider-inner:before {
  background-color: #007bff;
  color: #fff;
  content: "APPROVED";
  padding-left: .75em;
}

.slider-inner:after {
  background-color: #dc3545;
  color: #FFF;
  content: "FAILED";
  padding-right: .75em;
  text-align: right;
}

.slider-circle {
  background-color: #FFF;
  border: 0;
  border-radius: 20px;
  bottom: 0;
  display: block;
  margin: 5px;
  position: absolute;
  right: 91px;
  top: 0;
  transition: all 0.3s ease-in 0s; 
  width: 20px;
}

.slider-checkbox:checked + .slider-label .slider-inner {
  margin-left: 0;
}

.slider-checkbox:checked + .slider-label .slider-circle {
  background-color: #FFFFFF;
  right: 0; 
}
td.stickout{
    background-color: yellow;
}

.dataTables_filter{
    display: none;
}

a.btn-delete-interview{
    margin: -2.2rem -.5rem -1rem auto;
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


    <!--  
        /*========================================== MODALS ==========================================*/
    -->
    <!--  
        /*========================================== POSITONS BY EXPERT ==========================================*/
    -->
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
                <form action="" id="form-positions">
                <input type="hidden" name="expertId-p" id="expertId-p" value="">
                <ul class="list-group" id="list-positions">
                    <li class="list-group-item d-flex justify-content-between align-items-center">Cras justo odio <div ><input type="checkbox"></div></li>
                </ul>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="save-positions" class="btn btn-primary">Save</button>
            </div>
            
        </div>
    </div>
    </div>

    <!--  
        /*========================================== INTERVIEWS BY EXPERT ==========================================*/
    -->
    <div class="modal" id="interviews-expert" tabindex="-1" role="dialog" aria-labelledby="interviews-expertLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="interviews-expertLabel">INTERVIEWS - <span id="interview_expert_name">{expert Name}</span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div >
            <div class="row mb-4">
                <div class="col" id="list-interviews">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="text-uppercase">:name-type</h5>
                            <a href="#" class="btn btn-danger float-right btn-delete-interview" data-id=":id">Delete</a>
                        </div>
                        <div class="card-body">
                            <p class="card-text">:description</p>
                        </div>
                        <div class="card-footer text-muted">
                            <span>:result</span>
                            <span class="float-right">:date</span>
                        </div>
                    </div>
                </div>
            </div>

            <form action="" id="interviews-form" class="row">
                <div class="col-5">
                    <div class="form-group">
                        <label for="">Type</label>
                        <select class="form-control" name="type" id="interview_type">
                            <option value="technical">Technical</option>
                            <option value="psychological">Psychological</option>
                            <option value="commercial">Commercial</option>
                            <option value="client">Client</option>
                        </select>
                        <input type="hidden" name="expert_id" id="interview_expert_id">
                    </div>
                    <div class="form-group">
                        <label for="">Result</label>
                        <div class="slider">
                            <input type="checkbox" name="result" class="slider-checkbox" id="interview_result" >
                            <label class="slider-label" for="interview_result">
                            <span class="slider-inner"></span>
                            <span class="slider-circle"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Date</label>
                        <input type="text" class="form-control" name="date" id="interview_date">
                    </div>
                </div>
                <div class="col-7">
                    <div class="form-group">
                        <label for="">Description</label>
                        <textarea name="description" id="interview_description" class="form-control" rows="10"></textarea>
                    </div>
                </div>
            </form>
            </div>
            <div class="row mb-4">
                <div class="col">
                    <button class="btn btn-success" id="save-interview">SAVE</button>
                </div>
            </div>
            
        </div>
        
        </div>
    </div>
    </div>

    <div class="row">
        <div class="col">
            <h5>Experts: {{ count($experts) }} </h5>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-4">
            <input type="text" placeholder="Search By Name" class="form-control" id="search-column-name">
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table class="table table-bordered row-border order-column" id="allexperts">
                <thead class="thead-dark">
                <tr>
                    <th>Action</th>
                    <th style="width: 200px;">Name</th>
                    @foreach($current_tech as $categoryid => $category)
                        @foreach($category as $techid => $techlabel)
                            <th>{{$techlabel}}</th>
                        @endforeach
                    @endforeach
                    <th>Email</th>
                    <th>Age</th>
                    <th>Phone</th>
                    <th>Availability</th>
                    <th>Salary</th>
                    @foreach($after_tech as $categoryid => $category)
                        @foreach($category as $techid => $techlabel)
                            <th>{{$techlabel}}</th>
                        @endforeach
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach ($experts as $expert)
                <tr>
                    <td>
                        <form action="{{ route('experts.destroy', $expert->id ) }}" method="POST">
                            <a class="badge badge-primary" href="{{ route( 'experts.edit', $expert->id ) }}">Edit</a>
                            @if($expert->file_path != '' )
                            <a href="{{ $expert->file_path }}" download class="badge badge-dark text-light">DOWNLOAD</a>
                            @endif
                            <a href="#" data-id="{{ $expert->id }}" class="badge badge-info btn-position">Positions</a>
                            <a class="badge badge-secondary btn-interviews" data-id="{{ $expert->id }}" data-name="{{ $expert->fullname }}" href="#">Interviews</a>
                            @csrf
                            @method('DELETE')
                            <!-- <button type="submit" class="badge badge-danger">Delete</button> -->
                        </form>
                    </td>
                    <td>{{ $expert->fullname }}</td>
                    @foreach($current_tech as $categoryid => $category)
                        @foreach($category as $techid => $techlabel)
                        <td style="background-color: yellow;">{{ $expert->$techid }}</td>
                        @endforeach
                    @endforeach
                    <td>{{ $expert->email_address }}</td>
                    <td>{{ $expert->birthday }}</td>
                    <td>{{ $expert->phone }}</td>
                    <td>{{ $expert->availability }}</td>
                    <td>{{ $expert->salary }}</td>
                    @foreach($after_tech as $categoryid => $category)
                        @foreach($category as $techid => $techlabel)
                        <td >{{ $expert->$techid }}</td>
                        @endforeach
                    @endforeach
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('javascript')
<script type="text/javascript" src="{{ asset('/datatable/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/datatable/js/dataTables.fixedColumns.min.js') }}"></script>
<script type="text/javascript">

    $(document).ready(function (ev) {
        var options = {
            lengthMenu: [[50, 100, 150, -1], [50, 100, 150, "All"]],
            scrollY: "500px",
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 2
            },
            // searching: false
            // dom: "Bfrtip",
        }

        var table = $("#allexperts").DataTable( options );

        $("table tbody").on('click', 'a.btn-position' , function(ev){
            ev.preventDefault();
            var id = $(this).data("id");
            $('#expertId-p').val('');
            $.ajax({
                type:'POST',
                url: '{{ route("positions.enabled") }}',
                data: {expertId : id},
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){

                    $('#expertId-p').val(id);
                    $("#list-positions").html('');
                    var html = '';
                    for (let index = 0; index < data.length; index++) {
                        html += '<li class="list-group-item d-flex justify-content-between align-items-center">:name <div ><input type="checkbox" name="positions[]" value="'+data[index].id+'" '+ (data[index].active == 1? 'checked' : '') +' ></div></li>';
                        html = html.replace(':name' , data[index].name);
                    }
                    $("#list-positions").html(html);
                    $("#positionsExpert").modal();
                }
            });
            
        });

        $("#save-positions").on('click' , function(ev){
            
            var positionsIDs = [];
            $('#form-positions input[type="checkbox"]:checked').each( function(){
                positionsIDs.push($(this).val());
            } );
            var id = $('#expertId-p').val();

            $.ajax({
                type:'POST',
                url: '{{ route("positions.experts.attach") }}',
                data: {expertId : id, positions : positionsIDs},
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){

                    $("#positionsExpert").modal('hide');
                }
            });

        });

        var template_card_interview = $('#list-interviews').html();
        $('#list-interviews').html('');
        
        $("table tbody").on("click" , "a.btn-interviews" , function(ev){
            ev.preventDefault();
            var expertId = $(this).data("id");
            var expertName = $(this).data("name");
            
            $("#interview_expert_name").html( expertName );
            $("#interview_expert_id").val(expertId);
            $.ajax({
                type: 'POST',
                url: '{{ route("interviews.expert") }}',
                data: {expertId : expertId },
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(interviews){

                    var html = '';
                    for (let index = 0; index < interviews.length; index++) {
                        var html_card_interview = template_card_interview;
                        html_card_interview = html_card_interview.replace(':id' , interviews[index].id);
                        html_card_interview = html_card_interview.replace(':name-type' , interviews[index].type);
                        html_card_interview = html_card_interview.replace(':description' , interviews[index].description);
                        var result = interviews[index].result ? 'APPROVE' : 'FAILED';
                        html_card_interview = html_card_interview.replace(':result' , result);
                        var _date = new Date(interviews[index].date);
                        html_card_interview = html_card_interview.replace(':date' , ((_date.getDate() > 9) ? _date.getDate() : ('0' + _date.getDate())) + '/' + ((_date.getMonth() > 8) ? (_date.getMonth() + 1) : ('0' + (_date.getMonth() + 1))) + '/' +  _date.getFullYear() );
                        html += html_card_interview
                    }

                    $('#list-interviews').html(html);
                    $("#interviews-expert").modal();
                }
            });
            
        });

        function delay(callback, ms) {
            var timer = 0;
            return function() {
                var context = this, args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function () {
                callback.apply(context, args);
                }, ms || 0);
            };
        }

        $('#search-column-name').on( 'keyup', delay(function (ev) {
            var text = $(this).val();
            if( text .length >= 3){
                if(table){
                    table.columns( 1 ).search(
                        text
                    ).draw();
                }
            }else{
                if(table){
                    table.columns( 1 ).search(
                        ''
                    ).draw();
                }
            }
        } , 500 ));

    });
</script>   
@endsection