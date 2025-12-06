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
        Schema::create('acogida_circunstanciales', function (Blueprint $table) {
            $table->id();
             $table->foreignId('residente_id')->constrained('residentes')->onDelete('cascade');
            $table->date('fecha');
            $table->string('numdoc',15);
            $table->string('ciudad',20);
            $table->string('municipio',30);
            $table->string('tipologia',30);
            $table->string('firmante',50);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acogida_circunstanciales');
    }
};
