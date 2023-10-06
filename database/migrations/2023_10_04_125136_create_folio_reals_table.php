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
        Schema::create('folio_reals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('antecedente')->nullable()->references('id')->on('folio_reals');
            $table->string('tomo_antecedente')->nullable();
            $table->string('registro_antecedente')->nullable();
            $table->string('numero_propiedad_antecedente')->nullable();
            $table->string('distrito_antecedente')->nullable();
            $table->string('seccion_antecedente')->nullable();
            $table->string('estado');
            $table->foreignId('creado_por')->nullable()->references('id')->on('users');
            $table->foreignId('actualizado_por')->nullable()->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folio_reals');
    }
};
