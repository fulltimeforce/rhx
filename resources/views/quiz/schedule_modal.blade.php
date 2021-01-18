<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="interviews-expertLabel"><span class="show_expert_name">Schedule Email</span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="schedule_form" action="#">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="datetime">Date</label>
                            <input type="text" class="form-control" data-toggle="datepicker" name='schedule_date' value="{{$nowDate}}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="datetime">Time</label>
                            <select name="schedule_time" class="form-control" id="hour_picker">
                            @for ($i = 1; $i <= 24; $i++)
                                <option {{(intval($nowTime)) == $i?'selected':''}}>{{$i}}:00</option>
                            @endfor
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="row">
                <div class="col-12">
                    <button id="schedule_btn" class="btn btn-primary" data-id="{{$recruit->id}}">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>