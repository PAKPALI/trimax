<?php

namespace App\Http\Controllers;

use App\Models\Caisse;
use Illuminate\Http\Request;
use App\Models\OperationCaisse;
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
                    "msg" => "La somme saisie est différente de la somme confirmée".$request-> somme 
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
}
