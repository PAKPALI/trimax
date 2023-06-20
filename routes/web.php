<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaysController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BanqueController;
use App\Http\Controllers\CaisseController;
use App\Http\Controllers\SousCaisseController;
use App\Http\Controllers\TypeDepenseController;

Route::get('accueil', function () {
    return view('accueil');
})->name('accueil');

Route::get('', function () {
    $User = User::all();

    if($User->count() == 0){
        return view('auth.register');
    }else{
        if (Auth::check()) {
            return view('accueil');
        }else{
            return view('auth.login');
        }
    }
});

Route::get('connexion', function () {
    return view('auth.login');
})->name('conn');

// pays
Route::group(['prefix' => 'pays'], function () {
    // Get
    Route::get('', [PaysController::class, 'pays'])->name('pays');

    //post
    Route::post('ajouterPays', [PaysController::class, 'ajouterPays']);
    Route::post('updatePays', [PaysController::class, 'update']);
    Route::post('deletePays', [PaysController::class, 'delete']);
});

// banque
Route::group(['prefix' => 'banque'], function () {
    // Get
    Route::get('', [BanqueController::class, 'banque'])->name('banque');

    //post
    Route::post('ajouter', [BanqueController::class, "ajouter"]);
    Route::post('update', [BanqueController::class, 'update']);
    Route::post('delete', [BanqueController::class, 'delete']);
});

// type depense
Route::group(['prefix' => 'type_depense'], function () {
    // Get
    Route::get('', [TypeDepenseController::class, 'typeDepense'])->name('typeDepense');

    //post
    Route::post('ajouter', [TypeDepenseController::class, "ajouter"]);
    Route::post('update', [TypeDepenseController::class, 'update']);
    Route::post('delete', [TypeDepenseController::class, 'delete']);
});


// caisse
Route::group(['prefix' => 'caisse'], function () {
    // Get
    Route::get('depot', [CaisseController::class, "depot"])->name('caisse.depot');
    Route::get('retrait', [CaisseController::class, "retrait"])->name('caisse.retrait');
    Route::get('operation', [CaisseController::class, "operation"])->name('caisse.operation');

    //post
    Route::post('depot', [CaisseController::class, "ajoutDepot"]);
    Route::post('retrait', [CaisseController::class, "ajoutRetrait"]);
    Route::post('filterTable', [CaisseController::class, 'filterTable'])->name('filterTable');
});


// sous caisse
Route::group(['prefix' => 'sous_caisse'], function () {
    // Get
    Route::get('', [SousCaisseController::class, "sous_caisse"])->name('sous_caisse');
    Route::get('demande_depense', [SousCaisseController::class, "demande_depense"])->name('sous_caisse.demande_depense');
    Route::get('historique_operation', [SousCaisseController::class, "historique"])->name('sous_caisse.historique');

    //post
    Route::post('ajouter', [SousCaisseController::class, "ajouter"]);
    Route::post('update', [SousCaisseController::class, 'update']);
    Route::post('delete', [SousCaisseController::class, 'delete']);

    Route::post('demande_depense', [SousCaisseController::class, 'demande_depense_post']);
    Route::post('update_depense', [SousCaisseController::class, 'update_depense']);
    Route::post('valider_depense', [SousCaisseController::class, 'valider_depense']);
    Route::post('rejeter_depense', [SousCaisseController::class, 'rejeter_depense']);
});

// user
Route::group(['prefix' => 'utilisateurs'], function () {
    // Get
    Route::get('', [UserController::class, 'user'])->name('user');

    //post
    Route::post('ajouter', [UserController::class, "ajouter"]);
    Route::post('ajouter_admin', [UserController::class, "ajouter_admin"]);
    Route::post('update', [UserController::class, 'update']);
    Route::post('parametre', [UserController::class, 'parametre']);
    Route::post('connected', [UserController::class, 'connected']);
    Route::post('status', [UserController::class, 'status']);
    Route::post('delete', [UserController::class, 'delete']);
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
