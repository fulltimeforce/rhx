@extends('layouts.app');

@section('styles')
<style type="text/css">
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
</style>
@endsection

@section('content')
<div class="row">
    <div class="col">
        <h1>Requests</h1>
    </div>
</div>
<div class="row mb-4">
	<div class="col">
            <p>Result: <span id="count-requests"></span></p>
        </div>
        <div class="col text-right">
            
            <div class="form-group d-inline-block" style="max-width: 300px;">
                <input type="text" placeholder="Search By Name" class="form-control" id="search-column-name">
            </div>
            <button type="button" class="btn btn-success" id="search" style="vertical-align: top;">Search</button>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="{{ asset('/bootstrap-table/bootstrap-table.min.js') }}"></script>
<script type="text/javascript">
	var _records = 50;
	var _total_records = 0;
	var _count_records = 0;

	var _before_rows = 0;

	var _dataRows = [];
	var _page = 1;

	var search_name = "{{ $name }}";

	$("#search-column-name").val( search_name );

	function ajax_requests(_search_name , page){
	   $(".lds-ring").show();
	   var params = {
	       'rows': _records,
	       'page' : page , 
	       'name' : _search_name
	   };
	   $.ajax({
	       type:'GET',
	       url: '{{ route("requests.list") }}',
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
	           $("#count-requests").html( _count_records );
	           _dataRows = _data.rows;
	           tablebootstrap_filter( _data.rows);
	           if( page == 1 ) $("html, body").animate({ scrollTop: 0 }, "slow");
	           $(".lds-ring").hide();
	       }
	   });
	}
   function tablebootstrap_filter( data ){
		var columns = [
			{ field: 'fullname', title: "Name", width: 150 , class: 'frozencell'},
			{
				field: 'id',
				title: "Actions",
				valign: 'middle',
				align: 'left',
				clickToSelect: false,
		     	formatter : function(value,rowData,index) {
		         
		         var actions = '<a class="badge badge-info btn-edit-request" data-id="'+rowData.id+'" data-index="'+index+'" href="{{route("request.edit"))}}">Edit</a>\n';
		         actions += '<a class="badge badge-danger btn-delete-request" data-id="'+rowData.id+'" data-index="'+index+'" href="#">Edit</a>\n';
		         
		         actions = actions.replace(/:id/gi , rowData.id);
		         return actions;
		     	},
		     	width: 100,
		     	class: 'frozencell'
		 	}
		];
		$("#list-requests").bootstrapTable('destroy').bootstrapTable({
          height: undefined,
          columns: columns,
          data: data,
          theadClasses: 'table-dark',
          uniqueId: 'id'
      });
      // ========================================
            
      $("table tbody").on('click' , 'a.btn-delete-request' , function(ev){
          ev.preventDefault();
          var requestId = $(this).attr("data-id");
          var index = $(this).attr("data-index");
      })
   }
   $('#search').on('click' , function(){     
      search_name = $('#search-column-name').val();
      window.history.replaceState({
          edwin: "Fulltimeforce"
          }, "Page" , "{{ route('request.menu') }}" + '?'+ $.param(
              {   search : true , 
                  name: search_name
              }
              )
          );
      _page = 1;
      _count_records = 0;
      location.reload();      
  	});
  	ajax_requests( search_name , 1);
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
			          'name' : _text,
			  };
			  $(".lds-ring").show();
			  $.ajax({
			      type:'GET',
			      url: '{{ route("request.menu") }}',
			      data: $.param(data),
			      headers: {
			          'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
			          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			      },
			      success:function(data){

			          let _data = JSON.parse(data);
			          _before_rows = _data.total;
			          $("#list-requests").bootstrapTable('append', _data.rows );
			          
			          _count_records = _count_records + _data.rows.length;
			          $("#count-requests").html( _count_records );
			          $(".lds-ring").hide();
			      }
			  });
			}
      }
  	});
</script>
@endsection