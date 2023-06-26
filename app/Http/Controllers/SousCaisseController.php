<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pays;
use App\Models\Depense;
use App\Models\SousCaisse;
use App\Models\TypeDepense;
use Illuminate\Http\Request;
use App\Models\OperationSousCaisse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SousCaisseController extends Controller
{
    public function sous_caisse()
    {
        $SousCaisse = SousCaisse::all();
        $Pays = Pays::all();

        return view('sous_caisse.sous_caisse',[
            'SC' => $SousCaisse,
            'Pays' => $Pays,
        ]);
    }

    public function demande_depense()
    {
        $user_id = Auth::user()->id;

        if(Auth::user()->type_user ==1){
            $SousCaisse = SousCaisse::all();
            $S1 = SousCaisse::find(Auth::user()->sous_caisse_id);
            $Depense = Depense::where('status', 2)->get();
            $Depense_v = Depense::where('status', 1)->get();
            $Depense_r = Depense::where('status', 0)->get();
            $TypeDepense = TypeDepense::all();

            return view('sous_caisse.demande_depense',[
                's1' => $S1,
                'SC' => $SousCaisse,
                'Depense' => $Depense,
                'Depense_v' => $Depense_v,
                'Depense_r' => $Depense_r,
                'TypeDepense' => $TypeDepense,
            ]);
        }else{
            $SousCaisse = SousCaisse::all();
            $S1 = SousCaisse::find(Auth::user()->sous_caisse_id);
            $Depense = Depense::where('status', 2)->where('user_id', $user_id)->get();
            $Depense_v = Depense::where('status', 1)->where('user_id', $user_id)->get();
            $Depense_r = Depense::where('status', 0)->where('user_id', $user_id)->get();
            $TypeDepense = TypeDepense::all();
            
            return view('sous_caisse.demande_depense',[
                's1' => $S1,
                'SC' => $SousCaisse,
                'Depense' => $Depense,
                'Depense_v' => $Depense_v,
                'Depense_r' => $Depense_r,
                'TypeDepense' => $TypeDepense,
            ]);
        }
    }

    public function demande_depense_post(Request $request)
    {
        $error_messages = [
            "selection.required" => "Choisissez la sous caisse!",
            "somme.required" => "Saisissez la somme!",
            "somme.numeric" => "La somme doit etre numerique!",
            "confirmersomme.required" => "Confirmer la somme!",
            "confirmersomme.numeric" => "La somme doit etre numerique!",
            "type.required" => "Saisissez la description!",
            "desc.required" => "Saisissez la description!",
        ];

        $validator = Validator::make($request->all(),[
            'selection' => ['required'],
            'somme' => ['required'],
            'confirmersomme' => ['required'],
            'type' => ['required'],
            'desc' => ['required'],
        ], $error_messages);

        if($validator->fails())
            return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "AJOUT ECHOUE",
            "msg" => $validator->errors()->first()]);

        // requete sur la sous caisse
        $td = TypeDepense::find($request-> type);
        $somme = str_replace(" ", "", $request-> somme);

        // on verifie voir si les sommes sont positives
        if($somme>=0){
            // on verifie voir si les sommes sont identiques
            if($somme == str_replace(" ", "", $request-> confirmersomme)){
                // enregistrer la depense
                Depense::create([
                    'sous_caisse_id' => $request-> selection,
                    'type_depense_id' => $request-> type,
                    'user_id' => $request-> user_id,
                    'somme' => $somme,
                    'type' => $td->nom,
                    'desc' => $request-> desc,
                ]);
                // envoyez une reponse
                return response()->json([
                    "status" => true,
                    "reload" => true,
                    "redirect_to" => route('sous_caisse.demande_depense'),
                    "title" => "DEMANDE REUSSIE",
                    "msg" => "Veuillez consultez la liste en dessous!"
                ]);
                
            }else{
                return response()->json([
                    "status" => false,
                    "redirect_to" => '',
                    "title" => "DEMANDE ECHOUE",
                    "msg" => "La somme saisie est différente de la somme confirmée"
                ]);
            }
        }else{
            return response()->json([
                "status" => false,
                "redirect_to" => '',
                "title" => "DEPOT ECHOUE",
                "msg" => "La somme saisie ne doit pas etre inférieur a zéro"
            ]);
        }
        
    }

    public function ajouter(Request $request)
    {
        $error_messages = [
            "nom.required" => "Veuillez saisir le Nom!",
            "nom.unique" => "Le nom ".$request-> nom. " existe deja!",
            "ville.required" => "Veuillez saisir la ville!",
            "quartier.required" => "Veuillez saisir le quartier!",
        ];

        $validator = Validator::make($request->all(),[
            'nom' => ['required','unique:sous_caisses'],
            'ville' => ['required'],
            'quartier' => ['required'],
        ], $error_messages);

        if($validator->fails())
            return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "AJOUT ECHOUE",
            "msg" => $validator->errors()->first()]);

        $pays_id = $request-> selection;
        $pays = Pays::find($pays_id);

        if($pays_id){
            SousCaisse::create([
                'pays_id' => $pays_id ,
                'nom' => $request-> nom,
                'ville' => $request-> ville,
                'quartier' => $request-> quartier,
            ]);
    
            return response()->json([
                "status" => true,
                "reload" => true,
                "redirect_to" => route('sous_caisse'),
                "title" => "AJOUT REUSSI",
                "msg" => "La sous caisse au nom de ".$request-> nom." a bien été ajoutée"
            ]);
        }else{
            return response()->json([
                "status" => false,
                "title" => "AJOUT ECHOUE",
                "msg" => "Veuillez sélectionnez le pays!"
            ]);
        }
    }

    public function update(Request $request)
    {
        $error_messages = [
            "nom.required" => "Saisir le nom!",
            "ville.required" => "Saisir le ville!",
            "quartier.required" => "Saisir le quartier!",
        ];

        $validator = Validator::make($request->all(),[
            'nom' => 'required',
            'ville' => 'required',
            'quartier' => 'required',
        ], $error_messages);

        if($validator->fails())
            return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "MIS A JOUR ERRONE",
            "msg" => $validator->errors()->first()]);
        
        $id = $request-> id;
        
        $search = SousCaisse::find($id);
        if($search){
            $search -> update([
                'nom' => $request-> nom,
                'ville' => $request-> ville,
                'quartier' =>$request-> quartier,
            ]);
            return response()->json([
                "status" => true,
                "reload" => true,
                "redirect_to" => route('sous_caisse'),
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
        $search = SousCaisse::find($id);
        if($search){
            $search -> delete();

            return response()->json([
                "status" => true,
                "reload" => true,
                "redirect_to" => route('sous_caisse'),
                "title" => "SUPPRESSION",
                "msg" => "suppression reussie"
            ]);
        }else{
           
            return response()->json([
                "status" => false,
                "reload" => true,
                "title" => "SUPPRESSION",
                "msg" => "sous caisse inexistante"
            ]);
        }
    }

    public function historique()
    {
        $sousCaisse = SousCaisse::first();
        $somme_init = 0;

        $Operation = OperationSousCaisse::all();

        if($sousCaisse){
            return view('sous_caisse.historique',[
                'somme' => $sousCaisse->somme,
                'Operation' => $Operation,
            ]);
        }else{
            return view('sous_caisse.historique',[
                'somme' => $somme_init,
                'Operation' => $Operation,
            ]);
        }
    }

    public function update_depense(Request $request)
    {
        $error_messages = [
            "desc.required" => "Saisir la description!",
        ];

        $validator = Validator::make($request->all(),[
            'desc' => 'required',
        ], $error_messages);

        if($validator->fails())
            return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "MIS A JOUR ERRONE",
            "msg" => $validator->errors()->first()]);
        
        $id = $request-> id;
        
        $search = Depense::find($id);
        if($search){
            $search -> update([
                'desc' => $request-> desc,
            ]);
            return response()->json([
                "status" => true,
                "reload" => true,
                "redirect_to" => route('sous_caisse.demande_depense'),
                "title" => "MIS A JOUR REUSSIE",
                "msg" => "Mis a jour reussie"
            ]);
        }
    }

    public function valider_depense(Request $request)
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
        $Depense = Depense::find($id);
        $sousCaisse = SousCaisse::find($Depense->sous_caisse_id);
        if($Depense){
            $nouvelleSomme = $sousCaisse->somme - $Depense->somme;

            if( $nouvelleSomme > 0){
                $sousCaisse -> update([
                    'somme' => $nouvelleSomme,
                ]);
    
                $Depense -> update([
                    'status' => '1',
                    'validateur' => Auth::user()->nom,
                ]);
    
                return response()->json([
                    "status" => true,
                    "reload" => true,
                    "redirect_to" => route('sous_caisse.demande_depense'),
                    "title" => "VALIDATION REUSSIE",
                    "msg" => "validation reussie"
                ]);

            }else{
                return response()->json([
                    "status" => false,
                    "reload" => true,
                    "title" => "VALIDATION ECHOUEE",
                    "msg" => "Le compte de la sous caisse liee a cette depense n'a pas assez de fond pour supporter cette depense"
                ]);
            }
        }else{
            return response()->json([
                "status" => false,
                "reload" => true,
                "title" => "SUPPRESSION",
                "msg" => "Depense inexistante"
            ]);
        }
    }

    public function rejeter_depense(Request $request)
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
        $Depense = Depense::find($id);

        $Depense -> update([
            'status' => '0',
        ]);

        return response()->json([
            "status" => true,
            "reload" => true,
            "redirect_to" => route('sous_caisse.demande_depense'),
            "title" => "VALIDATION REJETEE",
            "msg" => "validation rejetee avec succes"
        ]);
    }

    public function operation()
    {
        $TypeErreur = "";
        $Message= "";

        $Operation = OperationSousCaisse::all();
        $SC = SousCaisse::all();
        return view('sous_caisse.operation',compact('TypeErreur','Message'),[
            'Operation' => $Operation,
            'SC' => $SC,
        ]);
    }

    public function filterTable(Request $request)
    {
        $this -> validate ($request,[
            'sousCaisse' => ['required'],
            // 'Date1' => ['required'],
            // 'Date2' => ['required'],
        ],$error_messages = [
            "sousCaisse.required" => "Selectionnez la sous caisse!",
            // "Date1.required" => "Veuillez remplir le champ date de début !",
            // "Date2.required" => "Veuillez remplir le champ date de fin !",
        ]);

        $typeOp = $request-> type;
        $sc = $request-> sousCaisse;
        $Date1 = $request-> Date1;
        $Date2 = $request-> Date2;
        $SC = SousCaisse::all();

        if($typeOp == "TOUT"){
            if($typeOp AND !$Date1 AND !$Date2){
                $Operation = OperationSousCaisse::orderBy('created_at', 'desc')-> where('sous_caisse_id', $sc)->get();
                if($Operation -> count() > "0"){
                    $qte_total_depot = $Operation -> where('type_op', "DEPOT")->sum('somme');
                    $qte_total_retrait = $Operation -> where('type_op', "RETRAIT")->sum('somme');
                    $qte_restante = $qte_total_depot - $qte_total_retrait;

                    $TypeErreur = "2";
                    $Message= "Résultat trouvé! consultez le tableau en dessous!";

                    return view('sous_caisse.operation',compact('TypeErreur','Message'),[
                        'Operation' => $Operation,
                        'SC' => $SC,
                        'qte_total_depot' => $qte_total_depot,
                        'qte_total_retrait' => $qte_total_retrait,
                        'qte_restante' => $qte_restante,
                    ]);

                }else{
                    $TypeErreur = "1";
                    $Message= "Résultat non trouvé";

                    return view('sous_caisse.operation',compact('TypeErreur','Message'),[
                        'SC' => $SC,
                        'qte_total_depot' => "",
                        'qte_total_retrait' =>"" ,
                        'qte_restante' => "",
                    ]);
                }

            }elseif($typeOp AND $Date1 AND $Date2){
                $ConvertDate1 = Carbon::createFromFormat('m/d/Y', $request-> Date1)->toDateString();
                $ConvertDate2 = Carbon::createFromFormat('m/d/Y', $request-> Date2)->toDateString();
                if($Date1 == $Date2){
                    $Operation = OperationSousCaisse::orderBy('created_at', 'desc')-> where('sous_caisse_id', $sc)->whereDate('created_at', $ConvertDate1 )->get();

                    if($Operation -> count() > "0"){
                        $qte_total_depot = $Operation -> where('type_operation', "DEPOT")->sum('somme');
                        $qte_total_retrait = $Operation -> where('type_operation', "RETRAIT")->sum('somme');
                        $qte_restante = $qte_total_depot - $qte_total_retrait;

                        $TypeErreur = "2";
                        $Message= "Résultat trouvé! consultez le tableau en dessous!";

                        return view('sous_caisse.operation',compact('TypeErreur','Message'),[
                            'Operation' => $Operation,
                            'SC' => $SC,
                            'qte_total_depot' => $qte_total_depot,
                            'qte_total_retrait' => $qte_total_retrait,
                            'qte_restante' => $qte_restante,
                        ]);
                    }else{
                        $TypeErreur = "1";
                        $Message= "Résultat non trouvé";

                        return view('sous_caisse.operation',compact('TypeErreur','Message'),[
                            'Operation' => $Operation,
                            'SC' => $SC,
                            'qte_total_depot' => "",
                            'qte_total_retrait' => "",
                            'qte_restante' => "",
                        ]);
                    }
                }else{
                    if($Date1 <=$Date2){
                        $Operation = OperationSousCaisse::orderBy('created_at', 'desc')-> where('sous_caisse_id', $sc)->whereBetween('created_at', [$ConvertDate1.' 00:00:00', $ConvertDate2.' 23:59:59'])->get();

                        if($Operation -> count() > "0"){
                            $qte_total_depot = $Operation -> where('type_operation', "DEPOT")->sum('somme');
                            $qte_total_retrait = $Operation -> where('type_operation', "RETRAIT")->sum('somme');
                            $qte_restante = $qte_total_depot - $qte_total_retrait;

                            $TypeErreur = "2";
                            $Message= "Résultat trouvé! consultez le tableau en dessous!";

                            return view('sous_caisse.operation',compact('TypeErreur','Message'),[
                                'Operation' => $Operation,
                                'SC' => $SC,
                                'qte_total_depot' => $qte_total_depot,
                                'qte_total_retrait' => $qte_total_retrait,
                                'qte_restante' => $qte_restante,
                            ]);
                        }else{
                            $TypeErreur = "1";
                            $Message= "Résultat non trouvé";

                            return view('sous_caisse.operation',compact('TypeErreur','Message'),[
                                'Operation' => $Operation,
                                'SC' => $SC,
                                'qte_total_depot' => "",
                                'qte_total_retrait' =>"",
                                'qte_restante' => "",
                            ]);
                        }
                    }else{
                        $TypeErreur = "1";
                        $Message= "La date de fin doit etre superieur a la date de debut ; Le sytème enverra la liste de toutes les opérations concernant la sous caisse choisit consultable dans le tableau ci dessous!";

                        $Operation = OperationSousCaisse::orderBy('created_at', 'desc')->where('sous_caisse_id', $sc)->get();

                        return view('sous_caisse.operation',compact('TypeErreur','Message'),[
                            'Operation' => $Operation,
                            'SC' => $SC,
                            'qte_total_depot' => "",
                            'qte_total_retrait' =>"",
                            'qte_restante' => "",
                        ]);
                    }
                }
            }else{
                $Operation = OperationSousCaisse::orderBy('created_at', 'desc')-> where('sous_caisse_id', $sc)->get();

                $TypeErreur = "1";
                $Message = "Les deux champs Date de début et Date de fin doivent être rempli pour un meilleur rendement! Le sytème enverra la liste de toutes les opérations concernant la sous caisse choisit consultable dans le tableau ci dessous!";

                return view('sous_caisse.operation',compact('TypeErreur','Message'),[
                    'Operation' => $Operation,
                    'SC' => $SC,
                    'qte_total_depot' => "",
                    'qte_total_retrait' =>"" ,
                    'qte_restante' => "",
                ]);
            }
        }
    }

    public function operation_depense()
    {
        $TypeErreur = "";
        $Message= "";

        $Depense = Depense::all();
        $SC = SousCaisse::all();
        return view('sous_caisse.operation_depense',compact('TypeErreur','Message'),[
            'Depense' => $Depense,
            'SC' => $SC,
        ]);
    }

    public function filterTableDepense(Request $request)
    {
        $this -> validate ($request,[
            'sousCaisse' => ['required'],
            'type_depense' => ['required'],
            // 'Date1' => ['required'],
            // 'Date2' => ['required'],
        ],$error_messages = [
            "sousCaisse.required" => "Selectionnez la sous caisse!",
            "type_depense.required" => "Selectionnez le type de depense!",
            // "Date1.required" => "Veuillez remplir le champ date de début !",
            // "Date2.required" => "Veuillez remplir le champ date de fin !",
        ]);

        $typeDep = $request-> type_depense;
        $sc = $request-> sousCaisse;
        $Date1 = $request-> Date1;
        $Date2 = $request-> Date2;
        $SC = SousCaisse::all();
        $Depense = Depense::all();

        if($typeDep == "0" or $typeDep == "1" or $typeDep == "2"){
            if(!$Date1 AND !$Date2){
                $Depense = Depense::orderBy('created_at', 'desc')-> where('sous_caisse_id', $sc)->where('status', $typeDep)->get();
                if($Depense -> count() > "0"){
                    $TypeErreur = "2";
                    $Message= "Résultat trouvé! consultez le tableau en dessous!";

                    return view('sous_caisse.operation_depense',compact('TypeErreur','Message'),[
                        'SC' => $SC,
                        'Depense' => $Depense,
                    ]);
                }else{
                    $TypeErreur = "1";
                    $Message= "Résultat non trouvé";

                    return view('sous_caisse.operation_depense',compact('TypeErreur','Message'),[
                        'SC' => $SC,
                        'Depense' => $Depense,
                    ]);
                }

            }elseif($Date1 AND $Date2){
                $ConvertDate1 = Carbon::createFromFormat('m/d/Y', $request-> Date1)->toDateString();
                $ConvertDate2 = Carbon::createFromFormat('m/d/Y', $request-> Date2)->toDateString();
                if($Date1 == $Date2){
                    $Depense = Depense::orderBy('created_at', 'desc')-> where('sous_caisse_id', $sc)->where('status', $typeDep)->whereDate('created_at', $ConvertDate1 )->get();
                    
                    if($Depense -> count() > "0"){
                        $TypeErreur = "2";
                        $Message= "Résultat trouvé! consultez le tableau en dessous!";

                        return view('sous_caisse.operation_depense',compact('TypeErreur','Message'),[
                            'Depense' => $Depense,
                            'SC' => $SC,
                        ]);
                    }else{
                        $TypeErreur = "1";
                        $Message= "Résultat non trouvé";

                        return view('sous_caisse.operation_depense',compact('TypeErreur','Message'),[
                            'Depense' => $Depense,
                            'SC' => $SC,
                        ]);
                    }
                }else{
                    if($Date1 <=$Date2){
                        $Depense = Depense::orderBy('created_at', 'desc')-> where('sous_caisse_id', $sc)->where('status', $typeDep)->whereBetween('created_at', [$ConvertDate1.' 00:00:00', $ConvertDate2.' 23:59:59'])->get();
                        if($Depense -> count() > "0"){
                            $TypeErreur = "2";
                            $Message= "Résultat trouvé! consultez le tableau en dessous!";

                            return view('sous_caisse.operation_depense',compact('TypeErreur','Message'),[
                                'Depense' => $Depense,
                                'SC' => $SC,
                            ]);
                        }else{
                            $TypeErreur = "1";
                            $Message= "Résultat non trouvé";

                            return view('sous_caisse.operation_depense',compact('TypeErreur','Message'),[
                                'Depense' => $Depense,
                                'SC' => $SC,
                            ]);
                        }
                    }else{
                        $TypeErreur = "1";
                        $Message= "La date de fin doit etre superieur a la date de debut pour un meilleur rendement; Le sytème enverra la liste de toutes les opérations de demande de depense concernant la sous caisse et le type de depense choisit consultable dans le tableau ci dessous!";

                        $Depense = Depense::orderBy('created_at', 'desc')-> where('sous_caisse_id', $sc)->where('status', $typeDep)->get();

                        return view('sous_caisse.operation_depense',compact('TypeErreur','Message'),[
                            'Depense' => $Depense,
                            'SC' => $SC,
                        ]);
                    }
                }
            }else{
                $Depense = Depense::orderBy('created_at', 'desc')-> where('sous_caisse_id', $sc)->where('status', $typeDep)->get();

                $TypeErreur = "1";
                $Message = "Les deux champs Date de début et Date de fin doivent être rempli pour un meilleur rendement! Le sytème enverra la liste de toutes les opérations de demande de depense concernant la sous caisse et le type de depense choisit consultable dans le tableau ci dessous!";

                return view('sous_caisse.operation_depense',compact('TypeErreur','Message'),[
                    'Depense' => $Depense,
                    'SC' => $SC,
                ]);
            }
        }
    }
}
