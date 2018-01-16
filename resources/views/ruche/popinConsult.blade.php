<?php
/**
 * Created by PhpStorm.
 * User: CARDON
 * Date: 15/01/2018
 * Time: 17:50
 */
?>
<script type="text/javascript">
    function AfficherFormIntervention() {
        // on récupère l'élément form.
        var form = document.getElementById('formCreateIntervention');
        var bouton = document.getElementById('addIntervention');
        form.style.display = "block";
        bouton.style.display = "none";
    }
    function CacherFormIntervention() {
        // on récupère l'élément form.
        var form = document.getElementById('formCreateIntervention');
        var bouton = document.getElementById('addIntervention');
        form.style.display = "none";
        bouton.style.display = "block";
    }
    // obtention des interventions
    function getInterventions(url) {
        $.ajax({
            url: url
        }).done(function (data) {
            var lesInterventions = data['interventions']['data'];
            var texte = "";
            lesInterventions.forEach(function (element) {
                var d = new Date(element['date_creation']);
                texte = texte + "<tr><td>" + d.toLocaleString() + '</td><td><p>' + element['texte'] + "</p></td></tr>";
            })
            $('#lesInterventions').html(texte);
            $('#pagination').html(data['pagination']);
        }).fail(function () {
            alert('Les interventions ne peuvent pas être chargés.');
        });
    }
    // requete ajax pour l'ajout d'intervention sur une ruche en validant le formulaire
    $(function () {

        $(document).on('submit', '#formCreateIntervention', function (e) {
            e.preventDefault();
            $.ajax({
                        method: $(this).attr('method'),
                        url: $(this).attr('action'),
                        data: $(this).serialize(),
                        dataType: "json"
                    })
                    .done(function (data) {
                        getInterventions('home?idRuche='+data['idRuche']+'&page=1');
                        CacherFormIntervention();
                    })
                    .fail(function (data) {
                        document.getElementById('texte').setAttribute( 'placeholder','impossible de stocker une intervention vide')
                    });
        });
    })
    // script clique sur la pagination, requête ajax pour les résultats à afficher
    $(function () {
        $('body').on('click', '.pagination a', function (e) {
            e.preventDefault();

            var url = $(this).attr('href');
            getInterventions(url);
            window.history.pushState("", "", url);
        });
    });

</script>


<!-- Ma pop in / form modal pour consulter une ruche pour ses interventions -->
<div class="modal fade" id="myModalInterv" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <button id="addProduction" style="background:PaleGoldenRod;">Production</button>
                    Consulter les interventions de votre ruche
                </h4>
            </div>
            <div class="modal-body">
                <!-- formulaire d'ajout d'intervention pour une ruche, ce form se situe dans la pop in de la consultation-->
                <form style="display:none;" id="formCreateIntervention" class="form-horizontal" role="form"
                      method="POST"
                      action="{{ route('interventionInsert') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="idRuche" name="idRuche" value="">
                    <div class="form-group">
                        <!-- <label class="col-md-4 control-label">Texte</label> -->
                        <div class="col-md-6" style="margin: auto;width: 100%;">
                            <textarea placeholder="Saisissez votre intervention" class="form-control" name="texte"
                                      id="texte" style="max-width:100%;" required="required"></textarea>
                            <small class="help-block"></small>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button onclick="CacherFormIntervention()" id="Annuler" type="button"
                                    class="btn btn-primary"><span>Annuler</span></button>
                            <button type="submit" class="btn btn-primary" id="enregistreIntervention">
                                Enregistrer
                            </button>
                        </div>
                    </div>
                </form>
                <table id="tableau" summary="Classement des interventions">
                    <thead>
                    <tr>
                        <th scope="col">Date de création</th>
                        <th scope="col">Texte</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <td colspan="2">FIN DU TABLEAU SUR CETTE PAGE</td>
                    </tr>
                    </tfoot>
                    <tbody id="lesInterventions">
                    <!-- le contenu a mettre ici via fonction  -->
                    </tbody>
                </table>
                @if(isset($pagination))
                    <div id="pagination">{{$pagination}} </div>
                @else
                    <div id="pagination"></div>
                @endif
                <button id="addIntervention" onclick="AfficherFormIntervention()"
                        style="background:PaleGoldenRod;display: block;margin:auto;">Add Intervention
                </button>

            </div>
        </div>
    </div>
</div>
<!-- fin de la pop in consultation d'une ruche pour ses interventions -->
