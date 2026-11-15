<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NhanVienSeeder extends Seeder
{
    public function run(): void
    {

        DB::table('nhan_viens')->insert([
            [
                'id' => 1,
                'ho_va_ten' => 'Hồ Văn Cường',
                'email' => 'cuong.ho@gmail.com',
                'password' => Hash::make('123456'),
                'so_dien_thoai' => '0901234567',
                'tinh_trang' => 1,
                'id_chuc_vu' => 1, // Admin/Quản lý
                'is_master' => 1,
            ],
            [
                'id' => 2,
                'ho_va_ten' => 'Nguyễn Pha Chế',
                'email' => 'phache@gmail.com',
                'password' => Hash::make('123456'),
                'so_dien_thoai' => '0907654321',
                'tinh_trang' => 1,
                'id_chuc_vu' => 3, // Pha chế
                'is_master' => 0,
            ],
            [
                'id' => 3,
                'ho_va_ten' => 'Lê Phục Vụ',
                'email' => 'phucvu@gmail.com',
                'password' => Hash::make('123456'),
                'so_dien_thoai' => '0988888888',
                'tinh_trang' => 1,
                'id_chuc_vu' => 5, // Phục vụ
                'is_master' => 0,
            ],
            [
                'id' => 4,
                'ho_va_ten' => 'Trần Thu Ngân',
                'email' => 'thungan@gmail.com',
                'password' => Hash::make('123456'),
                'so_dien_thoai' => '0977777777',
                'tinh_trang' => 1,
                'id_chuc_vu' => 6, // Thu ngân
                'is_master' => 0,
            ],
            [
                'id' => 5,
                'ho_va_ten' => 'Phạm Bảo Vệ',
                'email' => 'baove@gmail.com',
                'password' => Hash::make('123456'),
                'so_dien_thoai' => '0966666666',
                'tinh_trang' => 1,
                'id_chuc_vu' => 7, // Bảo vệ
                'is_master' => 0,
            ],
            [
                'id' => 6,
                'ho_va_ten' => 'Nguyễn Văn Hùng',
                'email' => 'hung.nguyen@gmail.com',
                'password' => Hash::make('123456'),
                'so_dien_thoai' => '0911222333',
                'tinh_trang' => 1,
                'id_chuc_vu' => 1, // Admin/Quản lý
                'is_master' => 0,
            ],
            [
                'id' => 7,
                'ho_va_ten' => 'Lê Thị Mai',
                'email' => 'mai.le@gmail.com',
                'password' => Hash::make('123456'),
                'so_dien_thoai' => '0922333444',
                'tinh_trang' => 1,
                'id_chuc_vu' => 3, // Pha chế
                'is_master' => 0,
            ],
            [
                'id' => 8,
                'ho_va_ten' => 'Trần Quốc Anh',
                'email' => 'quocanh@gmail.com',
                'password' => Hash::make('123456'),
                'so_dien_thoai' => '0933444555',
                'tinh_trang' => 1,
                'id_chuc_vu' => 5, // Phục vụ
                'is_master' => 0,
            ],
            [
                'id' => 9,
                'ho_va_ten' => 'Phạm Khánh Linh',
                'email' => 'khanhlinh@gmail.com',
                'password' => Hash::make('123456'),
                'so_dien_thoai' => '0944555666',
                'tinh_trang' => 1,
                'id_chuc_vu' => 6, // Thu ngân
                'is_master' => 0,
            ],
            [
                'id' => 10,
                'ho_va_ten' => 'Đặng Hoàng Nam',
                'email' => 'hoangnam.nv@gmail.com',
                'password' => Hash::make('123456'),
                'so_dien_thoai' => '0955666777',
                'tinh_trang' => 1,
                'id_chuc_vu' => 7, // Bảo vệ
                'is_master' => 0,
            ]
        ]);
    }
}
