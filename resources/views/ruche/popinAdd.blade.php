<?php
/**
 * Created by PhpStorm.
 * User: CARDON
 * Date: 15/01/2018
 * Time: 17:49
 */
?>
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
                        var id = data['id'];
                        var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(maLat, maLong),
                            draggable: true,
                            id: id,
                            animation: google.maps.Animation.DROP,
                            map: map,
                            title: monTitre
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
