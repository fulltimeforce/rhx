<div class="modal-dialog modal-lg" role="document" style="max-width: 800px;">
   <div class="modal-content">
      <div class="modal-header">
         <h5 class="modal-title" id="overview-title-label">RAVEN Overview</h5>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span>
         </button>
      </div>
      <div class="modal-body">
         <div class="row">
            @if ($test)
            <div class="col-12 px-lg-5">
               <div id="ov-columns">
                  @foreach ($groups as $k => $group)
                  @php
                     $count = 0
                  @endphp
                     <div class="ov-col">
                        <p class="ov-col-title">GROUP {{$k}}</p>
                        <div class="ov-col-rounded">
                           @foreach ($group as $answer)
                              <div class="ov-answer">
                                 <span class="anw-number">{{strlen((string) $answer["q"]) == 1 ? "0".$answer["q"] : $answer["q"]}})</span>
                                 <div class="anw-input {{$answer["class"]}}">
                                    <div><span>{{$answer["answer"]}}</span></div>
                                 </div>
                              </div>
                              @php
                                 if($answer["class"] == "correct") {
                                    $count++;
                                 }
                              @endphp
                           @endforeach
                        </div>
                        <p class="ov-col-footer">{{$count}}/12</p>
                     </div>
                  @endforeach
               </div>
            </div>
            @else
            <div class="col-12 px-lg-5">
               <div style="border: 1px solid #bbbbbb; border-radius: 5px; padding: 1rem;">
                  <h5>No raven answers found...</h5>
               </div>
            </div>
            @endif
            <div class="col-12 px-lg-5 my-3">
               <div style="display: flex; justify-content: space-between;">
                  <div class="ov-result-container" style="width: 210px">
                     <div class="container-title">
                        TOTAL SCORE
                     </div>
                     <div class="container-append">
                        {{$recruit->raven_total?:'??'}}/60
                     </div>
                  </div>
                  @if ($recruit->raven_status == "invalid")
                     <a class="btn btn-warning btn-quiz-restore" data-id="{{$recruit->id}}">Restore Test</a>
                  @endif
               </div>
               
            </div>
         </div>
      </div>
   </div>
</div>