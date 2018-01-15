<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <link rel="icon" type="image/png" href="images/abeille.png"/>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        // mettre l'objet csrf token dans l'entête de la requête ajax
        $(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
    </script>

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
                margin: 50px auto;
            }
            #tableau {
                font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
                font-size: 12px;
                margin: 10px 0;
                width: 100%;
                text-align: left;
                border-collapse: collapse;
            }
            #tableau th {
                font-size: 13px;
                font-weight: normal;
                padding: 8px;
                background: #b9c9fe url('http://4.bp.blogspot.com/_xDpoN6UfFFY/S-J2gjh1nPI/AAAAAAAACbg/7lNsVpks2oY/s1600/gradhead.png') repeat-x;
                border-top: 2px solid #d3ddff;
                border-bottom: 1px solid #fff;
                color: #039;
            }
            #tableau td {
                max-width: 250px;
                padding: 8px;
                border-bottom: 1px solid #fff;
                color: #669;
                border-top: 1px solid #fff;
                background: #e8edff url('http://1.bp.blogspot.com/_xDpoN6UfFFY/S-J2f5yBC3I/AAAAAAAACbY/zWXYXsR-w5E/s1600/gradback.png') repeat-x;
            }
            #tableau tfoot tr td {
                background: #e8edff;
                font-size: 16px;
                color: #99c;
                text-align:center;
            }
            #tableau tbody tr:hover td {
                background: #d0dafd url('http://4.bp.blogspot.com/_xDpoN6UfFFY/S-J2hsztUzI/AAAAAAAACbo/ztV1CK0RUrE/s1600/gradhover.png') repeat-x;
                color: #339;
            }
            #tableau a:hover {
                text-decoration:underline;
            }
        </style>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAb6e8EUsL6tb_kK2T1brzB0CkUIDsTRwE&sensor=false"></script>
        <script>
            var map;
            function initialize() {
                // récupération des ruches de l'utilisateur
                var mesRuches = <?php echo json_encode($ruches);?>;
                // option de la map
                var mapOptions = {
                    zoom: 5,
                    center: new google.maps.LatLng(48, 2),
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }

                // création d'une Google Map avec API
                map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
                // Ajout d'un marqueur sur la map par ruche, le marqueur est déplaçable et à un effet bounce au chargement de la map
                mesRuches.forEach(function (uneRuche) {
                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(uneRuche['latitude'], uneRuche['longitude']),
                        draggable: true,
                        id: uneRuche['id'],
                        animation: google.maps.Animation.DROP,
                        map: map,
                        title: uneRuche['titre']
                    });
                    marker.addListener('click', function() {
                        $.ajax({
                                    method: 'get',
                                    url: 'ruche/consulter/'+marker.id,
                                    data: marker.id,
                                    dataType: "json"
                                })
                                .done(function (data) {
                                    $('#myModalInterv').modal();
                                    var lesInterventions = data['interventions']['data'];
                                    var texte="";
                                    lesInterventions.forEach(function(element){
                                        var d = new Date(element['date_creation']);
                                        texte=texte+"<tr><td>"+d.toLocaleString()+'</td><td><p>'+element['texte']+"</p></td></tr>";
                                    })
                                    document.getElementById('lesInterventions').innerHTML=texte;
                                    $('#pagination').html(data['pagination']);
                                })
                                .fail(function (data) {
                                    alert('erreur');
                                });
                    });
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
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse">
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
                           <!-- <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a> -->

                           <!-- <ul class="dropdown-menu" role="menu"> -->
                                <li>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Déconnexion
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                           <!-- </ul> -->
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
