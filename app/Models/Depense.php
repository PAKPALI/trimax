<?php

namespace App\Models;

use App\Models\User;
use App\Models\SousCaisse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Depense extends Model
{
    use HasFactory;
    protected $fillable = ['sous_caisse_id','type_depense_id','user_id','somme','type','desc','status','user','validateur'];

    public function sousCaisse(){
        return  $this ->belongsTo(SousCaisse::class);
    }

    public function user(){
        return  $this ->belongsTo(User::class);
    }
}