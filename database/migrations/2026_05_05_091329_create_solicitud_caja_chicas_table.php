<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(){
    Schema::create('solicitud_caja_chicas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('gestion_id')->constrained('gestiones')->cascadeOnDelete();
        $table->string('codigo'); // 05052026-1
        $table->date('fecha');
        $table->string('estado')->default('pendiente');
        $table->boolean('impreso')->nullable()->default(null);
        $table->timestamps();
        $table->softDeletes();
    });
   }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_caja_chicas');
    }
};
