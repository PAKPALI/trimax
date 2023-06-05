<?php

namespace App\Http\Controllers;

use App\Models\Pays;
use App\Models\Depense;
use App\Models\SousCaisse;
use Illuminate\Http\Request;
use App\Models\OperationSousCaisse;
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
        $SousCaisse = SousCaisse::all();
        $S1 = SousCaisse::find(4);
        $Depense = Depense::all();
        

        return view('sous_caisse.demande_depense',[
            's1' => $S1,
            'SC' => $SousCaisse,
            'Depense' => $Depense,
        ]);
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
            // "desc.required" => "Saisissez la description!",
        ];

        $validator = Validator::make($request->all(),[
            'selection' => ['required'],
            'somme' => ['required','numeric'],
            'confirmersomme' => ['required','numeric'],
            'type' => ['required'],
        ], $error_messages);

        if($validator->fails())
            return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "AJOUT ECHOUE",
            "msg" => $validator->errors()->first()]);

        // requete sur la sous caisse
        $sousCaisse = SousCaisse::find($request-> selection);

        // on verifie voir si les sommes sont positives
        if($request-> somme >=0){
            // on verifie voir si les sommes sont identiques
            if($request-> somme == $request-> confirmersomme){
                // enregistrer la depense
                Depense::create([
                    'sous_caisse_id' => $request-> selection,
                    'somme' => $request-> somme,
                    'type' => $request-> type,
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
}
