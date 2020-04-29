@extends('layouts.app' , ['controller' => 'user'])

@section('styles')

<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap-table/bootstrap-table.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.css') }}"/>

<style>
.radio-favorite input{
    display: none;
}
.radio-favorite label{
    position: relative;
    top: 1px;
    min-height: 36px;
}
.radio-favorite label::before {
    content: "";
    width: 30px;
    height: 30px;
    border: 1px solid #e4e4e4;
    border-radius: 2px;
    float: left;
    cursor: pointer;
    background-size: 85%;
    background-repeat: no-repeat;
    background-position: 3px 2px;
    background-color: #dc3545;
    background-image: url("../public/image/icon-tick.svg");
    display: block;
    position: absolute;
    left: 0;
    top: 0px;
}
.radio-favorite input:checked + label::before{
    background-color: #1e7e34;
}
</style>
@endsection
 
@section('content')
    
    <div class="row mb-4">
        <div class="col-12">
            <h5>Menu: </h5>
            <div class="card">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item" style="height: 56px;">
                        <div class="row">
                            <div class="col-8">
                                Resume
                            </div>
                            <div class="col-4">
                                <div class="radio-favorite text-right pr-5">
                                    <input type="radio" class="page-change" name="menu" id="menu-1" value="resume" {{ $user->default_page == 'resume' ? 'checked' : ''  }}>
                                    <label for="menu-1">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item" style="height: 56px;">
                        <div class="row">
                            <div class="col-8">
                                Logs
                            </div>
                            <div class="col-4">
                                <div class="radio-favorite text-right pr-5">
                                    <input type="radio" class="page-change" name="menu" id="menu-2" value="log" {{ $user->default_page == 'log' ? 'checked' : ''  }}>
                                    <label for="menu-2">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item" style="height: 56px;">
                        <div class="row">
                            <div class="col-8">
                                Experts
                            </div>
                            <div class="col-4">
                                <div class="radio-favorite text-right pr-5">
                                    <input type="radio" class="page-change" name="menu" id="menu-3" value="expert" {{ $user->default_page == 'expert' ? 'checked' : ''  }}>
                                    <label for="menu-3">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item" style="height: 56px;">
                        <div class="row">
                            <div class="col-8">
                                Careers
                            </div>
                            <div class="col-4">
                                <div class="radio-favorite text-right pr-5">
                                    <input type="radio" class="page-change" name="menu" id="menu-4" value="careers" {{ $user->default_page == 'careers' ? 'checked' : ''  }}>
                                    <label for="menu-4">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <h5 class="mb-3">Change Password:</h5>
            <div class="form-row">
                <div class="col-4">
                    <input type="password" class="form-control mb-4" id="txt-new-password" >
                    <button class="btn btn-success" id="change-password">Submit</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')

<script type="text/javascript" src="{{ asset('/bootstrap-table/bootstrap-table.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function (ev) {
        $('.page-change').on('change', function(){
            var page = $(this).val();
            $.ajax({
                type:'POST',
                url: "{{ route('user.changepage') }}",
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { page : page }   ,
                success:function(data){
                }
            });
        });

        $('#change-password').on('click', function(){
            
            var password = $("#txt-new-password").val();
            if( password.trim() == '' ) {
                $("#txt-new-password").focus();
                return;
            }
            $.ajax({
                type:'POST',
                url: "{{ route('user.changepassword') }}",
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { password : password }   ,
                success:function(data){
                    location.reload();
                }
            });
        })
    })
</script>

@endsection