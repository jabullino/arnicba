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
        Schema::create('egreso_residentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('residente_id')->constrained('residentes')->onDelete('cascade');
            $table->foreignId('gestion_id')->constrained('gestiones')->onDelete('cascade');
            $table->foreignId('motivo_id')->constrained('motivo_egresos')->onDelete('cascade'); 
            $table->string('destino',150)->nullable();
            $table->foreignId('municipio_id')->constrained('municipios')->onDelete('cascade');
            $table->date('fecha');
            $table->tinyInteger('numjuzgado');
            $table->string('numdoc',20);
            $table->string('nomjuez',255);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('egreso_residentes');
    }
};
