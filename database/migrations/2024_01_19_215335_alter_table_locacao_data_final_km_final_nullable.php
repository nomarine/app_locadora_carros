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
        Schema::table('locacoes', function(Blueprint $table) {
            $table->dateTime('data_final_previsto_periodo')->nullable()->change();
            $table->dateTime('data_final_realizado_periodo')->nullable()->change();
            $table->integer('km_final')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locacoes', function(Blueprint $table) {
            $table->dateTime('data_final_previsto_periodo')->nullable(false)->change();
            $table->dateTime('data_final_realizado_periodo')->nullable(false)->change();
            $table->integer('km_final')->nullable(false)->change();
        });
    }
};
