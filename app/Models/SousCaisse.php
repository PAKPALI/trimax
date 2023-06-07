<?php

namespace App\Models;

use App\Models\Pays;
use App\Models\User;
use App\Models\Depense;
use App\Models\OperationSousCaisse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function depense(){
        return $this->hasMany(Depense::class);
    }
    public function user(){
        return $this->hasMany(User::class);
    }
}
