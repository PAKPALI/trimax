<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SousCaisse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function user()
    {
        $User = User::where('type_user', 2)->get();
        $SousCaisse = SousCaisse::all();

        return view('user',[
            'SC' => $SousCaisse,
            'User' => $User,
        ]);
    }

    public function profil()
    {

        return view('profil');
    }

    public function updatePassword(Request $request)
    {

        $error_messages = [
            "AM.required" => "Remplir le champ ancien mot de passe!",
            "NM.required" => "Remplir le champ nouveau mot de passe!",
            "CM.required" => "Remplir le champ confirmer mot de passe!",
            "NM.min" => "Le nouveau mot de passe doit comporter au moins 8 caracteres!",
        ];

        $validator = Validator::make($request->all(),[
            'AM' => ['required', 'min:8'],
            'NM' => ['required', 'min:8'],
            'CM' => ['required', 'min:8'],
        ], $error_messages);

        if($validator->fails())
            return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "CONNECTION ECHOUEE",
            "msg" => $validator->errors()->first()]);

        $id = $request-> id;
        $User = User::find($id);

        if(Hash::check($request-> AM, $User-> password)){

            if($request-> NM == $request-> CM){
                $search = User::find($id);
                if($search){
                    $search -> update([
                        'password' =>  Hash::make($request-> CM)
                    ]);
                    return response()->json([
                        "status" => true,
                        "reload" => true,
                        "redirect_to" => "0",
                        "title" => "MIS A JOUR REUSSIE",
                        "msg" => "Mis a jour reussie"
                    ]);
                }
            }else{

                return response()->json([

                    "status" => false,
                    "reload" => false,
                    "title" => "CONNECTION ECHOUE",
                    "msg" => "Le nouveau mot de passe et la confirmation du mot de passe sont différents"
    
                ]);

            }

        }else{

            return response()->json([

                "status" => false,
                "reload" => false,
                "title" => "CONNECTION ECHOUE",
                "msg" => "L'ancien mot de passe saisie ne correspond pas au mot de passe enregistré dans la base de donnée"

            ]);
        }
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

    public function ajouter_admin(Request $request)
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
            'type_user' => 1,
            'connected' => 1,
            'password' => Hash::make($request['password']),
        ]);

        return response()->json([
            "status" => true,
            "reload" => true,
            "redirect_to" => route('conn'),
            "title" => "AJOUT REUSSI",
            "msg" => "L'admin au nom de ".$request-> nom." a bien été ajouté"
        ]);
    }

    public function update(Request $request)
    {
        $error_messages = [
            "nom.required" => "Remplir le champ nom!",
            "email.required" => "Remplir le champ nom!",
            // "email.unique" => "L'email '".$request-> email. "' existe deja!",
        ];

        $validator = Validator::make($request->all(),[
            'nom' => ['required'],
            // 'email' => ['required','unique:users'],
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
            if($sc == 0){
                $search -> update([
                    'sous_caisse_id' => null,
                ]);
                return response()->json([
                    "status" => true,
                    "reload" => true,
                    "redirect_to" => route('user'),
                    "title" => "MIS A JOUR REUSSIE",
                    "msg" => "Mis a jour reussie"
                ]);
            }else{
                $search -> update([
                    'sous_caisse_id' => $sc,
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

    public function connected(Request $request)
    {
        
        $id = $request-> id;
        $connected = $request-> connected;
        
        $search = User::find($id);
        if($search){
            if($connected == 0){
                $search -> update([
                    'connected' => false,
                ]);
                return response()->json([
                    "status" => true,
                    "reload" => true,
                    "redirect_to" => route('user'),
                    "title" => "CONNEXION DESACTIVER",
                    "msg" => "Desactivation reussie pour ".$search->nom
                ]);
            }else{
                $search -> update([
                    'connected' => true,
                ]);
                return response()->json([
                    "status" => true,
                    "reload" => true,
                    "redirect_to" => route('user'),
                    "title" => "CONNEXION ACTIVER",
                    "msg" => "activation reussie pour ".$search->nom
                ]);
            }
        }
    }

    public function status(Request $request)
    {
        
        $id = $request-> id;
        $connected = $request-> connected;
        
        $search = User::find($id);
        if($search){
            if($connected == 0){
                $search -> update([
                    'status_client' => false,
                ]);
                return response()->json([
                    "status" => true,
                    "reload" => true,
                    "redirect_to" => route('user'),
                    "title" => "GESTION CLIENT DESACTIVER",
                    "msg" => "Desactivation reussie pour ".$search->nom
                ]);
            }else{
                $search -> update([
                    'status_client' => true,
                ]);
                return response()->json([
                    "status" => true,
                    "reload" => true,
                    "redirect_to" => route('user'),
                    "title" => "GESTION CLIENT ACTIVER",
                    "msg" => "activation reussie pour ".$search->nom
                ]);
            }
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
        $search = User::find($id);
        if($search){
            $search -> delete();

            return response()->json([
                "status" => true,
                "reload" => true,
                "redirect_to" => route('user'),
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

    public function outUser(Request $request)
    {
        $id =  $request->id;
        // $user = User::find($id);

        // Auth::logout($user);
        $request->session()->invalidate();

        return response()->json([
            "status" => true,
            "reload" => true,
            "redirect_to" => route('conn'),
            "title" => "DECONNEXION REUSSI",
            'check' => Auth::check(),
            "msg" => "Au revoir, a bientot"
        ]);
    }
}
