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
        Schema::create('sous_caisses', function (Blueprint $table) {
            $table->id();
            $table ->foreignId('pays_id')->constrained() ->onDelete('cascade');
            $table->bigInteger('somme')->default('0');
            $table->longText('nom');
            $table->longText('ville');
            $table->longText('quartier');
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
        Schema::dropIfExists('sous_caisses');
    }
};
