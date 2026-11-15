<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NhaCungCapSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('nha_cung_caps')->insert([
            [
                'id' => 1,
                'ten_nha_cung_cap' => 'Cửa Hàng Nông Sản Sạch Đà Lạt',
                'email' => 'dalatfarm@gmail.com',
                'nguoi_lien_he' => 'Nguyễn Văn Đà Lạt',
                'so_dien_thoai' => '02633111222',
                'dia_chi' => '12 Trần Hưng Đạo, TP. Đà Lạt',
                'tinh_trang' => 1
            ],
            [
                'id' => 2,
                'ten_nha_cung_cap' => 'Đại Lý Sữa & Nguyên Liệu Pha Chế Vinamilk',
                'email' => 'vinamilk@gmail.com',
                'nguoi_lien_he' => 'Trần Thị Sữa',
                'so_dien_thoai' => '02838111222',
                'dia_chi' => '10 Tân Trào, Quận 7, TP. HCM',
                'tinh_trang' => 1
            ],
            [
                'id' => 3,
                'ten_nha_cung_cap' => 'Cà Phê Nguyên Chất Trung Nguyên',
                'email' => 'trungnguyen@gmail.com',
                'nguoi_lien_he' => 'Đặng Lê Trung Nguyên',
                'so_dien_thoai' => '02839111222',
                'dia_chi' => '82 Bùi Thị Xuân, Quận 1, TP. HCM',
                'tinh_trang' => 1
            ]
        ]);
    }
}
