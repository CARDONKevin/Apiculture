<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
    public function index()
    {
        // récupération des ruches de l'utilisateur
        $ruches = DB::table('ruches')
            ->select('*')
            ->where('idUser', '=', Auth::user()->id)
            ->get();
        // retourne la vue home avec les ruches pour la construction des marker de la map
        return view('home')->with("ruches",$ruches);
    }
}
