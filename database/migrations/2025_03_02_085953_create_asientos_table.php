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
        Schema::create('asientos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gestion_id');
            $table->date('fec_asiento');
            $table->unsignedBigInteger('tc_id')->nullable();
            $table->unsignedBigInteger('tv_id')->nullable();
            $table->string('recibo',15)->nullable();
            $table->string('factura',15)->nullable();
            $table->unsignedBigInteger('cuenta');
            $table->unsignedBigInteger('sub_cuenta');
            $table->decimal('monto_bs',10,2);
            $table->decimal('monto_sus',10,2);
            $table->unsignedBigInteger('origenfondos_id')->default('1');
            $table->unsignedBigInteger('tipomovimiento_id')->default('1');   
            $table->unsignedBigInteger('proyecto_id')->nullable();
            $table->unsignedBigInteger('estado_id')->default('1');
            $table->softdeletes();
            $table->timestamps();
            $table->foreign('gestion_id')->references('id')->on('gestiones')->onDelete('cascade');
            $table->foreign('tc_id')->references('id')->on('tipo_cambio_compras')->onDelete('cascade');
            $table->foreign('tv_id')->references('id')->on('tipo_cambio_ventas')->onDelete('cascade');
            $table->foreign('cuenta')->references('id')->on('cuentas')->onDelete('cascade');
            $table->foreign('sub_cuenta')->references('id')->on('sub_cuentas')->onDelete('cascade');
            $table->foreign('tipomovimiento_id')->references('id')->on('tipo_movimientos')->onDelete('cascade');
            $table->foreign('origenfondos_id')->references('id')->on('origen_fondos')->onDelete('cascade');
            $table->foreign('proyecto_id')->references('id')->on('proyectos')->onDelete('cascade');
            $table->foreign('estado_id')->references('id')->on('estados')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asientos');
    }
};
