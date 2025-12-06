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
        Schema::create('bonos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',50);
            $table->decimal('monto',5,2)->nullable();
            $table->decimal('porcentaje',5,2)->nullable();
            $table->softdeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonos');
    }
};
