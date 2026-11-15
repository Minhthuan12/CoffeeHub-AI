<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhieuNhapKhoSeeder extends Seeder
{
    public function run(): void
    {
        // Gieo phiếu nhập kho
        DB::table('phieu_nhap_khos')->insert([
            [
                'id' => 1,
                'id_nhan_vien' => 1,
                'tong_tien' => 1450000,
                'ghi_chu' => 'Nhập nguyên liệu định kỳ ngày 01/06/2026',
                'created_at' => '2026-06-01 08:30:00',
                'updated_at' => '2026-06-01 08:30:00'
            ],
            [
                'id' => 2,
                'id_nhan_vien' => 1,
                'tong_tien' => 980000,
                'ghi_chu' => 'Nhập nguyên liệu định kỳ ngày 04/06/2026',
                'created_at' => '2026-06-04 08:30:00',
                'updated_at' => '2026-06-04 08:30:00'
            ]
        ]);

        // Gieo chi tiết phiếu nhập
        DB::table('chi_tiet_phieu_nhaps')->insert([
            // Phiếu nhập 1
            [
                'id' => 1,
                'id_nguyen_lieu' => 1, // Hạt Cà Phê Robusta
                'id_phieu_nhap' => 1,
                'so_luong' => 20,
                'don_gia' => 50000,
                'thanh_tien' => 1000000,
                'created_at' => '2026-06-01 08:45:00',
                'updated_at' => '2026-06-01 08:45:00'
            ],
            [
                'id' => 2,
                'id_nguyen_lieu' => 3, // Sữa Đặc Vinamilk
                'id_phieu_nhap' => 1,
                'so_luong' => 15,
                'don_gia' => 30000,
                'thanh_tien' => 450000,
                'created_at' => '2026-06-01 08:45:00',
                'updated_at' => '2026-06-01 08:45:00'
            ],
            // Phiếu nhập 2
            [
                'id' => 3,
                'id_nguyen_lieu' => 2, // Hạt Cà Phê Arabica
                'id_phieu_nhap' => 2,
                'so_luong' => 10,
                'don_gia' => 68000,
                'thanh_tien' => 680000,
                'created_at' => '2026-06-04 08:45:00',
                'updated_at' => '2026-06-04 08:45:00'
            ],
            [
                'id' => 4,
                'id_nguyen_lieu' => 5, // Đường Cát Trắng
                'id_phieu_nhap' => 2,
                'so_luong' => 20,
                'don_gia' => 15000,
                'thanh_tien' => 300000,
                'created_at' => '2026-06-04 08:45:00',
                'updated_at' => '2026-06-04 08:45:00'
            ]
        ]);

    }
}
