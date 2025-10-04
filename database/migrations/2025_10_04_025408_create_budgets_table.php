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
        Schema::create('budgets', function (Blueprint $table) {
            $table->bigIncrements('pkid');
            $table->uuid('uuid')->unique();
            $table->foreignId('departement_id')->nullable()->constrained('departements', 'pkid');

            $table->bigInteger('pagu_pegawai')->default(0);
            $table->bigInteger('realisasi_pegawai')->default(0);
            $table->bigInteger('pagu_barang')->default(0);
            $table->bigInteger('realisasi_barang')->default(0);
            $table->bigInteger('pagu_modal')->default(0);
            $table->bigInteger('realisasi_modal')->default(0);
            
            $table->integer('tahun');
            $table->integer('bulan');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
