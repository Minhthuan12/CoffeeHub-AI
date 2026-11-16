<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChucNangController;
use App\Http\Controllers\ChucVuController;
use App\Http\Controllers\NhanVienController;
use App\Http\Controllers\ChiTietPhanQuyenController;
use App\Http\Controllers\DanhMucController;
use App\Http\Controllers\SanPhamController;
use App\Http\Controllers\KhuVucBanController;
use App\Http\Controllers\BanController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// ==========================================
// PUBLIC ROUTES (Không cần đăng nhập)
// ==========================================
Route::post('/admin/nhan-vien/login', [NhanVienController::class, 'login']);
Route::post('/admin/nhan-vien/dang-ky', [NhanVienController::class, 'dangKy']);
Route::post('/admin/dang-ky', [NhanVienController::class, 'dangKy']);
Route::get('/admin/chuc-vu/public-active', [ChucVuController::class, 'getDataActive']);
Route::get('/check-login', [NhanVienController::class, 'checkLogin']);
Route::post('/quen-mat-khau', [NhanVienController::class, 'quenMatKhau']);
Route::post('/dat-mat-khau-moi', [NhanVienController::class, 'datMatKhauMoi']);

// Public Menu & Bàn cho Khách quét mã QR
Route::get('/menu/public', [SanPhamController::class, 'getDataActive']);
Route::get('/menu/danh-muc', [DanhMucController::class, 'getData']);
Route::get('/ban/public', [BanController::class, 'getBanTrangThai']);

// ==========================================
// PROTECTED ROUTES (Cần đăng nhập / Phân quyền)
// ==========================================
Route::prefix('admin')->group(function () {

    // Chức năng
    Route::prefix('chuc-nang')->group(function () {
        Route::get('/get-data', [ChucNangController::class, 'getData'])->middleware('checkLogin:1');
        Route::post('/store', [ChucNangController::class, 'store'])->middleware('checkLogin:2');
        Route::post('/delete', [ChucNangController::class, 'destroy'])->middleware('checkLogin:3');
        Route::post('/update', [ChucNangController::class, 'update'])->middleware('checkLogin:4');
    });

    // Chi tiết phân quyền
    Route::prefix('chi-tiet-phan-quyen')->group(function () {
        Route::post('/danh-sach-da-cap', [ChiTietPhanQuyenController::class, 'getQuyenDaCap'])->middleware('checkLogin:5');
        Route::post('/cap-quyen', [ChiTietPhanQuyenController::class, 'capQuyen'])->middleware('checkLogin:6');
    });

    // Chức vụ
    Route::prefix('chuc-vu')->group(function () {
        Route::get('/get-data', [ChucVuController::class, 'getData'])->middleware('checkLogin:7');
        Route::get('/get-data-active', [ChucVuController::class, 'getDataActive'])->middleware('checkLogin:8');
        Route::post('/store', [ChucVuController::class, 'store'])->middleware('checkLogin:10');
        Route::post('/delete', [ChucVuController::class, 'destroy'])->middleware('checkLogin:12');
        Route::post('/update', [ChucVuController::class, 'update'])->middleware('checkLogin:13');
        Route::post('/change-status', [ChucVuController::class, 'changeStatus'])->middleware('checkLogin:14');
    });

    // Nhân viên
    Route::prefix('nhan-vien')->group(function () {
        Route::get('/get-data', [NhanVienController::class, 'getData'])->middleware('checkLogin:15');
        Route::get('/get-data-active', [NhanVienController::class, 'getDataActive'])->middleware('checkLogin:16');
        Route::post('/store', [NhanVienController::class, 'store'])->middleware('checkLogin:18');
        Route::post('/delete', [NhanVienController::class, 'destroy'])->middleware('checkLogin:20');
        Route::post('/update', [NhanVienController::class, 'update'])->middleware('checkLogin:21');
        Route::post('/change-status', [NhanVienController::class, 'changeStatus'])->middleware('checkLogin:22');
    });

    // Trang cá nhân nhân viên
    Route::prefix('trang-ca-nhan')->group(function () {
        Route::get('/thong-tin', [NhanVienController::class, 'getProfile'])->middleware('checkLogin:123');
        Route::post('/cap-nhat-thong-tin', [NhanVienController::class, 'updateProfile'])->middleware('checkLogin:124');
        Route::post('/doi-mat-khau', [NhanVienController::class, 'changePassword'])->middleware('checkLogin:125');
    });

    // Danh mục
    Route::prefix('danh-muc')->group(function () {
        Route::get('/get-data', [DanhMucController::class, 'getData'])->middleware('checkLogin:53');
        Route::post('/store', [DanhMucController::class, 'store'])->middleware('checkLogin:54');
        Route::post('/delete', [DanhMucController::class, 'destroy'])->middleware('checkLogin:55');
        Route::post('/update', [DanhMucController::class, 'update'])->middleware('checkLogin:56');
        Route::post('/change-status', [DanhMucController::class, 'changeStatus'])->middleware('checkLogin:57');
    });

    // Sản phẩm
    Route::prefix('san-pham')->group(function () {
        Route::get('/get-data', [SanPhamController::class, 'getData'])->middleware('checkLogin:58');
        Route::get('/get-data-active', [SanPhamController::class, 'getDataActive'])->middleware('checkLogin:128');
        Route::post('/store', [SanPhamController::class, 'store'])->middleware('checkLogin:59');
        Route::post('/delete', [SanPhamController::class, 'destroy'])->middleware('checkLogin:60');
        Route::post('/update', [SanPhamController::class, 'update'])->middleware('checkLogin:61');
        Route::post('/change-status', [SanPhamController::class, 'changeStatus'])->middleware('checkLogin:62');
    });

    // Khu vực bàn
    Route::prefix('khu-vuc-ban')->group(function () {
        Route::get('/get-data', [KhuVucBanController::class, 'getData'])->middleware('checkLogin:67');
        Route::post('/store', [KhuVucBanController::class, 'store'])->middleware('checkLogin:68');
        Route::post('/delete', [KhuVucBanController::class, 'destroy'])->middleware('checkLogin:69');
        Route::post('/update', [KhuVucBanController::class, 'update'])->middleware('checkLogin:70');
    });

    // Bàn
    Route::prefix('ban')->group(function () {
        Route::get('/get-data', [BanController::class, 'getData'])->middleware('checkLogin:71');
        Route::post('/store', [BanController::class, 'store'])->middleware('checkLogin:72');
        Route::post('/delete', [BanController::class, 'destroy'])->middleware('checkLogin:73');
        Route::post('/update', [BanController::class, 'update'])->middleware('checkLogin:74');
        Route::post('/change-status', [BanController::class, 'changeStatus'])->middleware('checkLogin:75');
        Route::get('/ban-hang/get-data', [BanController::class, 'getBanTrangThai'])->middleware('checkLogin:109');
    });
});
