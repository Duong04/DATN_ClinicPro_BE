<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tạo bảng category_medication
        Schema::create('category_medication', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    
        // Tạo bảng medications
        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();
    
            // Khóa ngoại cho category_id
            $table->foreign('category_id')->references('id')->on('category_medication');
        });
    
        // Tạo bảng prescriptions
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Sử dụng UUID làm khóa chính
            $table->unsignedBigInteger('user_id'); // Người tạo đơn thuốc
            $table->unsignedBigInteger('patient_id'); // Bệnh nhân
            $table->string('name'); // Tên đơn thuốc
            $table->text('description')->nullable(); // Mô tả đơn thuốc
            $table->timestamps();
    
            // Khóa ngoại liên kết đến bảng users (giả sử bạn có bảng users)
            $table->foreign('user_id')->references('id')->on('users');
            // Khóa ngoại liên kết đến bảng patients (giả sử bạn có bảng patients)
            $table->foreign('patient_id')->references('id')->on('patients');
        });
    
        // Tạo bảng prescription_infos
        Schema::create('prescription_infos', function (Blueprint $table) {
            $table->id();
            $table->uuid('prescription_id'); // Liên kết đến đơn thuốc
            $table->string('instructions'); // Hướng dẫn dùng thuốc
            $table->unsignedBigInteger('medication_id'); // Liên kết đến bảng medications
            $table->integer('quantity'); // Số lượng thuốc
            $table->integer('duration'); // Thời gian dùng thuốc
            $table->timestamps();
    
            // Khóa ngoại liên kết đến bảng prescriptions
            $table->foreign('prescription_id')->references('id')->on('prescriptions')->onDelete('cascade');
            // Khóa ngoại liên kết đến bảng medications
            $table->foreign('medication_id')->references('id')->on('medications')->onDelete('cascade');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('prescription_infos');
        Schema::dropIfExists('prescriptions');
        Schema::dropIfExists('medications');
        Schema::dropIfExists('category_medication');
    }
    
};
