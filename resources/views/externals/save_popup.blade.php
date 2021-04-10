@php
    $isEdit = isset($recruit);
@endphp
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="create_external">{{$isEdit?'Edit: '.$recruit->fullname:'Add new External Expert'}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="close">
            <span>&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form name="new-recruit-form" id="new-recruit-form" action="{{ route('externals.save') }}" method="POST" enctype="multipart/form-data">@csrf
            <div class="row">
                <div class="col-md-12">
                    {{-- <input type="hidden" name="file_path" id="file_path" value="" class="form-control"> --}}
                    @if ($isEdit)
                    <input type="hidden" name="recruit_id" id="recruit_id" class="form-control" value="{{$recruit->id}}">
                    @endif
                    <input type="hidden" name="index" id="index" class="form-control">
                    <div class="form-group">
                        <label for="fullname">Name *</label>
                        <input type="text" name="fullname" id="fullname" class="form-control" value="{{$isEdit?$recruit->fullname:''}}">
                    </div>                      
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="position_id">Positions *</label>
                        <select id="position_id" class="form-control" name="position_id" >
                            @foreach($positions as $pid => $position)
                                <option value="{{ $position->id }}" {{$isEdit?$recruit->position_id == $position->id?'selected':'':''}}>{{ $position->name }}</option>
                            @endforeach
                        </select>
                        @if ($isEdit)
                        <input type="hidden" name="rp_id" value="{{$recruit->rp_id}}">
                            
                        @endif
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="agent_id">Agent *</label>
                        <select name="agent_id" id="agent_id" class="form-control">
                            @foreach($agents as $pid => $agent)
                                <option value="{{$agent->id}}" {{$isEdit?$recruit->agent_id == $agent->id?'selected':'':''}}>{{$agent->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone_number">Phone</label>
                        <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{$isEdit?$recruit->phone_number:''}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email_address">Email</label>
                        <input type="email" name="email_address" id="email_address" class="form-control" value="{{$isEdit?$recruit->email_address:''}}">
                    </div> 
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="salary">Salary</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{$isEdit?$recruit->type_money == 'sol'?'S/':'$':'S/'}}
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item change-money" data-money="sol" href="#">S/</a>
                                    <a class="dropdown-item change-money" data-money="dolar" href="#">$</a>
                                </div>
                            </div>
                            <input type="hidden" name="type_money" value="{{$isEdit?$recruit->type_money == 'sol'?'sol':'dolar':'sol'}}" id="type_money">
                            <input type="number" min="0" name="salary" class="form-control" id="salary" value="{{$isEdit?$recruit->salary:''}}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="availability">Availability</label>
                        <select name="availability" id="availability" class="form-control">
                            <option value="Inmediata" {{$isEdit?$recruit->availability == "Inmediata"?'Inmediata':'':''}}>Inmediata</option>
                            <option value="1 semana" {{$isEdit?$recruit->availability == "1 semana"?'1 semana':'':''}}>1 semana</option>
                            <option value="2 semanas" {{$isEdit?$recruit->availability == "2 semanas"?'2 semanas':'':''}}>2 semanas</option>
                            <option value="3 semanas" {{$isEdit?$recruit->availability == "3 semanas"?'3 semanas':'':''}}>3 semanas</option>
                            <option value="1 mes o más" {{$isEdit?$recruit->availability == "1 mes o más"?'1 mes o más':'':''}}>1 mes o más</option>
                        </select>
                    </div> 
                </div>
                @if (!$isEdit)    
                <div class="col-md-12">
                    <div class="form-group form-inline">
                        <label for="availability">CV File</label>
                        <div class="custom-file mt-2">
                            <input type="file" class="custom-file-input" name="file_path" id="file_path" accept="application/msword, application/pdf, .doc, .docx">
                            <label id="add-file-label" class="custom-file-label" for="file_path">Add a File (max 2M)</label>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <div class="form-group" id="btn-form-save">
            <label>&nbsp;</label>
            <button type="submit" id="save_recruit" class="btn btn-success form-control">Save</button>
        </div>
    </div>
</div>