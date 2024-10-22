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
        Schema::create('category_medication', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropPrimary('id');
            $table->uuid('id')->change();
            $table->primary('id');
            $table->string('description', 255);
            $table->dropColumn(['frequency', 'dosage', 'duration', 'instructions']);
        });

        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('category_medication');
        });

        Schema::create('prescription_infos', function (Blueprint $table) {
            $table->id();
            $table->uuid('prescription_id');
            $table->string('instructions');
            $table->unsignedBigInteger('medication_id');
            $table->integer('quantity');
            $table->integer('duration');
            $table->timestamps();

            $table->foreign('prescription_id')->references('id')->on('prescriptions')->onDelete('cascade');
            $table->foreign('medication_id')->references('id')->on('medications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_medication');
        Schema::dropIfExists('medications');
        Schema::dropIfExists('prescription_infos');
    }
};
