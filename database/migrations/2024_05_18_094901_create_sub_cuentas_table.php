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
        Schema::create('sub_cuentas', function (Blueprint $table) {
            $table->unsignedBigInteger('cuenta_id');
            $table->id();
            $table->string('cod_sub_cuentas',70);
            $table->string('nombre',70);
            $table->timestamps();
            $table->foreign('cuenta_id')->references('id')->on('cuentas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_cuentas');
    }
};
