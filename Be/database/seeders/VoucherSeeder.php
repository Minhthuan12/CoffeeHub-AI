<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('vouchers')->insert([
            [
                'id' => 1,
                'ma_voucher' => 'ROCKER10',
                'thoi_gian_bat_dau' => '2026-06-01',
                'thoi_gian_ket_thuc' => '2026-07-31',
                'loai_giam' => 1, // 1: giảm %
                'so_giam_gia' => 10,
                'so_tien_toi_da' => 3000,
                'don_hang_toi_thieu' => 5000
            ],
            [
                'id' => 2,
                'ma_voucher' => 'CAFEK26',
                'thoi_gian_bat_dau' => '2026-06-01',
                'thoi_gian_ket_thuc' => '2026-07-31',
                'loai_giam' => 2, // 2: giảm trực tiếp số tiền
                'so_giam_gia' => 2000,
                'so_tien_toi_da' => 2000,
                'don_hang_toi_thieu' => 10000
            ]
        ]);
    }
}
