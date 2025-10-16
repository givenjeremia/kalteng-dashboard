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
        Schema::create('e_performances', function (Blueprint $table) {
            $table->bigIncrements('pkid');
            $table->uuid('uuid')->unique();

            $table->foreignId('departement_id')->nullable()->constrained('departements', 'pkid'); // Tidak Pake

            $table->integer('tahun')->default(date('Y')); // Tidak Pake
            $table->integer('bulan')->default(1); // Tidak Pake

            $table->decimal('target', 6, 2)->default(0); // Pake
            $table->decimal('tercapai', 6, 2)->default(0); // Pake
            $table->decimal('persentase_capaian', 6, 2)->default(0); // Pake

            $table->decimal('tidak_tercapai', 6, 2)->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('e_performances');
    }
};
