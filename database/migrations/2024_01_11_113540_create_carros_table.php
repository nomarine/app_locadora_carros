<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('carros', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('modelo_id');
            $table->string('placa', 10)->unique();
            $table->boolean('disponivel');
            $table->integer('km');
            $table->timestamps();

            $table->foreign('modelo_id')->references('id')->on('modelos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carros', function(Blueprint $table) {
            $table->dropForeign('carros_modelo_id_foreign');
            $table->drop('modelo_id');
        });
        Schema::dropIfExists('carros');
    }
};
