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
        Schema::table('hoa_dons', function (Blueprint $table) {
            if (!Schema::hasColumn('hoa_dons', 'yeu_cau_tien_mat')) {
                $table->tinyInteger('yeu_cau_tien_mat')->default(0)->comment('1: Khách yêu cầu thu tiền mặt tại bàn (UC17)')->after('phuong_thuc_thanh_toan');
            }
            if (!Schema::hasColumn('hoa_dons', 'ly_do_huy')) {
                $table->string('ly_do_huy', 255)->nullable()->comment('Lý do thu ngân từ chối hoặc hủy đơn')->after('yeu_cau_tien_mat');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hoa_dons', function (Blueprint $table) {
            if (Schema::hasColumn('hoa_dons', 'yeu_cau_tien_mat')) {
                $table->dropColumn('yeu_cau_tien_mat');
            }
            if (Schema::hasColumn('hoa_dons', 'ly_do_huy')) {
                $table->dropColumn('ly_do_huy');
            }
        });
    }
};
