<?php

use Illuminate\Database\Migrations\Migration; 
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phieu_nhap_khos', function (Blueprint $table) {
            $table->id();
            $table->integer('tong_tien')->default(0);
            $table->string('ghi_chu')->nullable();
            $table->integer('id_nhan_vien')->nullable();
            $table->integer('tinh_trang')->default(0); // 0: Nháp, 1: Đã chốt
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phieu_nhap_khos');
    }
};
