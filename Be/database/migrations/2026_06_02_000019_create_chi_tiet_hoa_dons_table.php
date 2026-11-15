<?php

use Illuminate\Database\Migrations\Migration; 
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chi_tiet_hoa_dons', function (Blueprint $table) {
            $table->id();
            $table->integer('id_chi_tiet_cha')->nullable();
            $table->integer('so_luong');
            $table->integer('don_gia');
            $table->text('ghi_chu')->nullable();
            $table->integer('id_hoa_don');
            $table->integer('id_san_pham');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_hoa_dons');
    }
};
