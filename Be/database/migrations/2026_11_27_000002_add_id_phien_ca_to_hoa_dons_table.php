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
            if (!Schema::hasColumn('hoa_dons', 'id_phien_ca')) {
                $table->unsignedBigInteger('id_phien_ca')->nullable()->after('id_phan_cong_ca_lam')->comment('Liên kết với phiên ca làm việc UC30');
                $table->index('id_phien_ca');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hoa_dons', function (Blueprint $table) {
            if (Schema::hasColumn('hoa_dons', 'id_phien_ca')) {
                $table->dropIndex(['id_phien_ca']);
                $table->dropColumn('id_phien_ca');
            }
        });
    }
};
