<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'KANE') }}</title>

    <!-- Scripts -->
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/0.10.0/lodash.min.js"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.12.0/css/all.css">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        body{
            font-family: "font";        
        }
        @font-face{
            src: url('fonts/font.ttf');
            font-family: "font";
        }

        }
    </style>
</head>
<body>
    <div id="app" >
        @if (Route::has('register'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
        </li>
         @endif
        <nav class="navbar mx-auto fixed-top shadow-lg p-0" 
        style="background: #457fca;  
        background: -webkit-linear-gradient(to right, #5691c8, #457fca);  
        background: linear-gradient(to right, #5691c8, #457fca); ">
            <div class="container-fluid p-0  text-center">
                    <!-- Left Side Of Navbar -->
                    <ul class="header mx-auto col-12 col-sm-10 col-md-6 col-lg-3 row p-0">
                            <div class="col-2 col-sm-1 float-left mx-auto">
                                <router-link to="/profile" >
                                        <i class="fas fa-house border-bottom p-2"></i>
                                </router-link>
                            </div>
                   
                   
                            <div class="col-2 col-sm-1 float-left mx-auto">
                                <router-link to="/notification"  >
                                    <i class="fas fa-bell border-bottom p-2"></i>
                                </router-link>
                            </div>

                            <div class="col-2 col-sm-1 float-left mx-auto">
                                <router-link to="/home"  >
                                    <i class="fas fa-user-friends border-bottom p-2"></i>
                                </router-link>
                            </div>

                            <div class="col-2 col-sm-1 float-left mx-auto">
                                <router-link to="/public" >
                                    <i class="fas fa-globe-asia border-bottom p-2"></i>
                                </router-link>
                            </div>
                    
                            
                            
                            <div class="col-2 col-sm-1 float-left mx-auto">
                                <router-link to="/chat">
                                    <i class="fas fa-comments border-bottom p-2"></i>
                                </router-link>
                            </div>

                            <div class="col-2 col-sm-1 float-right mx-auto" >
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                   <i class="fas fa-power-off border-bottom p-2"></i>
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                </form>
                           </div>
                    </ul>
                   
                </div>
        </nav>

        <main class="py-4 mt-5">
            @yield('content')
        </main>
    </div>
</body>
</html>
