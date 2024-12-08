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
         // Tạo bảng services (dịch vụ)
         Schema::create('services', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('service_name');
            $table->text('description')->nullable();
            $table->double('price');
            $table->timestamps();
        });

        // Tạo bảng payment_methods (phương thức thanh toán)
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('method_name'); // Tiền mặt, Thẻ tín dụng, Chuyển khoản, v.v.
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Tạo bảng service_medical_history (liên kết giữa service và medical_history)
        Schema::create('service_medical_history', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('service_id');
            $table->string('medical_history_id'); // record_id đến bảng medical_histories
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('medical_history_id')->references('id')->on('medical_histories')->onDelete('cascade');
        });

        // Tạo bảng invoices (hóa đơn)
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('patient_id'); // Liên kết với bảng patients
            $table->double('total_amount'); // Tổng số tiền của hóa đơn
            $table->enum('status', ['pending', 'paid', 'partially_paid'])->default('pending'); // Trạng thái hóa đơn
            $table->timestamps();

            // Khóa ngoại đến bảng patients (giả sử bạn đã có bảng patients)
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
        });

        // Tạo bảng payments (thanh toán)
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('invoice_id'); // Liên kết đến bảng invoices
            $table->string('payment_method_id'); // Liên kết đến phương thức thanh toán
            $table->double('amount'); // Số tiền thanh toán
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending'); // Trạng thái thanh toán
            $table->timestamps();

            // Khóa ngoại đến bảng invoices và payment_methods
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('service_medical_history');
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('services');
        Schema::dropIfExists('invoices');
    }
};
