<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Table actions
        Schema::create('actions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->string('value', 255);
            $table->timestamps();
        });

        // Table permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->text('description');
            $table->timestamps();
            $table->softDeletes();
        });

        // Table permission_actions
        Schema::create('permission_actions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('permission_id')->unsigned();
            $table->bigInteger('action_id')->unsigned();
            $table->timestamps();

            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('cascade');
        });

        // Table roles
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->string('description', 255);
            $table->timestamps();
            $table->softDeletes();
        });

        // Table role_permissions
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('role_id')->unsigned();
            $table->bigInteger('permission_id')->unsigned();
            $table->bigInteger('action_id')->unsigned();
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('cascade');
        });

        // Table users
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_name', 255);
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->enum('status', ['active', 'inactive', 'disabled'])->default('inactive');
            $table->timestamps();
            $table->bigInteger('role_id')->unsigned();

            $table->foreign('role_id')->references('id')->on('roles');
        });

        // Table user_details
        Schema::create('user_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('address', 255)->nullable();
            $table->string('avatar', 255)->nullable();
            $table->string('phone_number', 10)->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->default('other');
            $table->bigInteger('user_id')->unsigned();
            $table->date('date_of_birth')->nullable();
            $table->bigInteger('department_id')->unsigned()->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments');
        });

        // Table departments
        Schema::create('departments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->bigInteger('manager_id')->unsigned();
            $table->timestamps();

            $table->foreign('manager_id')->references('id')->on('users');
        });

        // Table social_logins
        Schema::create('social_logins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->string('provider', 255);
            $table->string('provider_user_id', 255);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Table doctors
        Schema::create('doctors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('specialty', 255);
            $table->text('description');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Table patients
        Schema::create('patients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('medical_history');
            $table->string('insurance_number', 255)->nullable();
            $table->bigInteger('identity_card_id')->unsigned();
            $table->string('identity_card_number', 255);
            $table->bigInteger('user_id')->unsigned()->nullable();

            $table->foreign('identity_card_id')->references('id')->on('identity_card_types');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Table patient_infos
        Schema::create('patient_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('patient_id')->unsigned();
            $table->string('name', 255);
            $table->string('email', 255)->nullable();
            $table->string('phone_number', 15)->nullable();
            $table->string('address', 255)->nullable();
            $table->date('dob')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
        });

        // Table medical_histories
        Schema::create('medical_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('patient_id')->unsigned();
            $table->text('description');
            $table->string('diagnosis', 255);
            $table->text('treatment');
            $table->bigInteger('doctor_id')->unsigned();
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
        });

        // Table files
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('file', 255);
            $table->text('description')->nullable();
            $table->bigInteger('medical_history_id')->unsigned();
            $table->foreign('medical_history_id')->references('id')->on('medical_histories')->onDelete('cascade');
        });

        // Table appointments
        Schema::create('appointments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('patient_id')->unsigned();
            $table->bigInteger('doctor_id')->unsigned();
            $table->timestamp('appointment_date_time');
            $table->decimal('deposit_amount', 10, 2);
            $table->enum('booking_type', ['offline', 'online']);
            $table->string('appointment_type', 255)->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
        });

        // Table payments
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('appointment_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['credit_card', 'paypal', 'cash']);
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->timestamps();

            $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Table invoices
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('appointment_id')->unsigned();
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_status', ['paid', 'unpaid']);
            $table->timestamps();

            $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
        });

        // Table prescriptions
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('doctor_id')->unsigned();
            $table->bigInteger('patient_id')->unsigned();
            $table->string('name', 255);
            $table->text('description');
            $table->integer('quantity');
            $table->string('dosage', 255)->nullable();
            $table->timestamps();

            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
        });

        // Table feedbacks
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('rating');
            $table->text('content');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('package_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
        });

        // Table packages
        Schema::create('packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->string('slug', 255);
            $table->text('description')->nullable();
            $table->mediumText('content');
            $table->string('image', 255)->nullable();
            $table->timestamps();
        });

        // Table identity_card_types
        Schema::create('identity_card_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type_name', 50)->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('permission_actions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('users');
        Schema::dropIfExists('user_details');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('social_logins');
        Schema::dropIfExists('doctors');
        Schema::dropIfExists('patients');
        Schema::dropIfExists('patient_infos');
        Schema::dropIfExists('medical_histories');
        Schema::dropIfExists('files');
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('prescriptions');
        Schema::dropIfExists('feedbacks');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('identity_card_types');
    }
}
