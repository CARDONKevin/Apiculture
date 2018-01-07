<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @if (isset($ruches))
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        #map_canvas {
            height: 400px;
            width: 700px;
            margin:50px auto;
        }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAb6e8EUsL6tb_kK2T1brzB0CkUIDsTRwE&sensor=false"></script>
    <script>
        // effet bounce
        function toggleBounce() {
            if (marker.getAnimation() !== null) {
                marker.setAnimation(null);
            } else {
                marker.setAnimation(google.maps.Animation.BOUNCE);
            }
        }
        function initialize() {
            // récupération des ruches de l'utilisateur
            var mesRuches = <?php echo json_encode($ruches);?>;
            // option de la map
            var mapOptions = {
                zoom: 5,
                center: new google.maps.LatLng(48,2),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            // création d'une Google Map avec API
            var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
            // Ajout d'un marqueur sur la map par ruche, le marqueur est déplaçable et à un effet bounce au chargement de la map
            mesRuches.forEach(function(uneRuche){
                var marker= new google.maps.Marker({
                    position: new google.maps.LatLng(uneRuche['longitude'],uneRuche['latitude']),
                    draggable: true,
                    animation: google.maps.Animation.DROP,
                    map: map,
                    title: uneRuche['titre']
                });
                marker.addListener('click', toggleBounce);
            });
        }
    </script>
        @endif
</head>
<body onload="initialize()">
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image 
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a> -->
					<a class="navbar-brand" href="">
                        Apiculture
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Se connecter</a></li>
                            <li><a href="{{ route('register') }}">S'enregistrer</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
