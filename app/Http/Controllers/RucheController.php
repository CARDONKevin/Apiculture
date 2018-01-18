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
        // récupération des récoltes
        $recoltes = DB::table('recoltes')
            ->select(DB::raw('YEAR(date) year'), DB::raw('MONTH(date) month'), DB::raw('SUM(poids)as poidsTotal'))
            ->where('idRuche', '=', $ruche->id)
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
        // retourner une réponse JSON avec les données
        return response()->json([
            'id' => $ruche->id,
            'titre' => $ruche->titre,
            'longitude' => $ruche->longitude,
            'latitude' => $ruche->latitude,
            'recoltes' => $result,
            'poidsTotal' => $poidsTotal/1000
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
        // récupération des récoltes
        $recoltes = DB::table('recoltes')
            ->select(DB::raw('YEAR(date) year'), DB::raw('MONTH(date) month'), DB::raw('SUM(poids)as poidsTotal'))
            ->where('idRuche', '=', $id)
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
        return response()->json([
            'interventions' => $interventions,
           'pagination' => $pagination,
            'idRuche' => $id,
            'recoltes' => $result,
            'poidsTotal' => $poidsTotal/1000
        ]);
    }
}
