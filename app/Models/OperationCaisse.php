<?php

namespace App\Models;

use App\Models\Banque;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OperationCaisse extends Model
{
    use HasFactory;
    protected $fillable = ['somme','type_op','banque_id','sous_caisse','desc'];

    public function banque(){
        return  $this ->belongsTo(Banque::class);
    }
}
