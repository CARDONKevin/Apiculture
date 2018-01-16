<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Intervention;
use Illuminate\Database\Eloquent\Collection;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // récupération des ruches de l'utilisateur
        $ruches = DB::table('ruches')
            ->select('*')
            ->where('idUser', '=', Auth::user()->id)
            ->get();
        if ($request->ajax()) {
            // récupération des interventions paginés 10 par page  du plus récent au plus ancien, pour la page de pagination cliquée
            $interventions = DB::table('interventions')
                ->select('*')
                ->where('idRuche', '=', $_GET['idRuche'])
                ->latest('date_creation')
                ->paginate(10);
            $interventions->setPath('home');
            $pagination = ''.$interventions->appends(['idRuche' => $_GET['idRuche']])->links().'';
            return response()->json([
                'interventions' => $interventions,
                'pagination' => $pagination,
                'idRuche' => $_GET['idRuche']
            ]);
        }
        // retourne la vue home avec les ruches pour la construction des marker de la map
        return view('home')->with("ruches",$ruches);
    }
}
