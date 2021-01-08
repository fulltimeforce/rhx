<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Fulltimeforce') }}</title>

    <!-- Scripts -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/rhx.css') }}?{{ strtotime('now') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />
    <link href="{{ asset('/tokenize2/tokenize2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('/fontawesome/css/all.min.css') }}" rel="stylesheet" />

    

    <script src="{{ asset('js/app.js') }}"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
    <script type="text/javascript" src="{{ asset('/tablefilter/tablefilter.js') }}"></script>
    
    <script>
        var $_url_ajax = '{!! env("APP_URL_AJAX") !!}';
    </script>

    @yield('styles')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="https://fulltimeforce.com/wp-content/themes/ftf-2020/images/fulltimeforce-logo.svg" alt="Fulltimeforce Logo" id="logo">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        @auth
                        
                            @if( Auth::user()->role->id == 1 )
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="{{ route('log.schedules') }}">{{ __('Schedule') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('logs.index') }}">{{ __('Logs2') }}</a>
                            </li> -->
                            </li><li class="nav-item">
                                <a class="nav-link" href="{{ route('user.menu') }}">{{ __('Users') }}</a>
                            </li>
                            </li><li class="nav-item">
                                <a class="nav-link" href="{{ route('recruits.tech.menu') }}">{{ __('TECH') }}</a>
                            </li>
                            </li><li class="nav-item">
                                <a class="nav-link" href="{{ route('sales.menu') }}">{{ __('Sales') }}</a>
                            </li>
                            @endif
                            <!-- </li><li class="nav-item">
                                <a class="nav-link" href="{{ route('expert.portfolio.resume') }}">{{ __('Resume') }}</a>
                            </li> -->
                            @if( Auth::user()->role->id < 3 )
                            </li><li class="nav-item">
                                <a class="nav-link" href="{{ route('recruit.menu') }}">{{ __('RECRUITMENT') }}</a>
                            </li>
                            <!--</li><li class="nav-item">
                                <a class="nav-link" href="{{ route('specific.menu') }}">{{ __('SPCF. RECRUITMENT') }}</a>
                            </li> -->
                            </li><li class="nav-item">
                                <a class="nav-link" href="{{ route('recruiter.log') }}">{{ __('Logs') }}</a>
                            </li>
                            @endif
                            </li><li class="nav-item">
                                <a class="nav-link" href="{{ route('recruit.fce.menu') }}">{{ __('FCE') }}</a>
                            </li>
                            <!--</li><li class="nav-item">
                                <a class="nav-link" href="{{ route('experts.fce.menu') }}">{{ __('FCE') }}</a>
                            </li> -->
                            @if( Auth::user()->role->id < 3 )
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('experts.home') }}">{{ __('Experts') }}</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link" href="{{ route('experts.beta') }}">{{ __('Experts') }} BETA</a>
                            </li> --}}
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}">{{ __('Careers') }}</a>
                            </li>
                            @endif
                        @endauth
                        <!-- Authentication Links -->
                            

                        @guest
                            <li class="nav-item">
                                <a class="nav-link nav-login" href="{{ route('login.google') }}">{{ __('Iniciar sesión') }}</a>
                            </li>


                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    
                                    <a class="dropdown-item" href="{{ route('user.configuration') }}">
                                        Configuration
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <div style="position: absolute; top: 15px; right: 15px; z-index: 7000;" id="toast-container" name="toast-container"></div>    

        <main class="py-4">
            <div class="container-fluid">
                @yield('content')
            </div>

            <script type="text/javascript">
                $(document).ready(function () {
                    //CALL NOTIFIERS (ENABLED PER USER LEVEL) FUNCTIONS
                    function load_toast_notifiers(){
                        $.ajax({
                            type:'POST',
                            url: '{{ route("recruit.load.toast.notifiers") }}',
                            headers: {
                                'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success:function(data){
                                let _data = JSON.parse(data)
                                console.log(_data)
                                var _notifications = _data.notifications;
                                _notifications.forEach(element => {
                                    var now = new Date().getTime();
                                    var toast_time = new Date(element.created_at).getTime();
                                    var difference = Math.floor((toast_time-now)/86400000);

                                    if(difference==0){var result_time = 'Today';}
                                    if(difference==1){var result_time = difference + ' day ago'}
                                    if(difference>1){var result_time = difference + ' days ago'}

                                    var actions = '';

                                    actions +=  '<div class="toast" aria-live="assertive" aria-atomic="true" role="alert" data-autohide="false" style="width: 300px;">';
                                    actions +=      '<div class="toast-header">';
                                    actions +=          '<strong class="mr-auto">'+element.search_name+'</strong>';
                                    actions +=          '<small class="text-muted">'+result_time+'</small>';
                                    actions +=          '<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close" data-id="'+element.id+'">';
                                    actions +=              '<span aria-hidden="true">&times;</span>';
                                    actions +=          '</button>';
                                    actions +=      '</div>';
                                    actions +=      '<div class="toast-body" style="background-color: lightgray;">';
                                    actions +=          element.expert_name;
                                    actions +=      '</div>';
                                    actions +=  '</div>';

                                    $('#toast-container').prepend(actions);
                                });
                                $('.toast').toast('show');
                            }
                        });
                    }

                    //TRIGGER FUNCTION
                    load_toast_notifiers();

                    //CHANGE NOTIFIER STATE (DISABLED) ON CLOSE BUTTON CLICK
                    $(document).on('click', '.close', function(ev){
                        var notification_id = $(this).data("id");
                        if(notification_id){
                            $.ajax({
                                type:'POST',
                                url: '{{ route("recruit.delete.toast.notifiers") }}',
                                data: {notification_id: notification_id},
                                headers: {
                                    'Authorization':'Basic '+$('meta[name="csrf-token"]').attr('content'),
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success:function(data){}
                            });
                        }
                    });
                })
            </script>
        </main>

        @yield('javascript')
    </div>
</body>
</html>
