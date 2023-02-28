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
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->stirng('tipo');
            $table->string('nombre');
            $table->string('ap_paterno');
            $table->string('ap_materno');
            $table->string('curp');
            $table->string('rfc');
            $table->string('razon_social');
            $table->string('fecha_nacimiento');
            $table->string('nacionalidad');
            $table->string('estado_civil');
            $table->string('calle');
            $table->string('numero_exterior');
            $table->string('numero_interior');
            $table->string('colonia');
            $table->unsignedInteger('cp');
            $table->string('entidad');
            $table->string('municipio');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
