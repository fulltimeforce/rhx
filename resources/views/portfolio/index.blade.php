<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>{{ $expert->fullname }}</title>
  <link rel="stylesheet" href="{{ asset('/portfolio/css/color.css') }}">
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
              <li><a href="#resume">Resume</a></li>
              <li><a href="#portfolio">Portfolio</a></li>
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
          <li><a href="#resume">Resume</a></li>
          <li><a href="#portfolio">Portfolio</a></li>
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
          <img class="" src="{{ ( !is_null($expert->photo) )? route('home') .'/uploads/projects/'.$expert->photo : 'http://via.placeholder.com/350x400' }}" alt="avatar">
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
<!--Portfolio-->
@if( !is_null($expert->projects) && $expert->projects != '' )
@if( count( unserialize($expert->projects) ) > 0 )
  <section id="portfolio" class="container section">
    <div class="row">
      <div class="col-md-12">
        <h2 id="portfolio_header" class="section__title">My projects_</h2>
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
      <div class="row project-card" data-toggle="modal" data-target="#portfolioModal{{$project['index']}}" data-portfolio-tag="{{ isset($project['categories'][0]) ? $project['categories'][0] : 'empty' }}">
        <div class="col-md-6 col-lg-5 project-card__img">
          <img class="" src="{{ ( !is_null($project['image_name']) && $project['image_name'] != '' )? route('home') .'/uploads/projects/'.$project['image_name'] : route('home') .'/image/project-image.jpg' }}" alt="project-img">
        </div>
        <div class="col-md-6 col-lg-7 project-card__info">
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
          <a href="" class="project-card__link">{{ $project['url'] }}</a>
        </div>
      </div>
      @endforeach

    </div>
  </section>
@endif
@endif
<!--Portfolio-->

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
            <p class="resume-list__block-date">{{ $education['age_start'] }} - {{ $education['age_end'] }}</p>
            <p>
            {{ $education['profession'] }}
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
            <p class="resume-list__block-date">{{ $employment['age_start'] }} - {{ $employment['age_end'] }}</p>
            <p>
                {{ $employment['occupation'] }}
            </p>
            </div>
        @endforeach
        
      </div>
    </div>
    @endif
    @endif

    @if( !is_null($expert->skills) && $expert->skills != '' )
    @if( count( unserialize($expert->skills) ) > 0 )
    <div class="row section__resume progress-list js-progress-list">
        <div class="col-md-12">
            <h3 class="progress-list__title">general skills</h3>
        </div>

        @foreach( unserialize($expert->skills) as $pkey => $skill )
        <div class="col-md-5">
            
            <div class="progress-list__skill">
                <p>
                    <span class="progress-list__skill-title">{{ $skill['skill'] }}</span>
                    <span class="progress-list__skill-value">{{ $skill['value'] }}%</span>
                </p>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $skill['value'] }}" aria-valuemin="0" aria-valuemax="100" >
                    </div>
                </div>
            </div>
               
        </div>
        @endforeach

    </div>
    @endif
    @endif
  </section>
<!--Resume-->
<section class="container section"></section>


<!--Contact-->
  <!-- <div class="background" style="background-image: url(assets/img/img_bg_main.jpg)">
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
              <dd><a href="tel:82344563333">8 (234) 456-33-33</a></dd>
              <dt>Skype:</dt>
              <dd><a href="skype:iamivanovivan">iamivanovivan</a></dd>
              <dt>Email:</dt>
              <dd><a href="mailto:mail@mail.com">mail@mail.com</a></dd>
            </dl>
          </div>
          <div class="contacts__social">
            <ul>
              <li><a href="">Facebook</a></li>
              <li><a href="">Linkedin</a></li>
              <li><a href="">GitHub</a></li>
              <li><a href="">Bitbucket</a></li>
            </ul>
          </div>
        </div>
        <div class="col-md-7 col-lg-5">
          <div class="contacts__form">
            <p class="contacts__form-title">Or just write me a letter here_</p>
            <form class="js-form">
              <div class="form-group">
                <input class="form-field js-field-name" type="text" placeholder="Your name" required>
                <span class="form-validation"></span>
                <span class="form-invalid-icon"><i class="mdi mdi-close" aria-hidden="true"></i></span>
              </div>
              <div class="form-group">
                <input class="form-field js-field-email" type="email"  placeholder="Your e-mail" required>
                <span class="form-validation"></span>
                <span class="form-invalid-icon"><i class="mdi mdi-close" aria-hidden="true"></i></span>
              </div>
              <div class="form-group">
                <textarea class="form-field js-field-message" placeholder="Type the message here" required></textarea>
                <span class="form-validation"></span>
                <span class="form-invalid-icon"><i class="mdi mdi-close" aria-hidden="true"></i></span>
              </div>
              <button class="site-btn site-btn--form" type="submit" value="Send">Send</button>
            </form>
          </div>
          <div class="footer">
            <p>© 2016 Ivan Susanin. All Rights Reserved</p>
          </div>
        </div>
      </div>
    </div>
  </div> -->
<!--Contact-->

<!-- Portfolio Modal -->
  @foreach( unserialize($expert->projects) as $pkey => $project )
  <div class="modal fade portfolio-modal" id="portfolioModal{{$project['index']}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body col-md-11 col-lg-9 ml-auto mr-auto">
            <p class="portfolio-modal__title">{{$project['title']}}</p>
            <img class="portfolio-modal__img" src="{{ route('home') .'/uploads/projects/'.$project['image_name'] }}" alt="modal_img">
            <p class="portfolio-modal__description">
              {{$project['description']}}
            </p>
            <div class="portfolio-modal__link">
              <a href="{{$project['url']}}">{{$project['url']}}</a>
            </div>
            @if( !is_null($project['stacks']) )
            <div  class="portfolio-modal__stack">
              <p class="portfolio-modal__stack-title">Using stack:</p>
              <ul class="tags">

                @foreach( $project['stacks'] as $pstack => $stack )
                <li>{{ $stack }}</li>
                @endforeach
              </ul>
            </div>
            @endif
          </div>
      </div>
    </div>
  </div>
  @endforeach


<!-- Portfolio Modal -->

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
