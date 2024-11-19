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
        Schema::table('examination_packages', function (Blueprint $table) {
            $table->uuid('specialty_id')->nullable();
            $table->foreign('specialty_id')->references('id')->on('specialties')->onDelete('set null');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->uuid('package_id')->nullable();
            $table->foreign('package_id')->references('id')->on('examination_packages')->onDelete('set null');
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
