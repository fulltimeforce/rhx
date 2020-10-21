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
        <a class="nav-item nav-link nav-item-custom {{$tab == 'softskills' ? 'active' : ''}}" href="{{ route('recruit.softskills') }}">Evaluación</a>
        <a class="nav-item nav-link nav-item-custom {{$tab == 'selected' ? 'active' : ''}}" href="{{ route('recruit.selected') }}">Seleccionados</a>
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
            <p>{!! $message !!}</p>
        </div>
    @endif

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="modal fade" id="delete-audio" tabindex="-1" role="dialog" aria-labelledby="delete-audioLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="delete-audioLabel">Delete CV File</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    Are you sure you want to delete this file?
                    <input type="hidden" id="delete-audio-rp-id">
                    <input type="hidden" id="delete-audio-position-id">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="deleteAudio">Delete</button>
        </div>
        </div>
    </div>
    </div>
    
    <div class="row">

        <div class="col-12 mb-3">
          <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </div>

        <div class="col-12">
          <p>Records: <span id="count-recruit"></span></p>
        </div>
        
        <div class="col-6 text-left">
            <div class="form-group d-inline-block" style="max-width: 300px;">
                <select name="bulk-action" id="bulk-action" class="form-control" >
                    <option value="">-- Bulk Actions --</option>
                    <option value="approve">Approve</option>
                    <option value="disapprove">Disapprove</option>
                    <option value="trash">Move to Trash</option>
              </select>
            </div>
            <button class="btn btn-info" id="bulk-recruit" type="button" style="vertical-align: top;">Apply</button>
        </div>
        <div class="col-12 text-center mb-5">
            <table class="table row-border order-column" id="list-recruits" data-toggle="list-recruits">
            </table>
            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
        </div>

    </div>
    

@endsection

@section('javascript')

<script type="text/javascript" src="{{ asset('/bootstrap-table/bootstrap-table.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.js') }}"></script>

<script type="text/javascript">
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
                  $('input[name="btSelectAll"]').click();
              }
          });
      }

      function tablebootstrap_filter( data ){
        var columns = [
            { 
              field: 'id', 
              valign: 'middle',
              checkbox: true,
              clickToSelect: false,
              width: 20,
              formatter : function(value,rowData,index) {    
                  var actions = '';

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
                    var actions = '<a class="badge badge-secondary button-disabled" disabled>No Link</a>\n';
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
                var actions = '';

                actions += '<div class="btn-group mt-2 btn-upload-cv '+( rowData.file_path == null ? '' : 'd-none')+'" data-id="'+rowData.rp_id+'" data-positionid="'+rowData.recruit_id+'"> ';
                actions += '<label class="badge badge-secondary" for="cv-upload-evaluate-'+rowData.rp_id+'">Upload CV File</label>';
                actions += '<input type="file" class="custom-file-input cv-upload" id="cv-upload-evaluate-'+rowData.rp_id+'" data-id="'+rowData.rp_id+'" data-positionid="'+rowData.recruit_id+'" style="display:none;" >';
                actions += '</div>';

                actions += '<input class="bulk-input-value" type="hidden" data-index="'+index+'" data-rpid="'+rowData.rp_id+'" data-recruit-id="'+rowData.recruit_id+'">';

                actions += '<div class="btn-group btn-show-cv '+( rowData.file_path != null ? '' : 'd-none')+'" data-id="'+rowData.rp_id+'" data-positionid="'+rowData.recruit_id+'">';
                actions += '<a class="badge badge-success show-cv" href="'+rowData.file_path+'" data-id="'+rowData.rp_id+'" data-positionid="'+rowData.recruit_id+'" target="_blank">Download CV File</a>';
                actions += '<a href="#" class="badge badge-primary confirmation-upload-delete" data-id="'+rowData.rp_id+'" data-positionid="'+rowData.recruit_id+'"><i class="fas fa-trash"></i></a>';
                actions += '</div>';

                actions = actions.replace(/:id/gi , rowData.id);
                return actions;
                },
              class: 'frozencell',
            },
            {
              field: 'pos_id',
              title: "Phone Call",
              valign: 'middle',
              clickToSelect: false,
              width: 20,
              formatter : function(value,rowData,index) {    
                  var actions = '<a class="badge badge-primary recruit-call" data-phonecall="approve" data-positionid="'+rowData.pos_id+'" data-id="'+rowData.recruit_id+'" data-rpid="'+rowData.rp_id+'" href="#">YES</a>'+
                                ' <a class="badge badge-danger recruit-call" data-phonecall="disapprove" data-positionid="'+rowData.pos_id+'" data-id="'+rowData.recruit_id+'" data-rpid="'+rowData.rp_id+'" href="#">NO</a>'

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

        $("table tbody").on('click', 'a.recruit-call' , function(ev){
          ev.preventDefault();
          var id = $(this).data("id");
          var rpid = $(this).data("rpid");
          var positionid = $(this).data("positionid");
          var phonecall = $(this).data("phonecall");
          var confirmed = true;

          if(phonecall=="disapprove"){
            confirmed = confirm("Are you sure you want to "+ (phonecall=="approve"?"APPROVE":"DISAPPROVE") +" this profile?");
          }

          if(confirmed){
            $.ajax({
                type:'POST',
                url: '{{ route("recruit.postulant.call") }}',
                data: {id: id,rpid: rpid,positionid: positionid,phonecall: phonecall},
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
    $('body').on('change' , '.cv-upload' , function(ev){
        // ev.preventDefault();
        var file = this.files[0];
        var rp_id = $(this).data("id");
        var position_id = $(this).data("positionid");
        var bar = $('.progress-bar');

        var _formData = new FormData();
        _formData.append('file', file);
        _formData.append('rp_id', rp_id);
        _formData.append('position_id', position_id);

        $.ajax({
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = (evt.loaded / evt.total) * 100;
                        //Do something with upload progress here
                          bar.width(percentComplete+'%');
                    }
                }, false);
              return xhr;
            },
            type:'POST',
            url: "{{ route('recruit.postulant.upload.cv') }}",
            headers: {
                'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            contentType: false,
            cache: false,
            processData: false,
            data: _formData,
            success:function(data){
                $('.btn-upload-cv[data-id="'+rp_id+'"][data-positionid="'+position_id+'"]').addClass("d-none");
                $('.btn-show-cv[data-id="'+rp_id+'"][data-positionid="'+position_id+'"]').removeClass("d-none");
                $('.show-cv[data-id="'+rp_id+'"][data-positionid="'+position_id+'"]').attr("href" , data.file);
                bar.width('0%');
            }
        });
    })

    $("body").on('click' , '.confirmation-upload-delete' , function(ev){
        ev.preventDefault();
        var rp_id = $(this).data("id");
        var position_id = $(this).data("positionid");

        $("#delete-audio-rp-id").val(rp_id);
        $("#delete-audio-position-id").val(position_id);

        $("#delete-audio").modal();

    })

    $('#delete-audio').on('hidden.bs.modal', function (e) {
      $("#delete-audio-rp-id").val("");
      $("#delete-audio-position-id").val("");
    })

    $("#deleteAudio").on('click' , function(){
        $.ajax({
            type:'POST',
            url: "{{ route('recruit.postulant.delete.cv') }}",
            headers: {
                'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                rp_id : $("#delete-audio-rp-id").val(),
                position_id: $("#delete-audio-position-id").val()
            },
            success:function(data){
                var rp_id = $("#delete-audio-rp-id").val();
                var position_id = $("#delete-audio-position-id").val();
                $('.btn-upload-cv[data-id="'+rp_id+'"][data-positionid="'+position_id+'"]').removeClass("d-none");
                $('.btn-show-cv[data-id="'+rp_id+'"][data-positionid="'+position_id+'"]').addClass("d-none");
                $("#delete-audio").modal('hide');
            }
        });
    });

    $("#bulk-recruit").on('click' , function(){
        var action = $('#bulk-action').val();
        var rp_id_array = [];
        var recruit_id_array = [];

        if(action){
          var checked = $('input[name="btSelectItem"]:checked');

          if(checked.length>0){
              checked.each(function (){
                  var checkbox_index = $(this).data("index");
                  var rp_id_by_index = $('.bulk-input-value[data-index="'+checkbox_index+'"]').data("rpid");
                  var recruit_id_by_index = $('.bulk-input-value[data-index="'+checkbox_index+'"]').data("recruit-id");

                  rp_id_array.push(rp_id_by_index)
                  recruit_id_array.push(recruit_id_by_index)
              });
              console.log('rp', rp_id_array)
              console.log('recruit', recruit_id_array)
              console.log('action', action)
              console.log('tab', "{{ $tab }}")
              console.log('-----------')
              $.ajax({
                  type:'POST',
                  url: "{{ route('recruit.bulk') }}",
                  headers: {
                      'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  data: {
                      action : action,
                      rp_id_array: rp_id_array,
                      recruit_id_array: recruit_id_array,
                      tab: "{{ $tab }}",
                  },
                  success:function(data){
                    location.reload();
                  }
              });
          }else{
            alert('Please, select at least 1 POSTULANT to continue.');
          }

        }else{
          alert('Select a BULK ACTION to continue.');
        }
    });
</script>
@endsection