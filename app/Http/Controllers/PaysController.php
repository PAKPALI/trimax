<?php

namespace App\Http\Controllers;

use App\Models\Pays;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaysController extends Controller
{
    public function pays()
    {
        $Pays = Pays::all();

        return view('pays',[
            'Pays' => $Pays,
        ]);
    }

    public function ajouterPays(Request $request)
    {
        $error_messages = [
            "nom.required" => "Remplir le champ Nom du pays!",
            "nom.unique" => "Le nom ".$request-> nom. " existe deja!",
            "nom.alpha" => "Aucun nom d'un pays ne comporte un chiffre!",
        ];

        $validator = Validator::make($request->all(),[
            'nom' => ['required','unique:pays','alpha'],
        ], $error_messages);

        if($validator->fails())
            return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "AJOUT ECHOUE",
            "msg" => $validator->errors()->first()]);

        Pays::create([
            'nom' => $request-> nom,
        ]);

        return response()->json([
            "status" => true,
            "reload" => true,
            "redirect_to" => route('pays'),
            "title" => "AJOUT REUSSI",
            "msg" => "Le pays au nom de ".$request-> nom." a bien été ajouté"
        ]);
    }

    public function update(Request $request)
    {
        $error_messages = [
            "nom.required" => "Remplir le champ nom!",
        ];

        $validator = Validator::make($request->all(),[
            'nom' => 'required',
        ], $error_messages);

        if($validator->fails())
            return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "MIS A JOUR ERRONE",
            "msg" => $validator->errors()->first()]);
        
        $id = $request-> id;
        $nom = $request-> nom;
        
        $search = Pays::find($id);
        if($search){
            $search -> update([
                'nom' => $nom
            ]);
            return response()->json([
                "status" => true,
                "reload" => true,
                "redirect_to" => route('pays'),
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
        $search = Pays::find($id);
        if($search){
            $search -> delete();

            return response()->json([
                "status" => true,
                "reload" => true,
                "redirect_to" => route('pays'),
                "title" => "SUPPRESSION",
                "msg" => "suppression reussie"
            ]);
        }else{
           
            return response()->json([
                "status" => false,
                "reload" => true,
                "title" => "SUPPRESSION",
                "msg" => "Pays inexistant"
            ]);
        }
    }
}
