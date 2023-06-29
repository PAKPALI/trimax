<?php

namespace App\Models;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OperationClient extends Model
{
    use HasFactory;
    protected $fillable = ['client_id','somme','type_op','desc'];

    public function client(){
        return  $this ->belongsTo(Client::class);
    }
}
