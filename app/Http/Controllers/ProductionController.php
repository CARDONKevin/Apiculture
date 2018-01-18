<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Recolte;
use Illuminate\Support\Facades\Validator;

class ProductionController extends Controller
{
    protected function validator(array $data)
    {
        // validateur des données du formulaire pour une récolte
        return Validator::make($data, [
            'date' => 'required|date',
            'poids' => 'required',
            'idRucheRecolte' => 'required',
        ]);
    }

    protected function create(array $data)
    {
        // création d'une récolte avec données du formulaire
        return Recolte::create([
            'date' => $data['date'],
            'poids' => $data['poids'],
            'idRuche' => $data['idRucheRecolte'],
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
        // création de l'objet récolte et sauvegarde en base de données
        $recolte = $this->create($request->all());
        $recolte->save();
        // retouner les récoltes pour le graphique
        $recoltes = DB::table('recoltes')
            ->select(DB::raw('YEAR(date) year'), DB::raw('MONTH(date) month'), DB::raw('SUM(poids)as poidsTotal'))
            ->where('idRuche', '=', $recolte->idRuche)
            ->groupby('year', 'month')
            ->get();
        $result[] = ['date','poids(g)'];
        $poidsTotal=0;
        foreach ($recoltes as $key => $value) {
            if($value->month<10){
                $value->month="0".$value->month;
            }
            $result[++$key] = [$value->month.' / '.$value->year, $value->poidsTotal];
            $poidsTotal+=(int)$value->poidsTotal;
        }
        //réponse json avec les données du graphique
        return response()->json([
            'recoltes' => $result,
            'poidsTotal' => $poidsTotal/1000
        ]);
    }
}
