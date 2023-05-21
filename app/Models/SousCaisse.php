<?php

namespace App\Models;

use App\Models\Pays;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SousCaisse extends Model
{
    use HasFactory;

    protected $fillable = ['somme','nom','ville','quartier'];

    public function pays(){
        return  $this ->belongsTo(Pays::class);
    }
}
