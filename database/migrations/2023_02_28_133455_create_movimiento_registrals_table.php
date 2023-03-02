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
        Schema::create('movimiento_registrals', function (Blueprint $table) {
            $table->id();
            $table->string('estado');
            $table->foreignId('predio_id');
            $table->unsignedInteger('tramite');
            $table->timestamp('fecha_prelacion');
            $table->string('servicio');
            $table->string('tipo_servicio');
            $table->string('solicitante');
            $table->string('seccion');
            $table->foreignId('usuario_asignado')->references('id')->on('users');
            $table->foreignId('usuario_supervisor')->references('id')->on('users');
            $table->date('fecha_entrega');
            $table->foreignId('actualizado_por')->nullable()->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimiento_registrals');
    }
};
