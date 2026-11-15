<?php

use Illuminate\Database\Migrations\Migration; 
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phan_quyens', function (Blueprint $table) {
            $table->id();
            $table->integer('id_chuc_vu');
            $table->integer('id_chuc_nang');
            $table->integer('them')->default(0);
            $table->integer('sua')->default(0);
            $table->integer('xoa')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phan_quyens');
    }
};
