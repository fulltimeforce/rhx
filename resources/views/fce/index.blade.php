@extends('layouts.app' , ['controller' => 'experts'])

@section('styles')

<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap-table/bootstrap-table.min.css') }}"/>
 
<style>
caption{
    /* caption-side: top !important; */
    width: max-content !important;
    border: 1px solid;
    margin-bottom: 1.5rem;
}
#showURL{
    word-break: break-all;
}
#allexperts tbody tr td:nth-child(2){
    text-transform: capitalize;
}
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

.slider-inner:after {
  background-color: #dc3545;
  color: #FFF;
  content: "FAILED";
  padding-right: .75em;
  text-align: right;
}

.slider-circle {
  background-color: #FFF;
  border: 0;
  border-radius: 20px;
  bottom: 0;
  display: block;
  margin: 5px;
  position: absolute;
  right: 91px;
  top: 0;
  transition: all 0.3s ease-in 0s; 
  width: 20px;
}

.slider-checkbox:checked + .slider-label .slider-inner {
  margin-left: 0;
}

.slider-checkbox:checked + .slider-label .slider-circle {
  background-color: #FFFFFF;
  right: 0; 
}
td.stickout{
    background-color: yellow;
}
td.frozencell{
    background-color : #fafafa;
}
.dataTables_filter{
    display: none;
}
.txt-description{
    white-space: pre-line;
}
.ui-jqgrid .ui-jqgrid-btable tbody tr.jqgrow td,
.ui-jqgrid .ui-jqgrid-htable thead th div{
    white-space: normal;
}
.ui-jqgrid .table-bordered th.ui-th-ltr{
    color: #fff;
    background-color: #343a40;
}
.select2-container{
    width: 100% !important;
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
@keyframes lds-ring {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
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
main{
    position: relative;
}
.buble-audio{
    position: fixed;
    padding: .7rem;
    z-index: 2;
    background: #FFFFFF;
    right: 15px;
    bottom: 16px;
    max-width: 350px;
    width: 100%;
    border: 1px solid #000;
    font-size: 14px;
}
.section-audio{
    position: relative;
}
.buble-audio p{
    margin-bottom: 3px;
}
.section-audio .close-audio{
    position: absolute;
    right: -12px;
    top: -25px;
    background: #FFF;
    z-index: 4;
    font-size: 24px;
    line-height: 1;
    border-radius: 27px;
}
.speed-audio{
    font-size: 12px;
    margin-bottom: 5px;
}
.tab-fce{
    display: none;
}
.tab-fce.fce-active{
    display: flex;
}
</style>
@endsection

@section('content')

<!--  
        /*========================================== FCE ==========================================*/
    -->
<div class="modal fade" id="fceExpert" tabindex="-1" role="dialog" aria-labelledby="fceExpertLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="fceExpertLabel">FCE - <span id="fce_expert_name"></span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        
        <div class="modal-body">
            <div class="row">
                <input type="hidden" id="expert_index">
                <table id="list-audios" class="table table-dark mb-5">
                    <thead>
                        <tr>
                            <th><span id="audio_expert_name"></span></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <form class="col" id="form_fce">
                    <input type="hidden" id="expert_id" name="expert_id">
                    <div class="row tab-fce fce-active" data-tab="1">
                        <!-- <div class="form-group col-6">
                            <label for="fce_grammar_vocabulary">Grammar & Vocabulary</label>
                            <input type="number" step="0.01" min="0" class="form-control total-fce" id="fce_grammar_vocabulary" name="fce_grammar_vocabulary">
                        </div>
                        <div class="form-group col-6">
                            <label for="fce_discourse_management">Discourse Management</label>
                            <input type="number" step="0.01" min="0" class="form-control total-fce" id="fce_discourse_management" name="fce_discourse_management">
                        </div>
                        <div class="form-group col-6">
                            <label for="fce_pronunciation">Pronunciation</label>
                            <input type="number" step="0.01" min="0" class="form-control total-fce" id="fce_pronunciation" name="fce_pronunciation">
                        </div>
                        <div class="form-group col-6">
                            <label for="fce_interactive_communication">Interactive Communication</label>
                            <input type="number" step="0.01" min="0" class="form-control total-fce" id="fce_interactive_communication" name="fce_interactive_communication">
                        </div>
                        <div class="form-group col-6">
                            <label for="fce_total">TOTAL</label>
                            <input type="text" class="form-control" id="fce_total" name="fce_total" readonly>
                        </div>
                        <div class="form-group col-6">
                            <label for="fce_overall">Overall level</label>
                            <input type="text" class="form-control" id="fce_overall" name="fce_overall">
                        </div>
                        <div class="form-group col-12">
                            <label for="fce_comments">Comments</label>
                            <textarea class="form-control" cols="30" rows="10" id="fce_comments" name="fce_comments"></textarea>
                        </div> -->
                        <div class="col-12 mb-4">
                            <h3>Grammar & Vocabulary</h3>
                            <b>Grammatical Forms</b>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="fce_comments">Wide range simple and complex</label>
                                <input type="radio" class="col-3" name="grammatical_forms" value="C2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="fce_comments">Simple and some complex</label>
                                <input type="radio" class="col-3" name="grammatical_forms" value="C1">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="fce_comments">Simple and attempt complex</label>
                                <input type="radio" class="col-3" name="grammatical_forms" value="B2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="fce_comments">Good at simple</label>
                                <input type="radio" class="col-3" name="grammatical_forms" value="B1">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="fce_comments">Sufficient at simple</label>
                                <input type="radio" class="col-3" name="grammatical_forms" value="A2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="fce_comments">Limited at simple</label>
                                <input type="radio" class="col-3" name="grammatical_forms" value="A1">
                            </div>
                        </div>
                    </div>
                    <div class="row tab-fce" data-tab="2">
                        
                        <div class="col-12 mb-4">
                            <h3>Grammar & Vocabulary</h3>
                            <b>Vocabulary</b>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="vocabulary">Unfamiliar and abstract topics</label>
                                <input type="radio" class="col-3" name="vocabulary" value="C2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="vocabulary">Unfamiliar topics vocabulary</label>
                                <input type="radio" class="col-3" name="vocabulary" value="C1">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="vocabulary">Good range of technical topics vocabulary</label>
                                <input type="radio" class="col-3" name="vocabulary" value="B2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="vocabulary">Some technical topics vocabulary</label>
                                <input type="radio" class="col-3" name="vocabulary" value="B1">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="vocabulary">Everyday situations vocabulary</label>
                                <input type="radio" class="col-3" name="vocabulary" value="A2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="vocabulary">Isolated word and phrases vocabulary</label>
                                <input type="radio" class="col-3" name="vocabulary" value="A1">
                            </div>
                        </div>
                    </div>
                    <div class="row tab-fce" data-tab="3">
                        <div class="col-12 mb-4">
                            <h3>Discourse Management</h3>
                            <b>Stretch of Language</b>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="stretch_language">Extended stretches with ease</label>
                                <input type="radio" class="col-3" name="stretch_language" value="C2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="stretch_language">Extended stretches with some difficulty</label>
                                <input type="radio" class="col-3" name="stretch_language" value="C1">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="stretch_language">Attempt extended stretches</label>
                                <input type="radio" class="col-3" name="stretch_language" value="B2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="stretch_language">Extended beyond short responses</label>
                                <input type="radio" class="col-3" name="stretch_language" value="B1">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="stretch_language">Rather short responses</label>
                                <input type="radio" class="col-3" name="stretch_language" value="A2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="stretch_language">Always short responses</label>
                                <input type="radio" class="col-3" name="stretch_language" value="A1">
                            </div>
                        </div>
                    </div>
                    <div class="row tab-fce" data-tab="4">
                        <div class="col-12 mb-4">
                            <h3>Discourse Management</h3>
                            <b>Cohesive Devices</b>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="cohesive_devices">Wide range of cohesive devices</label>
                                <input type="radio" class="col-3" name="cohesive_devices" value="C2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="cohesive_devices">Some range of cohesive devices</label>
                                <input type="radio" class="col-3" name="cohesive_devices" value="C1">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="cohesive_devices">Basis cohesive but fluent outcome</label>
                                <input type="radio" class="col-3" name="cohesive_devices" value="B2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="cohesive_devices">Basic cohesive devices</label>
                                <input type="radio" class="col-3" name="cohesive_devices" value="B1">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="cohesive_devices">Very few cohesive devices</label>
                                <input type="radio" class="col-3" name="cohesive_devices" value="A2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="cohesive_devices">Almost zero use of cohesive devices</label>
                                <input type="radio" class="col-3" name="cohesive_devices" value="A1">
                            </div>
                        </div>
                    </div>
                    <div class="row tab-fce" data-tab="5">
                        <div class="col-12 mb-4">
                            <h3>Discourse Management</h3>
                            <b>Hesitation</b>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="hesitation">No hesitation at all</label>
                                <input type="radio" class="col-3" name="hesitation" value="C2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="hesitation">Very little hesitation</label>
                                <input type="radio" class="col-3" name="hesitation" value="C1">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="hesitation">Little hesitation</label>
                                <input type="radio" class="col-3" name="hesitation" value="B2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="hesitation">Some hesitation</label>
                                <input type="radio" class="col-3" name="hesitation" value="B1">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="hesitation">Obvious hesitation</label>
                                <input type="radio" class="col-3" name="hesitation" value="A2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="hesitation">Lots of hesitation</label>
                                <input type="radio" class="col-3" name="hesitation" value="A1">
                            </div>
                        </div>
                    </div>
                    <div class="row tab-fce" data-tab="6">
                        <div class="col-12 mb-4">
                            <h3>Discourse Management</h3>
                            <b>Organizations of Ideas</b>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="organizations_ideas">Relevant, coherent and varied</label>
                                <input type="radio" class="col-3" name="organizations_ideas" value="C2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="organizations_ideas">Relevant and clear organizations of ideas</label>
                                <input type="radio" class="col-3" name="organizations_ideas" value="C1">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="organizations_ideas">Relevant</label>
                                <input type="radio" class="col-3" name="organizations_ideas" value="B2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="organizations_ideas">Mostly relevant</label>
                                <input type="radio" class="col-3" name="organizations_ideas" value="B1">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="organizations_ideas">Some relevant</label>
                                <input type="radio" class="col-3" name="organizations_ideas" value="A2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="organizations_ideas">Some relevant but limited by use of language</label>
                                <input type="radio" class="col-3" name="organizations_ideas" value="A1">
                            </div>
                        </div>
                    </div>
                    <div class="row tab-fce" data-tab="7">
                        <div class="col-12 mb-4">
                            <h3>Pronunciation</h3>
                            <b>Intonation</b>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="intonation">Always appropriate. Perfect</label>
                                <input type="radio" class="col-3" name="intonation" value="C2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="intonation">Almost appropriate with minor mistakes</label>
                                <input type="radio" class="col-3" name="intonation" value="C1">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="intonation">Generally appropriate</label>
                                <input type="radio" class="col-3" name="intonation" value="B2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="intonation">Sometimes appropriate + some mistakes</label>
                                <input type="radio" class="col-3" name="intonation" value="B1">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="intonation">Appropriate in rare ocassions</label>
                                <input type="radio" class="col-3" name="intonation" value="A2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="intonation">Always flat</label>
                                <input type="radio" class="col-3" name="intonation" value="A1">
                            </div>
                        </div>
                    </div>
                    <div class="row tab-fce" data-tab="8">
                        <div class="col-12 mb-4">
                            <h3>Pronunciation</h3>
                            <b>Phonological features</b>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="phonological_features">Perfection</label>
                                <input type="radio" class="col-3" name="phonological_features" value="C2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="phonological_features">Clearly articulated</label>
                                <input type="radio" class="col-3" name="phonological_features" value="C1">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="phonological_features">Some mistakes when try to articulate</label>
                                <input type="radio" class="col-3" name="phonological_features" value="B2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="phonological_features">Some control</label>
                                <input type="radio" class="col-3" name="phonological_features" value="B1">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="phonological_features">Limited control</label>
                                <input type="radio" class="col-3" name="phonological_features" value="A2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="phonological_features">Almost no control</label>
                                <input type="radio" class="col-3" name="phonological_features" value="A1">
                            </div>
                        </div>
                    </div>
                    <div class="row tab-fce" data-tab="9">
                        <div class="col-12 mb-4">
                            <h3>Pronunciation</h3>
                            <b>Intelligible</b>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="intelligible">Sounds like native</label>
                                <input type="radio" class="col-3" name="intelligible" value="C2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="intelligible">Almost like native</label>
                                <input type="radio" class="col-3" name="intelligible" value="C1">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="intelligible">Intelligible</label>
                                <input type="radio" class="col-3" name="intelligible" value="B2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="intelligible">Mostly intelligible</label>
                                <input type="radio" class="col-3" name="intelligible" value="B1">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="intelligible">Intelligible with difficulty for the listener</label>
                                <input type="radio" class="col-3" name="intelligible" value="A2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="intelligible">Often unintelligible</label>
                                <input type="radio" class="col-3" name="intelligible" value="A1">
                            </div>
                        </div>
                    </div>
                    <div class="row tab-fce" data-tab="10">
                        <div class="col-12 mb-4">
                            <h3>Interactive Communication</h3>
                            <b>Interaction</b>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="interaction">Widens scope/negotiates an outcome</label>
                                <input type="radio" class="col-3" name="interaction" value="C2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="interaction">Maintains scope/negotiates an outcome</label>
                                <input type="radio" class="col-3" name="interaction" value="C1">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="interaction">Maintains scope/outcome unconcluded</label>
                                <input type="radio" class="col-3" name="interaction" value="B2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="interaction">Having problem with keeping scope</label>
                                <input type="radio" class="col-3" name="interaction" value="B1">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="interaction">Maintain simple exchanges w/ some difficulty</label>
                                <input type="radio" class="col-3" name="interaction" value="A2">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <label class="col-9" for="interaction">Considerable difficulty keeping simple exchanges</label>
                                <input type="radio" class="col-3" name="interaction" value="A1">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <p class="mb-5 text-danger" id="error-fce-form" style="display: none;">*You must select all options</p>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" id="prev-fce" class="btn btn-primary" data-fce="1" style="display: none;">Prev</button>
            <button type="button" id="next-fce" class="btn btn-primary" data-fce="1">Next</button>
            <button type="button" id="save-fce" class="btn btn-success" style="display: none;">Save</button>
        </div>
        
    </div>
</div>
</div>

    

<div class="row">
    <div class="col">
        <h1>Experts </h1>
    </div>
</div>
<div class="row mb-4">
    <div class="col">
            <p>Result: <span id="count-expert"></span></p>
        </div>
        <div class="col text-right">
            
            <div class="form-group d-inline-block" style="max-width: 300px;">
                <input type="text" placeholder="Search By Name" class="form-control" id="search-column-name">
            </div>
            <button type="button" class="btn btn-success" id="search" style="vertical-align: top;">Search</button>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <table id="list-experts"></table>
        </div>
        <div class="col-12 text-center">
            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
        </div>
    </div>
</div>
@endsection

@section('javascript')

<script type="text/javascript" src="{{ asset('/bootstrap-table/bootstrap-table.min.js') }}"></script>

<script type="text/javascript">

$(document).ready(function () {
    $(".lds-ring").hide();

    var _records = 50;
    var _total_records = 0;
    var _count_records = 0;

    var _before_rows = 0;

    var _dataRows = [];
    var _page = 1;

    var search_name = "{{ $name }}";

    $("#search-column-name").val( search_name );

    function ajax_experts( basic , intermediate , advanced , _search_name , page){
            $(".lds-ring").show();
            var params = {
                'rows': _records,
                'page' : page , 
                'name' : _search_name,
                'audio': 1
            };
            $.ajax({
                type:'GET',
                url: '{{ route("experts.fce.list") }}',
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
                    $("#count-expert").html( _count_records );
                    _dataRows = _data.rows;
                    tablebootstrap_filter( _data.rows , basic , intermediate , advanced );
                    if( page == 1 ) $("html, body").animate({ scrollTop: 0 }, "slow");
                    $(".lds-ring").hide();
                }
            });
        }

        function tablebootstrap_filter( data ,a_keys_basic , a_keys_inter , a_keys_advan ){
            
            var a_keys_filter = a_keys_basic.concat( a_keys_inter, a_keys_advan );

            console.log(a_keys_filter);

            var columns = [
                {
                    field: 'id',
                    title: "Actions",
                    valign: 'middle',
                    align: 'left',
                    clickToSelect: false,
                    formatter : function(value,rowData,index) {
                        
                        var actions = '<a class="badge badge-info btn-fce" data-id="'+rowData.id+'" data-index="'+index+'" href="#">FCE</a>\n';
                        
                        actions = actions.replace(/:id/gi , rowData.id);

                        return actions;
                    },
                    width: 100,
                    class: 'frozencell'
                },
                { field: 'fullname', title: "Name", width: 150 , class: 'frozencell'}
            ];
            
            $("#list-experts").bootstrapTable('destroy').bootstrapTable({
                height: undefined,
                columns: columns,
                data: data,
                theadClasses: 'table-dark',
                uniqueId: 'id'
            });
            // ========================================
            
            $("table tbody").on('click' , 'a.btn-fce' , function(ev){
                ev.preventDefault();
                var expertId = $(this).attr("data-id");
                var index = $(this).attr("data-index");
                $("input:radio").prop('checked', false);
                $.ajax({
                    type: 'POST',
                    url: '{{ route("experts.fce") }}',
                    data: {expertId : expertId },
                    headers: {
                        'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(data){
                        console.log(data, "###########");

                        $("#list-audios tbody").html('');
                        var html='';
                        $("#audio_expert_name").html( data.fces.fullname );
                        if(data.audios){
                            for (let index = 0; index < data.audios.length; index++) {
                                html += '<tr data-audio="'+index+'">';
                                // html += '<td>'+data.audios[index].position_name+'</td>';
                                // html += '<td>'+data.audios[index].type+'</td>';
                                html += '<td> <p>'+data.audios[index].position_name+' - '+data.audios[index].type+'</p>';
                                html += '<a href="#" class="mr-1 btn btn-light speed-audio" data-speed="1">x1.00</a><a href="#" class="mr-1 btn btn-light speed-audio" data-speed="1.25">x1.25</a> <a href="#" class="mr-1 btn btn-light speed-audio" data-speed="1.5">x1.5</a> <a href="#" class="mr-1 btn btn-light speed-audio" data-speed="1.75">x1.75</a> <a href="#" class="mr-1 btn btn-light speed-audio" data-speed="2">x2.0</a>'
                                html += '<audio id="audio-player-'+index+'" src="'+data.audios[index].audio+'" controls></audio></td>';
                                html += '</tr>';
                            }
                        }

                        $("#list-audios tbody").html(html);

                        $("input:radio[name=grammatical_forms]").filter('[value='+data.fces.grammatical_forms+']').prop('checked', true);
                        $("input:radio[name=vocabulary]").filter('[value='+data.fces.vocabulary+']').prop('checked', true);
                        $("input:radio[name=stretch_language]").filter('[value='+data.fces.stretch_language+']').prop('checked', true);
                        $("input:radio[name=cohesive_devices]").filter('[value='+data.fces.cohesive_devices+']').prop('checked', true);
                        $("input:radio[name=hesitation]").filter('[value='+data.fces.hesitation+']').prop('checked', true);
                        $("input:radio[name=organizations_ideas]").filter('[value='+data.fces.organizations_ideas+']').prop('checked', true);
                        $("input:radio[name=intonation]").filter('[value='+data.fces.intonation+']').prop('checked', true);
                        $("input:radio[name=phonological_features]").filter('[value='+data.fces.phonological_features+']').prop('checked', true);
                        $("input:radio[name=intelligible]").filter('[value='+data.fces.intelligible+']').prop('checked', true);
                        $("input:radio[name=interaction]").filter('[value='+data.fces.interaction+']').prop('checked', true);

                        // $('#fce_grammar_vocabulary').val(data.fces.fce_grammar_vocabulary)
                        // $('#fce_discourse_management').val(data.fces.fce_discourse_management)
                        // $('#fce_pronunciation').val(data.fces.fce_pronunciation)
                        // $('#fce_interactive_communication').val(data.fces.fce_interactive_communication)
                        // $('#fce_total').val(data.fces.fce_total)
                        // $('#fce_overall').val(data.fces.fce_overall)
                        // $('#fce_comments').val(data.fces.fce_comments)

                        $("#error-fce-form").hide();

                        $('#expert_index').val(index)
                        $('#expert_id').val(expertId)

                        $("#fce_expert_name").html(data.fces.fullname);
                        
                        $(".tab-fce").removeClass("fce-active");
                        $(".tab-fce[data-tab='1']").addClass("fce-active");
                        $("#prev-fce").attr("data-fce", 1).hide();
                        $("#next-fce").attr("data-fce", 1).show();
                        $("#save-fce").hide();
                        $('#fceExpert').modal();
                    }
                });
            })

        }

        $("#prev-fce").on('click' , function(ev){
            var tab = $(this).attr("data-fce");
            $("#next-fce").attr("data-fce", tab);
            $(".tab-fce").removeClass("fce-active");
            var max = $(".tab-fce").length;
            
            $(".tab-fce[data-tab='"+tab+"']").addClass("fce-active");
            if(tab == 1){
                $(this).hide();
            }else{
                
                $("#next-fce").show();
            }
            tab = parseInt(tab) - 1;
            $(this).attr("data-fce", tab);
            $("#save-fce").hide();
        });

        $("#next-fce").on('click', function(ev){
            var tab = $(this).attr("data-fce");
            $("#prev-fce").attr("data-fce", tab);
            tab = parseInt(tab) + 1;
            $(".tab-fce").removeClass("fce-active");
            
            $(".tab-fce[data-tab='"+tab+"']").addClass("fce-active");
            var max = $(".tab-fce").length;
            if(tab == max){
                $(this).hide();
                $("#save-fce").show();
            }else{
                $("#save-fce").hide();
                $("#prev-fce").show();
            }
            $(this).attr("data-fce", tab);

            
        })

        $("#save-fce").on('click', function(){
            var expertId = $("#expert_id").val();
            var index = $("#expert_index").val();
            // var fce_overall = $("#fce_overall").val();
            // var fce_total = $("#fce_total").val();
            $("#error-fce-form").hide();
            var max = $('#form_fce').serializeArray();
            var count = $(".tab-fce").length;
            console.log(count , max);
            if( max.length < (count + 1) ){
                $("#error-fce-form").show();
                return true;
            }
            
            $.ajax({
                type: 'POST',
                url: '{{ route("experts.fce.save") }}',
                data: $('#form_fce').serialize(),
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){

                    $("#list-experts").bootstrapTable('updateRow', {
                        index: index,
                        row: {
                            fce_overall : data.fce_overall == '' ? '-' : '<span title="'+data.fce_total+'" >'+data.fce_overall+'</span>'
                        }
                    });
                    $("#fceExpert").modal('hide');
                }
            });
        })


        $('#search').on('click' , function(){
            
            search_name = $('#search-column-name').val();
            
            window.history.replaceState({
                edwin: "Fulltimeforce"
                }, "Page" , "{{ route('experts.fce') }}" + '?'+ $.param(
                    {   search : true , 
                        name: search_name
                    }
                    )
                );
            _page = 1;
            _count_records = 0;
            location.reload();
            // ajax_experts( a_basic_level, a_intermediate_level , a_advanced_level , search_name , 1);
            
        });

        ajax_experts( [] , [] , [] , search_name , 1);

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
                            'audio': 1
                    };
                    $(".lds-ring").show();
                    $.ajax({
                        type:'GET',
                        url: '{{ route("experts.fce.list") }}',
                        data: $.param(data),
                        headers: {
                            'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success:function(data){

                            let _data = JSON.parse(data);
                            _before_rows = _data.total;
                            $("#list-experts").bootstrapTable('append', _data.rows );
                            
                            _count_records = _count_records + _data.rows.length;
                            $("#count-expert").html( _count_records );
                            $(".lds-ring").hide();
                        }
                    });
                }
            }
        });

});


</script>
@endsection