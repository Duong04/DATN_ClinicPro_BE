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
    
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('cascade');
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
    
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('cascade');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
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

        Schema::create('identity_cards', function (Blueprint $table) {
            $table->id();
            $table->string('type_name', 50)->unique();
            $table->string('identity_card_number')->nullable();
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('manager_id');
            $table->timestamps();

            $table->foreign('manager_id')->references('id')->on('users');
        });

        Schema::create('user_infos', function (Blueprint $table) {
            $table->id();
            $table->string('fullname')->nullable();
            $table->string('address')->nullable();
            $table->string('avatar')->nullable();
            $table->string('phone_number', 10)->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->default('other')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->date('dob')->nullable();
            $table->unsignedBigInteger('identity_card_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('identity_card_id')->references('id')->on('identity_cards');
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
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('identity_card_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('identity_card_id')->references('id')->on('identity_cards');
        });

        Schema::create('patient_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->string('fullname', 255);
            $table->string('email', 255)->nullable();
            $table->string('phone_number', 15)->nullable();
            $table->string('address', 255)->nullable();
            $table->date('dob')->nullable();
            $table->string('avatar')->nullable()->default('https://res.cloudinary.com/dsdyprt1q/image/upload/v1726997687/CLINIC/avatars/kcopet60brdlxcpybxjw.png');
            $table->enum('gender', ['male', 'female', 'other'])->default('other')->nullable();
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('patients');
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

        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('file', 255);
            $table->text('description')->nullable();

            $table->unsignedBigInteger('medical_history_id');

            $table->foreign('medical_history_id')->references('id')->on('medical_histories')->onDelete('cascade');
        });
    
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->timestamp('appointment_date');
            $table->decimal('deposit_amount', 10, 2)->nullable();
            $table->enum('booking_type', ['offline', 'online']);
            $table->string('appointment_type', 255)->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->text('cancellation_reason')->nullable();
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
    
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();

            $table->string('name', 255);
            $table->text('description');
            $table->integer('quantity');
            $table->string('dosage', 255)->nullable();
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('patient_id');
            $table->timestamps();

            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->foreign('patient_id')->references('id')->on('patients');
        });

        Schema::create('examination_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug', 255);
            $table->text('description')->nullable();
            $table->mediumText('content')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->integer('rating');
            $table->text('content')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('package_id')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('package_id')->references('id')->on('examination_packages')->onDelete('cascade');
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
        Schema::dropIfExists('examination_packages');
        Schema::dropIfExists('identity_card_types');
    }
};
