<?php

namespace App\Http\Controllers;

use App\Intervention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Ruche;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class RucheController extends Controller
{
    protected function validator(array $data)
    {
        // validateur des données du formulaire pour une ruche
        return Validator::make($data, [
            'titre' => 'required|max:255',
            'longitude' => 'required',
            'latitude' => 'required',
        ]);
    }

    protected function create(array $data)
    {
        // création d'une ruche avec données du formulaire
        return Ruche::create([
            'titre' => $data['titre'],
            'longitude' => $data['longitude'],
            'latitude' => $data['latitude'],
            'idUser' => Auth::user()->id,
        ]);
    }

    public function insert(Request $request)
    {
        // vérification de la validation conforme au validateur du formulaire
        $validator = $this->validator($request->all());
        // si cela fail
        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
        // si cela marche
        // création de l'objet ruche et sauvegarde en base de données
        $ruche = $this->create($request->all());
        $ruche->save();
        // retourner une réponse JSON avec les données
        return response()->json([
            'id' => $ruche->id,
            'titre' => $ruche->titre,
            'longitude' => $ruche->longitude,
            'latitude' => $ruche->latitude
        ]);
    }

    public function read($id)
    {
        // récupération des interventions paginés 10 par page du plus récent au plus ancien
        $interventions = DB::table('interventions')
            ->select('*')
            ->where('idRuche', '=', $id)
            ->latest('date_creation')
            ->paginate(10);
        $interventions->setPath('home');
        $pagination = ''.$interventions->appends(['idRuche' => $id])->links().'';
        return response()->json([
            'interventions' => $interventions,
           'pagination' => $pagination,
            'idRuche' => $id
        ]);
    }
}
