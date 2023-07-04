<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Banque;
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
        $Banque = Banque::all();
        $caisse = Caisse::first();
        $somme_init = 0;

        $operation = OperationCaisse::where('type_op', "depot") ->get();

        if($caisse){
            return view('caisse.depot',[
                'somme' => $caisse->somme,
                'Operation' => $operation,
                'Banque' => $Banque,
            ]);
        }else{
            return view('caisse.depot',[
                'somme' => $somme_init,
                'Operation' => $operation,
                'Banque' => $Banque,
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
            // "somme.numeric" => "La somme doit etre numerique!",
            "confirmersomme.required" => "Confirmer la somme!",
            "confirmersomme.numeric" => "La somme doit etre numerique!",
            "desc.required" => "Saisissez la description!",
        ];

        $validator = Validator::make($request->all(),[
            'banque' => ['required'],
            'somme' => ['required'],
            'confirmersomme' => ['required'],
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

        if(str_replace(" ", "", $request-> somme)>=0){
            if(str_replace(" ", "", $request-> somme) == str_replace(" ", "", $request-> confirmersomme)){
                // si la caisse existe
                if($caisse){
                    // operation pour avoir la nouvelle quantitée
                    $nouvelleQuantite = $caisse -> somme + str_replace(" ", "", $request-> somme);
                    // mise a jour sur la caisse
                    $caisse -> update([
                        'somme' => $nouvelleQuantite,
                    ]);
                    // enrgistrer loperation
                    OperationCaisse::create([
                        'somme' => str_replace(" ", "", $request-> somme),
                        'type_op' => "DEPOT",
                        'banque_id' => $request-> banque,
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
                        'somme' => str_replace(" ", "", $request-> somme),
                    ]);
                    // enrgistrer loperation
                    OperationCaisse::create([
                        'somme' => str_replace(" ", "", $request-> somme),
                        'type_op' => "DEPOT",
                        'banque_id' => $request-> banque,
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
            // "somme.numeric" => "La somme doit etre numerique!",
            "confirmersomme.required" => "Confirmer la somme!",
            // "confirmersomme.numeric" => "La somme doit etre numerique!",
            "desc.required" => "Saisissez la description!",
        ];

        $validator = Validator::make($request->all(),[
            'somme' => ['required'],
            'confirmersomme' => ['required'],
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
        $somme = str_replace(" ", "", $request-> somme);

        if($request-> selection){
            // on verifie voir si les sommes sont identiques
            if($somme >=0){
                if($somme == str_replace(" ", "", $request-> confirmersomme)){
                    // si la caisse existe
                    if($caisse){
                        // operation pour avoir la nouvelle quantitée de la caisse
                        $nouvelleQuantiteCaisse = $caisse -> somme - $somme;
                        // verifier si la qte saisie est inferieure  a la somme dans la caisse
                        if($nouvelleQuantiteCaisse>=0){
                            // mise a jour sur la caisse
                            $caisse -> update([
                                'somme' => $nouvelleQuantiteCaisse,
                            ]);
                            // operation pour avoir la nouvelle quantitée de la sous caisse
                            $nouvelleQuantiteSousCaisse = $sousCaisse -> somme + $somme;
                            $sousCaisse -> update([
                                'somme' => $nouvelleQuantiteSousCaisse,
                            ]);
                            // enregistrer loperation a la caisse
                            OperationCaisse::create([
                                'somme' => $somme,
                                'type_op' => "RETRAIT",
                                'sous_caisse' => $sousCaisse-> nom,
                                'desc' => $request-> desc,
                            ]);
                            // enregistrer loperation a la sous caisse
                            $sousCaisse->operation()->create([
                                'nom_sous_caisse' => $sousCaisse->nom,
                                'somme' => $somme,
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
                "msg" => "Choisissez la sous caisse"
            ]);
        }
    }

    public function operation()
    {
        $TypeErreur = "";
        $Message= "";

        $Operation = OperationCaisse::all();
        return view('caisse.operation',compact('TypeErreur','Message'),[
            'Operation' => $Operation,
        ]);
    }

    public function filterTable(Request $request)
    {
        $this -> validate ($request,[
            'type' => ['required'],
            // 'Date1' => ['required'],
            // 'Date2' => ['required'],
        ],$error_messages = [
            "type.required" => "Selectionnez le type d'operation!",
            // "Date1.required" => "Veuillez remplir le champ date de début !",
            // "Date2.required" => "Veuillez remplir le champ date de fin !",
        ]);

        $typeOp = $request-> type;
        $Date1 = $request-> Date1;
        $Date2 = $request-> Date2;

        if($typeOp == "TOUT"){
            if($typeOp AND !$Date1 AND !$Date2){
                $Operation = OperationCaisse::orderBy('created_at', 'desc')->get();
                if($Operation -> count() > "0"){
                    $qte_total_depot = $Operation -> where('type_op', "DEPOT")->sum('somme');
                    $qte_total_retrait = $Operation -> where('type_op', "RETRAIT")->sum('somme');
                    $qte_restante = $qte_total_depot - $qte_total_retrait;

                    $TypeErreur = "2";
                    $Message= "Résultat trouvé! consultez le tableau en dessous!";

                    return view('caisse.operation',compact('TypeErreur','Message'),[
                        'Operation' => $Operation,
                        'qte_total_depot' => $qte_total_depot,
                        'qte_total_retrait' => $qte_total_retrait,
                        'qte_restante' => $qte_restante,
                    ]);

                }else{
                    $TypeErreur = "1";
                    $Message= "Résultat non trouvé";

                    return view('caisse.operation',compact('TypeErreur','Message'),[
                        'Operation' => $Operation,
                        'qte_total_depot' => "",
                        'qte_total_retrait' =>"" ,
                        'qte_restante' => "",
                    ]);
                }

            }elseif($typeOp AND $Date1 AND $Date2){
                $ConvertDate1 = Carbon::createFromFormat('m/d/Y', $request-> Date1)->toDateString();
                $ConvertDate2 = Carbon::createFromFormat('m/d/Y', $request-> Date2)->toDateString();
                if($Date1 == $Date2){
                    $Operation = OperationCaisse::orderBy('created_at', 'desc')->whereDate('created_at', $ConvertDate1 )->get();

                    if($Operation -> count() > "0"){
                        $qte_total_depot = $Operation -> where('type_operation', "DEPOT")->sum('somme');
                        $qte_total_retrait = $Operation -> where('type_operation', "RETRAIT")->sum('somme');
                        $qte_restante = $qte_total_depot - $qte_total_retrait;

                        $TypeErreur = "2";
                        $Message= "Résultat trouvé! consultez le tableau en dessous!";

                        return view('caisse.operation',compact('TypeErreur','Message'),[
                            'Operation' => $Operation,
                            'qte_total_depot' => $qte_total_depot,
                            'qte_total_retrait' => $qte_total_retrait,
                            'qte_restante' => $qte_restante,
                        ]);
                    }else{
                        $TypeErreur = "1";
                        $Message= "Résultat non trouvé";

                        return view('caisse.operation',compact('TypeErreur','Message'),[
                            'Operation' => $Operation,
                            'qte_total_depot' => "",
                            'qte_total_retrait' => "",
                            'qte_restante' => "",
                        ]);
                    }
                }else{
                    if($Date1 <=$Date2){
                        $Operation = OperationCaisse::orderBy('created_at', 'desc')->whereBetween('created_at', [$ConvertDate1.' 00:00:00', $ConvertDate2.' 23:59:59'])->get();

                        if($Operation -> count() > "0"){
                            $qte_total_depot = $Operation -> where('type_operation', "DEPOT")->sum('somme');
                            $qte_total_retrait = $Operation -> where('type_operation', "RETRAIT")->sum('somme');
                            $qte_restante = $qte_total_depot - $qte_total_retrait;

                            $TypeErreur = "2";
                            $Message= "Résultat trouvé! consultez le tableau en dessous!";

                            return view('caisse.operation',compact('TypeErreur','Message'),[
                                'Operation' => $Operation,
                                'qte_total_depot' => $qte_total_depot,
                                'qte_total_retrait' => $qte_total_retrait,
                                'qte_restante' => $qte_restante,
                            ]);
                        }else{
                            $TypeErreur = "1";
                            $Message= "Résultat non trouvé";

                            return view('caisse.operation',compact('TypeErreur','Message'),[
                                'Operation' => $Operation,
                                'qte_total_depot' => "",
                                'qte_total_retrait' =>"",
                                'qte_restante' => "",
                            ]);
                        }
                    }else{
                        $TypeErreur = "1";
                        $Message= "La date de fin doit etre superieur a la date de debut ; Le sytème enverra la liste de toutes les opérations concernant le type d'opération choisit consultable dans le tableau ci dessous!";

                        $Operation = OperationCaisse::orderBy('created_at', 'desc')->get();

                        return view('caisse.operation',compact('TypeErreur','Message'),[
                            'Operation' => $Operation,
                            'qte_total_depot' => "",
                            'qte_total_retrait' =>"",
                            'qte_restante' => "",
                        ]);
                    }
                }
            }else{
                $Operation = OperationCaisse::orderBy('created_at', 'desc')->get();

                $TypeErreur = "1";
                $Message = "Les deux champs Date de début et Date de fin doivent être rempli pour un meilleur rendement! Le sytème enverra la liste de toutes les opérations concernant le type d'opération choisit consultable dans le tableau ci dessous!";

                return view('caisse.operation',compact('TypeErreur','Message'),[
                    'Operation' => $Operation,
                    'qte_total_depot' => "",
                    'qte_total_retrait' =>"" ,
                    'qte_restante' => "",
                ]);
            }
        }elseif($typeOp == "DEPOT"){
            if($typeOp AND !$Date1 AND !$Date2){
                $Operation = OperationCaisse::orderBy('created_at', 'desc')-> where('type_Op', $typeOp)->get();
                if($Operation -> count() > "0"){
                    $qte_total_depot = $Operation -> where('type_op', "DEPOT")->sum('somme');
                    $qte_total_retrait = $Operation -> where('type_op', "RETRAIT")->sum('somme');
                    $qte_restante = $qte_total_depot - $qte_total_retrait;

                    $TypeErreur = "2";
                    $Message= "Résultat trouvé! consultez le tableau en dessous!";

                    return view('caisse.operation',compact('TypeErreur','Message'),[
                        'Operation' => $Operation,
                        'qte_total_depot' => $qte_total_depot,
                        'qte_total_retrait' => $qte_total_retrait,
                        'qte_restante' => $qte_restante,
                    ]);
                }else{
                    $TypeErreur = "1";
                    $Message= "Résultat non trouvé";

                    return view('caisse.operation',compact('TypeErreur','Message'),[
                        'qte_total_depot' => "",
                        'qte_total_retrait' =>"" ,
                        'qte_restante' => "",
                    ]);
                }

            }elseif($typeOp AND $Date1 AND $Date2){
                $ConvertDate1 = Carbon::createFromFormat('m/d/Y', $request-> Date1)->toDateString();
                $ConvertDate2 = Carbon::createFromFormat('m/d/Y', $request-> Date2)->toDateString();
                if($Date1 == $Date2){
                    $Operation = OperationCaisse::orderBy('created_at', 'desc')-> where('type_Op', $typeOp)->whereDate('created_at', $ConvertDate1 )->get();

                    if($Operation -> count() > "0"){
                        $qte_total_depot = $Operation -> where('type_operation', "DEPOT")->sum('somme');
                        $qte_total_retrait = $Operation -> where('type_operation', "RETRAIT")->sum('somme');
                        $qte_restante = $qte_total_depot - $qte_total_retrait;

                        $TypeErreur = "2";
                        $Message= "Résultat trouvé! consultez le tableau en dessous!";

                        return view('caisse.operation',compact('TypeErreur','Message'),[
                            'Operation' => $Operation,
                            'qte_total_depot' => $qte_total_depot,
                            'qte_total_retrait' => $qte_total_retrait,
                            'qte_restante' => $qte_restante,
                        ]);
                    }else{
                        $TypeErreur = "1";
                        $Message= "Résultat non trouvé";

                        return view('caisse.operation',compact('TypeErreur','Message'),[
                            'Operation' => $Operation,
                            'qte_total_depot' => "",
                            'qte_total_retrait' => "",
                            'qte_restante' => "",
                        ]);
                    }
                }else{
                    if($Date1 <=$Date2){
                        $Operation = OperationCaisse::orderBy('created_at', 'desc')->where('type_Op', $typeOp)->whereBetween('created_at', [$ConvertDate1.' 00:00:00', $ConvertDate2.' 23:59:59'])->get();

                        if($Operation -> count() > "0"){
                            $qte_total_depot = $Operation -> where('type_operation', "DEPOT")->sum('somme');
                            $qte_total_retrait = $Operation -> where('type_operation', "RETRAIT")->sum('somme');
                            $qte_restante = $qte_total_depot - $qte_total_retrait;

                            $TypeErreur = "2";
                            $Message= "Résultat trouvé! consultez le tableau en dessous!";

                            return view('caisse.operation',compact('TypeErreur','Message'),[
                                'Operation' => $Operation,
                                'qte_total_depot' => $qte_total_depot,
                                'qte_total_retrait' => $qte_total_retrait,
                                'qte_restante' => $qte_restante,
                            ]);
                        }else{
                            $TypeErreur = "1";
                            $Message= "Résultat non trouvé";

                            return view('caisse.operation',compact('TypeErreur','Message'),[
                                'Operation' => $Operation,
                                'qte_total_depot' => "",
                                'qte_total_retrait' =>"",
                                'qte_restante' => "",
                            ]);
                        }
                    }else{
                        $TypeErreur = "1";
                        $Message= "La date de fin doit etre superieur a la date de debut ; Le sytème enverra la liste de toutes les opérations concernant le type d'opération choisit consultable dans le tableau ci dessous!";

                        $Operation = OperationCaisse::orderBy('created_at', 'desc')->where('type_Op', $typeOp)->get();

                        return view('caisse.operation',compact('TypeErreur','Message'),[
                            'Operation' => $Operation,
                            'qte_total_depot' => "",
                            'qte_total_retrait' =>"",
                            'qte_restante' => "",
                        ]);
                    }
                }
            }else{
                $Operation = OperationCaisse::orderBy('created_at', 'desc')->where('type_Op', $typeOp)->get();

                $TypeErreur = "1";
                $Message = "Les deux champs Date de début et Date de fin doivent être rempli pour un meilleur rendement! Le sytème enverra la liste de toutes les opérations concernant le type d'opération choisit consultable dans le tableau ci dessous!";

                return view('caisse.operation',compact('TypeErreur','Message'),[
                    'Operation' => $Operation,
                    'qte_total_depot' => "",
                    'qte_total_retrait' =>"" ,
                    'qte_restante' => "",
                ]);
            }
        }else{
            if($typeOp AND !$Date1 AND !$Date2){
                $Operation = OperationCaisse::orderBy('created_at', 'desc')-> where('type_Op', $typeOp)->get();
                if($Operation -> count() > "0"){
                    $qte_total_depot = $Operation -> where('type_op', "DEPOT")->sum('somme');
                    $qte_total_retrait = $Operation -> where('type_op', "RETRAIT")->sum('somme');
                    $qte_restante = $qte_total_depot - $qte_total_retrait;

                    $TypeErreur = "2";
                    $Message= "Résultat trouvé! consultez le tableau en dessous!";

                    return view('caisse.operation',compact('TypeErreur','Message'),[
                        'Operation' => $Operation,
                        'qte_total_depot' => $qte_total_depot,
                        'qte_total_retrait' => $qte_total_retrait,
                        'qte_restante' => $qte_restante,
                    ]);
                }else{
                    $TypeErreur = "1";
                    $Message= "Résultat non trouvé";

                    return view('caisse.operation',compact('TypeErreur','Message'),[
                        'qte_total_depot' => "",
                        'qte_total_retrait' =>"" ,
                        'qte_restante' => "",
                    ]);
                }

            }elseif($typeOp AND $Date1 AND $Date2){
                $ConvertDate1 = Carbon::createFromFormat('m/d/Y', $request-> Date1)->toDateString();
                $ConvertDate2 = Carbon::createFromFormat('m/d/Y', $request-> Date2)->toDateString();
                if($Date1 == $Date2){
                    $Operation = OperationCaisse::orderBy('created_at', 'desc')-> where('type_Op', $typeOp)->whereDate('created_at', $ConvertDate1 )->get();

                    if($Operation -> count() > "0"){
                        $qte_total_depot = $Operation -> where('type_operation', "DEPOT")->sum('somme');
                        $qte_total_retrait = $Operation -> where('type_operation', "RETRAIT")->sum('somme');
                        $qte_restante = $qte_total_depot - $qte_total_retrait;

                        $TypeErreur = "2";
                        $Message= "Résultat trouvé! consultez le tableau en dessous!";

                        return view('caisse.operation',compact('TypeErreur','Message'),[
                            'Operation' => $Operation,
                            'qte_total_depot' => $qte_total_depot,
                            'qte_total_retrait' => $qte_total_retrait,
                            'qte_restante' => $qte_restante,
                        ]);
                    }else{
                        $TypeErreur = "1";
                        $Message= "Résultat non trouvé";

                        return view('caisse.operation',compact('TypeErreur','Message'),[
                            'Operation' => $Operation,
                            'qte_total_depot' => "",
                            'qte_total_retrait' => "",
                            'qte_restante' => "",
                        ]);
                    }
                }else{
                    if($Date1 <=$Date2){
                        $Operation = OperationCaisse::orderBy('created_at', 'desc')->where('type_Op', $typeOp)->whereBetween('created_at', [$ConvertDate1.' 00:00:00', $ConvertDate2.' 23:59:59'])->get();

                        if($Operation -> count() > "0"){
                            $qte_total_depot = $Operation -> where('type_operation', "DEPOT")->sum('somme');
                            $qte_total_retrait = $Operation -> where('type_operation', "RETRAIT")->sum('somme');
                            $qte_restante = $qte_total_depot - $qte_total_retrait;

                            $TypeErreur = "2";
                            $Message= "Résultat trouvé! consultez le tableau en dessous!";

                            return view('caisse.operation',compact('TypeErreur','Message'),[
                                'Operation' => $Operation,
                                'qte_total_depot' => $qte_total_depot,
                                'qte_total_retrait' => $qte_total_retrait,
                                'qte_restante' => $qte_restante,
                            ]);
                        }else{
                            $TypeErreur = "1";
                            $Message= "Résultat non trouvé";

                            return view('caisse.operation',compact('TypeErreur','Message'),[
                                'Operation' => $Operation,
                                'qte_total_depot' => "",
                                'qte_total_retrait' =>"",
                                'qte_restante' => "",
                            ]);
                        }
                    }else{
                        $TypeErreur = "1";
                        $Message= "La date de fin doit etre superieur a la date de debut ; Le sytème enverra la liste de toutes les opérations concernant le type d'opération choisit consultable dans le tableau ci dessous!";

                        $Operation = OperationCaisse::orderBy('created_at', 'desc')->where('type_Op', $typeOp)->get();

                        return view('caisse.operation',compact('TypeErreur','Message'),[
                            'Operation' => $Operation,
                            'qte_total_depot' => "",
                            'qte_total_retrait' =>"",
                            'qte_restante' => "",
                        ]);
                    }
                }
            }else{
                $Operation = OperationCaisse::orderBy('created_at', 'desc')->where('type_Op', $typeOp)->get();

                $TypeErreur = "1";
                $Message = "Les deux champs Date de début et Date de fin doivent être rempli pour un meilleur rendement! Le sytème enverra la liste de toutes les opérations concernant le type d'opération choisit consultable dans le tableau ci dessous!";

                return view('caisse.operation',compact('TypeErreur','Message'),[
                    'Operation' => $Operation,
                    'qte_total_depot' => "",
                    'qte_total_retrait' =>"" ,
                    'qte_restante' => "",
                ]);
            }
        }
    }
}
