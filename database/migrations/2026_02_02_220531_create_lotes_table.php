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
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo',50);
            $table->date('fec_ingre');
            $table->decimal('cantidad',7,2);
            $table->date('fec_venc')->nullable();
            $table->decimal('precio',7,2);
            $table->decimal('saldo',10,2);
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('origen_id');
            $table->softdeletes();
             $table->timestamps();
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
            $table->foreign('origen_id')->references('id')->on('origen_fondos')->onDelete('cascade');
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotes');
    }
};
