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
        Schema::create('propiedads', function (Blueprint $table) {
            $table->id();
            $table->string('acto_contenido');
            $table->unsignedTinyInteger('numero_propiedad')->comment("Número de propiedad dentro de la escritura");
            $table->unsignedDecimal('superficie_terreno', 18,2)->nullable();
            $table->unsignedDecimal('superficie_construccion', 18,2)->nullable();
            $table->unsignedDecimal('superficie_judicial', 18,2)->nullable();
            $table->unsignedDecimal('superficie_notarial', 18,2)->nullable();
            $table->unsignedDecimal('area_comun_terreno', 10, 2)->nullable();
            $table->unsignedDecimal('area_comun_construccion', 10, 2)->nullable();
            $table->unsignedDecimal('valor_terreno_comun', 10, 2)->nullable();
            $table->unsignedDecimal('valor_construccion_comun', 10, 2)->nullable();
            $table->unsignedDecimal('valor_total_terreno', 18,2)->nullable();
            $table->unsignedDecimal('valor_total_construccion', 18,2)->nullable();
            $table->unsignedDecimal('valor_catastral', 18,2)->nullable();
            $table->unsignedInteger('monto_transaccion');
            $table->string('divisa');
            $table->date('fecha_inscripcion');
            $table->string('tipo_vialidad')->nullable();
            $table->string('tipo_asentamiento')->nullable();
            $table->string('nombre_vialidad')->nullable();
            $table->string('nombre_asentamiento')->nullable();
            $table->string('numero_exterior')->nullable();
            $table->string('numero_exterior_2')->nullable();
            $table->string('numero_adicional')->nullable();
            $table->string('numero_adicional_2')->nullable();
            $table->string('numero_interior')->nullable();
            $table->string('lote')->nullable();
            $table->string('manzana')->nullable();
            $table->string('codigo_postal')->nullable();
            $table->string('lote_fraccionador')->nullable();
            $table->string('manzana_fraccionador')->nullable();
            $table->string('etapa_fraccionador')->nullable();
            $table->text('nombre_predio')->nullable();
            $table->string('nombre_edificio')->nullable();
            $table->string('clave_edificio')->nullable();
            $table->string('departamento_edificio')->nullable();
            $table->text('entre_vialidades');
            $table->string('estado');
            $table->string('municipio');
            $table->string('ciudad')->nullable();
            $table->string('localidad')->nullable();
            $table->string('poblado')->nullable();
            $table->string('ejido')->nullable();
            $table->string('parcela')->nullable();
            $table->string('solar')->nullable();
            $table->foreignId('escritura_id')->constrained();
            $table->foreignId('distrito_id')->constrained();
            $table->unsignedInteger('cc_estado');
            $table->unsignedInteger('cc_region_catastral');
            $table->unsignedInteger('cc_municipio');
            $table->unsignedInteger('cc_zona_catastral');
            $table->unsignedInteger('cc_sector');
            $table->unsignedInteger('cc_manzana');
            $table->unsignedInteger('cc_predio');
            $table->unsignedInteger('cc_edificio');
            $table->unsignedInteger('cc_departamento');
            $table->unsignedInteger('cp_localidad');
            $table->unsignedInteger('cp_oficina');
            $table->unsignedInteger('cp_tipo_predio');
            $table->text('descripcion')->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignId('movimiento_registral_id')->constrained();
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
        Schema::dropIfExists('propiedads');
    }
};
