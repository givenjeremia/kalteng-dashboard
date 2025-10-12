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
        Schema::create('emonev', function (Blueprint $table) {
            $table->bigIncrements('pkid');
            $table->uuid('uuid')->unique();

    
            $table->foreignId('departement_id')->nullable()->constrained('departements', 'pkid');

    
            $table->integer('tahun')->default(date('Y'));
            $table->integer('bulan')->default(1);


            $table->decimal('anggaran', 12, 2)->default(0);
            $table->decimal('fisik', 12, 2)->default(0);
            $table->decimal('gap', 12, 2)->default(0);
            $table->decimal('kinerja_satker', 6, 2)->default(0);
            $table->string('keterangan')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emonev');
    }
};
