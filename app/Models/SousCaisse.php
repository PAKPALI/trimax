<?php

namespace App\Models;

use App\Models\Pays;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\OperationSousCaisse;

class SousCaisse extends Model
{
    use HasFactory;

    protected $fillable = ['pays_id','somme','nom','ville','quartier'];

    public function pays(){
        return  $this ->belongsTo(Pays::class);
    }

    public function operation(){
        return $this->hasMany(OperationSousCaisse::class);
    }
}
