<?php

use Illuminate\Database\Migrations\Migration; 
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chi_tiet_phieu_nhaps', function (Blueprint $table) {
            $table->id();
            $table->integer('so_luong');
            $table->integer('don_gia');
            $table->integer('thanh_tien');
            $table->integer('id_nguyen_lieu');
            $table->integer('id_phieu_nhap');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_phieu_nhaps');
    }
};
