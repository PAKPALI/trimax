<?php

namespace App\Http\Controllers;

use App\Models\Section1;
use App\Models\Section2;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class pages_admin_controller extends Controller
{

    public function dashboard()
    {
        return view('admin/dashboard');
    }
    public function accueil_admin()
    {

        $section1 = Section1::first();
        $section2 = Section2::first();
        if ($section1 && $section2) {
            return view('admin/accueil_admin', [
                'id1' => $section1->id,
                'id2' => $section2->id,
                'texte_principal' => $section1->texte_principal,
                'texte_secondaire' => $section1->texte_secondaire,
                'texte' => $section2->texte,
            ]);
        } else {
            if ($section1) {
                return view('admin/accueil_admin', [
                    'id1' => $section1->id,
                    'id2' => "",
                    'texte_principal' => $section1->texte_principal,
                    'texte_secondaire' => $section1->texte_secondaire,
                    'texte' => "PAS DE TEXTE ENREGISTRÉ",
                ]);
            }elseif($section2) {
                return view('admin/accueil_admin', [
                    'id1' => "",
                    'id2' => $section2->id,
                    'texte_principal' =>"PAS DE TEXTE ENREGISTRÉ",
                    'texte_secondaire' => "PAS DE TEXTE ENREGISTRÉ",
                    'texte' => $section2->texte,
                ]);
            }else {
                return view('admin/accueil_admin', [
                    'id1' => "",
                    'id2' => "",
                    'texte_principal' =>"PAS DE TEXTE ENREGISTRÉ",
                    'texte_secondaire' => "PAS DE TEXTE ENREGISTRÉ",
                    'texte' => "PAS DE TEXTE ENREGISTRÉ",
                ]);
            }

        }
    }
    // public function accueil_admin()
    // {
    //     $section1 = Section1::first();
    //     $section2 = Section2::first();

    //     return view('admin/accueil_admin', [
    //         'id1' => optional($section1)->id,
    //         'id2' => optional($section2)->id,
    //         'texte_principal' => optional($section1)->texte_principal ?? "PAS DE TEXTE ENREGISTRÉ",
    //         'texte_secondaire' => optional($section1)->texte_secondaire ?? "PAS DE TEXTE ENREGISTRÉ",
    //         'texte' => optional($section2)->texte ?? "PAS DE TEXTE ENREGISTRÉ",
    //     ]);
    // }

    public function about_admin()
    {
        return view('admin/about_admin');
    }
    public function professeurs_admin()
    {
        return view('admin/professeurs_admin');
    }
    public function events_admin()
    {
        return view('admin/events_admin');
    }
    public function formations_admin()
    {
        return view('admin/formations_admin');
    }
    public function galerie_admin()
    {
        return view('admin/galerie_admin');
    }
    public function contact_admin()
    {
        return view('admin/contact_admin');
    }
    public function inbox()
    {
        return view('admin/inbox');
    }
    public function send()
    {
        return view('admin/send');
    }

    // function post
    public function updateSection1(Request $request)
    {
        $error_messages = [
            "texte_principal.required" => "Veillez remplir le champ Texte Principal",
            "texte_secondaire.required" => "Veillez remplir le champ Texte Secondaire",
        ];

        $validator = Validator::make($request->all(), [
            'texte_principal' => ['required'],
            'texte_secondaire' => ['required'],
        ], $error_messages);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "reload" => false,
                "title" => "MISE A JOUR ECHOUEE",
                "msg" => $validator->errors()->first()
            ]);
        } else {
            //Remplir le "protected filiable" avant la modification
            $id = $request['id1'];
            $img = $request->file('image');

            // si l'id existe alors faire la mise ajour 
            if ($id) {
                // si l'image existe
                if ($img) {
                    $imageName = $request->file('image')->getClientOriginalName();
                    // $path = $request->file('image')->store('public/imagesClient');
                    // $path = $img->store('imagesClient');
                    $path = Storage::putFile('public/imagesClient', $request->file('image'));
                    $imageExtension = $request->file('image')->getClientOriginalExtension();

                    $section1 = Section1::find($id);
                    // verifier l'extension
                    if ($imageExtension == "jpeg" or $imageExtension == "jpg" or $imageExtension == "png") {
                        $section1->update([
                            'texte_principal' => $request['texte_principal'],
                            'texte_secondaire' => $request['texte_secondaire'],
                            'image_name' => $imageName,
                            'path' => $path,
                        ]);

                        return response()->json([
                            "status" => true,
                            "reload" => true,
                            "redirect_to" => route('accueil_admin'),
                            "title" => "MISE A JOUR REUSSIE",
                            "msg" => ""
                        ]);
                    } else {
                        return response()->json([
                            "status" => false,
                            "redirect_to" => null,
                            "title" => "MISE A JOUR ECHOUEE",
                            "msg" => "L'extension de l'image doit être en jpeg , jpg ou pnj"
                        ]);
                    }
                } else {
                    $section1 = Section1::find($id);
                    $section1->update([
                        'texte_principal' => $request['texte_principal'],
                        'texte_secondaire' => $request['texte_secondaire'],
                    ]);
                    return response()->json([
                        "status" => true,
                        "reload" => true,
                        "redirect_to" => route('accueil_admin'),
                        "title" => "MISE A JOUR REUSSIE",
                        "msg" => ""
                    ]);
                }
            } else {
                // si l'image existe
                if ($img) {
                    $imageName = $request->file('image')->getClientOriginalName();
                    // $path = $request->file('image')->store('imagesClient', $imageName.time());
                    $path = Storage::putFile('public/imagesClient', $request->file('image'));
                    $imageExtension = $request->file('image')->getClientOriginalExtension();

                    // verifier l'extension
                    if ($imageExtension == "jpeg" or $imageExtension == "jpg" or $imageExtension == "png") {
                        Section1::create([
                            'texte_principal' => $request['texte_principal'],
                            'texte_secondaire' => $request['texte_secondaire'],
                            'image_name' => $imageName,
                            'path' => $path,
                        ]);
                        return response()->json([
                            "status" => true,
                            "reload" => true,
                            "redirect_to" => route('accueil_admin'),
                            "title" => "MISE A JOUR REUSSIE",
                            "msg" => ""
                        ]);
                    } else {
                        return response()->json([
                            "status" => false,
                            "redirect_to" => null,
                            "title" => "MISE A JOUR ECHOUEE",
                            "msg" => "L'extension de l'image doit être en jpeg , jpg ou pnj"
                        ]);
                    }
                    return response()->json([
                        "status" => true,
                        "reload" => true,
                        "redirect_to" => route('accueil_admin'),
                        "title" => "MISE A JOUR REUSSIE",
                        "msg" => ""
                    ]);
                } else {
                    Section1::create([
                        'texte_principal' => $request->texte_principal,
                        'texte_secondaire' => $request->texte_secondaire,
                    ]);
                    return response()->json([
                        "status" => true,
                        "reload" => true,
                        "redirect_to" => route('accueil_admin'),
                        "title" => "MISE A JOUR REUSSIE",
                        "msg" => ""
                    ]);
                }
            }
        }
    }

    public function updateSection2(Request $request)
    {
        $error_messages = [
            "texte.required" => "Veillez remplir le champ Texte",
        ];

        $validator = Validator::make($request->all(), [
            'texte' => ['required'],
        ], $error_messages);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "reload" => false,
                "title" => "MISE A JOUR ECHOUEE",
                "msg" => $validator->errors()->first()
            ]);
        } else {
            //Remplir le "protected filiable" avant la modification
            $id = $request['id2'];
            $img = $request->file('image');

            // si l'id existe alors faire la mise ajour 
            if ($id) {
                // si l'image existe
                if ($img) {
                    $imageName = $request->file('image')->getClientOriginalName();
                    $path = $request->file('image')->store('imagesClient', $imageName.time());
                    $imageExtension = $request->file('image')->getClientOriginalExtension();

                    $section2 = Section2::find($id);
                    // verifier l'extension
                    if ($imageExtension == "jpeg" or $imageExtension == "jpg" or $imageExtension == "png") {
                        $section2->update([
                            'texte' => $request['texte'],
                            'image_name' => $imageName,
                            'path' => $path,
                        ]);

                        return response()->json([
                            "status" => true,
                            "reload" => true,
                            "redirect_to" => route('accueil_admin'),
                            "title" => "MISE A JOUR REUSSIE",
                            "msg" => ""
                        ]);
                    } else {
                        return response()->json([
                            "status" => false,
                            "redirect_to" => null,
                            "title" => "MISE A JOUR ECHOUEE",
                            "msg" => "L'extension de l'image doit être en jpeg , jpg ou pnj"
                        ]);
                    }
                } else {
                    $section2 = Section2::find($id);
                    $section2->update([
                        'texte' => $request['texte'],
                    ]);
                    return response()->json([
                        "status" => true,
                        "reload" => true,
                        "redirect_to" => route('accueil_admin'),
                        "title" => "MISE A JOUR REUSSIE",
                        "msg" => ""
                    ]);
                }
            } else {
                // si l'image existe
                if ($img) {
                    $imageName = $request->file('image')->getClientOriginalName();
                    $path = $request->file('image')->store('imagesClient', $imageName.time());
                    $imageExtension = $request->file('image')->getClientOriginalExtension();

                    // verifier l'extension
                    if ($imageExtension == "jpeg" or $imageExtension == "jpg" or $imageExtension == "png") {
                        Section2::create([
                            'texte' => $request['texte'],
                            'image_name' => $imageName,
                            'path' => $path,
                        ]);
                        return response()->json([
                            "status" => true,
                            "reload" => true,
                            "redirect_to" => route('accueil_admin'),
                            "title" => "MISE A JOUR REUSSIE",
                            "msg" => ""
                        ]);
                    } else {
                        return response()->json([
                            "status" => false,
                            "redirect_to" => null,
                            "title" => "MISE A JOUR ECHOUEE",
                            "msg" => "L'extension de l'image doit être en jpeg , jpg ou pnj"
                        ]);
                    }
                    return response()->json([
                        "status" => true,
                        "reload" => true,
                        "redirect_to" => route('accueil_admin'),
                        "title" => "MISE A JOUR REUSSIE",
                        "msg" => ""
                    ]);
                } else {
                    Section2::create([
                        'texte' => $request->texte,
                    ]);
                    return response()->json([
                        "status" => true,
                        "reload" => true,
                        "redirect_to" => route('accueil_admin'),
                        "title" => "MISE A JOUR REUSSIE",
                        "msg" => ""
                    ]);
                }
            }
        }
    }
}