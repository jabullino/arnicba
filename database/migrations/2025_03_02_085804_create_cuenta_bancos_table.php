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
        Schema::create('cuenta_bancos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('banco_id');
            $table->unsignedBigInteger('tipocuenta_id');
            $table->unsignedBigInteger('tipomoneda_id');
            $table->string('numcuenta',20);
            $table->softdeletes();
            $table->foreign('banco_id')->references('id')->on('bancos')->onDelete('cascade');
            $table->foreign('tipocuenta_id')->references('id')->on('tipo_cuentas')->onDelete('cascade');
            $table->foreign('tipomoneda_id')->references('id')->on('tipo_monedas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuenta_bancos');
    }
};
