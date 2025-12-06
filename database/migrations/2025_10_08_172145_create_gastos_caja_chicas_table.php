<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gastos_caja_chicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entregas_id')->constrained('entregas_caja_chicas')->onDelete('cascade');
            $table->foreignId('cuenta_id')->constrained('cuentas')->onDelete('cascade');
            $table->foreignId('subcuenta_id')->constrained('sub_cuentas')->onDelete('cascade');
            $table->date('fecha_doc');
            $table->timestamp('fecha_registro')->useCurrent();
            $table->string('factura',15);
            $table->string('recibo',15);
            $table->decimal('importe',10,2);
            $table->string('status',15)->default('pendiente');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gastos_caja_chicas');
    }
};
