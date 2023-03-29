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
        Schema::create('predios', function (Blueprint $table) {
            $table->id();
            $table->string("folio_real")->nullbale()->unique();
            $table->unsignedTinyInteger('numero_propiedad')->comment("Número de propiedad dentro de la escritura");
            $table->unsignedDecimal('valor', 15,2);
            $table->string('tipo_moneda');
            $table->unsignedInteger('superficie_terreno');
            $table->unsignedInteger('superficie_construccion');
            $table->text('vialidad');
            $table->string('numero_exterior');
            $table->string('numero_interior');
            $table->string('colonia');
            $table->unsignedInteger('cp');
            $table->text('entre_vialidades');
            $table->string('municipio');
            $table->string('localidad')->nullable();
            $table->string('poblado')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('ejido')->nullable();
            $table->string('parcela')->nullable();
            $table->text('linderos')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('estado');
            $table->foreignId('escritura_id')->constrained();
            $table->foreignId('distrito_id')->constrained();
            /* Comunicación con catastro */
            $table->unsignedInteger('cc_estado');
            $table->unsignedInteger('cc_region_catastral');
            $table->unsignedInteger('cc_municipio');
            $table->unsignedInteger('cc_zona_catastral');
            $table->unsignedInteger('cc_localidad');
            $table->unsignedInteger('cc_sector');
            $table->unsignedInteger('cc_manzana');
            $table->unsignedInteger('cc_predio');
            $table->unsignedInteger('cc_edificio');
            $table->unsignedInteger('cc_departamento');
            $table->unsignedInteger('cp_localidad');
            $table->unsignedInteger('cp_oficina');
            $table->unsignedInteger('cp_tipo_predio');
            $table->unsignedInteger('cp_numero_predio');

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
        Schema::dropIfExists('predios');
    }
};
