<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CaLamViecSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ca_lam_viecs')->insert([
            ['id' => 1, 'ten_ca' => 'Sáng', 'gio_bat_dau' => '06:00:00', 'gio_ket_thuc' => '12:00:00', 'tinh_trang' => 1],
            ['id' => 2, 'ten_ca' => 'Chiều', 'gio_bat_dau' => '12:00:00', 'gio_ket_thuc' => '18:00:00', 'tinh_trang' => 1],
            ['id' => 3, 'ten_ca' => 'Tối', 'gio_bat_dau' => '18:00:00', 'gio_ket_thuc' => '23:00:00', 'tinh_trang' => 1],
        ]);
    }
}
