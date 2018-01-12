<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Ruche;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

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
            'titre' => $ruche->titre,
            'longitude' => $ruche->longitude,
            'latitude' => $ruche->latitude
        ]);
    }
}
