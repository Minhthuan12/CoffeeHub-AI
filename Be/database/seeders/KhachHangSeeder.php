<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KhachHangSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('khach_hangs')->insert([
            ['id' => 1,  'ho_va_ten' => 'Khách Vãng Lai',     'email' => 'khach.vanglai@gmail.com', 'so_dien_thoai' => '0999999999', 'tinh_trang' => 1],
            ['id' => 2,  'ho_va_ten' => 'Nguyễn Anh Tuấn',   'email' => 'anhtuan@gmail.com',       'so_dien_thoai' => '0912345678', 'tinh_trang' => 1],
            ['id' => 3,  'ho_va_ten' => 'Trần Thị Thuỷ',     'email' => 'thithuy@gmail.com',       'so_dien_thoai' => '0912876543', 'tinh_trang' => 1],
            ['id' => 4,  'ho_va_ten' => 'Lê Hoàng Nam',       'email' => 'hoangnam@gmail.com',      'so_dien_thoai' => '0934111222', 'tinh_trang' => 1],
            ['id' => 5,  'ho_va_ten' => 'Phạm Minh Quân',     'email' => 'minhquan@gmail.com',      'so_dien_thoai' => '0945222333', 'tinh_trang' => 1],
            ['id' => 6,  'ho_va_ten' => 'Hoàng Thanh Hà',     'email' => 'thanhha@gmail.com',       'so_dien_thoai' => '0956333444', 'tinh_trang' => 1],
            ['id' => 7,  'ho_va_ten' => 'Vũ Quốc Bảo',        'email' => 'quocbao@gmail.com',       'so_dien_thoai' => '0967444555', 'tinh_trang' => 1],
            ['id' => 8,  'ho_va_ten' => 'Đỗ Phương Vy',       'email' => 'phuongvy@gmail.com',      'so_dien_thoai' => '0978555666', 'tinh_trang' => 1],
            ['id' => 9,  'ho_va_ten' => 'Bùi Đức Anh',        'email' => 'ducanh@gmail.com',        'so_dien_thoai' => '0989666777', 'tinh_trang' => 1],
            ['id' => 10, 'ho_va_ten' => 'Đặng Ngọc Mai',      'email' => 'ngocmai@gmail.com',       'so_dien_thoai' => '0901777888', 'tinh_trang' => 1],
            ['id' => 11, 'ho_va_ten' => 'Nông Văn Tiến',      'email' => 'vantien@gmail.com',       'so_dien_thoai' => '0911888999', 'tinh_trang' => 1],
            ['id' => 12, 'ho_va_ten' => 'Trịnh Bảo Ngọc',     'email' => 'baongoc@gmail.com',       'so_dien_thoai' => '0922999000', 'tinh_trang' => 1],
            ['id' => 13, 'ho_va_ten' => 'Ngô Tiến Dũng',      'email' => 'tiendung@gmail.com',      'so_dien_thoai' => '0933000111', 'tinh_trang' => 1],
            ['id' => 14, 'ho_va_ten' => 'Dương Khánh Linh',   'email' => 'khanhlinh@gmail.com',    'so_dien_thoai' => '0944111222', 'tinh_trang' => 1],
            ['id' => 15, 'ho_va_ten' => 'Phan Nhật Minh',     'email' => 'nhatminh@gmail.com',      'so_dien_thoai' => '0955222333', 'tinh_trang' => 1],
            ['id' => 16, 'ho_va_ten' => 'Tô Mỹ Duyên',        'email' => 'myduyen@gmail.com',       'so_dien_thoai' => '0966333444', 'tinh_trang' => 1],
            ['id' => 17, 'ho_va_ten' => 'Đinh Văn Công',      'email' => 'vancong@gmail.com',       'so_dien_thoai' => '0977444555', 'tinh_trang' => 1],
            ['id' => 18, 'ho_va_ten' => 'Lý Thanh Tùng',      'email' => 'thanhtung@gmail.com',     'so_dien_thoai' => '0988555666', 'tinh_trang' => 1],
            ['id' => 19, 'ho_va_ten' => 'Hà Bảo Trang',       'email' => 'baotrang@gmail.com',      'so_dien_thoai' => '0999666777', 'tinh_trang' => 1],
            ['id' => 20, 'ho_va_ten' => 'Võ Đình Phong',      'email' => 'dinhphong@gmail.com',     'so_dien_thoai' => '0902111333', 'tinh_trang' => 1],
            ['id' => 21, 'ho_va_ten' => 'Mai Quỳnh Anh',      'email' => 'quynhanh@gmail.com',      'so_dien_thoai' => '0913222444', 'tinh_trang' => 1],
            ['id' => 22, 'ho_va_ten' => 'Thái Văn Hùng',      'email' => 'vanhung@gmail.com',       'so_dien_thoai' => '0924333555', 'tinh_trang' => 1],
            ['id' => 23, 'ho_va_ten' => 'Lương Minh Trí',     'email' => 'minhtri@gmail.com',       'so_dien_thoai' => '0935444666', 'tinh_trang' => 1],
            ['id' => 24, 'ho_va_ten' => 'Tạ Bích Phương',     'email' => 'bichphuong@gmail.com',    'so_dien_thoai' => '0946555777', 'tinh_trang' => 1],
            ['id' => 25, 'ho_va_ten' => 'Kiều Văn Sơn',       'email' => 'vanson@gmail.com',        'so_dien_thoai' => '0957666888', 'tinh_trang' => 1],
        ]);
    }
}
