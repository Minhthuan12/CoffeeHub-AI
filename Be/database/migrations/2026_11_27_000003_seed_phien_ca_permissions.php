<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\ChucNang;
use App\Models\ChiTietPhanQuyen;
use App\Models\ChucVu;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Thêm ChucNang
        $chucNangs = [
            [
                'id'            => 129,
                'ten_chuc_nang' => 'Quản lý Giao Ca (Mở/Chốt ca, Z-Report)',
            ],
            [
                'id'            => 130,
                'ten_chuc_nang' => 'Xem Lịch Sử Phiên Ca & Báo Cáo',
            ],
        ];

        foreach ($chucNangs as $cn) {
            DB::table('chuc_nangs')->updateOrInsert(
                ['id' => $cn['id']],
                ['ten_chuc_nang' => $cn['ten_chuc_nang'], 'created_at' => now(), 'updated_at' => now()]
            );
        }

        // 2. Cấp quyền mặc định cho các chức vụ hiện có
        $chucVus = DB::table('chuc_vus')->pluck('id');
        foreach ($chucVus as $idQuyen) {
            foreach ([129, 130] as $idCn) {
                DB::table('chi_tiet_phan_quyens')->updateOrInsert(
                    [
                        'id_quyen'     => $idQuyen,
                        'id_chuc_nang' => $idCn,
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('chi_tiet_phan_quyens')->whereIn('id_chuc_nang', [129, 130])->delete();
        DB::table('chuc_nangs')->whereIn('id', [129, 130])->delete();
    }
};
