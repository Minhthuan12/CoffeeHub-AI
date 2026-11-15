<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CongThucMonSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cong_thuc_mons')->insert([
            // Cà Phê Đen Đá (ID = 1)
            ['id_san_pham' => 1, 'id_nguyen_lieu' => 1, 'so_luong' => 0.025, 'don_vi' => 'kg'],
            ['id_san_pham' => 1, 'id_nguyen_lieu' => 5, 'so_luong' => 0.015, 'don_vi' => 'kg'],

            // Cà Phê Sữa Đá (ID = 2)
            ['id_san_pham' => 2, 'id_nguyen_lieu' => 1, 'so_luong' => 0.025, 'don_vi' => 'kg'],
            ['id_san_pham' => 2, 'id_nguyen_lieu' => 3, 'so_luong' => 0.1, 'don_vi' => 'hộp'],

            // Bạc Xỉu (ID = 3)
            ['id_san_pham' => 3, 'id_nguyen_lieu' => 1, 'so_luong' => 0.01, 'don_vi' => 'kg'],
            ['id_san_pham' => 3, 'id_nguyen_lieu' => 3, 'so_luong' => 0.15, 'don_vi' => 'hộp'],
            ['id_san_pham' => 3, 'id_nguyen_lieu' => 4, 'so_luong' => 0.06, 'don_vi' => 'hộp'],

            // Cà Phê Muối (ID = 4)
            ['id_san_pham' => 4, 'id_nguyen_lieu' => 1, 'so_luong' => 0.025, 'don_vi' => 'kg'],
            ['id_san_pham' => 4, 'id_nguyen_lieu' => 3, 'so_luong' => 0.1, 'don_vi' => 'hộp'],
            ['id_san_pham' => 4, 'id_nguyen_lieu' => 7, 'so_luong' => 0.05, 'don_vi' => 'hộp'],
            ['id_san_pham' => 4, 'id_nguyen_lieu' => 6, 'so_luong' => 0.002, 'don_vi' => 'kg'],

            // Cà Phê Trứng (ID = 5)
            ['id_san_pham' => 5, 'id_nguyen_lieu' => 1, 'so_luong' => 0.025, 'don_vi' => 'kg'],
            ['id_san_pham' => 5, 'id_nguyen_lieu' => 8, 'so_luong' => 2.0, 'don_vi' => 'quả'],
            ['id_san_pham' => 5, 'id_nguyen_lieu' => 3, 'so_luong' => 0.05, 'don_vi' => 'hộp'],
            ['id_san_pham' => 5, 'id_nguyen_lieu' => 5, 'so_luong' => 0.01, 'don_vi' => 'kg'],

            // Espresso Nóng (ID = 6)
            ['id_san_pham' => 6, 'id_nguyen_lieu' => 2, 'so_luong' => 0.018, 'don_vi' => 'kg'],

            // Americano Đá (ID = 7)
            ['id_san_pham' => 7, 'id_nguyen_lieu' => 2, 'so_luong' => 0.018, 'don_vi' => 'kg'],

            // Cappuccino (ID = 8)
            ['id_san_pham' => 8, 'id_nguyen_lieu' => 2, 'so_luong' => 0.018, 'don_vi' => 'kg'],
            ['id_san_pham' => 8, 'id_nguyen_lieu' => 4, 'so_luong' => 0.15, 'don_vi' => 'hộp'],

            // Latte Nóng (ID = 9)
            ['id_san_pham' => 9, 'id_nguyen_lieu' => 2, 'so_luong' => 0.018, 'don_vi' => 'kg'],
            ['id_san_pham' => 9, 'id_nguyen_lieu' => 4, 'so_luong' => 0.2, 'don_vi' => 'hộp'],

            // Cà Phê Dừa (ID = 10)
            ['id_san_pham' => 10, 'id_nguyen_lieu' => 1, 'so_luong' => 0.015, 'don_vi' => 'kg'],
            ['id_san_pham' => 10, 'id_nguyen_lieu' => 9, 'so_luong' => 0.2, 'don_vi' => 'lon'],
            ['id_san_pham' => 10, 'id_nguyen_lieu' => 3, 'so_luong' => 0.08, 'don_vi' => 'hộp'],

            // Trà Đào Hồng Đài (ID = 11)
            ['id_san_pham' => 11, 'id_nguyen_lieu' => 10, 'so_luong' => 0.01, 'don_vi' => 'kg'],
            ['id_san_pham' => 11, 'id_nguyen_lieu' => 13, 'so_luong' => 0.25, 'don_vi' => 'lon'],
            ['id_san_pham' => 11, 'id_nguyen_lieu' => 5, 'so_luong' => 0.02, 'don_vi' => 'kg'],

            // Hồng Trà Sữa (ID = 12)
            ['id_san_pham' => 12, 'id_nguyen_lieu' => 10, 'so_luong' => 0.015, 'don_vi' => 'kg'],
            ['id_san_pham' => 12, 'id_nguyen_lieu' => 3, 'so_luong' => 0.1, 'don_vi' => 'hộp'],
            ['id_san_pham' => 12, 'id_nguyen_lieu' => 5, 'so_luong' => 0.01, 'don_vi' => 'kg'],

            // Trà Sữa Trân Châu Đường Đen (ID = 13)
            ['id_san_pham' => 13, 'id_nguyen_lieu' => 10, 'so_luong' => 0.015, 'don_vi' => 'kg'],
            ['id_san_pham' => 13, 'id_nguyen_lieu' => 3, 'so_luong' => 0.1, 'don_vi' => 'hộp'],
            ['id_san_pham' => 13, 'id_nguyen_lieu' => 14, 'so_luong' => 0.05, 'don_vi' => 'kg'],
            ['id_san_pham' => 13, 'id_nguyen_lieu' => 5, 'so_luong' => 0.02, 'don_vi' => 'kg'],

            // Trà Sữa Matcha (ID = 14)
            ['id_san_pham' => 14, 'id_nguyen_lieu' => 15, 'so_luong' => 0.005, 'don_vi' => 'kg'],
            ['id_san_pham' => 14, 'id_nguyen_lieu' => 4, 'so_luong' => 0.15, 'don_vi' => 'hộp'],
            ['id_san_pham' => 14, 'id_nguyen_lieu' => 3, 'so_luong' => 0.08, 'don_vi' => 'hộp'],

            // Trà Sữa Ô Long (ID = 15)
            ['id_san_pham' => 15, 'id_nguyen_lieu' => 12, 'so_luong' => 0.015, 'don_vi' => 'kg'],
            ['id_san_pham' => 15, 'id_nguyen_lieu' => 3, 'so_luong' => 0.1, 'don_vi' => 'hộp'],
            ['id_san_pham' => 15, 'id_nguyen_lieu' => 5, 'so_luong' => 0.01, 'don_vi' => 'kg'],

            // Trà Vải Nhài (ID = 17)
            ['id_san_pham' => 17, 'id_nguyen_lieu' => 11, 'so_luong' => 0.01, 'don_vi' => 'kg'],
            ['id_san_pham' => 17, 'id_nguyen_lieu' => 16, 'so_luong' => 0.25, 'don_vi' => 'lon'],
            ['id_san_pham' => 17, 'id_nguyen_lieu' => 5, 'so_luong' => 0.02, 'don_vi' => 'kg'],

            // Trà Mãng Cầu (ID = 18)
            ['id_san_pham' => 18, 'id_nguyen_lieu' => 11, 'so_luong' => 0.01, 'don_vi' => 'kg'],
            ['id_san_pham' => 18, 'id_nguyen_lieu' => 17, 'so_luong' => 0.1, 'don_vi' => 'kg'],
            ['id_san_pham' => 18, 'id_nguyen_lieu' => 5, 'so_luong' => 0.03, 'don_vi' => 'kg'],

            // Trà Sen Vàng (ID = 19)
            ['id_san_pham' => 19, 'id_nguyen_lieu' => 12, 'so_luong' => 0.01, 'don_vi' => 'kg'],
            ['id_san_pham' => 19, 'id_nguyen_lieu' => 18, 'so_luong' => 0.03, 'don_vi' => 'kg'],
            ['id_san_pham' => 19, 'id_nguyen_lieu' => 5, 'so_luong' => 0.02, 'don_vi' => 'kg'],
            ['id_san_pham' => 19, 'id_nguyen_lieu' => 7, 'so_luong' => 0.03, 'don_vi' => 'hộp'],

            // Trà Dâu Macchiato (ID = 20)
            ['id_san_pham' => 20, 'id_nguyen_lieu' => 10, 'so_luong' => 0.01, 'don_vi' => 'kg'],
            ['id_san_pham' => 20, 'id_nguyen_lieu' => 19, 'so_luong' => 0.05, 'don_vi' => 'kg'],
            ['id_san_pham' => 20, 'id_nguyen_lieu' => 5, 'so_luong' => 0.02, 'don_vi' => 'kg'],
            ['id_san_pham' => 20, 'id_nguyen_lieu' => 26, 'so_luong' => 0.05, 'don_vi' => 'hộp'],

            // Matcha Đá Xay (ID = 21)
            ['id_san_pham' => 21, 'id_nguyen_lieu' => 15, 'so_luong' => 0.008, 'don_vi' => 'kg'],
            ['id_san_pham' => 21, 'id_nguyen_lieu' => 4, 'so_luong' => 0.1, 'don_vi' => 'hộp'],
            ['id_san_pham' => 21, 'id_nguyen_lieu' => 26, 'so_luong' => 0.05, 'don_vi' => 'hộp'],
            ['id_san_pham' => 21, 'id_nguyen_lieu' => 7, 'so_luong' => 0.03, 'don_vi' => 'hộp'],

            // Sô-cô-la Đá Xay (ID = 23)
            ['id_san_pham' => 23, 'id_nguyen_lieu' => 21, 'so_luong' => 0.02, 'don_vi' => 'kg'],
            ['id_san_pham' => 23, 'id_nguyen_lieu' => 4, 'so_luong' => 0.12, 'don_vi' => 'hộp'],
            ['id_san_pham' => 23, 'id_nguyen_lieu' => 26, 'so_luong' => 0.05, 'don_vi' => 'hộp'],

            // Cookie Cream Đá Xay (ID = 24)
            ['id_san_pham' => 24, 'id_nguyen_lieu' => 22, 'so_luong' => 3.0, 'don_vi' => 'gói'],
            ['id_san_pham' => 24, 'id_nguyen_lieu' => 4, 'so_luong' => 0.12, 'don_vi' => 'hộp'],
            ['id_san_pham' => 24, 'id_nguyen_lieu' => 26, 'so_luong' => 0.05, 'don_vi' => 'hộp'],
            ['id_san_pham' => 24, 'id_nguyen_lieu' => 3, 'so_luong' => 0.05, 'don_vi' => 'hộp'],

            // Việt Quất Đá Xay (ID = 25)
            ['id_san_pham' => 25, 'id_nguyen_lieu' => 23, 'so_luong' => 0.05, 'don_vi' => 'chai'],
            ['id_san_pham' => 25, 'id_nguyen_lieu' => 4, 'so_luong' => 0.1, 'don_vi' => 'hộp'],
            ['id_san_pham' => 25, 'id_nguyen_lieu' => 26, 'so_luong' => 0.05, 'don_vi' => 'hộp'],

            // Dâu Tây Đá Xay (ID = 26)
            ['id_san_pham' => 26, 'id_nguyen_lieu' => 19, 'so_luong' => 0.08, 'don_vi' => 'kg'],
            ['id_san_pham' => 26, 'id_nguyen_lieu' => 4, 'so_luong' => 0.1, 'don_vi' => 'hộp'],
            ['id_san_pham' => 26, 'id_nguyen_lieu' => 26, 'so_luong' => 0.05, 'don_vi' => 'hộp'],
            ['id_san_pham' => 26, 'id_nguyen_lieu' => 5, 'so_luong' => 0.02, 'don_vi' => 'kg'],

            // Chanh Tuyết (ID = 27)
            ['id_san_pham' => 27, 'id_nguyen_lieu' => 20, 'so_luong' => 0.05, 'don_vi' => 'kg'],
            ['id_san_pham' => 27, 'id_nguyen_lieu' => 5, 'so_luong' => 0.03, 'don_vi' => 'kg'],
            ['id_san_pham' => 27, 'id_nguyen_lieu' => 3, 'so_luong' => 0.04, 'don_vi' => 'hộp'],

            // Phúc Bồn Tử Đá Xay (ID = 28)
            ['id_san_pham' => 28, 'id_nguyen_lieu' => 24, 'so_luong' => 0.05, 'don_vi' => 'chai'],
            ['id_san_pham' => 28, 'id_nguyen_lieu' => 4, 'so_luong' => 0.1, 'don_vi' => 'hộp'],
            ['id_san_pham' => 28, 'id_nguyen_lieu' => 26, 'so_luong' => 0.05, 'don_vi' => 'hộp'],

            // Xoài Đá Xay (ID = 29)
            ['id_san_pham' => 29, 'id_nguyen_lieu' => 25, 'so_luong' => 0.15, 'don_vi' => 'kg'],
            ['id_san_pham' => 29, 'id_nguyen_lieu' => 4, 'so_luong' => 0.1, 'don_vi' => 'hộp'],
            ['id_san_pham' => 29, 'id_nguyen_lieu' => 5, 'so_luong' => 0.02, 'don_vi' => 'kg'],

            // Caramel Đá Xay (ID = 30)
            ['id_san_pham' => 30, 'id_nguyen_lieu' => 27, 'so_luong' => 0.04, 'don_vi' => 'chai'],
            ['id_san_pham' => 30, 'id_nguyen_lieu' => 4, 'so_luong' => 0.12, 'don_vi' => 'hộp'],
            ['id_san_pham' => 30, 'id_nguyen_lieu' => 26, 'so_luong' => 0.05, 'don_vi' => 'hộp'],
            ['id_san_pham' => 30, 'id_nguyen_lieu' => 2, 'so_luong' => 0.01, 'don_vi' => 'kg'],

            // Bánh Tiramisu (ID = 31)
            ['id_san_pham' => 31, 'id_nguyen_lieu' => 28, 'so_luong' => 0.05, 'don_vi' => 'kg'],
            ['id_san_pham' => 31, 'id_nguyen_lieu' => 8, 'so_luong' => 1.0, 'don_vi' => 'quả'],
            ['id_san_pham' => 31, 'id_nguyen_lieu' => 21, 'so_luong' => 0.01, 'don_vi' => 'kg'],
            ['id_san_pham' => 31, 'id_nguyen_lieu' => 30, 'so_luong' => 0.08, 'don_vi' => 'hộp'],

            // Bánh Mousse Chanh Leo (ID = 32)
            ['id_san_pham' => 32, 'id_nguyen_lieu' => 30, 'so_luong' => 0.08, 'don_vi' => 'hộp'],
            ['id_san_pham' => 32, 'id_nguyen_lieu' => 20, 'so_luong' => 0.04, 'don_vi' => 'kg'],
            ['id_san_pham' => 32, 'id_nguyen_lieu' => 26, 'so_luong' => 0.1, 'don_vi' => 'hộp'],
            ['id_san_pham' => 32, 'id_nguyen_lieu' => 5, 'so_luong' => 0.03, 'don_vi' => 'kg'],

            // Bánh Croissant Bơ Tỏi (ID = 33)
            ['id_san_pham' => 33, 'id_nguyen_lieu' => 28, 'so_luong' => 0.15, 'don_vi' => 'kg'],
            ['id_san_pham' => 33, 'id_nguyen_lieu' => 29, 'so_luong' => 0.06, 'don_vi' => 'kg'],
            ['id_san_pham' => 33, 'id_nguyen_lieu' => 31, 'so_luong' => 0.01, 'don_vi' => 'kg'],

            // Bánh Red Velvet (ID = 34)
            ['id_san_pham' => 34, 'id_nguyen_lieu' => 28, 'so_luong' => 0.05, 'don_vi' => 'kg'],
            ['id_san_pham' => 34, 'id_nguyen_lieu' => 8, 'so_luong' => 1.0, 'don_vi' => 'quả'],
            ['id_san_pham' => 34, 'id_nguyen_lieu' => 21, 'so_luong' => 0.005, 'don_vi' => 'kg'],
            ['id_san_pham' => 34, 'id_nguyen_lieu' => 30, 'so_luong' => 0.08, 'don_vi' => 'hộp'],

            // Cheesecake Việt Quất (ID = 35)
            ['id_san_pham' => 35, 'id_nguyen_lieu' => 30, 'so_luong' => 0.1, 'don_vi' => 'hộp'],
            ['id_san_pham' => 35, 'id_nguyen_lieu' => 23, 'so_luong' => 0.04, 'don_vi' => 'chai'],
            ['id_san_pham' => 35, 'id_nguyen_lieu' => 29, 'so_luong' => 0.03, 'don_vi' => 'kg'],
            ['id_san_pham' => 35, 'id_nguyen_lieu' => 28, 'so_luong' => 0.02, 'don_vi' => 'kg'],

            // Bánh Donut Dâu Tây (ID = 37)
            ['id_san_pham' => 37, 'id_nguyen_lieu' => 28, 'so_luong' => 0.08, 'don_vi' => 'kg'],
            ['id_san_pham' => 37, 'id_nguyen_lieu' => 19, 'so_luong' => 0.02, 'don_vi' => 'kg'],
            ['id_san_pham' => 37, 'id_nguyen_lieu' => 29, 'so_luong' => 0.02, 'don_vi' => 'kg'],
            ['id_san_pham' => 37, 'id_nguyen_lieu' => 8, 'so_luong' => 0.5, 'don_vi' => 'quả'],

            // Bánh Su Kem (ID = 38)
            ['id_san_pham' => 38, 'id_nguyen_lieu' => 28, 'so_luong' => 0.05, 'don_vi' => 'kg'],
            ['id_san_pham' => 38, 'id_nguyen_lieu' => 29, 'so_luong' => 0.03, 'don_vi' => 'kg'],
            ['id_san_pham' => 38, 'id_nguyen_lieu' => 8, 'so_luong' => 1.0, 'don_vi' => 'quả'],
            ['id_san_pham' => 38, 'id_nguyen_lieu' => 4, 'so_luong' => 0.08, 'don_vi' => 'hộp'],

            // Bánh Waffle Mật Ong (ID = 39)
            ['id_san_pham' => 39, 'id_nguyen_lieu' => 28, 'so_luong' => 0.08, 'don_vi' => 'kg'],
            ['id_san_pham' => 39, 'id_nguyen_lieu' => 8, 'so_luong' => 1.0, 'don_vi' => 'quả'],
            ['id_san_pham' => 39, 'id_nguyen_lieu' => 4, 'so_luong' => 0.05, 'don_vi' => 'hộp'],
            ['id_san_pham' => 39, 'id_nguyen_lieu' => 29, 'so_luong' => 0.02, 'don_vi' => 'kg'],

            // Bánh Mì Phô Mai Chảy (ID = 40)
            ['id_san_pham' => 40, 'id_nguyen_lieu' => 28, 'so_luong' => 0.12, 'don_vi' => 'kg'],
            ['id_san_pham' => 40, 'id_nguyen_lieu' => 30, 'so_luong' => 0.08, 'don_vi' => 'hộp'],
            ['id_san_pham' => 40, 'id_nguyen_lieu' => 29, 'so_luong' => 0.03, 'don_vi' => 'kg'],
            ['id_san_pham' => 40, 'id_nguyen_lieu' => 8, 'so_luong' => 1.0, 'don_vi' => 'quả'],
        ]);
    }
}
