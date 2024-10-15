<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn(['description', 'quantity', 'doctor_id']);

            $table->string('frequency', 255);
            $table->unsignedBigInteger('user_id');
            $table->integer('duration');
            $table->text('instructions');
            $table->foreign('user_id')->references('id')->on('doctors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->text('description');
            $table->integer('quantity');
            $table->unsignedBigInteger('doctor_id');

            $table->dropColumn(['frequency', 'user_id', 'duration', 'instructions']);
        });
    }
};
