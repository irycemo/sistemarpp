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
        Schema::create('escrituras', function (Blueprint $table) {
            $table->id();
            $table->string('tomo');
            $table->string('tomo_bis')->nullable();
            $table->string('registro');
            $table->string('registro_bis')->nullable();
            $table->date('fecha_inscripcion');
            $table->date('fecha_escritura')->nullable();
            $table->unsignedInteger('numero_hojas');
            $table->unsignedInteger('numero_paginas');
            $table->string('tipo_fedatario');
            $table->string('documento_presentado');
            $table->string('notaria');
            $table->string('nombre_notario')->nullable();
            $table->string('estado_notario')->nullable();
            $table->text('comentario')->nullbale();
            $table->foreignId('distrito_id')->constrained();
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
        Schema::dropIfExists('escrituras');
    }
};
