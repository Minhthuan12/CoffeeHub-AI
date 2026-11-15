<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('bans')->insert([
            // Tầng 1 (Khu vực ID = 1)
            ["id" => 1, "ten_ban" => "Bàn 1", "tinh_trang" => 0, "id_khu_vuc_ban" => 1],
            ["id" => 2, "ten_ban" => "Bàn 2", "tinh_trang" => 0, "id_khu_vuc_ban" => 1],
            ["id" => 3, "ten_ban" => "Bàn 3", "tinh_trang" => 0, "id_khu_vuc_ban" => 1],
            ["id" => 4, "ten_ban" => "Bàn 4", "tinh_trang" => 0, "id_khu_vuc_ban" => 1],
            ["id" => 5, "ten_ban" => "Bàn 5", "tinh_trang" => 0, "id_khu_vuc_ban" => 1],
            ["id" => 6, "ten_ban" => "Bàn 6", "tinh_trang" => 0, "id_khu_vuc_ban" => 1],

            // Tầng 2 (Khu vực ID = 2)
            ["id" => 7, "ten_ban" => "Bàn 7", "tinh_trang" => 0, "id_khu_vuc_ban" => 2],
            ["id" => 8, "ten_ban" => "Bàn 8", "tinh_trang" => 0, "id_khu_vuc_ban" => 2],
            ["id" => 9, "ten_ban" => "Bàn 9", "tinh_trang" => 0, "id_khu_vuc_ban" => 2],
            ["id" => 10, "ten_ban" => "Bàn 10", "tinh_trang" => 0, "id_khu_vuc_ban" => 2],
            ["id" => 11, "ten_ban" => "Bàn 11", "tinh_trang" => 0, "id_khu_vuc_ban" => 2],
            ["id" => 12, "ten_ban" => "Bàn 12", "tinh_trang" => 0, "id_khu_vuc_ban" => 2],

            // Ngoài Trời (Khu vực ID = 3)
            ["id" => 13, "ten_ban" => "Bàn 13", "tinh_trang" => 0, "id_khu_vuc_ban" => 3],
            ["id" => 14, "ten_ban" => "Bàn 14", "tinh_trang" => 0, "id_khu_vuc_ban" => 3],
            ["id" => 15, "ten_ban" => "Bàn 15", "tinh_trang" => 0, "id_khu_vuc_ban" => 3],
            ["id" => 16, "ten_ban" => "Bàn 16", "tinh_trang" => 0, "id_khu_vuc_ban" => 3],
            ["id" => 17, "ten_ban" => "Bàn 17", "tinh_trang" => 0, "id_khu_vuc_ban" => 3],
            ["id" => 18, "ten_ban" => "Bàn 18", "tinh_trang" => 0, "id_khu_vuc_ban" => 3],
            ["id" => 19, "ten_ban" => "Bàn 19", "tinh_trang" => 0, "id_khu_vuc_ban" => 3],
            ["id" => 20, "ten_ban" => "Bàn 20", "tinh_trang" => 0, "id_khu_vuc_ban" => 3],
        ]);
    }
}
