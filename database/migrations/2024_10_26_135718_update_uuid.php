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
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('actions');
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('category_medication');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('doctors');
        Schema::dropIfExists('examination_packages');
        Schema::dropIfExists('feedbacks');
        Schema::dropIfExists('files');
        Schema::dropIfExists('identity_cards');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('medical_histories');
        Schema::dropIfExists('medications');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('patients');
        Schema::dropIfExists('patient_infos');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('permission_actions');
        Schema::dropIfExists('prescriptions');
        Schema::dropIfExists('prescription_infos');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('users');
        Schema::dropIfExists('user_infos');
        Schema::dropIfExists('specialties');
        Schema::dropIfExists('social_logins');

        // Bảng 'actions'
        Schema::create('actions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('value');
            $table->timestamps();
        });

        // Bảng 'appointments'
        Schema::create('specialties', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Tạo bảng 'appointments'
        Schema::create('appointments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('patient_id');
            $table->timestamp('appointment_date');
            $table->decimal('deposit_amount', 10, 2)->nullable();
            $table->enum('booking_type', ['offline', 'online']);
            $table->string('appointment_type')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->text('cancellation_reason')->nullable();
            $table->uuid('user_id')->nullable();
            $table->uuid('specialty_id')->nullable();  // Phải là kiểu UUID
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('specialty_id')->references('id')->on('specialties')->onDelete('cascade');  // Đảm bảo kiểu UUID
        });

        // Bảng 'category_medication'
        Schema::create('category_medication', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->timestamps();
        });

        // Bảng 'departments'
        Schema::create('departments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->uuid('manager_id')->nullable();
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('manager_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Bảng 'doctors'
        Schema::create('doctors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('specialty_id')->nullable();
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('specialty_id')->references('id')->on('specialties')->onDelete('set null');
        });

        // Bảng 'examination_packages'
        Schema::create('examination_packages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->mediumText('content')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // Bảng 'feedbacks'
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('rating');
            $table->text('content')->nullable();
            $table->uuid('user_id');
            $table->uuid('package_id')->nullable();
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('examination_packages')->onDelete('cascade');
        });

        // Bảng 'files'
        Schema::create('files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('file');
            $table->text('description')->nullable();
            $table->uuid('medical_history_id');
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('medical_history_id')->references('id')->on('medical_histories')->onDelete('cascade');
        });

        // Bảng 'identity_cards'
        Schema::create('identity_cards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type_name', 50)->nullable();
            $table->string('identity_card_number')->nullable();
        });

        // Bảng 'invoices'
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('appointment_id');
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_status', ['paid', 'unpaid']);
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
        });

        // Bảng 'medical_histories'
        Schema::create('medical_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('patient_id');
            $table->text('description')->nullable();
            $table->string('diagnosis')->nullable();
            $table->text('treatment')->nullable();
            $table->uuid('doctor_id');
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
        });

        // Bảng 'medications'
        Schema::create('medications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->uuid('category_id');
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('category_id')->references('id')->on('category_medication')->onDelete('cascade');
        });

        // Bảng 'password_resets'
        Schema::create('password_resets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('otp');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Bảng 'patients'
        Schema::create('patients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('insurance_number')->nullable();
            $table->enum('status', ['active', 'inactive', 'deceased', 'transferred'])->default('active');
            $table->uuid('user_id')->nullable();
            $table->uuid('identity_card_id')->nullable();
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('identity_card_id')->references('id')->on('identity_cards')->onDelete('cascade');
        });

        // Bảng 'patient_infos'
        Schema::create('patient_infos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('patient_id')->nullable();
            $table->string('fullname');
            $table->string('avatar')->default('https://res.cloudinary.com/dsdyprt1q/image/upload/v1726997687/CLINIC/avatars/kcopet60brdlxcpybxjw.png');
            $table->string('email')->nullable();
            $table->string('phone_number', 15)->nullable();
            $table->string('address')->nullable();
            $table->date('dob')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->default('other');
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
        });

        // Bảng 'payments'
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('appointment_id');
            $table->uuid('user_id');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['credit_card', 'bank_transfer', 'cash', 'paypal']);
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->boolean('is_deposit')->default(false);
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Bảng 'permissions'
        Schema::create('permissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('description');
            $table->timestamps();
            $table->softDeletes();
        });

        // Bảng 'permission_actions'
        Schema::create('permission_actions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('permission_id');
            $table->uuid('action_id');
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('cascade');
        });

        // Bảng 'prescriptions'
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('patient_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
        });

        // Bảng 'prescription_infos'
        Schema::create('prescription_infos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('prescription_id');
            $table->uuid('medication_id');
            $table->string('instructions');
            $table->integer('quantity');
            $table->integer('duration');
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('prescription_id')->references('id')->on('prescriptions')->onDelete('cascade');
            $table->foreign('medication_id')->references('id')->on('medications')->onDelete('cascade');
        });

        // Bảng 'roles'
        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Bảng 'role_permissions'
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('role_id');
            $table->uuid('permission_id');
            $table->uuid('action_id');
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('cascade');
        });

        // Bảng 'users'
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('status', ['active', 'inactive', 'disabled'])->default('inactive');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('token')->nullable();
            $table->string('otp')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->uuid('role_id');
            $table->timestamps();
            $table->softDeletes();

            // Khóa ngoại
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

        Schema::create('social_logins', function (Blueprint $table) {
            $table->uuid('id')->primary();  // UUID
            $table->uuid('user_id');  // UUID, giống kiểu của bảng 'users'
            $table->string('provider');
            $table->string('provider_user_id');
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Bảng 'user_infos'
        Schema::create('user_infos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('fullname')->nullable();
            $table->string('address')->nullable();
            $table->string('avatar')->default('https://res.cloudinary.com/dsdyprt1q/image/upload/v1726997687/CLINIC/avatars/kcopet60brdlxcpybxjw.png');
            $table->string('phone_number', 10)->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->default('other');
            $table->uuid('user_id');
            $table->date('dob')->nullable();
            $table->uuid('identity_card_id')->nullable();
            $table->uuid('department_id')->nullable();
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('identity_card_id')->references('id')->on('identity_cards')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
