<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="interviews-expertLabel"><span class="show_expert_name">Manual Score</span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="score_form" action="#">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="datetime">Total Score</label>
                            <input type="number" class="form-control" name='total_score'>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="row">
                <div class="col-12">
                    <button id="score_btn" class="btn btn-primary" data-id="{{$recruit->id}}">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>