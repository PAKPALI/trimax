<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationCaisse extends Model
{
    use HasFactory;
    protected $fillable = ['somme','type_op','banque','sous_caisse','desc'];
}
