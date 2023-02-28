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
            $table->unsignedTinyInteger('numero_propiedad')->comment("Número de propiedad dentro de la escritura");
            $table->unsignedDecimal('valor', 15,2);
            $table->string('tipo_moneda');
            $table->unsignedInteger('superficie_terreno');
            $table->unsignedInteger('superficie_construccion');
            $table->text('calle');
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
            $table->timestamps();

            $table->unsignedInteger('estado');
            $table->unsignedInteger('region_catastral');
            $table->unsignedInteger('municipio');
            $table->unsignedInteger('zona_catastral');
            $table->unsignedInteger('localidad');
            $table->unsignedInteger('sector');
            $table->unsignedInteger('manzana');
            $table->unsignedInteger('predio');
            $table->unsignedInteger('edificio');
            $table->unsignedInteger('departamento');
            $table->unsignedInteger('clave_oficina');
            $table->unsignedInteger('tipo_predio');
            $table->unsignedInteger('numero_predio');
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
