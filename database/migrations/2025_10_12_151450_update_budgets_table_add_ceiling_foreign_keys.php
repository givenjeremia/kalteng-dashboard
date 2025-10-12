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
        Schema::table('budgets', function (Blueprint $table) {
            $table->dropColumn(['pagu_pegawai', 'pagu_barang', 'pagu_modal']);
            $table->foreignId('ceiling_id')->nullable()
                ->constrained('ceilings', 'pkid')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->dropForeign(['ceiling_id']);
            $table->dropColumn(['ceiling_id']);

            $table->bigInteger('pagu_pegawai')->default(0);
            $table->bigInteger('pagu_barang')->default(0);
            $table->bigInteger('pagu_modal')->default(0);
        });
    }
};
