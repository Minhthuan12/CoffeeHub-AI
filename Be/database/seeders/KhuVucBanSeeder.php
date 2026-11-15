<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KhuVucBanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('khu_vuc_bans')->insert([
            ['id' => 1, 'ten_khu_vuc' => 'Tầng 1 (Máy lạnh)', 'mo_ta' => 'Khu vực trong nhà có điều hòa mát mẻ'],
            ['id' => 2, 'ten_khu_vuc' => 'Tầng 2 (Sân thượng)', 'mo_ta' => 'Không gian thoáng mát nhiều cây xanh ngắm view thành phố'],
            ['id' => 3, 'ten_khu_vuc' => 'Khu Ngoài Trời', 'mo_ta' => 'Khu vực vỉa hè thoáng mát cho khách hút thuốc'],
        ]);
    }
}
