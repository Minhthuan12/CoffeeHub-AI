<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NguyenLieuSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('nguyen_lieus')->insert([
            // Cà phê & Cốt sữa
            ['id' => 1, 'ten_nguyen_lieu' => 'Hạt Cà Phê Robusta', 'don_vi_tinh' => 'kg', 'so_luong_ton' => 50.0, 'muc_canh_bao' => 10.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 3],
            ['id' => 2, 'ten_nguyen_lieu' => 'Hạt Cà Phê Arabica', 'don_vi_tinh' => 'kg', 'so_luong_ton' => 30.0, 'muc_canh_bao' => 8.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 3],
            ['id' => 3, 'ten_nguyen_lieu' => 'Sữa Đặc Vinamilk', 'don_vi_tinh' => 'hộp', 'so_luong_ton' => 120.0, 'muc_canh_bao' => 20.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 2],
            ['id' => 4, 'ten_nguyen_lieu' => 'Sữa Tươi Tiệt Trùng Vinamilk 1L', 'don_vi_tinh' => 'hộp', 'so_luong_ton' => 60.0, 'muc_canh_bao' => 15.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 2],
            ['id' => 5, 'ten_nguyen_lieu' => 'Đường Cát Trắng', 'don_vi_tinh' => 'kg', 'so_luong_ton' => 25.0, 'muc_canh_bao' => 5.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 1],
            ['id' => 6, 'ten_nguyen_lieu' => 'Muối Tinh', 'don_vi_tinh' => 'kg', 'so_luong_ton' => 10.0, 'muc_canh_bao' => 1.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 1],
            ['id' => 7, 'ten_nguyen_lieu' => 'Kem Béo Richs', 'don_vi_tinh' => 'hộp', 'so_luong_ton' => 45.0, 'muc_canh_bao' => 10.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 2],
            ['id' => 8, 'ten_nguyen_lieu' => 'Trứng Gà Ta', 'don_vi_tinh' => 'quả', 'so_luong_ton' => 200.0, 'muc_canh_bao' => 50.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 1],
            ['id' => 9, 'ten_nguyen_lieu' => 'Nước Cốt Dừa', 'don_vi_tinh' => 'lon', 'so_luong_ton' => 80.0, 'muc_canh_bao' => 15.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 2],

            // Trà & Topping trái cây
            ['id' => 10, 'ten_nguyen_lieu' => 'Trà Đen Lộc Phát', 'don_vi_tinh' => 'kg', 'so_luong_ton' => 15.0, 'muc_canh_bao' => 3.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 3],
            ['id' => 11, 'ten_nguyen_lieu' => 'Trà Nhài Lộc Phát', 'don_vi_tinh' => 'kg', 'so_luong_ton' => 15.0, 'muc_canh_bao' => 3.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 3],
            ['id' => 12, 'ten_nguyen_lieu' => 'Trà Ô Long Lộc Phát', 'don_vi_tinh' => 'kg', 'so_luong_ton' => 20.0, 'muc_canh_bao' => 4.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 3],
            ['id' => 13, 'ten_nguyen_lieu' => 'Đào Lát Đóng Hộp', 'don_vi_tinh' => 'lon', 'so_luong_ton' => 48.0, 'muc_canh_bao' => 10.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 1],
            ['id' => 14, 'ten_nguyen_lieu' => 'Trân Châu Đen', 'don_vi_tinh' => 'kg', 'so_luong_ton' => 30.0, 'muc_canh_bao' => 5.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 3],
            ['id' => 15, 'ten_nguyen_lieu' => 'Bột Matcha Uji', 'don_vi_tinh' => 'kg', 'so_luong_ton' => 10.0, 'muc_canh_bao' => 2.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 3],
            ['id' => 16, 'ten_nguyen_lieu' => 'Trái Vải Đóng Hộp', 'don_vi_tinh' => 'lon', 'so_luong_ton' => 40.0, 'muc_canh_bao' => 8.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 1],
            ['id' => 17, 'ten_nguyen_lieu' => 'Mãng Cầu Xiêm', 'don_vi_tinh' => 'kg', 'so_luong_ton' => 15.0, 'muc_canh_bao' => 3.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 1],
            ['id' => 18, 'ten_nguyen_lieu' => 'Hạt Sen Tươi', 'don_vi_tinh' => 'kg', 'so_luong_ton' => 10.0, 'muc_canh_bao' => 2.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 1],
            ['id' => 19, 'ten_nguyen_lieu' => 'Dâu Tây Đà Lạt', 'don_vi_tinh' => 'kg', 'so_luong_ton' => 15.0, 'muc_canh_bao' => 3.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 1],

            // Nguyên liệu đá xay & Trang trí
            ['id' => 20, 'ten_nguyen_lieu' => 'Chanh Tươi Đà Lạt', 'don_vi_tinh' => 'kg', 'so_luong_ton' => 10.0, 'muc_canh_bao' => 2.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 1],
            ['id' => 21, 'ten_nguyen_lieu' => 'Bột Sô-cô-la Bỉ', 'don_vi_tinh' => 'kg', 'so_luong_ton' => 15.0, 'muc_canh_bao' => 3.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 3],
            ['id' => 22, 'ten_nguyen_lieu' => 'Bánh Oreo', 'don_vi_tinh' => 'gói', 'so_luong_ton' => 60.0, 'muc_canh_bao' => 10.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 3],
            ['id' => 23, 'ten_nguyen_lieu' => 'Mứt Việt Quất', 'don_vi_tinh' => 'chai', 'so_luong_ton' => 20.0, 'muc_canh_bao' => 4.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 1],
            ['id' => 24, 'ten_nguyen_lieu' => 'Mứt Phúc Bồn Tử', 'don_vi_tinh' => 'chai', 'so_luong_ton' => 20.0, 'muc_canh_bao' => 4.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 1],
            ['id' => 25, 'ten_nguyen_lieu' => 'Xoài Cát Chín', 'don_vi_tinh' => 'kg', 'so_luong_ton' => 15.0, 'muc_canh_bao' => 3.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 1],
            ['id' => 26, 'ten_nguyen_lieu' => 'Kem Whipping Anchor', 'don_vi_tinh' => 'hộp', 'so_luong_ton' => 24.0, 'muc_canh_bao' => 5.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 2],
            ['id' => 27, 'ten_nguyen_lieu' => 'Sốt Caramel Hershey', 'don_vi_tinh' => 'chai', 'so_luong_ton' => 15.0, 'muc_canh_bao' => 3.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 3],

            // Nguyên liệu làm bánh ngọt
            ['id' => 28, 'ten_nguyen_lieu' => 'Bột Mì Meizan', 'don_vi_tinh' => 'kg', 'so_luong_ton' => 40.0, 'muc_canh_bao' => 10.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 1],
            ['id' => 29, 'ten_nguyen_lieu' => 'Bơ Lạt Anchor', 'don_vi_tinh' => 'kg', 'so_luong_ton' => 20.0, 'muc_canh_bao' => 4.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 2],
            ['id' => 30, 'ten_nguyen_lieu' => 'Cream Cheese Anchor', 'don_vi_tinh' => 'hộp', 'so_luong_ton' => 30.0, 'muc_canh_bao' => 6.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 2],
            ['id' => 31, 'ten_nguyen_lieu' => 'Tỏi Lý Sơn', 'don_vi_tinh' => 'kg', 'so_luong_ton' => 5.0, 'muc_canh_bao' => 1.0, 'tinh_trang' => 1, 'id_nha_cung_cap' => 1]
        ]);
    }
}
