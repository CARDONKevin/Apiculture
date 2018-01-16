<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Intervention;
use Illuminate\Support\Facades\Validator;

class InterventionController extends Controller
{
    protected function validator(array $data)
    {
        // validateur des données du formulaire pour une intervention
        return Validator::make($data, [
            'texte' => 'required',
        ]);
    }

    protected function create(array $data)
    {
        // création d'une intervention avec données du formulaire et la datetime systeme
        return Intervention::create([
            'date_creation' => date("Y-m-d H:i:s"),
            'texte' => $data['texte'],
            'idRuche' => $data['idRuche'],
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
        // création de l'objet intervention et sauvegarde en base de données
        $intervention = $this->create($request->all());
        $intervention->save();
        // retourner une réponse JSON avec les données
        return response()->json([
            'texte' => $intervention->texte,
            'date_creation' => $intervention->date_creation,
            'idRuche' => $intervention->idRuche
        ]);
    }
}
