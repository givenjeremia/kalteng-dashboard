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
        Schema::create('ikpa_scores', function (Blueprint $table) {
            $table->bigIncrements('pkid');
            $table->uuid('uuid')->unique();

    
            $table->foreignId('departement_id')->nullable()->constrained('departements', 'pkid');

            $table->integer('tahun')->default(date('Y'));
            $table->integer('bulan')->default(1);

            $table->decimal('deviation_dipa', 6, 2)->default(0);
            $table->decimal('revisi_dipa', 6, 2)->default(0);
            $table->decimal('penyerapan_anggaran', 6, 2)->default(0);
            $table->decimal('capaian_output', 6, 2)->default(0);
            $table->decimal('penyelesaian_tagihan', 6, 2)->default(0);
            $table->decimal('pengelolaan_up_tup', 6, 2)->default(0);
            $table->decimal('belanja_kontraktual', 6, 2)->default(0);
            $table->decimal('nilai_ikpa', 6, 2)->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ikpa_scores');
    }
};
