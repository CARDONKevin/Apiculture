<?php
/**
 * Created by PhpStorm.
 * User: CARDON
 * Date: 17/01/2018
 * Time: 10:54
 */
?>

<script type="text/javascript">
    // requete ajax pour l'ajout de récolte sur une ruche en validant le formulaire
    $(function () {

        $(document).on('submit', '#formCreateProduction', function (e) {
            e.preventDefault();
            $.ajax({
                        method: $(this).attr('method'),
                        url: $(this).attr('action'),
                        data: $(this).serialize(),
                        dataType: "json"
                    })
                    .done(function (data) {
                        // afficher les données des récoltes et nettoyer les données de la saisie formulaire
                        drawChart(data['recoltes'])
                        document.getElementById('poidsTotalRecoltes').innerHTML=data['poidsTotal'];
                        document.getElementById('poids').value="";
                        document.getElementById('datepicker').value="";
                    })
                    .fail(function (data) {
                            alert('Erreur, la création de récolte est impossible ')
                    });
        });
    })
</script>
        <!-- Ma pop in / form modal pour les productions de ruches -->
<div class="modal fade" id="myModalProduction" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close"  style="background: none; border: none; float: left;"><span
                        aria-hidden="true"><img src="images/fermer.png"/></span></button>
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel" style="text-align: center">Ajouter une nouvelle récolte</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-md-6" style="margin: auto;width: 100%;">
                        <div id="piechart"></div>
                    </div>
                </div>
                <form id="formCreateProduction" class="form-horizontal" role="form" method="POST"
                      action="{{ route('insertRecolte') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="idRucheRecolte" name="idRucheRecolte" value="">
                    <div class="form-group">
                        <label class="col-md-4 control-label">Date</label>
                        <div class="col-md-6">
                            <input type="date" class="form-control" name="date" id="datepicker" required>
                            <small class="help-block"></small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Poids (g) :</label>
                        <div class="col-md-6">
                            <input type="number" step="1" class="form-control" name="poids" id="poids" required>
                            <small class="help-block"></small>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                Add
                            </button>
                            <b><span style="margin-left: 5%"> KG des récoltes : <span id="poidsTotalRecoltes"></span></span></b>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<!-- fin de la pop in ajout de ruche  -->
