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
        Schema::table('emonev', function (Blueprint $table) {
            $table->decimal('anggaran', 18, 2)->default(0)->change();
            $table->decimal('fisik', 18, 2)->default(0)->change();
            $table->decimal('gap', 18, 2)->default(0)->change();
            $table->decimal('kinerja_satker', 10, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emonev', function (Blueprint $table) {
            //
        });
    }
};
