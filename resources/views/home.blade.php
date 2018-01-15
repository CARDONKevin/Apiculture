@extends('layouts.app')

@section('content')
<!-- inclusion de la pop in pour l'ajout des ruches-->
@include("ruche.popinAdd")
<!-- inclusion de la pop in pour la consultation des ruches, qui montre les interventions, bouuton ajout et production -->
@include("ruche.popinConsult")

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
