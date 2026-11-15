<?php

use Illuminate\Database\Migrations\Migration; 
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nguyen_lieus', function (Blueprint $table) {
            $table->id();
            $table->string('ten_nguyen_lieu');
            $table->string('don_vi_tinh');
            $table->integer('so_luong_ton');
            $table->integer('muc_canh_bao');
            $table->integer('tinh_trang');
            $table->integer('id_nha_cung_cap');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nguyen_lieus');
    }
};
