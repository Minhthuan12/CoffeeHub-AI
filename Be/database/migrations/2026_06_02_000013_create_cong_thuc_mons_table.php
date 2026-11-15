<?php

use Illuminate\Database\Migrations\Migration; 
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cong_thuc_mons', function (Blueprint $table) {
            $table->id();
            $table->double('so_luong');
            $table->string('don_vi');
            $table->integer('id_san_pham');
            $table->integer('id_nguyen_lieu');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cong_thuc_mons');
    }
};
