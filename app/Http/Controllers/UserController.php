<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SousCaisse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function user()
    {
        $User = User::all();
        $SousCaisse = SousCaisse::all();

        return view('user',[
            'SC' => $SousCaisse,
            'User' => $User,
        ]);
    }

    public function ajouter(Request $request)
    {
        $error_messages = [
            "nom.required" => "Remplir le champ Nom!",
            "email.required" => "Remplir le champ Email!",
            "email.unique" => "L'email ".$request-> email. " existe deja!",
            "password.required" => "Remplir le champ mot de passe!",
            "password.min" => "Le mot de passe doit comporter au moins 8 caracteres!",
            "password.confirmed" => "Les deux champs de mots de passe ne correspondent pas",
        ];

        $validator = Validator::make($request->all(),[
            'nom' => ['required'],
            'email' => ['required','unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], $error_messages);

        if($validator->fails())
            return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "AJOUT ECHOUE",
            "msg" => $validator->errors()->first()]);

        User::create([
            'nom' => $request-> nom,
            'email' => $request-> email,
            'password' => Hash::make($request['password']),
        ]);

        return response()->json([
            "status" => true,
            "reload" => true,
            "redirect_to" => route('user'),
            "title" => "AJOUT REUSSI",
            "msg" => "L'utilisateur au nom de ".$request-> nom." a bien été ajouté"
        ]);
    }

    public function update(Request $request)
    {
        $error_messages = [
            "nom.required" => "Remplir le champ nom!",
            "email.required" => "Remplir le champ nom!",
            "email.unique" => "Le nom ".$request-> email. " existe deja!",
        ];

        $validator = Validator::make($request->all(),[
            'nom' => ['required'],
            'email' => ['required','unique:users'],
        ], $error_messages);

        if($validator->fails())
            return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "MIS A JOUR ERRONE",
            "msg" => $validator->errors()->first()]);
        
        $id = $request-> id;
        $sc = $request-> sousCaisee;
        $nom = $request-> nom;
        $email = $request-> email;

        $emailExist = User::where('email', $email) ->first();
        
        $search = User::find($id);
        if($search){
            if($emailExist){
                $search -> update([
                    'nom' => $nom,
                ]);
                return response()->json([
                    "status" => true,
                    "reload" => true,
                    "redirect_to" => route('user'),
                    "title" => "MIS A JOUR REUSSIE",
                    "msg" => "Mis a jour reussie"
                ]);
            }
            else{
                $search -> update([
                    'nom' => $nom,
                    'email' => $email
                ]);
                return response()->json([
                    "status" => true,
                    "reload" => true,
                    "redirect_to" => route('user'),
                    "title" => "MIS A JOUR REUSSIE",
                    "msg" => "Mis a jour reussie"
                ]);
            }
        }
    }

    public function parametre(Request $request)
    {
        $error_messages = [
            "sousCaisse.required" => "Selectionnez la sous caisse pour la lier a l'utilisateur!",
        ];

        $validator = Validator::make($request->all(),[
            'sousCaisse' => ['required'],
        ], $error_messages);

        if($validator->fails())
            return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "MIS A JOUR ERRONE",
            "msg" => $validator->errors()->first()]);
        
        $id = $request-> id;
        $sc = $request-> sousCaisse;
        
        $search = User::find($id);
        if($search){
            $search -> update([
                'sous_caisses_id' => $sc,
            ]);
            return response()->json([
                "status" => true,
                "reload" => true,
                "redirect_to" => route('user'),
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
