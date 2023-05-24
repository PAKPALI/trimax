<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operation_sous_caisses', function (Blueprint $table) {
            $table->id();
            $table ->foreignId('sous_caisse_id')->constrained()->onDelete('cascade');
            $table->longText('nom_sous_caisse');
            $table->bigInteger('somme');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operation_sous_caisses');
    }
};
