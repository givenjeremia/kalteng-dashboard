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
        Schema::create('ceilings', function (Blueprint $table) {
            $table->bigIncrements('pkid');
            $table->uuid('uuid')->unique();
            $table->foreignId('departement_id')->nullable()->constrained('departements', 'pkid');

            $table->integer('tahun');
            $table->integer('bulan');

            $table->string('type_data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ceilings');
    }
};
