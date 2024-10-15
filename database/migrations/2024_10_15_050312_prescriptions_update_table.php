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
            $table->dropColumn(['description', 'quantity']);
            $table->string('frequency', 255);
            $table->integer('duration');
            $table->text('instructions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {}
};
