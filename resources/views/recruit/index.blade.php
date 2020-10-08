@extends('layouts.app' , ['controller' => 'position'])

@section('styles')

<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap-table/bootstrap-table.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.css') }}"/>

<style>
    /* The switch - the box around the slider */
.SliderSwitch {
  max-width: 600px;
  margin-left:auto;
  margin-right: auto;
  text-align: center;
}

.SliderSwitch input{
  visibility: hidden;
  display: inline-block;
  width: 1px;
  height: 1px;
}

.SliderSwitch label{
  font-family: Helvetica, Arial, sans-serif;
  pointer: cursor;
}

.SliderSwitch__container{
  background-color: #FF0000;
  height: 20px;
  display: inline-block;
  width: 50px;
  border-radius: 10px;
  position: relative;
  vertical-align: middle;
  box-shadow: inset 0px 0px 3px 1px rgba(0,0,0,0.3);
  margin-left: 10px;
  
  transition: background-color 300ms ease-in-out;
}

.SliderSwitch__toggle {
  display: block;
  height: 24px;
  width: 24px;
  border-radius: 12px;
  background-color: white;
  border: 1px solid #DDD;
  position: absolute;
  top: -2px;
  left: -2px;
  box-shadow: 0px 0px 3px rgba(0,0,0,0.2);
  cursor: pointer;
  
  transition: left 300ms ease-in-out;
}

.SliderSwitch__toggle:after {
  content: '\f00d';
  font-size: 12px;
  color: #FF4136;
  display: block;
  position: absolute;
  top: 50%;
  margin-top: -6px;
  left: 0;
  width: 100%;
  text-align: center;
  
  transition: color 300ms ease-in-out;
  
}

input:checked + .SliderSwitch__container{
  background-color: #01FF70;
}

input:checked + .SliderSwitch__container .SliderSwitch__toggle {
  left: calc( 100% - 20px );
}

input:checked + .SliderSwitch__container .SliderSwitch__toggle:after {
  content: '\f00c';
  color: #2ECC40;
}
table.dataTable thead .sorting:before,
table.dataTable thead .sorting:after{
    content: '';
}

td.stickout{
    background-color: yellow;
}
td.frozencell{
    background-color : #fafafa;
}

a.badge-success.focus, 
a.badge-success:focus,
a.badge-secondary.focus, 
a.badge-secondary:focus,
a.badge-danger.focus, 
a.badge-danger:focus,
a.badge-warning.focus, 
a.badge-warning:focus,
a.badge-primary.focus, 
a.badge-primary:focus{
    box-shadow: none;
}

.btn-group>.badge:not(:last-child):not(.dropdown-toggle){
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}
.btn-group>.badge:not(:first-child){
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}
.btn-group>.badge:not(:first-child) {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}
.btn-group>.badge{
    height: 21px;
}
.btn-group>.badge.badge-primary{
    font-size: 9px;
}
.btn-group>.badge.badge-primary i.fas:before{
    vertical-align: -webkit-baseline-middle;
}

.lds-ring {
  display: inline-block;
  position: relative;
  width: 80px;
  height: 80px;
}
.lds-ring div {
  box-sizing: border-box;
  display: block;
  position: absolute;
  width: 64px;
  height: 64px;
  margin: 8px;
  border: 8px solid #17a2b8;
  border-radius: 50%;
  animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
  border-color: #17a2b8 transparent transparent transparent;
}
.lds-ring div:nth-child(1) {
  animation-delay: -0.45s;
}
.lds-ring div:nth-child(2) {
  animation-delay: -0.3s;
}
.lds-ring div:nth-child(3) {
  animation-delay: -0.15s;
}
.nav-item-custom {
    border: 1px solid rgba(86, 61, 124, .2);
}

@keyframes lds-ring {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.button-disabled {
  cursor: not-allowed;
}

</style>
@endsection
 
@section('content')
      <nav class="nav nav-pills nav-fill mb-4">
        <a class="nav-item nav-link nav-item-custom {{$tab == 'postulant' ? 'active' : ''}}" href="{{ route('recruit.menu') }}">Postulantes</a>
        <a class="nav-item nav-link nav-item-custom {{$tab == 'outstanding' ? 'active' : ''}}" href="{{ route('recruit.outstanding') }}">Perfiles Destacados</a>
        <a class="nav-item nav-link nav-item-custom {{$tab == 'preselected' ? 'active' : ''}}" href="{{ route('recruit.preselected') }}">Pre-Seleccionados</a>
        <a class="nav-item nav-link disabled nav-item-custom" href="#">Evaluados Soft Skills</a>
        <a class="nav-item nav-link disabled nav-item-custom" href="#">Seleccionados</a>
      </nav>

    @if ($errors->any())
      <div class="alert alert-danger">
          <strong>Whoops!</strong> There were some problems with your input.<br><br>
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    
    <div class="row">
        <div class="col-12">
            <form name="new-recruit" id="new-recruit" action="{{ route('recruit.save') }}" method="POST" enctype="multipart/form-data">@csrf
            <table class="table" >
                <tr>
                    <td>
                        <div class="form-group">
                            <label for="fullname">Name</label>
                            <input type="text" name="fullname" id="fullname" class="form-control">
                        </div>
                    </td>
                    <td >
                        <div class="form-group">
                            <label for="identification_number">DNI/CE/Pasaporte</label>
                            <input type="text" name="identification_number" id="identification_number" class="form-control">
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <label for="position_id">Positions</label>
                            <select id="position_id" class="form-control" name="position_id" >
                                <option value="">None</option>
                                @foreach($positions as $pid => $position)
                                    <option value="{{ $position->id }}">{{ $position->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <label for="platform">Platform</label>
                            <select name="platform" id="platform" class="form-control" >
                                <option value="">None</option>
                                @foreach($platforms as $pid => $platform)
                                    <option value="{{$platform->value}}">{{$platform->label}}</option>
                                @endforeach
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="form-group" style="position: relative;">
                            <label for="phone_number">Phone</label>
                            <input type="text" name="phone_number" id="phone_number" class="form-control">
                        </div>
                    </td>
                    <td>
                        <div class="form-group" style="position: relative;">
                            <label for="email_address">Email</label>
                            <input type="text" name="email_address" id="email_address" class="form-control">
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <label for="profile_link">Link</label>
                            <input type="text" name="profile_link" id="profile_link" class="form-control">
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                          <label for="file_path">CV</label>
                          <div class="custom-file">
                            <input type="file" class="custom-file-input" name="file_path" id="file_path" accept="application/msword, application/pdf, .doc, .docx">
                            <label class="custom-file-label" for="file_path">UPLOAD CV (max 2M)</label>
                          </div>
                        </div>
                    </td>
                    <td>
                        <div class="form-group" id="btn-form-save">
                          <label>&nbsp;</label>
                          <button type="submit" id="save_recruit" class="btn btn-success">Save</button>
                        </div>
                    </td>
                </tr>
            </table>
            </form>
        </div>

        <div class="col-12 mb-3">
          <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </div>

        <div class="col-6">
          <p>Records: <span id="count-recruit"></span></p>
        </div>
        
        <div class="col-6 text-right">
            <div class="form-group d-inline-block" style="max-width: 300px;">
                <input type="text" placeholder="Search By Name" class="form-control" id="search-column-name">
            </div>
            <button class="btn btn-primary" id="search-recruit" type="button" style="vertical-align: top;">Buscar</button>
        </div>
        <div class="col-12 text-center mb-5">
            <table class="table row-border order-column" id="list-recruits"> 
            </table>
            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
        </div>

    </div>
    

@endsection

@section('javascript')

<script type="text/javascript" src="{{ asset('/bootstrap-table/bootstrap-table.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/jquery-form/jquery.form.js') }}"></script>

<script type="text/javascript">
    $(function() {

      var bar = $('.progress-bar');

      $('form').ajaxForm({
          beforeSend: function() {
            var percentVal = '0%';
            bar.width(percentVal);
          },
          uploadProgress: function(event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            bar.width(percentVal);
          },
          complete: function(xhr) {
            bar.width('0%');
            console.log(xhr.responseText)
          }
      });
    }); 

    $(document).ready(function (ev) {
        
      $(".lds-ring").hide();

      var _records = 50;
      var _total_records = 0;
      var _count_records = 0;

      var _before_rows = 0;

      var _dataRows = [];
      var _page = 1;
      
      var search_name = "{{ $s }}";

      $("#search-column-name").val( search_name );

      function ajax_recruits(_search_name, page){
          $(".lds-ring").show();

          var params = {
              'rows' : _records,
              'page' : page ,
              'name' : _search_name,
              'tab'  : "{{ $tab }}"
          };

          $.ajax({
              type:'GET',
              url: '{{ route("recruit.list") }}',
              data: $.param(params),
              headers: {
                  'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success:function(data){
                  let _data = JSON.parse(data)
                  _total_records = _data.totalNotFiltered;
                  _before_rows = _data.total;
                  _count_records = _count_records + _data.rows.length;
                  $("#count-recruit").html( _count_records );
                  _dataRows = _data.rows;
                  tablebootstrap_filter( _data.rows );
                  if( page == 1 ) $("html, body").animate({ scrollTop: 0 }, "slow");
                  $(".lds-ring").hide();
              }
          });
      }

      function tablebootstrap_filter( data ){
        var columns = [
            {
              field: 'id', 
              title: "Accion",
              valign: 'middle',
              clickToSelect: false,
              width: 20,
              formatter : function(value,rowData,index) {    
                  var actions = '<a class="badge badge-primary recruit-edit"   href=" '+ "{{ route('recruit.postulant.edit', ':id' ) }}"+ '">Edit</a>'+
                                ' <a class="badge badge-danger recruit-delete" data-positionid="'+rowData.position_id+'" data-id="'+rowData.id+'" href="#">Delete</a>';

                  actions = actions.replace(/:id/gi , rowData.id);
                  return actions;
                },
              class: 'frozencell',
            },
            {
              field: 'created_at', 
              title: "Date",
              width: 50,
              formatter : function(value,rowData,index) { 
                  var aux_date = new Date(rowData.created_at)
                  var actions = (aux_date.getDate())+'/'+(aux_date.getMonth()+1)+'/'+(aux_date.getFullYear())  

                  actions = actions.replace(/:id/gi , rowData.id);
                  return actions;
                },
              class: 'frozencell',
            },
            { field: 'user_name', title: "Recruiter", width: 75 , class: 'frozencell'},
            { field: 'fullname', title: "Postulant", width: 75 , class: 'frozencell'},
            {
              field: 'profile_link', 
              title: "Link",
              width: 50,
              formatter : function(value,rowData,index) {    
                  if(rowData.profile_link){
                    var actions = '<a class="badge badge-success btn-link-recruit" href="'+rowData.profile_link+'" target="_blank">Go to Link</a>\n';
                  }else{
                    var actions = '<a class="badge badge-secondary button-disabled" disabled>Go to Link</a>\n';
                  }
                  actions = actions.replace(/:id/gi , rowData.id);
                  return actions;
                },
              class: 'frozencell',
            },
            { field: 'position_name', title: "Position", width: 75 , class: 'frozencell'},
            { field: 'phone_number', title: "Phone", width: 75 , class: 'frozencell'},
            { field: 'email_address', title: "E-mail", width: 75 , class: 'frozencell'},
            {
              field: 'file_path', 
              title: "CV",
              width: 50,
              formatter : function(value,rowData,index) {    
                  if(rowData.file_path){
                    var actions = '<a class="badge badge-info btn-cv-recruit" href="'+rowData.file_path+'" target="_blank">Download CV</a>\n';
                  }else{
                    var actions = '<a class="badge badge-secondary button-disabled" disabled>Download CV</a>\n';
                  }
                  actions = actions.replace(/:id/gi , rowData.id);
                  return actions;
                },
              class: 'frozencell',
            },
            {
              title: "Outstanding",
              valign: 'middle',
              clickToSelect: false,
              width: 20,
              formatter : function(value,rowData,index) {    
                  var actions = '<a class="badge badge-primary recruit-outstanding" data-outstanding="approve" data-positionid="'+rowData.position_id+'" data-id="'+rowData.id+'" href="#">YES</a>'+
                                ' <a class="badge badge-danger recruit-outstanding" data-outstanding="disapprove" data-positionid="'+rowData.position_id+'" data-id="'+rowData.id+'" href="#">NO</a>'

                  actions = actions.replace(/:id/gi , rowData.id);
                  return actions;
                },
              class: 'frozencell',
            },
        ];
        
        $("#list-recruits").bootstrapTable('destroy').bootstrapTable({
            height: undefined,
            columns: columns,
            data: data,
            theadClasses: 'table-dark',
            uniqueId: 'id'
        });
        // =================== DELETE

        $("table tbody").on('click', 'a.recruit-outstanding' , function(ev){
          ev.preventDefault();
          var id = $(this).data("id");
          var positionid = $(this).data("positionid");
          var outstanding = $(this).data("outstanding");
          var confirmed = confirm("Are you sure you want to "+ (outstanding=="approve"?"APPROVE":"DISAPPROVE") +" this profile?");
          if(confirmed){
            $.ajax({
                type:'POST',
                url: '{{ route("recruit.postulant.outstanding") }}',
                data: {id : id,positionid: positionid,outstanding: outstanding},
                headers: {
                  'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){
                  //$("#list-users").bootstrapTable('removeByUniqueId',id);
                  location.reload();
                }
            });
          }
        });

        $("table tbody").on('click', 'a.recruit-delete' , function(ev){
          ev.preventDefault();
          var recruit_id = $(this).data("id");
          var position_id = $(this).data("positionid");

          var confirmed = confirm("Are you sure you want to DELETE this profile?");

          if(confirmed){
            $.ajax({
                type:'POST',
                url: '{{ route("recruit.postulant.delete") }}',
                data: {recruit_id : recruit_id,position_id: position_id},
                headers: {
                  'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){
                  //$("#list-users").bootstrapTable('removeByUniqueId',id);
                  location.reload();
                }
            });
          }
        });

      }

      $('#search-recruit').on('click' , function(){
        
        search_name = $('#search-column-name').val();
        
        window.history.replaceState({
            edwin: "Fulltimeforce"
            }, "Page" , "{{ route('recruit.menu') }}" + '?'+ $.param(
                {   search : true , 
                    name: search_name
                }
                )
            );
        _page = 1;
        _count_records = 0;
        location.reload();
      });

      ajax_recruits(search_name, 1);

      $(window).on('scroll', function (e){
        console.log( $(window).scrollTop() + $(window).height() , $(document).height() )
        if($(window).scrollTop() + $(window).height() >= $(document).height()) {
            console.log( _count_records , _total_records, _before_rows , _records , "##################" );
            if( _count_records < _total_records && _before_rows == _records ){
                _page++;
                var _text = $('#search-column-name').val();
                var data = {
                        'offset': _records,
                        'rows': _records,
                        'page' : _page , 
                        'tab'  : "{{ $tab }}",
                        'name' : _text,
                };
                $(".lds-ring").show();
                $.ajax({
                    type:'GET',
                    url: '{{ route("recruit.list") }}',
                    data: $.param(data),
                    headers: {
                        'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(data){

                        let _data = JSON.parse(data);
                        _before_rows = _data.total;
                        $("#list-sales").bootstrapTable('append', _data.rows );
                        
                        _count_records = _count_records + _data.rows.length;
                        $("#count-sale").html( _count_records );
                        $(".lds-ring").hide();
                    }
                });
            }
        }
      });

    });
</script>
<script>
    $("#save_recruit").on('click', function(ev){        
      ev.preventDefault();
      var link = $("#profile_link").val();
      var file_path = $("#file_path").val();

      if(!link && !file_path){
        alert('We need "Link" or "CV"')
      }else{
        $("#new-recruit").submit();
      }
    });

    $('#file_path').on('change',function(ev){
      //get the file name
      var fileName = $(this).val();
      //replace the "Choose a file" label
      $(this).next('.custom-file-label').html(ev.target.files[0].name);
    });

</script>

@endsection