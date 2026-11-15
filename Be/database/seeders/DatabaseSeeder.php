<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ChiTietHoaDon;
use App\Models\HoaDon;
use App\Models\PhanCongCaLam;
use App\Models\ChiTietPhieuNhap;
use App\Models\PhieuNhapKho;
use App\Models\DanhMuc;
use App\Models\SanPham;
use App\Models\NguyenLieu;
use App\Models\NhaCungCap;
use App\Models\CaLamViec;
use App\Models\NhanVien;
use App\Models\ChucVu;
use App\Models\User;
use App\Models\ChucNang;
use App\Models\ChiTietPhanQuyen;
use App\Models\KhuVucBan;
use App\Models\Ban;
use App\Models\KhachHang;
use App\Models\Voucher;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. TẮT FOREIGN KEY CHECKS ĐỂ TRUNCATE AN TOÀN
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tất cả các bảng chính và bảng quan hệ trung gian
        ChiTietPhanQuyen::truncate();
        ChucNang::truncate();
        ChiTietHoaDon::truncate();
        HoaDon::truncate();
        Voucher::truncate();
        KhachHang::truncate();
        Ban::truncate();
        KhuVucBan::truncate();
        SanPham::truncate();
        DanhMuc::truncate();
        ChiTietPhieuNhap::truncate();
        PhieuNhapKho::truncate();
        NguyenLieu::truncate();
        NhaCungCap::truncate();
        PhanCongCaLam::truncate();
        CaLamViec::truncate();
        NhanVien::truncate();
        ChucVu::truncate();
        User::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. GỌI TUẦN TỰ CÁC FILE SEEDER RIÊNG BIỆT (Theo thứ tự khóa ngoại phụ thuộc)
        $this->call([
            UserSeeder::class,
            ChucVuSeeder::class,
            ChucNangSeeder::class,
            PhanQuyenSeeder::class,
            NhanVienSeeder::class,
            CaLamViecSeeder::class,
            NhaCungCapSeeder::class,
            NguyenLieuSeeder::class,
            DanhMucSeeder::class,
            SanPhamSeeder::class,
            CongThucMonSeeder::class, // Phụ thuộc SanPham & NguyenLieu
            KhuVucBanSeeder::class,
            BanSeeder::class, // Phụ thuộc KhuVucBan
            KhachHangSeeder::class,
            VoucherSeeder::class,
            PhanCongCaLamSeeder::class,
            PhieuNhapKhoSeeder::class,
            HoaDonSeeder::class,
            ChiTietHoaDonSeeder::class,
        ]);
    }
}