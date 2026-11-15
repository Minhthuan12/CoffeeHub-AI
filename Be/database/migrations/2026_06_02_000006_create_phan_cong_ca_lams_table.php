<?php

use Illuminate\Database\Migrations\Migration; 
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phan_cong_ca_lams', function (Blueprint $table) {
            $table->id();
            $table->string('ghi_chu')->nullable();
            $table->integer('tinh_trang')->default(0);
            $table->integer('id_nhan_vien');
            $table->integer('id_ca_lam_viec');
            $table->date('ngay_lam_viec')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phan_cong_ca_lams');
    }
};
