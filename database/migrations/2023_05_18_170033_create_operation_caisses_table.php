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
        Schema::create('operation_caisses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('somme');
            $table ->foreignId('banque_id')->nullable()->constrained();
            $table->string('type_op');
            $table->string('sous_caisse')->nullable();
            $table->longText('desc');
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
        Schema::dropIfExists('operation_caisses');
    }
};
