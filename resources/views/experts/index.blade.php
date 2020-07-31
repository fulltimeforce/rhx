@extends('layouts.app' , ['controller' => 'experts'])

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap-table/bootstrap-table.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.css') }}"/>
 
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
    <div class="row">
        <div class="col">
            <h1>Experts ({{ $experts }})</h1>
        </div>
        <div class="col text-right">
            
            <a class="btn btn-info" id="url-generate" href="#">Generate URL</a>
        </div>
    </div>
    <div class="alert alert-warning alert-dismissible mt-3" role="alert" style="display: none;">
        <b>Copy successful!!!!</b>
        <p id="showURL"></p>
    </div>
    <!--  
        /*========================================== MODALS ==========================================*/
    -->

    <div id="audio-bublle" class="buble-audio" style="display: none;">
        <div class="section-audio">
            <button type="button" class="close-audio" >
                <span aria-hidden="true">&times;</span>
            </button>
            <!-- <table id="list-audios" class="table table-dark">
                <thead>
                    <tr>
                        <th><span id="audio_expert_name"></span></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table> -->
        </div>
    </div>

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
    <!--  
        /*========================================== POSITONS BY EXPERT ==========================================*/
    -->
    <div class="modal fade" id="positionsExpert" tabindex="-1" role="dialog" aria-labelledby="positionsExpertLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="positionsExpertLabel">ASSIGNED POSITIONS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <form action="" id="form-positions">
                <input type="hidden" name="expertId-p" id="expertId-p" value="">
                <ul class="list-group" id="list-positions">
                    <li class="list-group-item d-flex justify-content-between align-items-center">Cras justo odio <div ><input type="checkbox"></div></li>
                </ul>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="save-positions" class="btn btn-primary">Save</button>
            </div>
            
        </div>
    </div>
    </div>
    <!--  
        /*========================================== INTERVIEWS BY EXPERT ==========================================*/
    -->
    <div class="modal" id="interviews-expert" tabindex="-1" role="dialog" aria-labelledby="interviews-expertLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="interviews-expertLabel"><span id="interview_expert_name">{expert Name}</span> - INTERVIEWS</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row mb-4">
                <div class="col" id="list-interviews">
                    
                </div>
            </div>
            
        </div>
        
        </div>
    </div>
    </div>
    <!--  
        /*========================================== FORM ==========================================*/
    -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <br>
    
    <form action="{{ route('experts.filter') }}" class="row" method="POST">
        @csrf
        <div class="form-group col">
            <label for="basic_level">Basic</label>
            <select multiple id="basic_level" name="basic_level[]" class="form-control search-level basic" size="1">
                @foreach( $basic as $tid => $tlabel)
                    <option value="{{ $tid }}" selected > {{ $tlabel }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col">
            <label for="intermediate_level">Intermediate</label>
            <select multiple id="intermediate_level" name="intermediate_level[]" class="form-control search-level intermediate" size="1">
                @foreach( $intermediate as $tid => $tlabel)
                    <option value="{{ $tid }}" selected > {{ $tlabel }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col">
            <label for="advanced_level">Advanced</label>
            <select multiple id="advanced_level" name="advanced_level[]" class="form-control search-level advanced" size="1">
                @foreach( $advanced as $tid => $tlabel)
                    <option value="{{ $tid }}" selected > {{ $tlabel }}</option>
                @endforeach
            </select>
        </div>

    </form>
    <div class="row mb-4">
        <div class="col">
            <p>Result: <span id="count-expert"></span></p>
        </div>
        <div class="col text-right">
            <input type="hidden" name="selection" id="selection" value="{{ $selection }}">
            <btn class="btn {{ $selection == 1 ? 'btn-secondary' : ( $selection == 2 ? 'btn-danger' : ( $selection == 3 ? 'btn-warning' : 'btn-success' ) ) }}" id="change-selected">Selected</btn>
            <input type="checkbox" name="audio" id="audio">
            <label for="audio">With audio</label>
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
    
@endsection

@section('javascript')

<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script type="text/javascript" src="{{ asset('/bootstrap-table/bootstrap-table.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.js') }}"></script>

<script type="text/javascript">
    
    $(document).ready(function () {
        $(".lds-ring").hide();
        $('#interview_date').datetimepicker({
            format: "{{ config('app.date_format_javascript') }}",
            locale: "en"
        });

        var _records = 50;
        var _total_records = 0;
        var _count_records = 0;

        var _before_rows = 0;

        var _dataRows = [];

        var search_name = "{{ $name }}";

        var audios_filter = [];
        var audios_evaluate = [];
        var audios = [];
        var isSearch = false;

        $("#search-column-name").val( search_name );

        function ajax_experts( basic , intermediate , advanced , _search_name , page){
            $(".lds-ring").show();
            var params = {
                'rows': _records,
                'page' : page , 
                'basic': basic.join(',') , 
                'intermediate': intermediate.join(',') ,
                'advanced' : advanced.join(','),
                'name' : _search_name,
                'selection' : $("#selection").val(),
                'audio': $("#audio").is(":checked")
            };
            $.ajax({
                type:'GET',
                url: '{{ route("expert.listtbootstrap") }}',
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
                        var actions = '<a class="badge badge-primary" href=" '+ "{{ route('experts.edit', ':id' ) }}"+ ' ">Edit</a>\n';
                        actions += rowData.file_path == '' ? '' : '<a class="badge badge-dark text-light" download href="'+rowData.file_path+'" target="_blank">Download</a>\n';
                        // actions += '<a class="badge badge-info btn-position" data-id="'+rowData.id+'" href="#">Positions</a>\n';
                        actions += '<a class="badge badge-secondary btn-interviews" href="#" data-id="'+rowData.id+'" data-name="'+rowData.fullname+'">Interviews</a>\n';
                        actions += '<a class="badge badge-danger btn-delete-expert" data-id="'+rowData.id+'" href="#">Delete</a>';
                        // if( rowData.resume == null){
                        //     actions += '<span class="badge badge-secondary" >Resume</span>\n';
                        // }else{
                        //     actions += '<span class="badge badge-success" >Resume</span>\n';
                        // }
                        
                        actions += '<a class="badge badge-info btn-fce" data-id="'+rowData.id+'" data-index="'+index+'" href="#">FCE</a>\n';
                        
                        var audios__count = 0; 
                         
                        if( rowData.logs.length > 0 ){
                            for (let i = 0; i < rowData.logs.length; i++) {
                                if( rowData.logs[i].filter_audio != null ){
                                    audios__count++;
                                }
                                if( rowData.logs[i].evaluate_audio != null ){
                                    audios__count++; 
                                }
                            }
                        }
                        if( audios__count > 0){
                            // actions += '<a class="badge badge-primary btn-list-audio" data-name="'+rowData.fullname+'" data-id="'+rowData.id+'" href="#">Audio</a>';
                        }

                        actions += '<a href="#" class="badge btn-selection '+ ( rowData.selection == 1 ? 'badge-secondary': ( rowData.selection == 2 ? 'badge-danger' : ( rowData.selection == 3 ? 'badge-warning': 'badge-success') ) )+'" data-id="'+rowData.id+'" data-selection="'+rowData.selection+'" >Selected</a>';

                        actions = actions.replace(/:id/gi , rowData.id);

                        return actions;
                    },
                    width: 100,
                    class: 'frozencell'
                },
                { field: 'fullname', title: "Name", width: 150 , class: 'frozencell'}
            ];
            var columns_temp = [];
            var columns_info = [
                { field: 'email_address', title: "Email" },
                { field: 'age', title: "Age" },
                { field: 'phone', title: "Phone" },
                { field: 'availability', title: "Availability" },
                { field: 'salary', title: "Salary" ,width: 110 , formatter: function(value,rowData,index){ return value== null ? '-' : (rowData.type_money == 'sol' ? 'S/' : '$') + ' ' +value;} },
                { field: 'fce_overall', title: "Overall text", formatter : function(value,rowData,index) { return rowData.fce_overall == '' ? '-' : '<span title="'+rowData.fce_total+'" >'+rowData.fce_overall+'</span>' } },
                { field: 'linkedin', title: "Linkedin" },
                { field: 'github', title: "Github" },
                { field: 'experience', title: "Experience" },
            ];

            @foreach($technologies as $categoryid => $category)
                @foreach($category[1] as $techid => $techlabel)
                    
                    if ( a_keys_filter.filter(f => f=='{{$techid}}').length > 0 ){
                        columns.push( { field: '{{$techid}}', title: "{{$techlabel}}", class: 'stickout' } );
                    }else{
                        columns_temp.push( { field: '{{$techid}}', title: "{{$techlabel}}" , width: 110, align: 'center' } );
                    }
                @endforeach
            @endforeach

            $("#list-experts").bootstrapTable('destroy').bootstrapTable({
                height: undefined,
                columns: columns.concat(columns_info, columns_temp),
                data: data,
                fixedColumns: true,
                fixedNumber: 2,
                theadClasses: 'table-dark',
                uniqueId: 'id'
            });

            // =================== DELETE

            $("table tbody").on('click', 'a.btn-delete-expert' , function(ev){
                ev.preventDefault();
                var id = $(this).data("id");
                $.ajax({
                    type:'POST',
                    url: '{{ route("experts.deleteExpert") }}',
                    data: {expertId : id},
                    headers: {
                        'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(data){

                        $("#list-experts").bootstrapTable('removeByUniqueId',id);
                    }
                });

            });
            // =============== POSITIONS

            $("table tbody").on('click', 'a.btn-position' , function(ev){
                ev.preventDefault();
                var id = $(this).data("id");
                $('#expertId-p').val('');
                $.ajax({
                    type:'POST',
                    url: '{{ route("positions.enabled") }}',
                    data: {expertId : id},
                    headers: {
                        'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(data){

                        $('#expertId-p').val(id);
                        $("#list-positions").html('');
                        var html = '';
                        for (let index = 0; index < data.length; index++) {
                            html += '<li class="list-group-item d-flex justify-content-between align-items-center">:name <div ><input type="checkbox" name="positions[]" value="'+data[index].id+'" '+ (data[index].active == 1? 'checked' : '') +' ></div></li>';
                            html = html.replace(':name' , data[index].name);
                        }
                        $("#list-positions").html(html);
                        $("#positionsExpert").modal();
                    }
                });
                
            });

            // =============== LIST INTERVIEWS

            $("table tbody").on("click" , "a.btn-interviews" , function(ev){
                ev.preventDefault();
                var expertId = $(this).data("id");
                var expertName = $(this).data("name");
                
                $("#interview_expert_name").html( expertName );
                $("#interview_expert_id").val(expertId);
                $.ajax({
                    type: 'POST',
                    url: '{{ route("interviews.recruiterlog") }}',
                    data: {expertId : expertId },
                    headers: {
                        'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(interviews){
                        console.log(interviews);
                        var html = '';
                        for (let index = 0; index < interviews.length; index++) {
                            html += card_interviews( interviews[index] )
                        }
                        $("#list-interviews").html(html);
                        $("#interviews-expert").modal();
                    }
                });
                
            });

            // ========================================
            $("table tbody").on('click' , 'a.btn-selection' , function(ev){
                ev.preventDefault();
                var expertId = $(this).attr("data-id");
                var expertSelection = $(this).attr("data-selection");
                console.log(expertSelection , "expertSelection")
                var $this = $(this)
                var select = 1;
                switch( parseInt(expertSelection) ){
                    case 1: select = 2;break;
                    case 2: select = 3;break;
                    case 3: select = 4;break;
                    case 4: select = 1;break;
                }

                $.ajax({
                    type: 'POST',
                    url: '{{ route("experts.selection") }}',
                    data: {expertId : expertId , selection: select },
                    headers: {
                        'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(data){
                        $this.removeClass("badge-secondary")
                            .removeClass("badge-danger")
                            .removeClass("badge-warning")
                            .removeClass("badge-success");
                        switch( select ){
                            case 1: $this.addClass("badge-secondary");break;
                            case 2: $this.addClass("badge-danger");break;
                            case 3: $this.addClass("badge-warning");break;
                            case 4: $this.addClass("badge-success");break;
                        }
                        $this.attr("data-selection" , select )
                    }
                });
            })
            
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

            $('.total-fce').bind('keyup mouseup'  , function(){
                console.log("ddd");
                total = 0
                $.each( $('.total-fce') , function(i, value){
                    total += parseFloat($(value).val())
                });
                total = total.toFixed(2)
                $("#fce_total").val(total)

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

        $("#change-selected").on('click' , function(ev){
            switch( parseInt( $("#selection").val() ) ){
                case 1: $("#selection").val(2);break;
                case 2: $("#selection").val(3);break;
                case 3: $("#selection").val(4);break;
                case 4: $("#selection").val(1);break;
            }
            $(this).removeClass("btn-secondary")
                            .removeClass("btn-danger")
                            .removeClass("btn-warning")
                            .removeClass("btn-success");
            switch( parseInt( $("#selection").val() ) ){
                case 1: $(this).addClass("btn-secondary");break;
                case 2: $(this).addClass("btn-danger");break;
                case 3: $(this).addClass("btn-warning");break;
                case 4: $(this).addClass("btn-success");break;
            }

            search_name = $('#search-column-name').val();
            a_basic_level = $(".search-level.basic").val();
            a_intermediate_level = $(".search-level.intermediate").val();
            a_advanced_level = $(".search-level.advanced").val(); 
            window.history.replaceState({
                edwin: "Fulltimeforce"
                }, "Page" , "{{ route('experts.home') }}" + '?'+ $.param(
                    {   search : true , 
                        basic: a_basic_level.join(","),
                        intermediate: a_intermediate_level.join(","),
                        advanced: a_advanced_level.join(","),
                        audio: $("#audio").is(":checked"),
                        selection : $("#selection").val(),
                        name: search_name
                    }
                    )
                );
            _page = 1;
            _count_records = 0;
            location.reload();
        })

        $('table').on('click', '.btn-list-audio', function(ev){
            ev.preventDefault();
            var expert_id = $(this).data("id");
            var expert_name = $(this).data("name");
            $.ajax({
                type: 'POST',
                url: '{{ route("expert.audioslog") }}',
                data: {id : expert_id },
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(list_audios){

                    $("#list-audios tbody").html('');
                    var html='';
                    $("#audio_expert_name").html(expert_name);
                    for (let index = 0; index < list_audios.length; index++) {
                        html += '<tr data-audio="'+index+'">';
                        // html += '<td>'+list_audios[index].position_name+'</td>';
                        // html += '<td>'+list_audios[index].type+'</td>';
                        html += '<td> <p>'+list_audios[index].position_name+' - '+list_audios[index].type+'</p>';
                        html += '<a href="#" class="mr-1 btn btn-light speed-audio" data-speed="1">x1.00</a><a href="#" class="mr-1 btn btn-light speed-audio" data-speed="1.25">x1.25</a> <a href="#" class="mr-1 btn btn-light speed-audio" data-speed="1.5">x1.5</a> <a href="#" class="mr-1 btn btn-light speed-audio" data-speed="1.75">x1.75</a> <a href="#" class="mr-1 btn btn-light speed-audio" data-speed="2">x2.0</a>'
                        html += '<audio id="audio-player-'+index+'" src="'+list_audios[index].audio+'" controls></audio></td>';
                        html += '</tr>';
                    }

                    $("#list-audios tbody").html(html);
                    // $("#audiosModal").modal({
                    //     backdrop: 'static'
                    // });
                    $("#audio-bublle").show("slow")
                }
            });
            
        })
        // $('#audiosModal').on('hidden.bs.modal', function (e) {
        //     $("#list-audios tbody").html('');
        // })

        $(".section-audio .close-audio").on('click' , function(){
            $("#audio-bublle").hide("slow" , function(){
                $("#list-audios tbody").html('');
            })
        })

        $("body").on('click' , 'a.speed-audio' , function(ev){
            ev.preventDefault();
            var speed = $(this).data("speed");
            var index = $(this).parent().parent().data("audio");
            console.log( parseFloat( speed ) , speed )
            document.getElementById("audio-player-"+index).playbackRate = parseFloat(speed);
        })

        function card_interviews( _interview ){
            var html = '';
            html += '<div class="card mb-4">';
            html += '    <div class="card-header">';
            html += '        <div class="row">';
            html += '            <div class="col">';
            html += '                <h4 class="text-uppercase txt-type"><span>'+( (_interview.position == null)? "GENERAL" : _interview.position.name )+'</span> - <span>'+_interview.date+'</span></h4>';
            html += '            </div>';
            html += '        </div>';
            html += '    </div>';
            html += '    <div class="card-body">';
            for (let index = 0; index < _interview.notes.length; index++) {
                
                html += '<div class="card mb-3">';
                html += '    <div class="card-header">';
                html += '        <div class="row">';
                var _class = '';
                switch( _interview.notes[index].type ){
                    case 'commercial': 
                    case 'technique': 
                    case 'psychology': 
                        _class =  _interview.notes[index].type_value == null ? 'secondary' : (_interview.notes[index].type_value == 'approved' ? 'success' : ( _interview.notes[index].type_value == 'not approved' ? 'danger' : 'warning' ) ) 
                    break;
                    case 'cv': 
                    case 'experience': 
                    case 'communication': 
                    case 'english': 
                        _class =  _interview.notes[index].type_value == null ? 'secondary' : (_interview.notes[index].type_value == 'approved' ? 'success' : 'danger') 
                    break;
                }
                html += '            <div class="col">';
                html += '                <span class="text-uppercase badge badge-'+_class+'">'+ _interview.notes[index].type +'</span>';
                html += '            </div>';
                html += '        </div>';
                html += '    </div>';
                
                html += '    <div class="card-body">';
                html += '        <p class="card-text txt-description ">'+ _interview.notes[index].note +'</p>';
                html += '    </div>';
                html += '</div>';
            }
                    
            html += '    </div>';
            html += '</div>';
            return html;
        }

        var loading = false;
        var scroll_previus = 0;
        var _page = 1;
        
        // =============================== LAZY LOADING SCROLL ================================= 

        $("#list-experts").on('scroll-body.bs.table' , function(e, arg1){
            console.log(e);
            
            var _height = $(e.target).height();
            var _positionScroll = $("#list-experts").bootstrapTable('getScrollPosition');
            var _diff = 491;

        });

        $(window).on('scroll', function (e){
            
            if($(window).scrollTop() + $(window).height() == $(document).height()) {
                console.log( _count_records , _total_records, _before_rows , _records , "##################" );
                if( _count_records < _total_records && _before_rows == _records ){
                    _page++;
                    let a_basic_level = $(".search-level.basic").val();
                    let a_intermediate_level = $(".search-level.intermediate").val();
                    let a_advanced_level = $(".search-level.advanced").val();
                    var _text = $('#search-column-name').val();
                    var data = {
                            'offset': _records,
                            'rows': _records,
                            'page' : _page , 
                            'basic': _text == '' ? a_basic_level.join(',') : '', 
                            'intermediate': _text == '' ? a_intermediate_level.join(',') : '',
                            'advanced' : _text == '' ? a_advanced_level.join(',') : '',
                            'name' : _text,
                            'selection' : $("#selection").val(),
                            'audio': $("#audio").is(":checked")
                    };
                    $(".lds-ring").show();
                    $.ajax({
                        type:'GET',
                        url: '{{ route("expert.listtbootstrap") }}',
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

        // ================================================================================
        $("#audio").prop( 'checked' , false )
        @if( $audio )
            
            $("#audio").prop( 'checked' , true )
        @endif

        @if( $search )
            var basic = [];
            @foreach($basic as $tid => $tlabel)
                basic.push( "{{$tid}}" );
            @endforeach
            var intermediate = [];
            @foreach($intermediate as $tid => $tlabel)
                intermediate.push( "{{$tid}}" );
            @endforeach
            var advanced = [];
            @foreach($advanced as $tid => $tlabel)
                advanced.push( "{{$tid}}" );
            @endforeach
            
            ajax_experts( basic , intermediate , advanced , search_name , 1);
        @else

            ajax_experts( [] , [] , [] , '' , 1);
        @endif

        $(".search-level").select2({
            ajax: {
                url: "{{ route('expert.technologies') }}",
                dataType: 'json',
                data: function (params) {
                    var query = {
                        search: params.term,
                    }
                    // Query parameters will be ?search=[term]&type=public
                    return query;
                },
                processResults: function (data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: data
                    };
                }

            }
        });
        
        
        $('#url-generate').on('click', function (ev) {

            ev.preventDefault();
            $.ajax({
                type:'GET',
                url: "{{ route('applicant.register.signed') }}" ,
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){
                    $('#showURL').html(data);

                    var el = document.createElement("textarea");
                    el.value = data;
                    el.style.position = 'absolute';                 
                    el.style.left = '-9999px';
                    el.style.top = '0';
                    el.setSelectionRange(0, 99999);
                    el.setAttribute('readonly', ''); 
                    document.body.appendChild(el);
                    
                    el.focus();
                    el.select();

                    var success = document.execCommand('copy')
                    if(success){
                        $(".alert").slideDown(200, function() {
                            
                        });
                    }
                    setTimeout(() => {
                        $(".alert").slideUp(500, function() {
                            document.body.removeChild(el);
                        });
                    }, 4000);  
                }
            });
        });

        var a_basic_level = [];
        var a_intermediate_level = [];
        var a_advanced_level = [];

        var is_jqgrid = false;


        $('#search').on('click' , function(){
            
            search_name = $('#search-column-name').val();
            a_basic_level = $(".search-level.basic").val();
            a_intermediate_level = $(".search-level.intermediate").val();
            a_advanced_level = $(".search-level.advanced").val(); 
            window.history.replaceState({
                edwin: "Fulltimeforce"
                }, "Page" , "{{ route('experts.home') }}" + '?'+ $.param(
                    {   search : true , 
                        basic: a_basic_level.join(","),
                        intermediate: a_intermediate_level.join(","),
                        advanced: a_advanced_level.join(","),
                        audio: $("#audio").is(":checked"),
                        selection : $("#selection").val(),
                        name: search_name
                    }
                    )
                );
            _page = 1;
            _count_records = 0;
            location.reload();
            // ajax_experts( a_basic_level, a_intermediate_level , a_advanced_level , search_name , 1);
            
        });

        $("#search-column-name").keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                search_name = $('#search-column-name').val();
                a_basic_level = $(".search-level.basic").val();
                a_intermediate_level = $(".search-level.intermediate").val();
                a_advanced_level = $(".search-level.advanced").val(); 
                window.history.replaceState({
                    edwin: "Fulltimeforce"
                    }, "Page" , "{{ route('experts.home') }}" + '?'+ $.param(
                        {   search : true , 
                            basic: a_basic_level.join(","),
                            intermediate: a_intermediate_level.join(","),
                            advanced: a_advanced_level.join(","),
                            audio: $("#audio").is(":checked"),
                            selection : $("#selection").val(),
                            name: search_name
                        }
                        )
                    );
                _page = 1;
                _count_records = 0;
                location.reload();
            }
        })

        function delay(callback, ms) {
            var timer = 0;
            return function() {
                var context = this, args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function () {
                callback.apply(context, args);
                }, ms || 0);
            };
        }

        // ===================== SHOW POSITIONS =====================

        $("#save-positions").on('click' , function(ev){
            
            var positionsIDs = [];
            $('#form-positions input[type="checkbox"]:checked').each( function(){
                positionsIDs.push($(this).val());
            } );
            var id = $('#expertId-p').val();

            $.ajax({
                type:'POST',
                url: '{{ route("positions.experts.attach") }}',
                data: {expertId : id, positions : positionsIDs},
                headers: {
                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){

                    $("#positionsExpert").modal('hide');
                    
                }
            });

        });

        var template_card_interview = $('#list-interviews').html();
        $('#list-interviews').html('');

        $('table').on('click' , '.copy-link' , function(ev){
            var data = $(this).data("info");
            ev.preventDefault();
            var el = document.createElement("textarea");
            el.value = data;
            el.style.top = '0';
            el.setSelectionRange(0, 99999);
            el.setAttribute('readonly', ''); 
            this.appendChild(el);
            el.focus();
            el.select();
            var success = document.execCommand('copy');
            console.log(success, "dsdsdsdsdsdsdsdsdsds");
            this.removeChild(el);

        })

        $('#interview_date').datetimepicker({
            format: "{{ config('app.date_format_javascript') }}",
            locale: "en",
            defaultDate : new Date()
        });

        $("#clear-interview").on('click' , function(){

            $("#interview_type").val('');
            $("#interview_date").val( moment().format("{{ config('app.date_format_javascript') }}") );
            $("#about").val('');
            $("#interview_description").val('');
            $("#interview_result").prop('checked', false);
            
            $("#interview_id").val( '' );

            $('#form-btn-edit').hide();
            $('#form-btn-save').show();

        });

    });
 
</script>   
@endsection