<?php

namespace App\Models;

use App\Models\SousCaisse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pays extends Model
{
    use HasFactory;
    protected $fillable = ['nom'];

    public function sousCaisse(){
        return $this->hasMany(SousCaisse::class);
    }
}
