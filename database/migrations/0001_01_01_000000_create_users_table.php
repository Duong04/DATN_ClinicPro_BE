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
        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('value');
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('permission_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('action_id');
            $table->timestamps();

            $table->foreign('permission_id')->references('id')->on('permissions');
            $table->foreign('action_id')->references('id')->on('actions');
        });


        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('action_id');
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('permission_id')->references('id')->on('permissions');
            $table->foreign('action_id')->references('id')->on('actions');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fullname')->nullable();
            $table->enum('status', ['active', 'inactive', 'disabled'])->nullable()->default('inactive');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->unsignedBigInteger('role_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('role_id')->references('id')->on('roles');
        });

        Schema::create('identity_card_types', function (Blueprint $table) {
            $table->id();
            $table->string('type_name', 50)->unique();
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('manager_id');
            $table->timestamps();

            $table->foreign('manager_id')->references('id')->on('users');
        });

        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->string('address')->nullable();
            $table->string('avatar')->nullable();
            $table->string('phone_number', 10)->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->default('other');
            $table->unsignedBigInteger('user_id');
            $table->date('date_of_birth')->nullable();
            $table->unsignedBigInteger('identity_card_id')->nullable();
            $table->string('identity_card_number')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('identity_card_id')->references('id')->on('identity_card_types');
            $table->foreign('department_id')->references('id')->on('departments');
        });

        Schema::create('social_logins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('provider');
            $table->string('provider_user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('specialty');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->text('medical_history')->nullable();
            $table->string('insurance_number')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('medical_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->text('description');
            $table->string('diagnosis');
            $table->text('treatment');
            $table->unsignedBigInteger('doctor_id');
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('doctor_id')->references('id')->on('doctors');
        });

        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->timestamp('appointment_date');
            $table->decimal('deposit_amount', 10, 2)->nullable();
            $table->boolean('is_deposit_paid')->default(false);
            $table->enum('booking_type', ['offline', 'online']);
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('doctor_id')->references('id')->on('doctors');
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appointment_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['credit_card', 'bank_transfer', 'cash', 'paypal']);
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->boolean('is_deposit')->default(false);
            $table->timestamps();

            $table->foreign('appointment_id')->references('id')->on('appointments');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appointment_id');
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_status', ['paid', 'unpaid']);
            $table->timestamps();

            $table->foreign('appointment_id')->references('id')->on('appointments');
        });

        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('unit', 50);
            $table->decimal('price_per_unit', 10, 2);
            $table->timestamps();
        });

        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('patient_id');
            $table->timestamps();

            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->foreign('patient_id')->references('id')->on('patients');
        });

        Schema::create('prescription_medications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prescription_id');
            $table->unsignedBigInteger('medication_id');
            $table->integer('quantity');
            $table->string('dosage')->nullable();
            $table->timestamps();

            $table->foreign('prescription_id')->references('id')->on('prescriptions');
            $table->foreign('medication_id')->references('id')->on('medications');
        });

        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->mediumText('content')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->integer('rating');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('package_id')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('actions');
        Schema::dropIfExists('permission_actions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
