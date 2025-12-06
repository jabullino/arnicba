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
        Schema::create('movimiento_cuentas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bancoid');
            $table->date('fecha');
            $table->time('hora');
            $table->string('nombre',100);
            $table->string('descripcion',120);
            $table->decimal('debito',12,2);
            $table->decimal('credito',12,2);
            $table->decimal('saldo',12,2);
            $table->softdeletes();
            $table->timestamps();
            $table->foreign('bancoid')->references('id')->on('cuenta_bancos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimiento_cuentas');
    }
};
