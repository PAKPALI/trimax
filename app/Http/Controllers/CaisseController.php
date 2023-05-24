<?php

namespace App\Http\Controllers;

use App\Models\Caisse;
use App\Models\SousCaisse;
use Illuminate\Http\Request;
use App\Models\OperationCaisse;
use App\Models\OperationSousCaisse;
use Illuminate\Support\Facades\Validator;

class CaisseController extends Controller
{
    public function depot()
    {
        $caisse = Caisse::first();
        $somme_init = 0;

        $operation = OperationCaisse::where('type_op', "depot") ->get();

        if($caisse){
            return view('caisse.depot',[
                'somme' => $caisse->somme,
                'Operation' => $operation,
            ]);
        }else{
            return view('caisse.depot',[
                'somme' => $somme_init,
                'Operation' => $operation,
            ]);
        }
    }

    public function retrait()
    {
        $caisse = Caisse::first();
        $somme_init = 0;

        $operation = OperationCaisse::where('type_op', "retrait") ->get();

        $SousCaisse = SousCaisse::all();

        if($caisse){
            return view('caisse.retrait',[
                'somme' => $caisse->somme,
                'Operation' => $operation,
                'SousCaisse' => $SousCaisse,
            ]);
        }else{
            return view('caisse.retrait',[
                'somme' => $somme_init,
                'Operation' => $operation,
                'SousCaisse' => $SousCaisse,
            ]);
        }
    }


    public function ajoutDepot(Request $request)
    {
        $error_messages = [
            "banque.required" => "Mettez le nom de la banque",
            "somme.required" => "Saisissez la somme!",
            "somme.numeric" => "La somme doit etre numerique!",
            "confirmersomme.required" => "Confirmer la somme!",
            "confirmersomme.numeric" => "La somme doit etre numerique!",
            "desc.required" => "Saisissez la description!",
        ];

        $validator = Validator::make($request->all(),[
            'banque' => ['required'],
            'somme' => ['required','numeric'],
            'confirmersomme' => ['required','numeric'],
            'desc' => ['required'],
        ], $error_messages);

        if($validator->fails())
            return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "AJOUT ECHOUE",
            "msg" => $validator->errors()->first()]);

        // requete sur la caisse
        $caisse = Caisse::first();
        // on verifie voir si les sommes sont identiques

        if($request-> somme >=0){
            if($request-> somme == $request-> confirmersomme){
                // si la caisse existe
                if($caisse){
                    // operation pour avoir la nouvelle quantitée
                    $nouvelleQuantite = $caisse -> somme + $request-> somme;
                    // mise a jour sur la caisse
                    $caisse -> update([
                        'somme' => $nouvelleQuantite,
                    ]);
                    // enrgistrer loperation
                    OperationCaisse::create([
                        'somme' => $request-> somme,
                        'type_op' => "DEPOT",
                        'banque' => $request-> banque,
                        'desc' => $request-> desc,
                    ]);
                    // envoyez une reponse
                    return response()->json([
                        "status" => true,
                        "reload" => true,
                        "redirect_to" => route('caisse.depot'),
                        "title" => "DEPOT REUSSI",
                        "msg" => ""
                    ]);
                }else{ //si la caisse nexiste pas
                    //creer la caisse et ajouter la somme saisie
                    Caisse::create([
                        'somme' => $request-> somme,
                    ]);
                    // enrgistrer loperation
                    OperationCaisse::create([
                        'somme' => $request-> somme,
                        'type_op' => "DEPOT",
                        'banque' => $request-> banque,
                        'desc' => $request-> desc,
                    ]);
                    // envoyez une reponse
                    return response()->json([
                        "status" => true,
                        "reload" => true,
                        "redirect_to" => route('caisse.depot'),
                        "title" => "DEPOT REUSSI",
                        "msg" => ""
                    ]);
                }
            }else{
                return response()->json([
                    "status" => false,
                    "redirect_to" => '',
                    "title" => "DEPOT ECHOUE",
                    "msg" => "La somme saisie est différente de la somme confirmée"
                ]);
            }
        }else{
            return response()->json([
                "status" => false,
                "redirect_to" => '',
                "title" => "DEPOT ECHOUE",
                "msg" => "La somme saisie ne doit pas etre inferieur a zéro"
            ]);
        }
    }

    public function ajoutRetrait(Request $request)
    {
        $error_messages = [
            "somme.required" => "Saisissez la somme!",
            "somme.numeric" => "La somme doit etre numerique!",
            "confirmersomme.required" => "Confirmer la somme!",
            "confirmersomme.numeric" => "La somme doit etre numerique!",
            "desc.required" => "Saisissez la description!",
        ];

        $validator = Validator::make($request->all(),[
            'somme' => ['required','numeric'],
            'confirmersomme' => ['required','numeric'],
            'desc' => ['required'],
        ], $error_messages);

        if($validator->fails())
            return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "AJOUT ECHOUE",
            "msg" => $validator->errors()->first()]);

        // requete sur la caisse
        $caisse = Caisse::first();

        // requete sur la sous caisse
        $sousCaisse = SousCaisse::find($request-> selection);

        if($request-> selection){
            // on verifie voir si les sommes sont identiques
            if($request-> somme >=0){
                if($request-> somme == $request-> confirmersomme){
                    // si la caisse existe
                    if($caisse){
                        // operation pour avoir la nouvelle quantitée de la caisse
                        $nouvelleQuantiteCaisse = $caisse -> somme - $request-> somme;
                        // verifier si la qte saisie est inferieure  a la somme dans la caisse
                        if($nouvelleQuantiteCaisse>=0){
                            // mise a jour sur la caisse
                            $caisse -> update([
                                'somme' => $nouvelleQuantiteCaisse,
                            ]);
                            // operation pour avoir la nouvelle quantitée de la sous caisse
                            $nouvelleQuantiteSousCaisse = $sousCaisse -> somme + $request-> somme;
                            $sousCaisse -> update([
                                'somme' => $nouvelleQuantiteSousCaisse,
                            ]);
                            // enregistrer loperation a la caisse
                            OperationCaisse::create([
                                'somme' => $request-> somme,
                                'type_op' => "RETRAIT",
                                'sous_caisse' => $sousCaisse-> nom,
                                'desc' => $request-> desc,
                            ]);
                            // enregistrer loperation a la sous caisse
                            $sousCaisse->operation()->create([
                                'nom_sous_caisse' => $sousCaisse->nom,
                                'somme' => $request-> somme,
                            ]);
                            // envoyez une reponse
                            return response()->json([
                                "status" => true,
                                "reload" => true,
                                "redirect_to" => route('caisse.retrait'),
                                "title" => "TRANSFERT REUSSI",
                                "msg" => "Veuillez consultez la liste en dessous!"
                            ]);
                        }else{
                            // envoyez une reponse
                            return response()->json([
                                "status" => false,
                                "redirect_to" => route('caisse.depot'),
                                "title" => "TRANSFERT ECHOUE",
                                "msg" => "La somme totale  est insuffisante!"
                            ]);
                        }
                    }else{ //si la caisse nexiste pas
                        return response()->json([
                            "status" => false,
                            "redirect_to" => route('caisse.depot'),
                            "title" => "TRANSFERT ECHOUE",
                            "msg" => "Impossible!La caisse est vide!"
                        ]);
                    }
                }else{
                    return response()->json([
                        "status" => false,
                        "redirect_to" => '',
                        "title" => "DEPOT ECHOUE",
                        "msg" => "La somme saisie est différente de la somme confirmée"
                    ]);
                }
            }else{
                return response()->json([
                    "status" => false,
                    "redirect_to" => '',
                    "title" => "DEPOT ECHOUE",
                    "msg" => "La somme saisie ne doit pas etre inferieur a zéro"
                ]);
            }
        }else{
            return response()->json([
                "status" => false,
                "redirect_to" => '',
                "title" => "DEPOT ECHOUE",
                "msg" => "Choisissez la sous agence"
            ]);
        }
    }
}
