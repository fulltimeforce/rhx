@extends('layouts.app' , ['controller' => 'position'])

@section('styles')

<link rel="stylesheet" type="text/css" href="{{ asset('/datatable/dataTables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/datatable/jquery.dataTables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/datatable/css/dataTables.bootstrap4.min.css') }}"/>

@endsection
 
@section('content')
    <div class="row mb-4">
        <div class="col">

            <h1>Schedules</h1>

        </div>
    </div>

    <div class="row mb-4">
        <div class="col">
            <table class="table row-border order-column m-0" id="table-schedule" >
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Expert</th>
                        <th>Position</th>
                        <th>Recruiter</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th style="width : 200px;">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $logs as $lid => $log)
                        <tr>
                            <td>{{ explode(" " , $log->datetime)[0] }}</td>
                            <td>{{ explode(" " , $log->datetime)[1] }} {{ explode(" " , $log->datetime)[2] }}</td>
                            <td>{{ $log->expert->fullname }}</td> 
                            <td>{{ $log->position->name }}</td>
                            <td>{{ $log->user->name }}</td>
                            <td>{{ $log->expert->phone }}</td>
                            <td>{{ $log->expert->email }}</td>
                            <td style="width : 200px">
                                <div class="form-group">
                                    <textarea name="" id="" style="height: 155px;" class="form-control txt-notes mb-2">{{ $log->notes }}</textarea>
                                    <button class="btn btn-sm btn-primary save-note" data-id="{{ $log->id }}">Save</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('javascript')

<script type="text/javascript" src="{{ asset('/datatable/jquery.dataTables.min.js') }}"></script>

<script>
$(document).ready(function (ev) {

    var table = $('#table-schedule').DataTable({
        lengthMenu: [[15, 30, 50, -1], [15, 30, 50, "All"]],
        scrollY: "500px",
        scrollX: true,
        searching: false,
        scrollCollapse: true,
        ordering: false,
    });

    $('.save-note').on('click' , function(ev){
        ev.preventDefault();
        var id = $(this).data("id");
        var txt = $(this).parent().find(".txt-notes").val();

        $.ajax({
            type: 'POST',
            url: '{{ route("log.schedule.save") }}',
            data:{
              id:   id,
              note : txt
            },
            headers: {
                'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            
            success:function(data){

            }

        });
    });
});
</script>
@endsection