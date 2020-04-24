@extends('layouts.app' , ['controller' => 'position'])

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<style>
#form-skills .skill-delete,
#form-employments .employment-delete,
#form-educations .education-delete,
#form-project .project-delete,
.list-videos .add-video,
.list-images .add-image,
.form-videos .remove-video,
.form-images .remove-image{
    display: none;
}
.project-delete{
    position: absolute;
    top: 4px;
    right: 19px;
}
</style>

@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="d-inline">Expert - {{ $expert->fullname }}</h2>
        <a href="{{ route('home')}}/expert/template/{{ $expert->slug }}" target="_blank" class="btn btn-info">Preview</a>
    </div>
</div>

<form action="{{ route('expert.saveportfolio') }}" method="POST" enctype="multipart/form-data">
@csrf
<input type="hidden" name="id" value="{{ $expert->id }}"> 
<div class="row">
    <div class="col-12 mb-4">
        <h5>PROFILE</h5>
        <div class="form-row">
            <div class="col-6 mb-2">
                <label for="">Full Name</label>
                <input type="text" class="form-control" value="{{ $expert->fullname }}" name="fullname" required>
            </div>
            <div class="col-6 mb-2">
                <label for="">Photo</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input upload-image" id="customFile" name="photo_url">
                    <label class="custom-file-label" for="customFile">{{ ($expert->photo != '' && !is_null($expert->photo))? $expert->photo : 'Choose file' }}</label>
                    <input type="hidden" name="photo" value="{{ $expert->photo }}">
                </div>
            </div>
            <div class="col-2 mb-2">
                <label for="age">Age</label>
                <input type="number" value="{{ $expert->age }}" class="form-control" name="age">
            </div>
            <div class="col-10 mb-2">
                <label for="work">Position</label>
                <input type="text" class="form-control" value="{{ $expert->work }}" name="work">
                
            </div>
            <div class="col-6 mb-2">
                <label for="email">Email</label>
                <input type="text" class="form-control" value="{{ $expert->email }}" name="email" required>
            </div>
            <div class="col-6">
                <label for="address">Address</label>
                <input type="text" class="form-control" value="{{ $expert->address }}" name="address">
            </div>
            <div class="col-6">
                <label for="availability">Availability</label>
                <input type="text" class="form-control" value="{{ $expert->availability }}" name="availability">
            </div>
            <div class="col-12">
                <label for="github">Github</label>
                <input type="text" class="form-control" value="{{ $expert->github }}" name="github">
            </div>
            <div class="col-12">
                <label for="linkedin">Linkedin</label>
                <input type="text" class="form-control" value="{{ $expert->linkedin }}" name="linkedin">
            </div>

        </div>
    </div>
    
    <div class="col-12 mb-4">
        <h5>HI</h5>
        <textarea name="description" id="description" rows="5" class="form-control">{{ $expert->description }}</textarea>
    </div>
    <div class="col-12 mb-4">
        <h5>PROJECTS</h5>
        <section class="mb-4" id="list-projects">
            @if( !is_null($expert->projects) && $expert->projects != '' )
            @foreach( unserialize($expert->projects) as $pkey => $project )
            <div class="mb-4 card">
                <section class="form-row card-body">
                <a href="#" class="text-danger project-delete"><i class="fas fa-trash"></i></a>
                <div class="col-6">
                    <label for="project_title">Title</label>
                    <input type="text" class="form-control txt-project-title" name="project_title[]" value="{{ $project['title'] }}">
                    <input type="hidden" class="project-index" name="project_index[]" value="{{ $project['index'] }}">
                </div>
                <div class="col-6">
                    <label for="">URL</label>
                    <input type="text" name="project_url[]" class="form-control project-url" value="{{ $project['url'] }}">
                </div>

                <div class="col-12">
                <label for="">Video</label>
                    <section class="list-videos">
                    @foreach( $project['videos'] as $vkey => $video )
                        @if( !is_null( $video ) )
                        <div class="row section-videos mb-3" data-video="{{ $vkey }}">
                            <div class="col-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fab fa-youtube"></i></span>
                                    </div>
                                    <input type="text" class="form-control project-video" name="project_video_{{ $project['index'] }}[]" value="{{ $video }}">
                                </div>
                            </div>
                            <div class="col-2">
                                <a href="#" class="btn btn-success add-video" data-time="{{ $project['index'] }}" data-index="{{ $vkey }}"><i class="fas fa-plus"></i></a>
                                <a href="#" class="btn btn-danger remove-video" ><i class="fas fa-trash"></i></a>
                            </div>
                        </div>
                        @endif        
                    @endforeach
                    </section>
                    <section class="form-videos">
                        <div class="row section-videos mb-3" data-video="{{ count( $project['videos'] ) }}">
                            <div class="col-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fab fa-youtube"></i></span>
                                    </div>
                                    <input type="text" class="form-control project-video" name="project_video_{{ $project['index'] }}[]">
                                </div>
                            </div>
                            <div class="col-2">
                                <a href="#" class="btn btn-success add-video" data-time="{{ $project['index'] }}" data-index="{{ count( $project['videos'] ) }}"><i class="fas fa-plus"></i></a>
                                <a href="#" class="btn btn-danger remove-video" ><i class="fas fa-trash"></i></a>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="col-12">
                    <label for="">Image</label>
                    <section class="list-images">
                    @foreach( $project['images'] as $ikey => $image )
                        @if( !is_null($image) )
                        <div class="row section-images mb-3" data-image="{{ $ikey }}">
                            <div class="col-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-images"></i></span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg" class="form-control upload-image project-image" name="project_image_{{ $project['index'] }}[]" id="project_image_file_{{ $ikey }}_{{ $project['index'] }}">
                                    <label class="custom-file-label project-image-label" for="project_image_file_{{ $ikey }}_{{ $project['index'] }}">{{ $image }}</label>
                                    <input type="hidden" name="project_image_name_{{ $project['index'] }}[]" class="upload-image-name" value="{{ $image }}">
                                </div>
                            </div>
                            </div>
                            <div class="col-2">
                                <a href="#" class="btn btn-success add-image" data-time="{{ $project['index'] }}" data-index="{{ $ikey }}"><i class="fas fa-plus"></i></a>
                                <a href="#" class="btn btn-danger remove-image" ><i class="fas fa-trash"></i></a>
                            </div>
                        </div>
                        @endif
                    @endforeach
                    </section>
                    <section class="form-images">
                        <div class="row section-images mb-3" data-image="{{ count( $project['images'] ) }}">
                            <div class="col-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-images"></i></span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg" class="form-control upload-image project-image" name="project_image_{{ $project['index'] }}[]" id="project_image_file_{{ count( $project['images'] ) }}_{{ $project['index'] }}">
                                    <label class="custom-file-label project-image-label" for="project_image_file_{{ count( $project['images'] ) }}_{{ $project['index'] }}">Choose file</label>
                                    <input type="hidden" name="project_image_name_{{ $project['index'] }}[]" class="upload-image-name">
                                </div>
                            </div>
                            </div>
                            <div class="col-2">
                                <a href="#" class="btn btn-success add-image" data-time="{{ $project['index'] }}" data-index="{{ count( $project['images'] ) }}"><i class="fas fa-plus"></i></a>
                                <a href="#" class="btn btn-danger remove-image" ><i class="fas fa-trash"></i></a>
                            </div>
                        </div>
                    </section>

                </div>

                <div class="col-12">
                    <label for="description">Description</label>
                    <textarea name="project_description[]" rows="5" class="form-control txt-project-description">{{ $project['description'] }}</textarea>
                </div>
                <div class="col-4">
                    <label for="project_categories">Category</label>
                    <select name="project_categories_{{ $project['index'] }}[]" class="form-control tag-categories sel-project-categories" multiple size="1">
                        @if( !is_null($project['categories']) && $project['categories'] != '' )
                        @foreach( $project['categories'] as $category )
                            <option value="{{ $category }}" selected>{{ $category }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-8">
                    <label for="project_stacks">Stacks</label>
                    <select name="project_stacks_{{ $project['index'] }}[]" class="form-control tag-select sel-project-stacks" multiple size="1">
                        @if( !is_null($project['stacks']) && $project['stacks'] != '' )
                        @foreach( $project['stacks'] as $stack )
                            <option value="{{ $stack }}" selected>{{ $stack }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                
                </section>
            </div>
            @endforeach
            @endif
        </section>
        <section id="form-project">
        <div class="mb-4 section-form-project card" data-project="{{ strtotime( date('Y-m-d H:i:s') ) }}">
            <section class="form-row card-body">
            <a href="#" class="text-danger project-delete"><i class="fas fa-trash"></i></a>
            <div class="col-6">
                <label for="project_title">Title</label>
                <input type="text" class="form-control project-title" name="project_title[]" >
                <input type="hidden" class="project-index" name="project_index[]" value="{{ strtotime( date('Y-m-d H:i:s') ) }}">
            </div>
            <div class="col-6">
                <label for="">URL</label>
                <input type="text" name="project_url[]" class="form-control project-url">
            </div>
            <div class="col-12">
                <label for="">Video</label>
                <section class="list-videos">
                </section>
                <section class="form-videos">
                    <div class="row section-videos mb-3" data-video="0">
                        <div class="col-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fab fa-youtube"></i></span>
                                </div>
                                <input type="text" class="form-control project-video" name="project_video_{{ strtotime( date('Y-m-d H:i:s') ) }}[]">
                            </div>
                        </div>
                        <div class="col-2">
                            <a href="#" class="btn btn-success add-video" data-time="{{ strtotime( date('Y-m-d H:i:s') ) }}" data-index="0"><i class="fas fa-plus"></i></a>
                            <a href="#" class="btn btn-danger remove-video" ><i class="fas fa-trash"></i></a>
                        </div>
                    </div>
                </section>
            </div>
            <div class="col-12">
                <label for="">Image</label>
                <section class="list-images">
                </section>
                <section class="form-images">
                    <div class="row section-images mb-3" data-image="0">
                        <div class="col-10">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-images"></i></span>
                            </div>
                            <div class="custom-file">
                                <input type="file" accept="image/png, image/jpeg, image/jpg" class="form-control upload-image project-image" name="project_image_{{ strtotime( date('Y-m-d H:i:s') ) }}[]" id="project_image_file_0_{{ strtotime( date('Y-m-d H:i:s') ) }}">
                                <label class="custom-file-label project-image-label" for="project_image_file_0_{{ strtotime( date('Y-m-d H:i:s') ) }}">Choose file</label>
                                <input type="hidden" name="project_image_name_{{ strtotime( date('Y-m-d H:i:s') ) }}[]" class="upload-image-name">
                            </div>
                        </div>
                        </div>
                        <div class="col-2">
                            <a href="#" class="btn btn-success add-image" data-time="{{ strtotime( date('Y-m-d H:i:s') ) }}" data-index="0"><i class="fas fa-plus"></i></a>
                            <a href="#" class="btn btn-danger remove-image" ><i class="fas fa-trash"></i></a>
                        </div>
                    </div>
                </section>

            </div>
            <div class="col-12">
                <label for="description">Description</label>
                <textarea name="project_description[]" rows="5" class="form-control project-description" ></textarea>
            </div>
            <div class="col-4">
                <label for="project_categories">Category</label>
                <select name="project_categories_{{ strtotime( date('Y-m-d H:i:s') ) }}[]"  class="form-control tag-categories project-categories" multiple size="1">
                </select>
            </div>
            <div class="col-8">
                <label for="project_stacks">Stacks</label>
                <select name="project_stacks_{{ strtotime( date('Y-m-d H:i:s') ) }}[]"  class="form-control tag-select project-stacks" multiple size="1"></select>
            </div>
            
            </section>
        </div>
        </section>
        <div class="row">
            <div class="col">
                <button type="button" class="btn btn-primary" id="add-project">Add</button>
            </div>
        </div>
    </div>
    <div class="col-12 mb-4">

        <div class="form-row mb-4" >

            <div class="col-12 mb-5">
                <h5>Education</h5>
                <section class="form-row mb-3" id="list-educations">

                @if( !is_null($expert->education) && $expert->education != '' )
                @foreach( unserialize($expert->education) as $pkey => $education )
                
                <div class="mb-3 card w-100" >
                    <section class="form-row card-body">
                    <div class="col-4">
                        <label for="">University</label>
                        <input type="text" class="form-control education-university" name="education_university[]" value="{{ $education['university'] }}">
                    </div>
                    <div class="col-2">
                        <label for="">Period</label>
                        <input type="text" class="form-control education-period" name="education_period[]" value="{{ $education['period'] }}">
                    </div>
                    <div class="col-4">
                        <label for="">Description</label>
                        <textarea name="education_description[]" rows="4" class="form-control education-description">{{ $education['description'] }}</textarea>
                    </div>
                    <div class="col pt-4 pl-4">
                        <a href="#" class="text-danger education-delete"><i class="fas fa-trash"></i></a>
                    </div>
                    </section>
                </div>
                
                @endforeach
                @endif
                </section>
                <section id="form-educations">
                    <div class="mb-3 card w-100 section-form-educations" data-education="{{ strtotime( date('Y-m-d H:i:s') ) }}">
                    <section class="form-row card-body">
                        <div class="col-4">
                            <label for="">University</label>
                            <input type="text" class="form-control education-university" name="education_university[]" >
                        </div>
                        <div class="col-2">
                            <label for="">Period</label>
                            <input type="text" class="form-control education-period" name="education_period[]" >
                        </div>
                        
                        <div class="col-4">
                            <label for="">Description</label>
                            <textarea name="education_description[]" rows="4" class="form-control education-description"></textarea>
                        </div>
                        <div class="col pt-4 pl-4">
                            <a href="#" class="text-danger education-delete"><i class="fas fa-trash"></i></a>
                        </div>
                    </section>
                    </div>
                </section>
                
                <div class="form-row">
                    <div class="col">
                        <button type="button" class="btn btn-primary" id="add-education">Add</button>
                    </div>
                </div>
            </div>

            <div class="col-12 mb-5">
                <h5>Employment</h5>
                <section class="" id="list-employments">
                @if( !is_null($expert->employment) && $expert->employment != '' )
                @foreach( unserialize($expert->employment) as $pkey => $employment )
                    <div class="card mb-3 w-100" id="form-employments">
                        <section class="form-row card-body">
                        <div class="col-4">
                            <label for="">Workplace</label>
                            <input type="text" class="form-control employment-workplace" name="employment_workplace[]" value="{{ $employment['workplace'] }}">
                        </div>
                        <div class="col-2">
                            <label for="">Period</label>
                            <input type="text" class="form-control employment-period" name="employment_period[]" value="{{ $employment['period'] }}">
                        </div>
                        <div class="col-4">
                            <label for="">Occupation</label>
                            <input type="text" class="form-control employment-occupation" name="employment_occupation[]" value="{{ $employment['occupation'] }}">
                        </div>
                        <div class="col pt-4 pl-4">
                            <a href="#" class="text-danger employment-delete"><i class="fas fa-trash"></i></a>
                        </div>
                        </section>
                    </div>

                @endforeach
                @endif
                </section>
                <section id="form-employments">
                    <div class="card mb-3 w-100 section-form-employments" data-employment="{{ strtotime( date('Y-m-d H:i:s') ) }}">
                    <section class="form-row card-body">
                        <div class="col-4">
                            <label for="">Workplace</label>
                            <input type="text" class="form-control employment-workplace" name="employment_workplace[]">
                        </div>
                        <div class="col-2">
                            <label for="">Period</label>
                            <input type="text" class="form-control employment-period" name="employment_period[]">
                        </div>
                        <div class="col-4">
                            <label for="">Occupation</label>
                            <input type="text" class="form-control employment-occupation" name="employment_occupation[]">
                        </div>
                        <div class="col pt-4 pl-4">
                            <a href="#" class="text-danger employment-delete"><i class="fas fa-trash"></i></a>
                        </div>
                    </section>
                    </div>
                </section>
                <div class="form-row">
                    <div class="col">
                        <button type="button" class="btn btn-primary" id="add-employment">Add</button>
                    </div>
                </div>
            </div>

            <div class="col-12 mb-5">
                <h5>Skills</h5>
                <section class="" id="list-skills">
                @if( !is_null($expert->skills) && $expert->skills != '' )
                @foreach( unserialize($expert->skills) as $pkey => $skill )
                <div class="form-row mb-3"  >
                    <div class="col">
                        <label for="">Skill</label>
                        <input type="text" class="form-control skills-skill" name="skills_skill[]" value="{{ $skill['skill'] }}">
                    </div>
                    <div class="col">

                        <label for="">Value</label>
                        <select name="skills_value[]" id="" class="form-control skills-value">
                            <option value="basic" {{ ($skill['value'] == 'basic')? 'selected' : ''   }} >Basic</option>
                            <option value="intermediate"  {{ ($skill['value'] == 'intermediate')? 'selected' : ''   }}>Intermediate</option>
                            <option value="advanced"  {{ ($skill['value'] == 'advanced')? 'selected' : ''   }}>Advanced</option>
                        </select>
                        
                        
                    </div>
                    <div class="col pt-4 pl-4">
                        <a href="#" class="text-danger skill-delete"><i class="fas fa-trash"></i></a>
                    </div>
                </div>
                
                @endforeach
                @endif
                </section>
                <section id="form-skills">
                    <div class="form-row mb-3 section-form-skills" data-skill="{{ strtotime( date('Y-m-d H:i:s') ) }}">
                        <div class="col">
                            <label for="">Skill</label>
                            <input type="text" class="form-control skills-skill" name="skills_skill[]">
                        </div>
                        <div class="col">
                            <label for="">Value</label>
                            <select name="skills_value[]" id="" class="form-control skills-value">
                                <option value="basic"  >Basic</option>
                                <option value="intermediate"  >Intermediate</option>
                                <option value="advanced"  >Advanced</option>
                            </select>
                        </div>
                        <div class="col pt-4 pl-4">
                            <a href="#" class="text-danger skill-delete"><i class="fas fa-trash"></i></a>
                        </div>
                    </div>
                </section>
                
                <div class="row">
                    <div class="col">
                        <button class="btn btn-primary" type="button" id="add-skill">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
<div class="row">
    <div class="col"><button type="submit" class="btn btn-success">Save</button></div>             
</div>
</form>
@endsection

@section('javascript')
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script type="text/javascript">
$(document).ready(function (ev) {

    
    // ================================= PROJECT =======================

    $("body").on('change' , '.upload-image' , function(ev){
        var file = this.files[0];
        console.log( file.size);
        if( file.size > 4300000){
            alert('Use a file size no more than 4M');
            return;
        }
        
        var _this = $(this);
        var _formData = new FormData();
        _formData.append('file', file);
        $.ajax({
            url:"{{ route('expert.uploadproject') }}",
            method:"POST",
            data: _formData,
            headers: {
                'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            contentType: false,
            cache: false,
            processData: false,
            success:function(data)
            {
                console.log(data, "0000000000");
                _this.next().html(data);
                _this.next().next().val(data);
            }
        })
        // 
    });
    var options_tags = {
        tags: true,
        tokenSeparators: [','],
        insertTag: function (data, tag) {
            // Insert the tag at the end of the results
            data.push(tag);
        }
    };
    $(".tag-select").select2(options_tags);

    $(".tag-categories").select2({
        tags: true,
        maximumSelectionLength: 1,
        tokenSeparators: [','],
        insertTag: function (data, tag) {
            // Insert the tag at the end of the results
            data.push(tag);
        }
    });

    // ================================= PROJECT =======================
    
    $("#add-project").on('click' , function(ev){
        
        var _index = $("#form-project").find('.section-form-project').attr("data-project");
        
        var title = $("#form-project").find('.project-title').val();
        var description = $("#form-project").find('.project-description').val();
        var categories = $("#form-project").find('.project-categories').val();
        var stacks = $("#form-project").find('.project-stacks').val();
        var image = $("#form-project").find('.upload-image-name').val();
        var url = $("#form-project").find('.project-url').val();

        var videos_form = $("#form-project").find('.section-videos');
        var images_form = $("#form-project").find('.section-images');

        if( title.trim() == "" ){
            $("#form-project").find('.project-title').focus();
            return;
        } 

        $("#form-project").find('.project-categories').select2('destroy');

        $("#form-project").find('.project-stacks').select2('destroy');

        var html_project = $("#form-project").html();
        
        $("#list-projects").append( html_project );

        var n_index = moment().valueOf();
        
        $("#list-projects").find( '.section-form-project[data-project="'+_index+'"]' )
            .find('.project-title').val(title);

        $("#list-projects").find( '.section-form-project[data-project="'+_index+'"]' )
            .find('.project-url').val(url);
        
        $("#list-projects").find( '.section-form-project[data-project="'+_index+'"]' )
            .find('.project-description').val(description);

        // $("#list-projects").find( '.section-form-project[data-project="'+_index+'"]' )
        //     .find('.upload-image-label').html(image);

        // $("#list-projects").find( '.section-form-project[data-project="'+_index+'"]' )
        //     .find('.upload-image-name').val(image);
        
        $("#list-projects").find( '.section-form-project[data-project="'+_index+'"]' )
            .find('.project-categories').html( html_options_tags(categories) ).select2({
                tags: true,
                maximumSelectionLength: 1,
                tokenSeparators: [','],
                insertTag: function (data, tag) {
                    // Insert the tag at the end of the results
                    data.push(tag);
                }
            });
        
        $("#list-projects").find( '.section-form-project[data-project="'+_index+'"]' )
            .find('.project-stacks').html( html_options_tags(stacks) ).select2(options_tags);

        $.each( videos_form , function(index, value){
            var index_video = $(this).attr("data-video");
            var video_url = $(this).find(".project-video").val();
            $("#list-projects").find( '.section-form-project[data-project="'+_index+'"]' )
            .find('.section-videos[data-video="'+index_video+'"]').find('.project-video').val(video_url);
        });

        $.each( images_form , function(index, value){
            var index_video = $(this).attr("data-image");
            var video_url = $(this).find(".upload-image-name").val();
            $("#list-projects").find( '.section-form-project[data-project="'+_index+'"]' )
                .find('.section-videos[data-image="'+index_video+'"]')
                .find('.upload-image-label').html(video_url);
            $("#list-projects").find( '.section-form-project[data-project="'+_index+'"]' )
                .find('.section-videos[data-image="'+index_video+'"]')
                .find('.upload-image-name').val(video_url);
        });
            
        // -------------------------------
        $("#form-project").find('.project-title').val('');
        $("#form-project").find('.project-description').val('');
        $("#form-project").find('.project-url').val('');
        
        $("#form-project").find('.section-form-project')
            .find('.project-image-label').html("Choose");
        
        
        $("#form-project").find('.section-form-project')
            .find('.project-categories').html('').select2(options_tags);
        $("#form-project").find('.section-form-project')
            .find('.project-stacks').html('').select2(options_tags);

        $("#form-project").find('.section-form-project')
            .attr('data-project', n_index );

        $("#form-project").find('.project-index').val(n_index);

        $("#form-project").find('.list-videos').html('');
        $("#form-project").find('.section-videos').attr('data-video' , 0);
        $("#form-project").find('.section-videos')
            .find('.project-video').val('').attr('name' , 'project_video_'+n_index+'[]');
        $("#form-project").find('.section-videos')
            .find('.add-video')
            .attr('data-time' , 'project_video_'+n_index)
            .attr('data-index' , 0);

        $("#form-project").find('.list-images').html('');
        $("#form-project").find('.section-images').attr('data-image' , 0);
        $("#form-project").find('.section-images')
            .find('.project-image')
            .attr('id' , 'project_image_file_0_'+n_index)
            .attr('name' , 'project_image_'+n_index+'[]');
        $("#form-project").find('.section-images')
            .find('.project-image-label')
            .attr('for' , 'project_image_file_0_'+n_index);
        $("#form-project").find('.section-images')
            .find('.upload-image-name').val('')
            .attr('name' , 'project_image_name_'+n_index+'[]');

        $("#form-project").find('.section-form-project')
            .find('.project-categories').attr("name" , "project_categories_" + n_index + "[]");

        $("#form-project").find('.section-form-project')
            .find('.project-stacks').attr("name" , "project_stacks_" + n_index + "[]");

        
    });

    $("#add-education").on('click' , function(ev){
        
        var html_education = $("#form-educations").html();
        var _index = $("#form-educations").find('.section-form-educations').attr("data-education");
        var university = $("#form-educations").find('.education-university').val();
        var period = $("#form-educations").find('.education-period').val();

        var description = $("#form-educations").find('.education-description').val();
        
        if( university.trim() == "" ){
            $("#form-educations").find('.education-university').focus();
            return;
        } 

        var n_index = moment().valueOf();

        $("#list-educations").append(html_education);

        $("#list-educations").find( '.section-form-educations[data-education="'+_index+'"]' )
            .find('.education-university').val(university);
        $("#list-educations").find( '.section-form-educations[data-education="'+_index+'"]' )
            .find('.education-period').val(period);
        
        $("#list-educations").find( '.section-form-educations[data-education="'+_index+'"]' )
            .find('.education-description').val(description);

        $("#form-educations").find('.section-form-educations')
            .attr('data-education', n_index );

        $("#form-educations").find('.education-university').val('');
        $("#form-educations").find('.education-period').val('');
        $("#form-educations").find('.education-description').val('');
    });

    $("#add-employment").on('click' , function(ev){
        var html_employment = $("#form-employments").html();
        var _index = $("#form-employments").find('.section-form-employments').attr("data-employment");
        var workplace = $("#form-employments").find('.employment-workplace').val();
        var period = $("#form-employments").find('.employment-period').val();
        var occupation = $("#form-employments").find('.employment-occupation').val();

        if( workplace.trim() == "" ){
            $("#form-employments").find('.employment-workplace').focus();
            return;
        } 

        var n_index = moment().valueOf();

        $("#list-employments").append( html_employment );

        $("#list-employments").find( '.section-form-employments[data-employment="'+_index+'"]' )
            .find('.employment-workplace').val(workplace);
        $("#list-employments").find( '.section-form-employments[data-employment="'+_index+'"]' )
            .find('.employment-period').val(period);

        $("#list-employments").find( '.section-form-employments[data-employment="'+_index+'"]' )
            .find('.employment-occupation').val(occupation);

        $("#form-employments").find('.section-form-employments')
            .attr('data-employment', n_index );

        $("#form-employments").find('.employment-workplace').val('');
        $("#form-employments").find('.employment-period').val('');
        $("#form-employments").find('.employment-occupation').val('');
    });

    $("#add-skill").on('click' , function(ev){
        var html_skills = $("#form-skills").html();
        var _index = $("#form-skills").find('.section-form-skills').attr("data-skill");
        var skill = $("#form-skills").find('.skills-skill').val();
        var value = $("#form-skills").find('.skills-value').val();

        if( skill.trim() == "" ){
            $("#form-skills").find('.skills-skill').focus();
            return;
        } 

        var n_index = moment().valueOf();

        $("#list-skills").append(html_skills);

        $("#list-skills").find( '.section-form-skills[data-skill="'+_index+'"]' )
            .find('.skills-skill').val(skill);
        $("#list-skills").find( '.section-form-skills[data-skill="'+_index+'"]' )
            .find('.skills-value').val(value);

        $("#form-skills").find('.section-form-skills')
            .attr('data-skill', n_index );

        $("#form-skills").find('.skills-skill').val('');
        $("#form-skills").find('.skills-value').val('');

    });

    $("body").on('click' , '.skill-delete', function(ev){
        ev.preventDefault();
        $(this).parent().parent().slideUp('fast' , function(){
            $(this).remove();
        })
    });

    $("body").on('click' , '.project-delete', function(ev){
        ev.preventDefault();
        $(this).parent().parent().slideUp('fast' , function(){
            $(this).remove();
        })
    });
    
    $("body").on('click' , '.employment-delete , .education-delete', function(ev){
        ev.preventDefault();
        $(this).parent().parent().parent().slideUp('fast' , function(){
            $(this).remove();
        });
    });

    $('body').on('click' , '.add-image' , function(ev){
        ev.preventDefault();
        var index = $(this).attr("data-index");
        var time = $(this).data("time");
        console.log(index , time);
        var section_image = $(this).parent().parent();
        var html_section_image = $(this).parent().parent().parent().html();

        // insert image
        $(this).parent().parent().parent().parent().find('.list-images').append( html_section_image );

        $(this).parent().parent().find('.project-image')
            // .attr("name" , "project_image_"+time+"[]" )
            .attr("id" , "project_image_file_"+(parseInt(index)+1)+"_"+time+"" );
            
        $(this).parent().parent().find('.project-image-label')
            .attr("for" , "project_image_file_"+(parseInt(index)+1)+"_"+time )
            .html('Choose file');

        $(this).parent().parent().attr("data-images" , parseInt(index) + 1 );

        $(this).attr("data-index" , (parseInt(index)+1));

        // $(this).parent().parent().find('.upload-image-name')
        //     .attr("name" , "project_image_name_"+time+"[]" )
            
    });

    $('body').on('click' , '.add-video' , function(ev){
        ev.preventDefault();
        var index = $(this).attr("data-index");
        var time = $(this).data("time");
        var url_video = $(this).parent().parent().find('.project-video').val();
        
        var html_section_video = $(this).parent().parent().parent().html();

        $(this).parent().parent().parent().parent().find('.list-videos').append( html_section_video );

        $("#form-project").find( '.section-form-project[data-project="'+time+'"]' )
            .find('.list-videos .section-videos[data-video="'+index+'"]').find(".project-video").val(url_video);

        $(this).parent().parent().attr("data-video" , parseInt(index) + 1 );

        $(this).parent().parent().find('.project-video').val('');

        $(this).attr("data-index" , (parseInt(index)+1));
        
    });

    function html_options_tags( tags ){
        // var a_tags = tags;
        var html_options = ''; 
        for (let index = 0; index < tags.length; index++) {
            html_options +='<option value="'+tags[index]+'" selected>'+tags[index]+'</option>'; 
        }
        return html_options;
    }

    

});
</script>

@endsection