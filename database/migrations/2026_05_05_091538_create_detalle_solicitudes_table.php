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
    Schema::create('detalle_solicitudes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('solicitud_caja_chica_id')->constrained('solicitud_caja_chicas')->cascadeOnDelete();
        $table->string('descripcion');
        $table->decimal('cantidad', 10, 2);
        $table->timestamps();
        $table->softDeletes();
    });
   }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_solicitudes');
    }
};
