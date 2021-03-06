<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ $expert->fullname }}</title>
  <link rel="stylesheet" href="{{ asset('/portfolio/css/color.css') }}?v={{ strtotime( date('Y-m-d H:i:s') ) }}">
  <link href="https://fonts.googleapis.com/css?family=Roboto|Roboto+Mono|Inconsolata" rel="stylesheet">
  <link href="https://cdn.materialdesignicons.com/2.0.46/css/materialdesignicons.min.css" rel="stylesheet">
</head>
<body>

<!--Main menu-->
  <div class="menu">
    <div class="container">
      <div class="row">
        <div class="menu__wrapper d-none d-lg-block col-md-12">
          <nav class="">
            <ul>
              <li><a href="#hello">Hello</a></li>
              @if( !is_null($expert->projects) && $expert->projects != '' )
              @if( count( unserialize($expert->projects) ) > 0 )
              <li><a href="#portfolio">Portfolio</a></li>
              @endif
              @endif
              
              <li><a href="#resume">Resume</a></li>
              
              @if( !is_null($expert->skills) && $expert->skills != '' )
              @if( count( unserialize($expert->skills) ) > 0 )
              <li><a href="#skills">Skills</a></li>
              @endif
              @endif
              <li><a href="#contact">Contact</a></li>
            </ul>
          </nav>
        </div>
        <div class="menu__wrapper col-md-12 d-lg-none">
          <button type="button" class="menu__mobile-button">
            <span><i class="fa fa-bars" aria-hidden="true"></i></span>
          </button>
        </div>
      </div>
    </div>
  </div>
<!--Main menu-->

<!-- Mobile menu -->
  <div class="mobile-menu d-lg-none">
    <div class="container">
      <div class="mobile-menu__close">
        <span><i class="mdi mdi-close" aria-hidden="true"></i></span>
      </div>
      <nav class="mobile-menu__wrapper">
        <ul>
          <li><a href="#hello">Hello</a></li>
          @if( !is_null($expert->projects) && $expert->projects != '' )
          @if( count( unserialize($expert->projects) ) > 0 )
          <li><a href="#portfolio">Portfolio</a></li>
          @endif
          @endif
          <li><a href="#resume">Resume</a></li>
          @if( !is_null($expert->skills) && $expert->skills != '' )
          @if( count( unserialize($expert->skills) ) > 0 )
          <li><a href="#skills">Skills</a></li>
          @endif
          @endif
          <li><a href="#contact">Contact</a></li>
        </ul>
      </nav>
    </div>
  </div>
<!-- Mobile menu -->

<!--Header-->
  <header class="main-header" style="background-image: url(assets/img/img_bg_header.jpg)">
    <div class="container">
      <div class="row personal-profile">
        <div class="col-md-4 personal-profile__avatar">
          <img class="" src="{{ ( !is_null($expert->photo) && $expert->photo != '' )? route('home') .'/uploads/projects/'.$expert->photo : 'http://via.placeholder.com/350x400' }}" alt="avatar">
        </div>
        <div class="col-md-8">
          <p class="personal-profile__name"> {{ $expert->fullname }} </p>
          <p class="personal-profile__work"> {{ $expert->work }} </p>
          <div class="personal-profile__contacts">
            <dl class="contact-list contact-list__opacity-titles">
              <dt>Age:</dt>
              <dd>{{ $expert->age }}</dd>
              <dt>Email:</dt>
              <dd><a href="mailto:mail@mail.com">{{ $expert->email }}</a></dd>
              @if( !is_null($expert->address) && $expert->address != '' )
              <dt>Address:</dt>
              <dd>{{ $expert->address }}</dd>
              @endif
              @if( !is_null($expert->availability) && $expert->availability != '' )
              <dt>Availability:</dt>
              <dd>{{ $expert->availability }}</dd>
              @endif
            </dl>
          </div>
          <p class="personal-profile__social">
            @if( !is_null($expert->github) && $expert->github != '' )
            <a href="{{ $expert->github }}" target="_blank"><i class="fa fa-github"></i></a>
            @endif
            @if( !is_null($expert->linkedin) && $expert->linkedin != '' )
            <a href="{{ $expert->linkedin }}" target="_blank"><i class="fa fa-linkedin-square"></i></a>
            @endif
            @if( !is_null($expert->facebook) && $expert->facebook != '' )
            <a href="{{ $expert->facebook }}" target="_blank"><i class="fa fa-facebook-square"></i></a>
            @endif
          </p>
        </div>
      </div>
    </div>
  </header>
<!--Header-->

<!--Hello-->
<section id="hello" class="container section">
    <div class="row">
      <div class="col-md-10">
        <h2 id="hello_header" class="section__title">Hi_</h2>
        <p class="section__description">
          {{ $expert->description }}
        </p>
      </div>
    </div>
  </section>
<!--Hello-->

<!--Portfolio-->
@if( !is_null($expert->projects) && $expert->projects != '' )
@if( count( unserialize($expert->projects) ) > 0 )
  <section id="portfolio" class="container section">
    <div class="row">
      <div class="col-md-12">
        <h2 id="portfolio_header" class="section__title">Portfolio_</h2>
      </div>
    </div>
    <div class="row portfolio-menu">
      <div class="col-md-12">
        <nav>
          <ul>
            <li><a href="" data-portfolio-target-tag="all">all</a></li>
            @php
              $collet_cat = array()
            @endphp
            @foreach( unserialize($expert->projects) as $pkey => $project )
              @if( isset( $project['categories'][0] ) )
                @php
                  $collet_cat[] = $project['categories'][0]
                @endphp
              @else
                @php
                  $collet_cat[] = 'empty'
                @endphp
              @endif
              
            @endforeach
              
            @php
              $collet_cat = collect($collet_cat)->unique()
            @endphp

            @foreach( $collet_cat->values()->all() as $pkey => $cat )
              
              <li><a href="" data-portfolio-target-tag="{{$cat}}">{{$cat}}</a></li>
            @endforeach
          </ul>
        </nav>
      </div>
    </div>
    <div class="portfolio-cards">
      @foreach( unserialize($expert->projects) as $pkey => $project )
      <div class="row project-card" data-portfolio-tag="{{ isset($project['categories'][0]) ? $project['categories'][0] : 'empty' }}">

        <div class="col-md-12 project-card__info">
          <h3 class="project-card__title">{{ $project['title'] }}</h3>
          <p class="project-card__description">
          {{ $project['description'] }}
          </p>
          @if( !is_null($project['stacks']) )
          <p class="project-card__stack">Used stack:</p>
          <ul class="tags">
            
            @foreach( $project['stacks'] as $pstack => $stack )
            <li>{{ $stack }}</li>
            @endforeach
            
          </ul>
          @endif
          @foreach( array_merge($project['videos'] , $project['images'] ) as $vkey => $v )
          @if( !is_null($v) )
          <a class="project-card__link link_left" data-toggle="modal" data-target="#projectModal_{{ $project['index'] }}">Preview</a>
          @php
           break
          @endphp
          @endif
          @endforeach
          <a href="{{ $project['url'] }}" target="_blank" class="project-card__link">{{ $project['url'] }}</a>
        </div>
      </div>
      @endforeach

    </div>
  </section>
@endif
@endif
<!--Portfolio-->

  <hr>

<!--Resume-->
  <section id="resume" class="container section">
    <div class="row">
      <div class="col-md-10">
        <h2 id="resume_header" class="section__title">Resume_</h2>
        <p class="section__description">
            {{ $expert->resume }}
        </p>
      </div>
    </div>
    @if( !is_null($expert->education) && $expert->education != '' )
    @if( count( unserialize($expert->education) ) > 0 )
    <div class="row">
      <div class="col-md-8 section__resume resume-list">
        <h3 class="resume-list_title">education</h3>
        
        @foreach( unserialize($expert->education) as $pkey => $education )
            <div class="resume-list__block">
            <p class="resume-list__block-title">{{ $education['university'] }} </p>
            <p class="resume-list__block-date">{{ $education['period'] }} </p>
            <p>
            {{ $education['description'] }}
            </p>
            </div>
        @endforeach
        
      </div>
    </div>
    @endif
    @endif

    @if( !is_null($expert->employment) && $expert->employment != '' )
    @if( count( unserialize($expert->employment) ) > 0 )
    <div class="row">
      <div class="col-md-8 section__resume resume-list">
        <h3 class="resume-list_title">employment</h3>
        
        @foreach( unserialize($expert->employment) as $pkey => $employment )
            <div class="resume-list__block">
            <p class="resume-list__block-title">{{ $employment['workplace'] }}</p>
            <p class="resume-list__block-date">{{ $employment['period'] }} </p>
            <p>
                {{ $employment['occupation'] }}
            </p>
            </div>
        @endforeach
        
      </div>
    </div>
    @endif
    @endif

    
  </section>
<!--Resume-->
@if( !is_null($expert->skills) && $expert->skills != '' )
  @if( count( unserialize($expert->skills) ) > 0 )
<section class="container section" id="skills">

  <div class="row section__resume progress-list js-progress-list">
      <div class="col-md-12">
          <h2 id="resume_header" class="section__title">General skills_</h2>
          
      </div>
      @php
        $_order_skills = array()
      @endphp
      @php
        $_basic_skills = array()
      @endphp
      @php
        $_intermediate_skills = array()
      @endphp
      @php
        $_advanced_skills = array()
      @endphp

      @foreach( unserialize($expert->skills) as $pkey => $skill )
        @if( $skill['value'] == 'basic' )
          @php
            $_basic_skills[] = $skill
          @endphp
        @elseif( $skill['value'] == 'intermediate' )
          @php
            $_intermediate_skills[] = $skill
          @endphp
        @elseif( $skill['value'] == 'advanced' )
          @php
            $_advanced_skills[] = $skill
          @endphp
        @endif
      @endforeach

      @php
        $_order_skills = array_merge( $_advanced_skills, $_intermediate_skills , $_basic_skills)

      @endphp

      @foreach( $_order_skills as $pkey => $skill )
      <div class="col-md-5">
          
          <div class="progress-list__skill">
              <p>
                  <span class="progress-list__skill-title">{{ $skill['skill'] }}</span>
                  
              </p>
              <div class="progress">
              
                  <div class="progress-bar" role="progressbar" aria-valuenow="{{ ( ($skill['value'] == 'basic')? 30 : ( ($skill['value'] == 'intermediate')? 70 : 100) )   }}" aria-valuemin="0" aria-valuemax="100" >
                  </div>
              </div>
          </div>
              
      </div>
      @endforeach

  </div>
  
</section>
@endif
@endif
<section class="container section"></section>


<!--Contact-->
  <div class="background" style="background-image: url(assets/img/img_bg_main.jpg)">
    <div id="contact" class="container section">
      <div class="row">
        <div class="col-md-12">
          <p id="contacts_header" class="section__title">Get in touch_</p>
        </div>
      </div>
      <div class="row contacts">
        <div class="col-md-5 col-lg-4">
          <div class="contacts__list">
            <dl class="contact-list">
              <dt>Phone:</dt>
              <dd><a href="tel:964220417">(+51) 964 220 417</a></dd>
              <dt>Skype:</dt>
              <dd><a href="skype:bitsngeeks">Bitsngeeks</a></dd>
              <dt>Email:</dt>
              <dd><a href="mailto:info@fulltimeforce.com">info@fulltimeforce.com</a></dd>
            </dl>
          </div>
          <div class="contacts__social">
            
          </div>
        </div>
        <div class="col-md-7 col-lg-5">
          <div class="contacts__social">
            <ul>
              <li><a href="https://www.facebook.com/fulltimeforce" target="_blank">Facebook</a></li>
              <li><a href="https://www.linkedin.com/company/fulltimeforce/" target="_blank">Linkedin</a></li>
              <li><a href="https://www.instagram.com/workatfulltimeforce" target="_blank">Instagram</a></li>
            </ul>
          </div>
          <div class="footer">
            <p>© 2020 Fulltimeforce. All Rights Reserved</p>
          </div>
        </div>
      </div>
    </div>
  </div>
<!--Contact-->

<!-- Portfolio Modal -->
@if( !is_null($expert->projects) && $expert->projects != '' )
@if( count( unserialize($expert->projects) ) > 0 )
@foreach( unserialize($expert->projects) as $pkey => $project )
<!-- Modal -->
<div class="modal fade" id="projectModal_{{ $project['index'] }}" tabindex="-1" role="dialog" aria-labelledby="projectModal_{{ $project['index'] }}Label" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="projectModal_{{ $project['index'] }}Label">{{ $project['title'] }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pt-4 pb-5">
        <div id="carouselProject_{{ $project['index'] }}" class="carousel slide w-100 h-100" >
            <ol class="carousel-indicators">
              @php
                $videos__images_count = 0
              @endphp
              @foreach( array_merge($project['videos'] , $project['images'] ) as $vkey => $v )
              @if( !is_null($v) )
              <li data-target="#carouselProject_{{ $project['index'] }}" data-slide-to="{{$videos__images_count}}" class="{{ $videos__images_count == 0? 'active' : '' }}"></li>
              @php
                $videos__images_count++
              @endphp
              @endif
              @endforeach
            </ol>
            <div class="carousel-inner w-100 h-100">
              @php
                $videos_count = 0
              @endphp
              @foreach( $project['videos'] as $vkey => $video )
              @if( !is_null($video) )
              <div class="w-100 h-100 carousel-item {{ $videos_count == 0? 'active' : '' }}">
                <div class="w-100 h-100" style="min-height: 350px;">
                  <iframe class="w-100 h-100" style="min-height: 350px;" src="{{ $video }}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
              </div>
              @php
                $videos_count++
              @endphp
              @endif
              @endforeach

              @php
                $images_count = 0
              @endphp
              @foreach( $project['images'] as $vkey => $image )
              @if( !is_null($image) )
              <div class="carousel-item h-100 {{ ( $images_count == 0 && $project['videos'][0] == null )? 'active' : '' }} ">
                <div class="d-flex flex-wrap align-content-center h-100">
                <img class="d-block w-100" src="{{ route('home') .'/uploads/projects/'.$image }}" >
                </div>
                
              </div>
              @php
                $images_count++
              @endphp
              @endif
              @endforeach
            </div>
            <a class="carousel-control-prev" href="#carouselProject_{{ $project['index'] }}" role="button" data-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselProject_{{ $project['index'] }}" role="button" data-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
          </div>
      </div>
    </div>
  </div>
</div>
@endforeach
@endif
@endif


  <script src="{{ asset('/portfolio/js/jquery-2.2.4.min.js') }}"></script>
  <script src="{{ asset('/portfolio/js/popper.min.js') }}"></script>
  <script src="{{ asset('/portfolio/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('/portfolio/js/menu.js') }}"></script>
  <script src="{{ asset('/portfolio/js/jquery.waypoints.js') }}"></script>
  <script src="{{ asset('/portfolio/js/progress-list.js') }}"></script>
  <script src="{{ asset('/portfolio/js/section.js') }}"></script>
  <script src="{{ asset('/portfolio/js/portfolio-filter.js') }}"></script>
  <script src="{{ asset('/portfolio/js/slider-carousel.js') }}"></script>
  <script src="{{ asset('/portfolio/js/mobile-menu.js') }}"></script>
  <script src="{{ asset('/portfolio/js/mbclicker.min.js') }}"></script>
  <script src="{{ asset('/portfolio/js/site-btn.js') }}"></script>
  <script src="{{ asset('/portfolio/js/style-switcher.js') }}"></script>
</body>
</html>
