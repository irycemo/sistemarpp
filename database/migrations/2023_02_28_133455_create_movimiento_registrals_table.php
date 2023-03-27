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
            $table->unsignedDecimal("monto", 18, 2);
            $table->foreignId('predio_id')->nullable();
            $table->string("tomo")->nullable();
            $table->boolean("tomo_bis")->nullable();
            $table->string("registro")->nullable();
            $table->boolean("registro_bis")->nullable();
            $table->unsignedInteger('tramite')->unique();
            $table->timestamp('fecha_prelacion');
            $table->string('tipo_servicio');
            $table->string('solicitante');
            $table->string('seccion');
            $table->string('distrito');
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
