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
        Schema::create('permiso_salidas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gestion_id')->constrained('gestiones')->onDelete('cascade');
            $table->foreignId('cargo_id')->constrained('cargos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('num_permiso',20);
            $table->string('institucion',100);
            $table->string('motivo',300);
            $table->timestamp('fecha_solicitud');
            $table->date('fecha_salida');
            $table->time('hora_salida');
            $table->time('hora_retorno')->nullable();
            $table->string('destino',100);
            $table->string('observaciones',400)->nullable();
            $table->string('estado',15);
            $table->boolean('aprobado')->nullable()->default(null);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permiso_salidas');
    }
};
