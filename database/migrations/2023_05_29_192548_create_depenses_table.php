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
        Schema::create('depenses', function (Blueprint $table) {
            $table->id();
            $table ->foreignId('sous_caisse_id')->constrained() ->onDelete('cascade');
            $table ->foreignId('type_depense_id')->constrained();
            $table ->foreignId('user_id')->constrained();
            $table->bigInteger('somme');
            $table->longText('type');
            $table->longText('desc')->nullable();
            $table->Integer('status')->default('2');
            $table->longText('validateur')->nullable();
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
        Schema::dropIfExists('depenses');
    }
};
