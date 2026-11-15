<?php

use Illuminate\Database\Migrations\Migration; 
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hoa_dons', function (Blueprint $table) {
            $table->id();
            $table->string('ma_hoa_don')->unique();
            $table->integer('tong_tien');
            $table->integer('tien_truoc_giam')->default(0);
            $table->integer('so_tien_giam')->default(0);
            $table->integer('tinh_trang')->default(1);
            $table->integer('id_ban');
            $table->integer('id_khach_hang');
            $table->integer('id_phan_cong_ca_lam');
            $table->integer('id_voucher')->nullable();
            $table->integer('phuong_thuc_thanh_toan')->nullable()->comment('1=Tiền mặt, 2=Chuyển khoản/QR');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hoa_dons');
    }
};
