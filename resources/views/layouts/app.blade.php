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
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        // Charge google charts sur le site
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        // dessine le graphique grâce aux valeurs récupérer
        function drawChart(data) {
            var donnees = new google.visualization.DataTable();
            donnees.addColumn('string', 'Date');
            donnees.addColumn('number', 'Poids (g)');
            data.forEach(function(element){
                donnees.addRow([element[0], parseInt(element[1])]);
             })
            // Optional; add a title and set the width and height of the chart
            var options = {'title':'Production de votre ruche en gramme par mois', pointSize: 5, 'width':550, 'height':300};
            // Display the chart inside the <div> element with id="piechart"
            var chart = new google.visualization.LineChart(document.getElementById('piechart'));
            chart.draw(donnees, options);
        }

    </script>

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        footer{
            background:black;
            color: white;
            text-align: center;
            position:absolute;
            bottom:0;
            width:100%;
            height: 5%;
        }
    </style>
    @if (isset($ruches))
        <style>
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
            }
            body{
                background: url('images/fondRuche.jpg');
            }
            button{
                border-radius:8px;
                font:bold 13px Arial;
            }
            #map_canvas {
                height: 400px;
                width: 100%;
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
            #floating-panel {
                position: absolute;
                top: 35%;
                left: 17%;
                z-index: 5;
                padding: 5px;
                text-align: center;
                font-family: 'Roboto','sans-serif';
                line-height: 30px;
                padding-left: 10px;
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
            var markers = [];
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
                    markers.push(marker);
                    marker.addListener('mousedown', function() {
                        document.getElementById('markerSelectedID').setAttribute('value', marker.id);
                        document.getElementById('markerSelectedTitle').setAttribute('value', marker.title);
                    });
                    marker.addListener('mouseup', function() {
                        document.getElementById('markerSelectedID').setAttribute('value', '0');
                        document.getElementById('markerSelectedTitle').setAttribute('value', 'aucune balise');
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
                                    document.getElementById('idRuche').setAttribute('value', data['idRuche']);
                                    document.getElementById('idRucheRecolte').setAttribute('value', data['idRuche']);
                                    document.getElementById('lesInterventions').innerHTML=texte;
                                    $('#pagination').html(data['pagination']);
                                    drawChart(data['recoltes'], data['poidsTotal'] );
                                    document.getElementById('poidsTotalRecoltes').innerHTML=data['poidsTotal'];
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
                <img src="images/abeille.png" style="float: left; width:6%; height: 6%" />
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
<footer>

    <p>Projet PHP réalisé par Kévin CARDON</p>

</footer>
</body>
</html>
