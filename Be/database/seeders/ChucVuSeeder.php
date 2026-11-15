<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChucVuSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('chuc_vus')->insert([
            ['id' => 1, 'ten_chuc_vu' => 'Admin/Quản lý', 'slug_chuc_vu' => 'admin', 'tinh_trang' => 1],
            ['id' => 2, 'ten_chuc_vu' => 'Trưởng ca', 'slug_chuc_vu' => 'truong-ca', 'tinh_trang' => 1],
            ['id' => 3, 'ten_chuc_vu' => 'Pha chế', 'slug_chuc_vu' => 'pha-che', 'tinh_trang' => 1],
            ['id' => 4, 'ten_chuc_vu' => 'Bếp', 'slug_chuc_vu' => 'bep', 'tinh_trang' => 1],
            ['id' => 5, 'ten_chuc_vu' => 'Phục vụ', 'slug_chuc_vu' => 'phuc-vu', 'tinh_trang' => 1],
            ['id' => 6, 'ten_chuc_vu' => 'Thu ngân', 'slug_chuc_vu' => 'thu-ngan', 'tinh_trang' => 1],
            ['id' => 7, 'ten_chuc_vu' => 'Bảo vệ', 'slug_chuc_vu' => 'bao-ve', 'tinh_trang' => 1],
            ['id' => 8, 'ten_chuc_vu' => 'Tạp vụ', 'slug_chuc_vu' => 'tap-vu', 'tinh_trang' => 1],
            ['id' => 9, 'ten_chuc_vu' => 'Kế toán', 'slug_chuc_vu' => 'ke-toan', 'tinh_trang' => 1],
        ]);
    }
}
