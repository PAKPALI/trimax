<?php

namespace App\Http\Controllers;

use App\Models\Pays;
use App\Models\SousCaisse;
use Illuminate\Http\Request;
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

    public function ajouter(Request $request)
    {
        $error_messages = [
            "nom.required" => "Veuillez saisir le Nom!",
            "nom.unique" => "Le nom ".$request-> nom. " existe deja!",
            "ville.required" => "Veuillez saisir la ville!",
            "quartier.required" => "Veuillez saisir le quartier!",
        ];

        $validator = Validator::make($request->all(),[
            'nom' => ['required','unique:pays'],
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
            $pays->sousCaisse()->create([
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

}
