@extends('layouts.app' , ['controller' => 'position'])

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap-table/bootstrap-table.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.css') }}"/>

<style>

</style>

@endsection

@section('content')
    <div class="row">
        <div class="col-8">
            <h1>Resume</h1>
        </div>
        <div class="col-4">
            <div class="row">
              <div class="col">
                <select name="" id="experts" size="1" class="form-control"></select>
              </div>
              <div class="col">
                <button class="btn btn-success" id="new-resume">New Resume</button>
              </div>
            </div>
        </div>
    </div>

   
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    
    <div class="row row-cols-1 ">
    
    <div class="col mb-4">

        <table id="table-resume">
        </table>

    </div>
    
    </div>
@endsection

@section('javascript')

<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script type="text/javascript" src="{{ asset('/bootstrap-table/bootstrap-table.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function (ev) {
        $('[data-toggle="tooltip"]').tooltip({
            trigger : 'click'
        });

        $('#experts').select2({
          ajax: {
            url: "{{ route('experts.search') }}",
            data: function (params) {
              var query = {
                name: params.term,
              }
              // Query parameters will be ?search=[term]&type=public
              return query;
            },
            processResults: function (data) {
              // Transforms the top-level key of the response object from 'items' to 'results'
              return {
                results: data.map(m => { return {
                  id: m.id,
                  text: m.fullname
                }})
              };
            }
          }
        });

        $("#new-resume").on('click' , function(){
          var expertId = $("#experts").val();
          if(expertId == '') return;
          $.ajax({
              url:"{{ route('expert.portfolio.save') }}",
              method:"POST",
              data: {
                expertId : expertId,
              },
              headers: {
                  'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success:function(data)
              {
                table_resume();
              }
          });
        })

        $('body').on('click', '.delete-resume' , function(ev){
          ev.preventDefault();
          var _id = $(this).data("id");
          $.ajax({
              url:"{{ route('expert.portfolio.delete') }}",
              method:"POST",
              data: {
                id : _id,
              },
              headers: {
                  'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success:function(data)
              {
                table_resume();
              }
          });
        })

        function table_resume(){
          $("#table-resume").bootstrapTable('destroy').bootstrapTable({
            columns: [
                { field: 'user.name', title: "Recruiter" },
                { field: 'expert.fullname', title: "Name" },
                { field: 'work', title: "Title" },
                { field: 'id', title: "Actions" , width: 400 , formatter: function(value,rowData,index){
                    
                        var actions = '<a href="'+ "{{ route('expert.portfolio.form', ':id') }}" +'" class="btn btn-success">Edit</a>';
                        actions += '  <a href="'+ "{{ route('home') }}" + "/expert/"+ rowData.slug +'" class="btn btn-info">View</a>';
                        if( "{{ Auth::user()->role->id }}" == 1){
                          actions += '  <a href="#" class="btn btn-danger delete-resume" data-id="'+rowData.id+'">Delete</a>';
                        }
                        actions = actions.replace(/:id/gi , rowData.id);
                        return actions;
                    
                    } 
                }
            ],

            url : "{{ route('expert.portfolio.resume.list') }}",
            theadClasses: 'table-dark',
            uniqueId: 'id',

          });

        }
        
        table_resume();
        

    });
</script>

@endsection