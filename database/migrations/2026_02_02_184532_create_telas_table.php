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
        Schema::create('telas', function (Blueprint $table) {
            $table->id();
            $table->string('ancho',10);
            $table->string('largo',10);
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('color_id');
            $table->softDeletes();
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
            $table->foreign('color_id')->references('id')->on('colores')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telas');
    }
};
