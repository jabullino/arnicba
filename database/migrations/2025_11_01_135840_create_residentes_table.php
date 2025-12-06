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
        Schema::create('residentes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',40);
            $table->string('apellido',50)->nullable();
            $table->date('fecnac')->nullable();
            $table->string('ci',12)->nullable();
            $table->string('ext')->nullable();
            $table->string('ciudad',30)->nullable();
            $table->string('provincia')->nullable();
            $table->date('fec_ingreso');
            $table->date('fec_egreso')->nullable();
            $table->string('foto')->nullable(); 
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residentes');
    }
};
