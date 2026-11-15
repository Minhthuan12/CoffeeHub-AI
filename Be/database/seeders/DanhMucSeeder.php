<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DanhMucSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('danh_mucs')->insert([
            ['id' => 1, 'ten_danh_muc' => 'Cà Phê', 'slug_danh_muc' => 'ca-phe', 'icon_danh_muc' => 'fa-solid fa-mug-hot', 'tinh_trang' => 1],
            ['id' => 2, 'ten_danh_muc' => 'Trà & Trà Sữa', 'slug_danh_muc' => 'tra-tra-sua', 'icon_danh_muc' => 'fa-solid fa-leaf', 'tinh_trang' => 1],
            ['id' => 3, 'ten_danh_muc' => 'Đá Xay (Ice Blended)', 'slug_danh_muc' => 'da-xay-ice-blended', 'icon_danh_muc' => 'fa-solid fa-ice-cream', 'tinh_trang' => 1],
            ['id' => 4, 'ten_danh_muc' => 'Bánh Ngọt', 'slug_danh_muc' => 'banh-ngot', 'icon_danh_muc' => 'fa-solid fa-cookie', 'tinh_trang' => 1],
            ['id' => 5, 'ten_danh_muc' => 'Nước Ngọt', 'slug_danh_muc' => 'nuoc-ngot', 'icon_danh_muc' => 'fa-solid fa-bottle-water', 'tinh_trang' => 1],
        ]);
    }
}
