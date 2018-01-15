<?php
/**
 * Created by PhpStorm.
 * User: CARDON
 * Date: 15/01/2018
 * Time: 17:50
 */
?>
<script type="text/javascript">
    // script clicque sur la pagination, requête ajax pour les résultats à afficher
    $(function() {
        $('body').on('click', '.pagination a', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            getInterventions(url);
            window.history.pushState("", "", url);
        });

        function getInterventions(url) {
            $.ajax({
                url : url
            }).done(function (data) {
                var lesInterventions = data['interventions']['data'];
                var texte="";
                lesInterventions.forEach(function(element){
                    var d = new Date(element['date_creation']);
                    texte=texte+"<tr><td>"+d.toLocaleString()+'</td><td><p>'+element['texte']+"</p></td></tr>";
                })
                $('#lesInterventions').html(texte);
                $('#pagination').html(data['pagination']);
            }).fail(function () {
                alert('Les interventions ne peuvent pas être chargés.');
            });
        }
    });

</script>



<!-- Ma pop in / form modal pour consulter une ruche pour ses interventions -->
<div class="modal fade" id="myModalInterv" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel"> <button id="addProduction" style="background:PaleGoldenRod;">Production</button> Consulter les interventions de votre ruche</h4>
            </div>
            <div class="modal-body">
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
                    <div id="pagination"> </div>
                @endif
                <button id="addIntervention" style="background:PaleGoldenRod;display: block;margin:auto;">Add Intervention</button>

            </div>
        </div>
    </div>
</div>
<!-- fin de la pop in consultation d'une ruche pour ses interventions -->
