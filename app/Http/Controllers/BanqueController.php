<?php

namespace App\Http\Controllers;

use App\Models\Banque;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BanqueController extends Controller
{
    public function banque()
    {
        $Banque = Banque::all();

        return view('banque',[
            'Banque' => $Banque,
        ]);
    }

    public function ajouter(Request $request)
    {
        $error_messages = [
            "nom.required" => "Remplir le champ Ajouter banque!",
            "nom.unique" => "Le nom ".$request-> nom. " existe deja!",
        ];

        $validator = Validator::make($request->all(),[
            'nom' => ['required','unique:banques'],
        ], $error_messages);

        if($validator->fails())
            return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "AJOUT ECHOUE",
            "msg" => $validator->errors()->first()]);

        Banque::create([
            'nom' => $request-> nom,
        ]);

        return response()->json([
            "status" => true,
            "reload" => true,
            "redirect_to" => route('banque'),
            "title" => "AJOUT REUSSI",
            "msg" => "La banque au nom de ".$request-> nom." a bien été ajouté"
        ]);
    }

    public function update(Request $request)
    {
        $error_messages = [
            "nom.required" => "Remplir le champ nom!",
            "nom.unique" => "Le nom ".$request-> nom. " existe deja!",
        ];

        $validator = Validator::make($request->all(),[
            'nom' => ['required','unique:banques'],
        ], $error_messages);

        if($validator->fails())
            return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "MIS A JOUR ERRONE",
            "msg" => $validator->errors()->first()]);
        
        $id = $request-> id;
        $nom = $request-> nom;
        
        $search = Banque::find($id);
        if($search){
            $search -> update([
                'nom' => $nom
            ]);
            return response()->json([
                "status" => true,
                "reload" => true,
                "redirect_to" => route('banque'),
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
        $search = Banque::find($id);
        if($search){
            $search -> delete();

            return response()->json([
                "status" => true,
                "reload" => true,
                "redirect_to" => route('banque'),
                "title" => "SUPPRESSION",
                "msg" => "suppression reussie"
            ]);
        }else{
           
            return response()->json([
                "status" => false,
                "reload" => true,
                "title" => "SUPPRESSION",
                "msg" => "Banque inexistante"
            ]);
        }
    }
}
