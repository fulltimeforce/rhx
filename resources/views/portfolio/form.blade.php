@extends('layouts.app' , ['controller' => 'position'])

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<style>

</style>

@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <h2>Expert - {{ $expert->fullname }}</h2>
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
                <label for="work">Work</label>
                
                <select name="work[]" id="work" class="form-control tag-select" multiple size="1">
                    @if( !is_null($expert->work) )
                    @foreach( explode(',' , $expert->work ) as $work )
                        <option value="{{ $work }}" selected>{{ $work }}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="col-6 mb-2">
                <label for="email">Email</label>
                <input type="text" class="form-control" value="{{ $expert->email }}" name="email" required>
            </div>
            <div class="col-6">
                <label for="address">Address</label>
                <input type="text" class="form-control" value="{{ $expert->address }}" name="address">
            </div>
            <div class="col-12">
                <label for="github">Github</label>
                <input type="text" class="form-control" value="{{ $expert->github }}" name="github">
            </div>
            <div class="col-12">
                <label for="linkedin">Linkedin</label>
                <input type="text" class="form-control" value="{{ $expert->linkedin }}" name="linkedin">
            </div>
            <div class="col-12">
                <label for="facebook">Facebook</label>
                <input type="text" class="form-control" value="{{ $expert->facebook }}" name="facebook">
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
                <div class="col-6">
                    <label for="project_title">Title</label>
                    <input type="text" class="form-control txt-project-title" name="project_title[]" value="{{ $project['title'] }}">
                    <input type="hidden" class="project-index" name="project_index[]" value="{{ $project['index'] }}">
                </div>
                <div class="col-6">
                    <label for="">Image</label>
                    <div class="custom-file">
                        <input type="file" class="form-control upload-image" name="project_image_file[]" id="project_image_file_">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                        <input type="hidden" name="project_image_name[]" value="{{ $project['image_name'] }}">
                    </div>
                </div>
                <div class="col-12">
                    <label for="description">Description</label>
                    <textarea name="project_description[]" rows="5" class="form-control txt-project-description">{{ $project['description'] }}</textarea>
                </div>
                <div class="col-6">
                    <label for="project_categories">Categories</label>
                    <select name="project_categories_{{ $project['index'] }}[]" class="form-control tag-categories sel-project-categories" multiple size="1">
                        @foreach( $project['categories'] as $category )
                            <option value="{{ $category }}" selected>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6">
                    <label for="project_stacks">Stacks</label>
                    <select name="project_stacks_{{ $project['index'] }}[]" class="form-control tag-select sel-project-stacks" multiple size="1">
                        @foreach( $project['stacks'] as $stack )
                            <option value="{{ $stack }}" selected>{{ $stack }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6">
                    <label for="">URL</label>
                    <input type="text" name="project_url[]" class="form-control project-url" value="{{ $project['url'] }}">
                </div>
                </section>
            </div>
            @endforeach
            @endif
        </section>
        <section id="form-project">
        <div class="mb-4 section-form-project card" data-project="{{ strtotime( date('Y-m-d H:i:s') ) }}">
            <section class="form-row card-body">
            <div class="col-6">
                <label for="project_title">Title</label>
                <input type="text" class="form-control project-title" name="project_title[]" >
                <input type="hidden" class="project-index" name="project_index[]" value="{{ strtotime( date('Y-m-d H:i:s') ) }}">
            </div>
            <div class="col-6">
                <label for="">Image</label>
                <div class="custom-file">
                    <input type="file" class="form-control upload-image project-image" name="project_image[]" id="project_image_file_{{ strtotime( date('Y-m-d H:i:s') ) }}">
                    <label class="custom-file-label project-image-label" for="project_image_file_{{ strtotime( date('Y-m-d H:i:s') ) }}">Choose file</label>
                    <input type="hidden" name="project_image_name[]" class="upload-image-name">
                </div>
            </div>
            <div class="col-12">
                <label for="description">Description</label>
                <textarea name="project_description[]" rows="5" class="form-control project-description" ></textarea>
            </div>
            <div class="col-3">
                <label for="project_categories">Categories</label>
                <select name="project_categories_{{ strtotime( date('Y-m-d H:i:s') ) }}[]"  class="form-control tag-categories project-categories" multiple size="1">
                </select>
            </div>
            <div class="col-6">
                <label for="project_stacks">Stacks</label>
                <select name="project_stacks_{{ strtotime( date('Y-m-d H:i:s') ) }}[]"  class="form-control tag-select project-stacks" multiple size="1"></select>
            </div>
            <div class="col-3">
                <label for="">URL</label>
                <input type="text" name="project_url[]" class="form-control project-url">
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
        <h5>RESUME</h5>
        <div class="form-row mb-4" >
            <div class="col-12 mb-4">
                <textarea name="resume" id="resume" rows="5" class="form-control">{{ $expert->resume }}</textarea>
            </div>

            <div class="col-12 mb-5">
                <h5>Education</h5>
                <section class="form-row mb-3" id="list-educations">

                @if( !is_null($expert->education) && $expert->education != '' )
                @foreach( unserialize($expert->education) as $pkey => $education )
                
                <div class="mb-3 card" >
                    <section class="form-row card-body">
                    <div class="col-4">
                        <label for="">University</label>
                        <input type="text" class="form-control education-university" name="education_university[]" value="{{ $education['university'] }}">
                    </div>
                    <div class="col-2">
                        <label for="">Age Start</label>
                        <input type="number" class="form-control education-age-start" name="education_age_start[]" value="{{ $education['age_start'] }}">
                    </div>
                    <div class="col-2">
                        <label for="">Age End</label>
                        <input type="number" class="form-control education-age-end" name="education_age_end[]" value="{{ $education['age_end'] }}">
                    </div>
                    <div class="col-4">
                        <label for="">Profession</label>
                        <input type="text" class="form-control education-profession" name="education_profession[]" value="{{ $education['profession'] }}">
                    </div>
                    </section>
                </div>
                
                @endforeach
                @endif
                </section>
                <section id="form-educations">
                    <div class="mb-3 card section-form-educations" data-education="{{ strtotime( date('Y-m-d H:i:s') ) }}">
                    <section class="form-row card-body">
                        <div class="col">
                            <label for="">University</label>
                            <input type="text" class="form-control education-university" name="education_university[]" >
                        </div>
                        <div class="col-2">
                            <label for="">Age Start</label>
                            <input type="number" class="form-control education-age-start" name="education_age_start[]" >
                        </div>
                        <div class="col-2">
                            <label for="">Age End</label>
                            <input type="number" class="form-control education-age-end" name="education_age_end[]" >
                        </div>
                        <div class="col-4">
                            <label for="">Profession</label>
                            <input type="text" class="form-control education-profession" name="education_profession[]" >
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
                    <div class="card mb-3" id="form-employments">
                        <section class="form-row card-body">
                        <div class="col">
                            <label for="">Workplace</label>
                            <input type="text" class="form-control employment-workplace" name="employment_workplace[]" value="{{ $employment['workplace'] }}">
                        </div>
                        <div class="col-4">
                            <label for="">Age Start</label>
                            <input type="text" class="form-control employment-age-start" name="employment_age_start[]" value="{{ $employment['age_start'] }}">
                        </div>
                        <div class="col-4">
                            <label for="">Age End</label>
                            <input type="text" class="form-control employment-age-end" name="employment_age_end[]" value="{{ $employment['age_end'] }}">
                        </div>
                        <div class="col-4">
                            <label for="">Occupation</label>
                            <input type="text" class="form-control employment-occupation" name="employment_occupation[]" value="{{ $employment['occupation'] }}">
                        </div>
                        </section>
                    </div>

                @endforeach
                @endif
                </section>
                <section id="form-employments">
                    <div class="card mb-3 section-form-employments" data-employment="{{ strtotime( date('Y-m-d H:i:s') ) }}">
                    <section class="form-row card-body">
                        <div class="col">
                            <label for="">Workplace</label>
                            <input type="text" class="form-control employment-workplace" name="employment_workplace[]">
                        </div>
                        <div class="col-2">
                            <label for="">Age Start</label>
                            <input type="number" class="form-control employment-age-start" name="employment_age_start[]">
                        </div>
                        <div class="col-2">
                            <label for="">Age End</label>
                            <input type="number" class="form-control employment-age-end" name="employment_age_end[]">
                        </div>
                        <div class="col-4">
                            <label for="">Occupation</label>
                            <input type="text" class="form-control employment-occupation" name="employment_occupation[]">
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
                        <input type="text" class="form-control skills-skill" name="skills-skill[]" value="{{ $skill['skill'] }}">
                    </div>
                    <div class="col">
                        <label for="">Value</label>
                        <input type="range" class="form-control skills-value" min="0" max="100" step="10" name="skills-value[]" value="{{ $skill['value'] }}" >
                        
                        <datalist id="tickmarks">
                        <option value="0" label="0">
                        <option value="10" label="10">
                        <option value="20" label="20">
                        <option value="30" label="30">
                        <option value="40" label="40">
                        <option value="50" label="50">
                        <option value="60" label="60">
                        <option value="70" label="70">
                        <option value="80" label="80">
                        <option value="90" label="90">
                        <option value="100" label="100%">
                        </datalist>
                        
                    </div>
                    <div class="col"></div>
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
                            <input type="range" list="tickmarks" class="form-control skills-value" min="0" max="100" step="10" name="skills_value[]">
                            <datalist id="tickmarks">
                            <option value="0" label="0">
                            <option value="10" label="10">
                            <option value="20" label="20">
                            <option value="30" label="30">
                            <option value="40" label="40">
                            <option value="50" label="50">
                            <option value="60" label="60">
                            <option value="70" label="70">
                            <option value="80" label="80">
                            <option value="90" label="90">
                            <option value="100" label="100%">
                            </datalist>
                        </div>
                        <div class="col">
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

    
    $("#add-project").on('click' , function(ev){
        
        var _index = $("#form-project").find('.section-form-project').attr("data-project");
        
        var title = $("#form-project").find('.project-title').val();
        var description = $("#form-project").find('.project-description').val();
        var categories = $("#form-project").find('.project-categories').val();
        var stacks = $("#form-project").find('.project-stacks').val();
        var image = $("#form-project").find('.upload-image-name').val();
        var url = $("#form-project").find('.project-url').val();

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

        $("#list-projects").find( '.section-form-project[data-project="'+_index+'"]' )
            .find('.upload-image-label').html(image);

        $("#list-projects").find( '.section-form-project[data-project="'+_index+'"]' )
            .find('.upload-image-name').val(image);
        
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

        $("#list-projects").find( '.section-form-project[data-project="'+_index+'"]' )
            .find('.project-image').attr("id" , "project_image_file_" + _index)  
        
       $("#list-projects").find( '.section-form-project[data-project="'+_index+'"]' )
            .find('.project-image-label').attr("for" , "project_image_file_" + _index);
            
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

        $("#form-project").find('.section-form-project')
            .find('.project-image').attr("id" , "project_image_file_" + n_index)  
        
        $("#form-project").find('.section-form-project')
            .find('.project-image-label').attr("for" , "project_image_file_" + n_index);

        $("#form-project").find('.section-form-project')
            .find('.project-categories').attr("name" , "project_categories_" + n_index + "[]");

        $("#form-project").find('.section-form-project')
            .find('.project-stacks').attr("name" , "project_stacks_" + n_index + "[]");

        
    });

    // ================================= PROJECT =======================
    
    $("#add-education").on('click' , function(ev){
        
        var html_education = $("#form-educations").html();
        var _index = $("#form-educations").find('.section-form-educations').attr("data-education");
        var university = $("#form-educations").find('.education-university').val();
        var age_star = $("#form-educations").find('.education-age-start').val();
        var age_end = $("#form-educations").find('.education-age-end').val();
        var profession = $("#form-educations").find('.education-profession').val();
        
        if( university.trim() == "" ){
            $("#form-educations").find('.education-university').focus();
            return;
        } 

        var n_index = moment().valueOf();

        $("#list-educations").append(html_education);

        $("#list-educations").find( '.section-form-educations[data-education="'+_index+'"]' )
            .find('.education-university').val(university);
        $("#list-educations").find( '.section-form-educations[data-education="'+_index+'"]' )
            .find('.education-age-start').val(age_star);
        $("#list-educations").find( '.section-form-educations[data-education="'+_index+'"]' )
            .find('.education-age-end').val(age_end);
        $("#list-educations").find( '.section-form-educations[data-education="'+_index+'"]' )
            .find('.education-profession').val(profession);

        $("#form-educations").find('.section-form-educations')
            .attr('data-education', n_index );

        $("#form-educations").find('.education-university').val('');
        $("#form-educations").find('.education-age-start').val('');
        $("#form-educations").find('.education-age-end').val('');
        $("#form-educations").find('.education-profession').val('');
    });


    $("#add-employment").on('click' , function(ev){
        var html_employment = $("#form-employments").html();
        var _index = $("#form-employments").find('.section-form-employments').attr("data-employment");
        var workplace = $("#form-employments").find('.employment-workplace').val();
        var age_star = $("#form-employments").find('.employment-age-start').val();
        var age_end = $("#form-employments").find('.employment-age-end').val();
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
            .find('.employment-age-start').val(age_star);
        $("#list-employments").find( '.section-form-employments[data-employment="'+_index+'"]' )
            .find('.employment-age-end').val(age_end);
        $("#list-employments").find( '.section-form-employments[data-employment="'+_index+'"]' )
            .find('.employment-occupation').val(occupation);

        $("#form-employments").find('.section-form-employments')
            .attr('data-employment', n_index );

        $("#form-employments").find('.employment-workplace').val('');
        $("#form-employments").find('.employment-age-start').val('');
        $("#form-employments").find('.employment-age-end').val('');
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