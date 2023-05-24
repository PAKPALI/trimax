<?php

namespace App\Models;

use App\Models\SousCaisse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OperationSousCaisse extends Model
{
    use HasFactory;

    protected $fillable = ['somme','nom_sous_caisse'];

    public function sousCaisse(){
        return  $this ->belongsTo(SousCaisse::class);
    }
}
