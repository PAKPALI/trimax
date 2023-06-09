<?php

namespace App\Models;

use App\Models\OperationCaisse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Banque extends Model
{
    use HasFactory;
    protected $fillable = ['nom'];

    public function operation(){
        return $this->hasMany(OperationCaisse::class);
    }
}
