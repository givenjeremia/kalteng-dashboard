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
        Schema::table('e_performances', function (Blueprint $table) {
            $table->longText('sasaran')->nullable();
            $table->longText('indikator')->nullable();
            $table->longText('satuan')->nullable();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('e_performances', function (Blueprint $table) {
            //
        });
    }
};
