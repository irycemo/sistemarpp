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

        Schema::table('movimiento_registrals', function (Blueprint $table) {
            $table->dropColumn('predio_id');
            $table->dropColumn('folio_real');
        });

        Schema::table('movimiento_registrals', function (Blueprint $table) {
            $table->foreignId('folio_real')->nullable()->references('id')->on('folio_reals');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
