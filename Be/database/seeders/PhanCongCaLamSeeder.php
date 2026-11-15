<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhanCongCaLamSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('phan_cong_ca_lams')->insert([
            // Ngày 2026-06-15
            ['id' => 1,  'id_nhan_vien' => 2, 'id_ca_lam_viec' => 1, 'ngay_lam_viec' => '2026-06-15', 'ghi_chu' => 'Ca sáng pha chế', 'tinh_trang' => 1],
            ['id' => 2,  'id_nhan_vien' => 3, 'id_ca_lam_viec' => 1, 'ngay_lam_viec' => '2026-06-15', 'ghi_chu' => 'Ca sáng phục vụ', 'tinh_trang' => 1],
            ['id' => 3,  'id_nhan_vien' => 5, 'id_ca_lam_viec' => 1, 'ngay_lam_viec' => '2026-06-15', 'ghi_chu' => 'Ca sáng bảo vệ',  'tinh_trang' => 1],
            ['id' => 4,  'id_nhan_vien' => 2, 'id_ca_lam_viec' => 2, 'ngay_lam_viec' => '2026-06-15', 'ghi_chu' => 'Ca chiều pha chế', 'tinh_trang' => 1],
            ['id' => 5,  'id_nhan_vien' => 4, 'id_ca_lam_viec' => 2, 'ngay_lam_viec' => '2026-06-15', 'ghi_chu' => 'Ca chiều thu ngân', 'tinh_trang' => 1],
            ['id' => 6,  'id_nhan_vien' => 1, 'id_ca_lam_viec' => 3, 'ngay_lam_viec' => '2026-06-15', 'ghi_chu' => 'Ca tối quản lý',  'tinh_trang' => 1],
            ['id' => 7,  'id_nhan_vien' => 3, 'id_ca_lam_viec' => 3, 'ngay_lam_viec' => '2026-06-15', 'ghi_chu' => 'Ca tối phục vụ',  'tinh_trang' => 1],

            // Ngày 2026-07-06
            ['id' => 8,  'id_nhan_vien' => 2, 'id_ca_lam_viec' => 1, 'ngay_lam_viec' => '2026-07-06', 'ghi_chu' => 'Ca sáng pha chế', 'tinh_trang' => 1],
            ['id' => 9,  'id_nhan_vien' => 3, 'id_ca_lam_viec' => 1, 'ngay_lam_viec' => '2026-07-06', 'ghi_chu' => 'Ca sáng phục vụ', 'tinh_trang' => 1],
            ['id' => 10, 'id_nhan_vien' => 5, 'id_ca_lam_viec' => 1, 'ngay_lam_viec' => '2026-07-06', 'ghi_chu' => 'Ca sáng bảo vệ',  'tinh_trang' => 1],
            ['id' => 11, 'id_nhan_vien' => 2, 'id_ca_lam_viec' => 2, 'ngay_lam_viec' => '2026-07-06', 'ghi_chu' => 'Ca chiều pha chế', 'tinh_trang' => 1],
            ['id' => 12, 'id_nhan_vien' => 4, 'id_ca_lam_viec' => 2, 'ngay_lam_viec' => '2026-07-06', 'ghi_chu' => 'Ca chiều thu ngân', 'tinh_trang' => 1],
            ['id' => 13, 'id_nhan_vien' => 1, 'id_ca_lam_viec' => 3, 'ngay_lam_viec' => '2026-07-06', 'ghi_chu' => 'Ca tối quản lý',  'tinh_trang' => 1],
            ['id' => 14, 'id_nhan_vien' => 3, 'id_ca_lam_viec' => 3, 'ngay_lam_viec' => '2026-07-06', 'ghi_chu' => 'Ca tối phục vụ',  'tinh_trang' => 1],

            // Ngày 2026-07-21
            ['id' => 15, 'id_nhan_vien' => 2, 'id_ca_lam_viec' => 1, 'ngay_lam_viec' => '2026-07-21', 'ghi_chu' => 'Ca sáng pha chế', 'tinh_trang' => 1],
            ['id' => 16, 'id_nhan_vien' => 3, 'id_ca_lam_viec' => 1, 'ngay_lam_viec' => '2026-07-21', 'ghi_chu' => 'Ca sáng phục vụ', 'tinh_trang' => 1],
            ['id' => 17, 'id_nhan_vien' => 4, 'id_ca_lam_viec' => 2, 'ngay_lam_viec' => '2026-07-21', 'ghi_chu' => 'Ca chiều thu ngân', 'tinh_trang' => 1],
            ['id' => 18, 'id_nhan_vien' => 1, 'id_ca_lam_viec' => 3, 'ngay_lam_viec' => '2026-07-21', 'ghi_chu' => 'Ca tối quản lý',  'tinh_trang' => 1],
            ['id' => 19, 'id_nhan_vien' => 3, 'id_ca_lam_viec' => 3, 'ngay_lam_viec' => '2026-07-21', 'ghi_chu' => 'Ca tối phục vụ',  'tinh_trang' => 1],
            ['id' => 20, 'id_nhan_vien' => 2, 'id_ca_lam_viec' => 3, 'ngay_lam_viec' => '2026-07-21', 'ghi_chu' => 'Ca tối pha chế',  'tinh_trang' => 1],
        ]);
    }
}
