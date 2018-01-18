<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// route page de démarrage : le formulaire de login
Route::get('/', function () {
    return view('auth.login');
});
// les routes de l'authentification
Auth::routes();
// route de la page home qui contient l'ensemble des infos des ruches de l'utilisateur
Route::get('/home', 'HomeController@index')->name('home');
// route pour l'insertion des ruches dans une pop in
Route::post('/ruche/insert',  'RucheController@insert')->name('rucheInsert');
// route pour consulter les interventions et les récoltes de la ruche via pop in
Route::get('/ruche/consulter/{id}',  'RucheController@read')->name('consultation');
// route pour l'insertion des interventions
Route::post('/intervention/insert',  'InterventionController@insert')->name('interventionInsert');
// route pour la saisie des récoltes pour une ruche
Route::post('/ruche/production',  'ProductionController@insert')->name('insertRecolte');

