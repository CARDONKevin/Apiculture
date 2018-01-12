@extends('layouts.app')

@section('content')

        <!-- JavaScripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script>
    // requete ajax pour l'ajout de ruche en validant le formulaire de la pop in
    $(function () {

        $('#addRuche').click(function () {
            $('#myModal').modal();
        });

        $(document).on('submit', '#formCreateRuche', function (e) {
            e.preventDefault();

            $('input+small').text('');
            $('input').parent().removeClass('has-error');
            $.ajax({
                        method: $(this).attr('method'),
                        url: $(this).attr('action'),
                        data: $(this).serialize(),
                        dataType: "json"
                    })
                    .done(function (data) {
                        $('.alert-success').removeClass('hidden');
                        $('#myModal').modal('hide');
                        var monTitre = data['titre'];
                        var maLong = data['longitude'];
                        var maLat = data['latitude'];
                        var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(maLat, maLong),
                            draggable: true,
                            animation: google.maps.Animation.DROP,
                            map: map,
                            title: monTitre
                        });
                        marker.addListener('click', toggleBounce);
                    })
                    .fail(function (data) {
                        $.each(data.responseJSON, function (key, value) {
                            var input = '#formCreateRuche input[id=' + key + ']';
                            $(input + '+small').text(value);
                            $(input).parent().addClass('has-error');

                        });
                    });
        });
    })
</script>
<!-- Ma pop in / form modal pour les markers de ruches -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Ajouter une nouvelle ruche</h4>
            </div>
            <div class="modal-body">

                <form id="formCreateRuche" class="form-horizontal" role="form" method="POST"
                      action="{{ route('rucheInsert') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="form-group">
                        <label class="col-md-4 control-label">Nom</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="titre" id="titre">
                            <small class="help-block"></small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Latitude</label>
                        <div class="col-md-6">
                            <input type="number" step="0.00000001" class="form-control" name="latitude" id="latitude">
                            <small class="help-block"></small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Longitude</label>
                        <div class="col-md-6">
                            <input type="number" step="0.00000001" class="form-control" name="longitude" id="longitude">
                            <small class="help-block"></small>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">Fermer</span></button>
                            <button type="submit" class="btn btn-primary">
                                Enregistrer
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<!-- fin de la pop in ajout de ruche  -->

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"> Tableau de bord</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    Vos ruches <b>{{ Auth::user()->name }} </b> <br/>

                    <!--   bouton avec icone de la ruche pour déclencherl'ajout via une pop in -->
                    <button id="addRuche" style="background:none;">Add Ruche<img src="images/rucheAbeille.png" alt="Add Ruche"
                                                                           style="width:50px; height:50px;"/></button>

                    <!-- la div avec un id de map_canvas permet l'affichage de la Google Map -->
                    <div id="map_canvas"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
