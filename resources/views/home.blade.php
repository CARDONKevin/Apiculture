@extends('layouts.app')

@section('content')
        <!-- inclusion de la pop in pour l'ajout des ruches-->
@include("ruche.popinAdd")
        <!-- inclusion de la pop in pour la consultation des ruches, qui montre les interventions, bouuton ajout et production -->
@include("ruche.popinConsult")
        <!-- inclusion de la pop in pour l'ajout de la production des ruches-->
@include("ruche.popinProduction")

<script>
    //fonction pour cacher le marker selectionné pour la suppression
    function setMapOnMarker(marker) {
        var id = document.getElementById('markerSelectedID').getAttribute('value');
        for (var i = 0; i < markers.length; i++) {
            if (markers[i].id == id) {
                markers[i].setMap(marker);
            }
        }
    }
    // fonction qui appel la fonction pour cacher un marker
    function clearMarker() {
        setMapOnMarker(null);
    }
    //  suppression du marker ou non avec demande de confirmation
    $(function () {
        $('#supMarker').mouseup(function () {
            var id = document.getElementById('markerSelectedID').getAttribute('value');
            var nom = document.getElementById('markerSelectedTitle').getAttribute('value');
            if (id != 0) {
                if (confirm("Voulez-vous vraiment supprimer la ruche : " + nom)) {
                    //alert("vous êtes d'accord");
                    $.ajax({
                                method: 'get',
                                url: 'ruche/supprimer/'+id,
                                data: id,
                                dataType: "json"
                            })
                            .done(function (data) {
                                document.getElementById('markerSelectedID').setAttribute('value', "0");
                                document.getElementById('markerSelectedTitle').setAttribute('value', "Aucune balise séléctionné");
                            })
                            .fail(function (data) {
                                alert('impossible de supprimer la ruche');
                            });
                    clearMarker();
                }
                else {
                    alert("La ruche " + nom + " n'a pas été supprimé de la liste");
                    document.getElementById('markerSelectedID').setAttribute('value', "0");
                    document.getElementById('markerSelectedTitle').setAttribute('value', "Aucune balise séléctionné");
                }
            }
        });
    });
</script>
<!-- affiche du nom de la personne et de la map avec les markers de position des ruches   -->
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

                    <!--   bouton avec icone de la ruche pour déclencher l'ajout via une pop in -->
                    <button id="addRuche" style="background:none;">Add Ruche<img src="images/rucheAbeille.png"
                                                                                 alt="Add Ruche"
                                                                                 style="width:50px; height:50px;"/>
                    </button>
                  <!--  bouton corbeille pour la suppression du marker avec des inputs qui contiennent ses infos -->
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="markerSelectedTitle" id="markerSelectedTitle"
                               value="Aucune balise séléctionné">
                        <input type="hidden" name="markerSelectedID" id="markerSelectedID" value="0">
                        <div id="floating-panel">
                            <button id="supMarker"><img src="images/poubelle.png"/></button>
                        </div>
                    <!-- la div avec un id de map_canvas permet l'affichage de la Google Map -->
                    <div id="map_canvas"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
