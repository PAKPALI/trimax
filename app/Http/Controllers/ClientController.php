<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\OperationClient;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function accueil()
    {
        $Client = Client::count();
        $totalOperationPret = OperationClient::where('type_Op', 'PRET')->sum('somme');
        $totalOperationRemb = OperationClient::where('type_Op', 'REMB')->sum('somme');
        $somme_restante = $totalOperationPret - $totalOperationRemb;

        return view('client.accueil',[
            'Client' => $Client,
            'totalOperationPret' => $totalOperationPret,
            'totalOperationRemb' => $totalOperationRemb,
            'somme_restante' => $somme_restante,
        ]);
    }

    public function client()
    {
        $Client = Client::all();

        return view('client.client',[
            'Client' => $Client,
        ]);
    }

    public function pret()
    {
        $Client = Client::all();
        $operation = OperationClient::where('type_op', "pret") ->get();
        return view('client.pret',[
            'Operation' => $operation,
            'Client' => $Client,
        ]);
    }

    public function remb()
    {
        $Client = Client::all();
        $operation = OperationClient::where('type_op', "remb") ->get();
        return view('client.remb',[
            'Operation' => $operation,
            'Client' => $Client,
        ]);
    }

    public function ajouter(Request $request)
    {
        $error_messages = [
            "nom.required" => "Remplir le champ Nom!",
            "prenom.required" => "Remplir le champ Prenom!",
            "tel.required" => "Remplir le champ Telephone!",
            "quartier.required" => "Remplir le champ Quartier!",
        ];

        $validator = Validator::make($request->all(),[
            'nom' => ['required'],
            'prenom' => ['required'],
            'tel' => ['required'],
            'quartier' => ['required'],
        ], $error_messages);

        if($validator->fails())
            return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "AJOUT ECHOUE",
            "msg" => $validator->errors()->first()]);

        Client::create([
            'nom' => $request-> nom,
            'prenom' => $request-> prenom,
            'tel' => $request-> tel,
            'quartier' => $request-> quartier,
        ]);

        return response()->json([
            "status" => true,
            "reload" => true,
            "redirect_to" => route('client'),
            "title" => "AJOUT REUSSI",
            "msg" => "L'utilisateur au nom de ".$request-> nom." a bien été ajouté"
        ]);
    }

    public function update( Request $request)
    {
        $error_messages = [
            "nom.required" => "Remplir le champ Nom!",
            "prenom.required" => "Remplir le champ Prenom!",
            "tel.required" => "Remplir le champ Telephone!",
            "quartier.required" => "Remplir le champ Quartier!",
        ];

        $validator = Validator::make($request->all(),[
            'nom' => ['required'],
            'prenom' => ['required'],
            'tel' => ['required'],
            'quartier' => ['required'],
        ], $error_messages);

        if($validator->fails())
            return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "MIS A JOUR ERRONE",
            "msg" => $validator->errors()->first()]);
    
        $search = Client::find($request-> id);
        if($search){
            $search -> update([
                'nom' => $request-> nom,
                'prenom' => $request-> prenom,
                'tel' => $request-> tel,
                'quartier' => $request-> quartier,
            ]);
            return response()->json([
                "status" => true,
                "reload" => true,
                "redirect_to" => route('client'),
                "title" => "MIS A JOUR REUSSIE",
                "msg" => "Mis a jour reussie"
            ]);
        }
    }

    public function delete(Request $request)
    {

        $error_messages = [
            "id.required" => "Remplir le champ id!",
            "id.numeric" => "Remplir le champ id avec les chiffres!",
        ];

        $validator = Validator::make($request->all(),[
            'id' => 'required| numeric'
        ], $error_messages);

        if($validator->fails())
            return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "SUPPRESSION",
            "msg" => $validator->errors()->first()]);
        
        $id = $request-> id;
        $search = Client::find($id);
        if($search){
            $search -> delete();
            return response()->json([
                "status" => true,
                "reload" => true,
                "redirect_to" => route('client'),
                "title" => "SUPPRESSION",
                "msg" => "suppression reussie"
            ]);
        }else{
           
            return response()->json([
                "status" => false,
                "reload" => true,
                "title" => "SUPPRESSION",
                "msg" => "Utilisateur inexistant"
            ]);
        }
    }

    public function ajouterPret(Request $request)
    {
        $error_messages = [
            "client.required" => "Selectionnez le client",
            "somme.required" => "Saisissez la somme!",
            // "somme.numeric" => "La somme doit etre numerique!",
            "confirmersomme.required" => "Confirmer la somme!",
            "confirmersomme.numeric" => "La somme doit etre numerique!",
            "desc.required" => "Saisissez la description!",
        ];

        $validator = Validator::make($request->all(),[
            'client' => ['required'],
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
        $client = Client::find($request-> client);
        // on verifie voir si les sommes sont identiques

        if(str_replace(" ", "", $request-> somme)>=0){
            if(str_replace(" ", "", $request-> somme) == str_replace(" ", "", $request-> confirmersomme)){
                $nouvelleQuantite = $client -> somme + str_replace(" ", "", $request-> somme);

                $client -> update([
                    'somme' => $nouvelleQuantite,
                ]);
                
                OperationClient::create([
                    'somme' => str_replace(" ", "", $request-> somme),
                    'type_op' => "PRET",
                    'client_id' => $request-> client,
                    'user_id' => $request-> user_id,
                    'desc' => $request-> desc,
                ]);
                // envoyez une reponse
                return response()->json([
                    "status" => true,
                    "reload" => true,
                    "redirect_to" => route('client.pret'),
                    "title" => "PRET REUSSI",
                    "msg" => ""
                ]);
            }else{
                return response()->json([
                    "status" => false,
                    "redirect_to" => '',
                    "title" => "PRET ECHOUE",
                    "msg" => "La somme saisie est différente de la somme confirmée"
                ]);
            }
        }else{
            return response()->json([
                "status" => false,
                "redirect_to" => '',
                "title" => "PRET ECHOUE",
                "msg" => "La somme saisie ne doit pas etre inferieur a zéro"
            ]);
        }
    }

    public function ajouterRemb(Request $request)
    {
        $error_messages = [
            "client.required" => "Selectionnez le client",
            "somme.required" => "Saisissez la somme!",
            // "somme.numeric" => "La somme doit etre numerique!",
            "confirmersomme.required" => "Confirmer la somme!",
            "confirmersomme.numeric" => "La somme doit etre numerique!",
            "desc.required" => "Saisissez la description!",
        ];

        $validator = Validator::make($request->all(),[
            'client' => ['required'],
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
        $client = Client::find($request-> client);
        // on verifie voir si les sommes sont identiques

        if(str_replace(" ", "", $request-> somme)>=0){
            if(str_replace(" ", "", $request-> somme) == str_replace(" ", "", $request-> confirmersomme)){
                if($client -> somme >0){
                    $nouvelleQuantite = $client -> somme - str_replace(" ", "", $request-> somme);

                    $client -> update([
                        'somme' => $nouvelleQuantite,
                    ]);
                    
                    OperationClient::create([
                        'somme' => str_replace(" ", "", $request-> somme),
                        'type_op' => "REMB",
                        'client_id' => $request-> client,
                        'user_id' => $request-> user_id,
                        'desc' => $request-> desc,
                    ]);
                    // envoyez une reponse
                    return response()->json([
                        "status" => true,
                        "reload" => true,
                        "redirect_to" => route('client.remb'),
                        "title" => "REMBOURSEMENT REUSSI",
                        "msg" => ""
                    ]);

                }else{
                    return response()->json([
                        "status" => false,
                        "redirect_to" => '',
                        "title" => "REMBOURSEMENT ECHOUE",
                        "msg" => "Le client ".$client->nom." ne detient aucun pret en cours",
                    ]);
                }
            }else{
                return response()->json([
                    "status" => false,
                    "redirect_to" => '',
                    "title" => "REMBOURSEMENT ECHOUE",
                    "msg" => "La somme saisie est différente de la somme confirmée"
                ]);
            }
        }else{
            return response()->json([
                "status" => false,
                "redirect_to" => '',
                "title" => "REMBOURSEMENT ECHOUE",
                "msg" => "La somme saisie ne doit pas etre inferieur a zéro"
            ]);
        }
    }

    public function operation()
    {
        $TypeErreur = "";
        $Message= "";

        $Operation = OperationClient::all();

        $somme_total_pret = $Operation -> where('type_op', "PRET")->sum('somme');
        $somme_total_remb = $Operation -> where('type_op', "REMB")->sum('somme');
        $somme_restante = $somme_total_pret - $somme_total_remb;

        $Client = Client::all();
        return view('client.operation',compact('TypeErreur','Message'),[
            'Operation' => $Operation,
            'Client' => $Client,
            'somme_total_pret' => $somme_total_pret,
            'somme_total_remb' => $somme_total_remb,
            'somme_restante' => $somme_restante,
        ]);
    }

    public function filterTable(Request $request)
    {
        $this -> validate ($request,[
            'type' => ['required'],
            'client' => ['required'],
            // 'Date1' => ['required'],
            // 'Date2' => ['required'],
        ],$error_messages = [
            "type.required" => "Selectionnez le type d'operation!",
            "client.required" => "Selectionnez le client!",
            // "Date1.required" => "Veuillez remplir le champ date de début !",
            // "Date2.required" => "Veuillez remplir le champ date de fin !",
        ]);

        $typeOp = $request-> type;
        $client_id = $request-> client;
        $Date1 = $request-> Date1;
        $Date2 = $request-> Date2;
        $Client = Client::all();

        if($typeOp == "TOUT"){
            if($typeOp AND !$Date1 AND !$Date2){
                $Operation = OperationClient::orderBy('created_at', 'desc')->where('client_id', $client_id)->get();

                $somme_total_pret = $Operation -> where('type_op', "PRET")->sum('somme');
                $somme_total_remb = $Operation -> where('type_op', "REMB")->sum('somme');
                $somme_restante = $somme_total_pret - $somme_total_remb;

                if($Operation -> count() > "0"){

                    $TypeErreur = "2";
                    $Message= "Résultat trouvé! consultez le tableau en dessous!";

                    return view('client.operation',compact('TypeErreur','Message'),[
                        'Operation' => $Operation,
                        'Client' => $Client,
                        'somme_total_pret' => $somme_total_pret,
                        'somme_total_remb' => $somme_total_remb,
                        'somme_restante' => $somme_restante,
                    ]);

                }else{
                    $TypeErreur = "1";
                    $Message= "Résultat non trouvé";

                    return view('client.operation',compact('TypeErreur','Message'),[
                        'Operation' => $Operation,
                        'Client' => $Client,
                        'somme_total_pret' => '',
                        'somme_total_remb' => '',
                        'somme_restante' => '',
                    ]);
                }

            }elseif($typeOp AND $Date1 AND $Date2){
                $ConvertDate1 = Carbon::createFromFormat('m/d/Y', $request-> Date1)->toDateString();
                $ConvertDate2 = Carbon::createFromFormat('m/d/Y', $request-> Date2)->toDateString();
                if($Date1 == $Date2){
                    $Operation = OperationClient::orderBy('created_at', 'desc')
                    ->where('client_id', $client_id)->whereDate('created_at', $ConvertDate1 )->get();

                    $somme_total_pret = $Operation -> where('type_op', "PRET")->sum('somme');
                    $somme_total_remb = $Operation -> where('type_op', "REMB")->sum('somme');
                    $somme_restante = $somme_total_pret - $somme_total_remb;

                    if($Operation -> count() > "0"){

                        $TypeErreur = "2";
                        $Message= "Résultat trouvé! consultez le tableau en dessous!";
    
                        return view('client.operation',compact('TypeErreur','Message'),[
                            'Operation' => $Operation,
                            'Client' => $Client,
                            'somme_total_pret' => $somme_total_pret,
                            'somme_total_remb' => $somme_total_remb,
                            'somme_restante' => $somme_restante,
                        ]);
    
                    }else{
                        $TypeErreur = "1";
                        $Message= "Résultat non trouvé";
    
                        return view('client.operation',compact('TypeErreur','Message'),[
                            'Operation' => $Operation,
                            'Client' => $Client,
                            'somme_total_pret' => '',
                            'somme_total_remb' => '',
                            'somme_restante' => '',
                        ]);
                    }
                }else{
                    if($Date1 <=$Date2){
                        $Operation = OperationClient::orderBy('created_at', 'desc')->where('client_id', $client_id)->whereBetween('created_at', [$ConvertDate1.' 00:00:00', $ConvertDate2.' 23:59:59'])->get();

                        if($Operation -> count() > "0"){

                            $somme_total_pret = $Operation -> where('type_op', "PRET")->sum('somme');
                            $somme_total_remb = $Operation -> where('type_op', "REMB")->sum('somme');
                            $somme_restante = $somme_total_pret - $somme_total_remb;

                            $TypeErreur = "2";
                            $Message= "Résultat trouvé! consultez le tableau en dessous!";
        
                            return view('client.operation',compact('TypeErreur','Message'),[
                                'Operation' => $Operation,
                                'Client' => $Client,
                                'somme_total_pret' => $somme_total_pret,
                                'somme_total_remb' => $somme_total_remb,
                                'somme_restante' => $somme_restante,
                            ]);
        
                        }else{
                            $TypeErreur = "1";
                            $Message= "Résultat non trouvé";
        
                            return view('client.operation',compact('TypeErreur','Message'),[
                                'Operation' => $Operation,
                                'Client' => $Client,
                                'somme_total_pret' => '',
                                'somme_total_remb' => '',
                                'somme_restante' => '',
                            ]);
                        }
                    }else{
                        $TypeErreur = "1";
                        $Message= "La date de fin doit etre superieur a la date de debut ; Le sytème enverra la liste de toutes les opérations concernant le type d'opération et le client  choisit consultable dans le tableau ci dessous!";

                        $Operation = OperationClient::orderBy('created_at', 'desc')->where('client_id', $client_id)->get();

                        $somme_total_pret = $Operation -> where('type_op', "PRET")->sum('somme');
                        $somme_total_remb = $Operation -> where('type_op', "REMB")->sum('somme');
                        $somme_restante = $somme_total_pret - $somme_total_remb;

                        return view('client.operation',compact('TypeErreur','Message'),[
                            'Operation' => $Operation,
                            'Client' => $Client,
                            'somme_total_pret' => $somme_total_pret,
                            'somme_total_remb' => $somme_total_remb,
                            'somme_restante' => $somme_restante,
                        ]);
                    }
                }
            }else{
                $Operation = OperationClient::orderBy('created_at', 'desc')->where('client_id', $client_id)->get();

                $somme_total_pret = $Operation -> where('type_op', "PRET")->sum('somme');
                $somme_total_remb = $Operation -> where('type_op', "REMB")->sum('somme');
                $somme_restante = $somme_total_pret - $somme_total_remb;

                $TypeErreur = "1";
                $Message = "Les deux champs Date de début et Date de fin doivent être rempli pour un meilleur rendement! Le sytème enverra la liste de toutes les opérations concernant le type d'opération et le client  choisit consultable dans le tableau ci dessous!";

                return view('client.operation',compact('TypeErreur','Message'),[
                    'Operation' => $Operation,
                    'Client' => $Client,
                    'somme_total_pret' => $somme_total_pret,
                    'somme_total_remb' => $somme_total_remb,
                    'somme_restante' => $somme_restante,
                ]);
            }
        }elseif($typeOp == "PRET"){
            if($typeOp AND !$Date1 AND !$Date2){
                $Operation = OperationClient::orderBy('created_at', 'desc')
                ->where('client_id', $client_id)-> where('type_Op', $typeOp)->get();

                $somme_total_pret = $Operation -> where('type_op', "PRET")->sum('somme');
                $somme_total_remb = $Operation -> where('type_op', "REMB")->sum('somme');
                $somme_restante = $somme_total_pret - $somme_total_remb;

                if($Operation -> count() > "0"){

                    $TypeErreur = "2";
                    $Message= "Résultat trouvé! consultez le tableau en dessous!";

                    return view('client.operation',compact('TypeErreur','Message'),[
                        'Operation' => $Operation,
                        'Client' => $Client,
                        'somme_total_pret' => $somme_total_pret,
                        'somme_total_remb' => $somme_total_remb,
                        'somme_restante' => $somme_restante,
                    ]);

                }else{
                    $TypeErreur = "1";
                    $Message= "Résultat non trouvé";

                    return view('client.operation',compact('TypeErreur','Message'),[
                        'Operation' => $Operation,
                        'Client' => $Client,
                        'somme_total_pret' => '',
                        'somme_total_remb' => '',
                        'somme_restante' => '',
                    ]);
                }

            }elseif($typeOp AND $Date1 AND $Date2){
                $ConvertDate1 = Carbon::createFromFormat('m/d/Y', $request-> Date1)->toDateString();
                $ConvertDate2 = Carbon::createFromFormat('m/d/Y', $request-> Date2)->toDateString();
                if($Date1 == $Date2){
                    $Operation = OperationClient::orderBy('created_at', 'desc')
                    ->where('client_id', $client_id)-> where('type_Op', $typeOp)
                    ->whereDate('created_at', $ConvertDate1 )->get();

                    $somme_total_pret = $Operation -> where('type_op', "PRET")->sum('somme');
                    $somme_total_remb = $Operation -> where('type_op', "REMB")->sum('somme');
                    $somme_restante = $somme_total_pret - $somme_total_remb;

                    if($Operation -> count() > "0"){

                        $TypeErreur = "2";
                        $Message= "Résultat trouvé! consultez le tableau en dessous!";
    
                        return view('client.operation',compact('TypeErreur','Message'),[
                            'Operation' => $Operation,
                            'Client' => $Client,
                            'somme_total_pret' => $somme_total_pret,
                            'somme_total_remb' => $somme_total_remb,
                            'somme_restante' => $somme_restante,
                        ]);
    
                    }else{
                        $TypeErreur = "1";
                        $Message= "Résultat non trouvé";
    
                        return view('client.operation',compact('TypeErreur','Message'),[
                            'Operation' => $Operation,
                            'Client' => $Client,
                            'somme_total_pret' => '',
                            'somme_total_remb' => '',
                            'somme_restante' => '',
                        ]);
                    }
                }else{
                    if($Date1 <=$Date2){
                        $Operation = OperationClient::orderBy('created_at', 'desc')
                        ->where('client_id', $client_id)->where('type_Op', $typeOp)
                        ->whereBetween('created_at', [$ConvertDate1.' 00:00:00', $ConvertDate2.' 23:59:59'])->get();

                        $somme_total_pret = $Operation -> where('type_op', "PRET")->sum('somme');
                        $somme_total_remb = $Operation -> where('type_op', "REMB")->sum('somme');
                        $somme_restante = $somme_total_pret - $somme_total_remb;

                        if($Operation -> count() > "0"){

                            $TypeErreur = "2";
                            $Message= "Résultat trouvé! consultez le tableau en dessous!";
        
                            return view('client.operation',compact('TypeErreur','Message'),[
                                'Operation' => $Operation,
                                'Client' => $Client,
                                'somme_total_pret' => $somme_total_pret,
                                'somme_total_remb' => $somme_total_remb,
                                'somme_restante' => $somme_restante,
                            ]);
        
                        }else{
                            $TypeErreur = "1";
                            $Message= "Résultat non trouvé";
        
                            return view('client.operation',compact('TypeErreur','Message'),[
                                'Operation' => $Operation,
                                'Client' => $Client,
                                'somme_total_pret' => '',
                                'somme_total_remb' => '',
                                'somme_restante' => '',
                            ]);
                        }
                    }else{
                        $TypeErreur = "1";
                        $Message= "La date de fin doit etre superieur a la date de debut ; Le sytème enverra la liste de toutes les opérations concernant le type d'opération et le client choisit consultable dans le tableau ci dessous!";

                        $Operation = OperationClient::orderBy('created_at', 'desc')
                        ->where('client_id', $client_id)->where('type_Op', $typeOp)->get();

                        $somme_total_pret = $Operation -> where('type_op', "PRET")->sum('somme');
                        $somme_total_remb = $Operation -> where('type_op', "REMB")->sum('somme');
                        $somme_restante = $somme_total_pret - $somme_total_remb;

                        return view('client.operation',compact('TypeErreur','Message'),[
                            'Operation' => $Operation,
                            'Client' => $Client,
                            'somme_total_pret' => $somme_total_pret,
                            'somme_total_remb' => $somme_total_remb,
                            'somme_restante' => $somme_restante,
                        ]);
                    }
                }
            }else{
                $Operation = OperationClient::orderBy('created_at', 'desc')
                ->where('client_id', $client_id)->where('type_Op', $typeOp)->get();

                $somme_total_pret = $Operation -> where('type_op', "PRET")->sum('somme');
                $somme_total_remb = $Operation -> where('type_op', "REMB")->sum('somme');
                $somme_restante = $somme_total_pret - $somme_total_remb;

                $TypeErreur = "1";
                $Message = "Les deux champs Date de début et Date de fin doivent être rempli pour un meilleur rendement! Le sytème enverra la liste de toutes les opérations concernant le type d'opération et le client  choisit consultable dans le tableau ci dessous!";

                return view('client.operation',compact('TypeErreur','Message'),[
                    'Operation' => $Operation,
                    'Client' => $Client,
                    'somme_total_pret' => $somme_total_pret,
                    'somme_total_remb' => $somme_total_remb,
                    'somme_restante' => $somme_restante,
                ]);
            }
        }else{
            if($typeOp AND !$Date1 AND !$Date2){
                $Operation = OperationClient::orderBy('created_at', 'desc')
                ->where('client_id', $client_id)-> where('type_Op', $typeOp)->get();

                $somme_total_pret = $Operation -> where('type_op', "PRET")->sum('somme');
                $somme_total_remb = $Operation -> where('type_op', "REMB")->sum('somme');
                $somme_restante = $somme_total_pret - $somme_total_remb;

                if($Operation -> count() > "0"){

                    $TypeErreur = "2";
                    $Message= "Résultat trouvé! consultez le tableau en dessous!";

                    return view('client.operation',compact('TypeErreur','Message'),[
                        'Operation' => $Operation,
                        'Client' => $Client,
                        'somme_total_pret' => $somme_total_pret,
                        'somme_total_remb' => $somme_total_remb,
                        'somme_restante' => $somme_restante,
                    ]);

                }else{
                    $TypeErreur = "1";
                    $Message= "Résultat non trouvé";

                    return view('client.operation',compact('TypeErreur','Message'),[
                        'Operation' => $Operation,
                        'Client' => $Client,
                        'somme_total_pret' => '',
                        'somme_total_remb' => '',
                        'somme_restante' => '',
                    ]);
                }

            }elseif($typeOp AND $Date1 AND $Date2){
                $ConvertDate1 = Carbon::createFromFormat('m/d/Y', $request-> Date1)->toDateString();
                $ConvertDate2 = Carbon::createFromFormat('m/d/Y', $request-> Date2)->toDateString();
                if($Date1 == $Date2){
                    $Operation = OperationClient::orderBy('created_at', 'desc')
                    ->where('client_id', $client_id)-> where('type_Op', $typeOp)
                    ->whereDate('created_at', $ConvertDate1 )->get();

                    $somme_total_pret = $Operation -> where('type_op', "PRET")->sum('somme');
                    $somme_total_remb = $Operation -> where('type_op', "REMB")->sum('somme');
                    $somme_restante = $somme_total_pret - $somme_total_remb;

                    if($Operation -> count() > "0"){

                        $TypeErreur = "2";
                        $Message= "Résultat trouvé! consultez le tableau en dessous!";
    
                        return view('client.operation',compact('TypeErreur','Message'),[
                            'Operation' => $Operation,
                            'Client' => $Client,
                            'somme_total_pret' => $somme_total_pret,
                            'somme_total_remb' => $somme_total_remb,
                            'somme_restante' => $somme_restante,
                        ]);
    
                    }else{
                        $TypeErreur = "1";
                        $Message= "Résultat non trouvé";
    
                        return view('client.operation',compact('TypeErreur','Message'),[
                            'Operation' => $Operation,
                            'Client' => $Client,
                            'somme_total_pret' => '',
                            'somme_total_remb' => '',
                            'somme_restante' => '',
                        ]);
                    }
                }else{
                    if($Date1 <=$Date2){
                        $Operation = OperationClient::orderBy('created_at', 'desc')
                        ->where('client_id', $client_id)->where('type_Op', $typeOp)
                        ->whereBetween('created_at', [$ConvertDate1.' 00:00:00', $ConvertDate2.' 23:59:59'])->get();

                        $somme_total_pret = $Operation -> where('type_op', "PRET")->sum('somme');
                        $somme_total_remb = $Operation -> where('type_op', "REMB")->sum('somme');
                        $somme_restante = $somme_total_pret - $somme_total_remb;

                        if($Operation -> count() > "0"){

                            $TypeErreur = "2";
                            $Message= "Résultat trouvé! consultez le tableau en dessous!";
        
                            return view('client.operation',compact('TypeErreur','Message'),[
                                'Operation' => $Operation,
                                'Client' => $Client,
                                'somme_total_pret' => $somme_total_pret,
                                'somme_total_remb' => $somme_total_remb,
                                'somme_restante' => $somme_restante,
                            ]);
        
                        }else{
                            $TypeErreur = "1";
                            $Message= "Résultat non trouvé";
        
                            return view('client.operation',compact('TypeErreur','Message'),[
                                'Operation' => $Operation,
                                'Client' => $Client,
                                'somme_total_pret' => '',
                                'somme_total_remb' => '',
                                'somme_restante' => '',
                            ]);
                        }
                    }else{
                        $TypeErreur = "1";
                        $Message= "La date de fin doit etre superieur a la date de debut ; Le sytème enverra la liste de toutes les opérations concernant le type d'opération et le client choisit consultable dans le tableau ci dessous!";

                        $Operation = OperationClient::orderBy('created_at', 'desc')
                        ->where('client_id', $client_id)->where('type_Op', $typeOp)->get();

                        $somme_total_pret = $Operation -> where('type_op', "PRET")->sum('somme');
                        $somme_total_remb = $Operation -> where('type_op', "REMB")->sum('somme');
                        $somme_restante = $somme_total_pret - $somme_total_remb;

                        return view('client.operation',compact('TypeErreur','Message'),[
                            'Operation' => $Operation,
                            'Client' => $Client,
                            'somme_total_pret' => $somme_total_pret,
                            'somme_total_remb' => $somme_total_remb,
                            'somme_restante' => $somme_restante,
                        ]);
                    }
                }
            }else{
                $Operation = OperationClient::orderBy('created_at', 'desc')
                ->where('client_id', $client_id)->where('type_Op', $typeOp)->get();

                $somme_total_pret = $Operation -> where('type_op', "PRET")->sum('somme');
                $somme_total_remb = $Operation -> where('type_op', "REMB")->sum('somme');
                $somme_restante = $somme_total_pret - $somme_total_remb;

                $TypeErreur = "1";
                $Message = "Les deux champs Date de début et Date de fin doivent être rempli pour un meilleur rendement! Le sytème enverra la liste de toutes les opérations concernant le type d'opération et le client choisit consultable dans le tableau ci dessous!";

                return view('client.operation',compact('TypeErreur','Message'),[
                    'Operation' => $Operation,
                    'Client' => $Client,
                    'somme_total_pret' => $somme_total_pret,
                    'somme_total_remb' => $somme_total_remb,
                    'somme_restante' => $somme_restante,
                ]);
            }
        }
    }
}
