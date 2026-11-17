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
use App\Http\Controllers\KhachHangController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\HoaDonController;
use App\Http\Controllers\ChiTietHoaDonController;
use App\Http\Controllers\GiaoDichController;
use App\Http\Controllers\OrderApiController;

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
Route::get('/lay-giao-dich', [GiaoDichController::class, 'getData']);

// Public Menu & Bàn cho Khách quét mã QR
Route::get('/menu/public', [SanPhamController::class, 'getDataActive']);
Route::get('/menu/danh-muc', [DanhMucController::class, 'getData']);
Route::get('/ban/public', [BanController::class, 'getBanTrangThai']);

// Quy trình Pay-First & Đặt món QR tại bàn
Route::post('/orders/qr-create', [OrderApiController::class, 'createQrOrder']);
Route::get('/orders/{id}/status', [OrderApiController::class, 'getOrderStatus']);
Route::get('/pos/orders/pending', [OrderApiController::class, 'getPendingQrOrders']);
Route::post('/pos/orders/{id}/approve', [OrderApiController::class, 'approveOrderPayment']);

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

    // Khách hàng
    Route::prefix('khach-hang')->group(function () {
        Route::get('/get-data', [KhachHangController::class, 'getData'])->middleware('checkLogin:76');
        Route::post('/store', [KhachHangController::class, 'store'])->middleware('checkLogin:77');
        Route::post('/delete', [KhachHangController::class, 'destroy'])->middleware('checkLogin:78');
        Route::post('/update', [KhachHangController::class, 'update'])->middleware('checkLogin:79');
        Route::post('/tim-khach-hang', [KhachHangController::class, 'timKhachHang'])->middleware('checkLogin:120');
        Route::post('/change-status', [KhachHangController::class, 'changeStatus'])->middleware('checkLogin:80');
    });

    // Voucher
    Route::prefix('voucher')->group(function () {
        Route::get('/get-data', [VoucherController::class, 'getData'])->middleware('checkLogin:81');
        Route::post('/store', [VoucherController::class, 'store'])->middleware('checkLogin:82');
        Route::post('/delete', [VoucherController::class, 'destroy'])->middleware('checkLogin:83');
        Route::post('/update', [VoucherController::class, 'update'])->middleware('checkLogin:84');
        Route::post('/ap-dung', [VoucherController::class, 'apDungVoucher'])->middleware('checkLogin:116');
        Route::post('/huy-voucher', [VoucherController::class, 'huyVoucher'])->middleware('checkLogin:117');
    });

    // Hóa đơn & Chi tiết hóa đơn
    Route::prefix('hoa-don')->group(function () {
        Route::get('/get-data', [HoaDonController::class, 'getData'])->middleware('checkLogin:85');
        Route::get('/mo-ban/{id_ban}', [HoaDonController::class, 'moBan'])->middleware('checkLogin:113');
        Route::get('/get-hoa-don/{id_ban}', [HoaDonController::class, 'getHoaDonTheoBan'])->middleware('checkLogin:118');
        Route::post('/thanh-toan', [HoaDonController::class, 'thanhToan'])->middleware('checkLogin:122');
        Route::post('/xac-nhan-thanh-toan', [HoaDonController::class, 'xacNhanThanhToan'])->middleware('checkLogin:122');
        Route::post('/store', [HoaDonController::class, 'store'])->middleware('checkLogin:87');
        Route::post('/delete', [HoaDonController::class, 'destroy'])->middleware('checkLogin:88');
        Route::post('/update', [HoaDonController::class, 'update'])->middleware('checkLogin:89');
        Route::post('/change-status', [HoaDonController::class, 'changeStatus'])->middleware('checkLogin:90');
        Route::get('/in-hoa-don/{id}', [HoaDonController::class, 'inHoaDon'])->middleware('checkLogin:122');
    });

    Route::prefix('chi-tiet-hoa-don')->group(function () {
        Route::post('/them-mon', [ChiTietHoaDonController::class, 'themMon'])->middleware('checkLogin:112');
        Route::get('/get-data', [ChiTietHoaDonController::class, 'getData'])->middleware('checkLogin:86');
        Route::get('/get-theo-hoa-don/{id_hoa_don}', [ChiTietHoaDonController::class, 'getTheoHoaDon'])->middleware('checkLogin:121');
        Route::get('/get-theo-ban/{id}', [ChiTietHoaDonController::class, 'getTheoBan'])->middleware('checkLogin:110');
        Route::get('/in-pha-che/{id}', [ChiTietHoaDonController::class, 'inPhaChe'])->middleware('checkLogin:111');
        Route::post('/store', [ChiTietHoaDonController::class, 'store'])->middleware('checkLogin:91');
        Route::post('/update', [ChiTietHoaDonController::class, 'update'])->middleware('checkLogin:119');
        Route::post('/delete', [ChiTietHoaDonController::class, 'destroy'])->middleware('checkLogin:92');
        Route::get('/bep-che-bien', [ChiTietHoaDonController::class, 'bepCheBien'])->middleware('checkLogin:114');
        Route::post('/xong-mon', [ChiTietHoaDonController::class, 'xongMon'])->middleware('checkLogin:115');
    });
});
