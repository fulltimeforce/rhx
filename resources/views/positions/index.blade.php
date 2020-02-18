@extends('layouts.app')
 
@section('content')
    <div class="row">
        <div class="col">
            <h1>Careers</h1>
            @auth
            <a class="btn btn-secondary float-right" href="{{ route('positions.create') }}">New Position</a>
            @endauth
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
                                <a href="#" data-position="{{$pid}}" class="btn btn-outline-primary float-right btn-apply-expert">Apply!</a> 
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <a href="{{ route('positions.edit', $position->id) }}" class="card-link">Edit</a>
                <a href="{{ route('positions.experts', $position->id) }}" class="card-link">Show applicants</a>
                @endguest
            </div>
        </div>
    </div>
    @endforeach
    
    </div>
@endsection

@section('javascript')

<script type="text/javascript">
    $(document).ready(function (ev) {

        $(".btn-apply-expert").on('click',function(ev){
            ev.preventDefault();
            var position = $(this).data("position")
            var email = $("input[name='email_"+position+"']").val();
            $.ajax({

                type:'POST',

                url:'/expert/validate',
                
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                data:{email:email},

                success:function(data){
                    
                    window.location = data;

                }

            });

        })
        

    });
</script>

@endsection