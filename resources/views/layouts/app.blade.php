<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.12.0/css/all.css">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    
    <style>
        body{
            background-color: #1d1d1d !important;
            font-weight: bold !important;
        }
        .card{
            margin-top: 30px !important;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 5px 5px 20px #151515,
                        -4px -4px 10px rgba(255, 255, 255, 0.1);
            border:none;
            background: none;
            justify-content: center;
            justify-items: center;
            margin: auto;
            

        }
        i{
            color: #426cb2 !important;
        }
        .ui {
            background: none;
            box-shadow: 4px 4px 10px #000000 inset,-4px -4px 10px #555 inset;
            border-radius: 20px;transition: .6s;
        }
        .ui:hover{
            box-shadow: -5px -5px 8px #426cb2 inset;  
        }
        li{
            box-shadow: 4px 4px 10px #000000 inset,-4px -4px 10px #555 inset;
            border-radius: 20px;padding: 5px; font-size: 13px;
        }
        a{
            color: #cbced1 !important
        }
        button{
            background: #426cb2; 
            border-radius: 20px !important;
            border:none;
            outline: none !important;
            padding: 7px;
            font-weight: 800;
            color: #cbced1;

        }
        input{
            background: none !important;
            outline: none;
            border:none;
            width: 85%;
            border-radius: 20px;
            color: #cbced1 !important;

        }
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            transition: background-color 5000s ease-in-out 0s;
        }

        input:-webkit-autofill {
            -webkit-box-shadow: /*your box-shadow*/,0 0 0 50px white inset;
            -webkit-text-fill-color: #eff4ff;
        }  

        i{
            font-size: 20px;
            color:#555;
        }
    </style>
</head>
<body>
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
