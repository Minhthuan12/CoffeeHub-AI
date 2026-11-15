<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('chi_tiet_hoa_dons', function (Blueprint $table) {
            $table->integer('tinh_trang')->default(0)->comment('0: Chờ chế biến, 1: Đang chế biến, 2: Đã hoàn thành');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chi_tiet_hoa_dons', function (Blueprint $table) {
            $table->dropColumn('tinh_trang');
        });
    }
};
