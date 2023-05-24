<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaysController;
use App\Http\Controllers\CaisseController;
use App\Http\Controllers\SousCaisseController;

Route::get('accueil', function () {
    return view('accueil');
})->name('accueil');

Route::get('', function () {
    return view('login');
});

// pays
Route::group(['prefix' => 'pays'], function () {
    // Get
    Route::get('', [PaysController::class, 'pays'])->name('pays');

    //post
    Route::post('ajouterPays', [PaysController::class, 'ajouterPays']);
    Route::post('updatePays', [PaysController::class, 'update']);
    Route::post('deletePays', [PaysController::class, 'delete']);
});


// caisse
Route::group(['prefix' => 'caisse'], function () {
    // Get
    Route::get('depot', [CaisseController::class, "depot"])->name('caisse.depot');
    Route::get('retrait', [CaisseController::class, "retrait"])->name('caisse.retrait');

    //post
    Route::post('depot', [CaisseController::class, "ajoutDepot"]);
    Route::post('retrait', [CaisseController::class, "ajoutRetrait"]);
});


// sous caisse
Route::group(['prefix' => 'sous_caisse'], function () {
    // Get
    Route::get('', [SousCaisseController::class, "sous_caisse"])->name('sous_caisse');
    Route::get('historique_operation', [SousCaisseController::class, "historique"])->name('sous_caisse.historique');

    //post
    Route::post('ajouter', [SousCaisseController::class, "ajouter"]);
    Route::post('update', [SousCaisseController::class, 'update']);
    Route::post('delete', [SousCaisseController::class, 'delete']);
});