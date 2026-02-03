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
        Schema::create('egreso_producto', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('egreso_id');
            $table->unsignedBigInteger('producto_id');
            $table->foreign('egreso_id')->references('id')->on('egresos')->OnDelete('cascade');
            $table->foreign('producto_id')->references('id')->on('productos')->OnDelete('cascade');
            $table->timestamps();
            $table->softdeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::dropIfExists('egreso_producto');
    }
};
