<?php

namespace App\Models;

use App\Models\SousCaisse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Depense extends Model
{
    use HasFactory;
    protected $fillable = ['sous_caisse_id','somme','type','desc','status'];

    public function sousCaisse(){
        return  $this ->belongsTo(SousCaisse::class);
    }
}
