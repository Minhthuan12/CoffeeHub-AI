<?php

use Illuminate\Database\Migrations\Migration; 
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('san_phams', function (Blueprint $table) {
            $table->id();
            $table->string('ten_san_pham');
            $table->string('slug_san_pham')->unique();
            $table->string('ma_code')->nullable();
            $table->text('mo_ta');
            $table->string('kich_thuoc');
            $table->integer('gia_ban');
            $table->text('hinh_anh');
            $table->integer('tinh_trang');
            $table->integer('id_danh_muc');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('san_phams');
    }
};
