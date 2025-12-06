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
        Schema::create('bono_sueldo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bono_id');
            $table->unsignedBigInteger('sueldo_id');
            $table->unsignedBigInteger(('user_id'));
            $table->decimal('monto',10,2);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('bono_id')->references('id')->on('bonos')->onDelete('cascade');
            $table->foreign('sueldo_id')->references('id')->on('sueldos')->onDelete('cascade');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bono_sueldo');
    }
};
