<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function client()
    {
        $Client = Client::all();

        return view('client.client',[
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

    public function update(Request $request)
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
}
