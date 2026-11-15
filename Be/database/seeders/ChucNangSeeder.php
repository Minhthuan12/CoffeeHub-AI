<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChucNangSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('chuc_nangs')->delete();
        DB::table('chuc_nangs')->insert([
            ["id" => 1, "ten_chuc_nang" => "Xem danh sách chức năng"],
            ["id" => 2, "ten_chuc_nang" => "Thêm mới chức năng"],
            ["id" => 3, "ten_chuc_nang" => "Xóa chức năng"],
            ["id" => 4, "ten_chuc_nang" => "Cập nhật chức năng"],

            ["id" => 5, "ten_chuc_nang" => "Xem danh sách quyền đã cấp"],

            ["id" => 6, "ten_chuc_nang" => "Cấp quyền/Thu hồi quyền"],

            ["id" => 7, "ten_chuc_nang" => "Xem danh sách chức vụ"],
            ["id" => 8, "ten_chuc_nang" => "Xem danh sách chức vụ đang hoạt động"],

            ["id" => 10, "ten_chuc_nang" => "Thêm mới chức vụ"],
            ["id" => 12, "ten_chuc_nang" => "Xóa chức vụ"],
            ["id" => 13, "ten_chuc_nang" => "Cập nhật chức vụ"],
            ["id" => 14, "ten_chuc_nang" => "Đổi trạng thái chức vụ"],

            ["id" => 15, "ten_chuc_nang" => "Xem danh sách nhân viên"],
            ["id" => 16, "ten_chuc_nang" => "Xem danh sách nhân viên đang hoạt động"],

            ["id" => 18, "ten_chuc_nang" => "Thêm mới nhân viên"],
            ["id" => 20, "ten_chuc_nang" => "Xóa nhân viên"],
            ["id" => 21, "ten_chuc_nang" => "Cập nhật nhân viên"],
            ["id" => 22, "ten_chuc_nang" => "Đổi trạng thái nhân viên"],

            ["id" => 23, "ten_chuc_nang" => "Xem danh sách nhà cung cấp"],
            ["id" => 24, "ten_chuc_nang" => "Xem danh sách nhà cung cấp đang hoạt động"],

            ["id" => 25, "ten_chuc_nang" => "Thêm mới nhà cung cấp"],
            ["id" => 26, "ten_chuc_nang" => "Xóa nhà cung cấp"],
            ["id" => 27, "ten_chuc_nang" => "Cập nhật nhà cung cấp"],
            ["id" => 28, "ten_chuc_nang" => "Đổi trạng thái nhà cung cấp"],

            ["id" => 29, "ten_chuc_nang" => "Xem danh sách nguyên liệu"],
            ["id" => 30, "ten_chuc_nang" => "Xem danh sách nguyên liệu đang hoạt động"],

            ["id" => 31, "ten_chuc_nang" => "Thêm mới nguyên liệu"],
            ["id" => 32, "ten_chuc_nang" => "Xóa nguyên liệu"],
            ["id" => 33, "ten_chuc_nang" => "Cập nhật nguyên liệu"],
            ["id" => 34, "ten_chuc_nang" => "Đổi trạng thái nguyên liệu"],

            ["id" => 35, "ten_chuc_nang" => "Xem danh sách ca làm việc"],
            ["id" => 36, "ten_chuc_nang" => "Xem danh sách ca làm việc đang hoạt động"],

            ["id" => 37, "ten_chuc_nang" => "Thêm mới ca làm việc"],
            ["id" => 38, "ten_chuc_nang" => "Xóa ca làm việc"],
            ["id" => 39, "ten_chuc_nang" => "Cập nhật ca làm việc"],
            ["id" => 40, "ten_chuc_nang" => "Đổi trạng thái ca làm việc"],

            ["id" => 41, "ten_chuc_nang" => "Xem danh sách phân công ca làm"],

            ["id" => 42, "ten_chuc_nang" => "Thêm phân công ca làm"],
            ["id" => 43, "ten_chuc_nang" => "Xóa phân công ca làm"],
            ["id" => 44, "ten_chuc_nang" => "Cập nhật phân công ca làm"],
            ["id" => 45, "ten_chuc_nang" => "Đổi trạng thái phân công ca làm"],

            ["id" => 46, "ten_chuc_nang" => "Xem danh sách phiếu nhập kho"],
            ["id" => 47, "ten_chuc_nang" => "Xem chi tiết phiếu nhập kho"],

            ["id" => 48, "ten_chuc_nang" => "Thêm mới phiếu nhập kho"],
            ["id" => 49, "ten_chuc_nang" => "Xóa phiếu nhập kho"],
            ["id" => 50, "ten_chuc_nang" => "Cập nhật phiếu nhập kho"],
            ["id" => 51, "ten_chuc_nang" => "Thêm chi tiết phiếu nhập kho"],
            ["id" => 52, "ten_chuc_nang" => "Xóa chi tiết phiếu nhập kho"],

            ["id" => 53, "ten_chuc_nang" => "Xem danh sách danh mục"],

            ["id" => 54, "ten_chuc_nang" => "Thêm mới danh mục"],
            ["id" => 55, "ten_chuc_nang" => "Xóa danh mục"],
            ["id" => 56, "ten_chuc_nang" => "Cập nhật danh mục"],
            ["id" => 57, "ten_chuc_nang" => "Đổi trạng thái danh mục"],

            ["id" => 58, "ten_chuc_nang" => "Xem danh sách sản phẩm"],
            ["id" => 128, "ten_chuc_nang" => "Xem danh sách sản phẩm đang hoạt động"],

            ["id" => 59, "ten_chuc_nang" => "Thêm mới sản phẩm"],
            ["id" => 60, "ten_chuc_nang" => "Xóa sản phẩm"],
            ["id" => 61, "ten_chuc_nang" => "Cập nhật sản phẩm"],
            ["id" => 62, "ten_chuc_nang" => "Đổi trạng thái sản phẩm"],

            ["id" => 63, "ten_chuc_nang" => "Xem công thức món ăn"],

            ["id" => 64, "ten_chuc_nang" => "Thêm mới công thức món"],
            ["id" => 65, "ten_chuc_nang" => "Xóa công thức món"],
            ["id" => 66, "ten_chuc_nang" => "Cập nhật công thức món"],

            ["id" => 67, "ten_chuc_nang" => "Xem danh sách khu vực bàn"],

            ["id" => 68, "ten_chuc_nang" => "Thêm mới khu vực bàn"],
            ["id" => 69, "ten_chuc_nang" => "Xóa khu vực bàn"],
            ["id" => 70, "ten_chuc_nang" => "Cập nhật khu vực bàn"],

            ["id" => 71, "ten_chuc_nang" => "Xem danh sách bàn"],

            ["id" => 72, "ten_chuc_nang" => "Thêm mới bàn"],
            ["id" => 73, "ten_chuc_nang" => "Xóa bàn"],
            ["id" => 74, "ten_chuc_nang" => "Cập nhật bàn"],
            ["id" => 75, "ten_chuc_nang" => "Đổi trạng thái bàn"],

            ["id" => 76, "ten_chuc_nang" => "Xem danh sách khách hàng"],

            ["id" => 77, "ten_chuc_nang" => "Thêm mới khách hàng"],
            ["id" => 78, "ten_chuc_nang" => "Xóa khách hàng"],
            ["id" => 79, "ten_chuc_nang" => "Cập nhật khách hàng"],
            ["id" => 80, "ten_chuc_nang" => "Đổi trạng thái khách hàng"],

            ["id" => 81, "ten_chuc_nang" => "Xem danh sách voucher"],

            ["id" => 82, "ten_chuc_nang" => "Thêm mới voucher"],
            ["id" => 83, "ten_chuc_nang" => "Xóa voucher"],
            ["id" => 84, "ten_chuc_nang" => "Cập nhật voucher"],

            ["id" => 85, "ten_chuc_nang" => "Xem danh sách hóa đơn"],
            ["id" => 86, "ten_chuc_nang" => "Xem chi tiết hóa đơn"],

            ["id" => 87, "ten_chuc_nang" => "Thêm mới hóa đơn"],
            ["id" => 88, "ten_chuc_nang" => "Xóa hóa đơn"],
            ["id" => 89, "ten_chuc_nang" => "Cập nhật hóa đơn"],
            ["id" => 90, "ten_chuc_nang" => "Đổi trạng thái hóa đơn"],
            ["id" => 91, "ten_chuc_nang" => "Thêm chi tiết hóa đơn"],
            ["id" => 92, "ten_chuc_nang" => "Xóa chi tiết hóa đơn"],

            ["id" => 93, "ten_chuc_nang" => "Xem phương thức thanh toán"],

            ["id" => 96, "ten_chuc_nang" => "Xem thông tin thanh toán"],

            ["id" => 97, "ten_chuc_nang" => "Thực hiện thanh toán"],
            ["id" => 98, "ten_chuc_nang" => "Xóa thanh toán"],

            ["id" => 99, "ten_chuc_nang" => "Thống kê doanh thu theo ngày"],
            ["id" => 100, "ten_chuc_nang" => "Thống kê sản phẩm đã bán theo ngày"],
            ["id" => 101, "ten_chuc_nang" => "Thống kê nguyên liệu đã nhập"],
            ["id" => 102, "ten_chuc_nang" => "Thống kê doanh thu theo danh mục"],
            ["id" => 103, "ten_chuc_nang" => "Thống kê doanh thu theo khu vực"],
            ["id" => 104, "ten_chuc_nang" => "Thống kê doanh thu 7 ngày gần nhất"],
            ["id" => 105, "ten_chuc_nang" => "Thống kê doanh thu danh mục dashboard"],
            ["id" => 106, "ten_chuc_nang" => "Thống kê doanh thu theo tháng"],
            ["id" => 107, "ten_chuc_nang" => "Xem hóa đơn gần nhất dashboard"],
            ["id" => 108, "ten_chuc_nang" => "Xem phân công ca làm hôm nay dashboard"],

            ["id" => 109, "ten_chuc_nang" => "Xem danh sách bàn của chức năng bán hàng"],
            ["id" => 110, "ten_chuc_nang" => "Xem chi tiết hóa đơn theo bàn"],
            ["id" => 111, "ten_chuc_nang" => "In phiếu chế biến"],
            ["id" => 112, "ten_chuc_nang" => "Thêm món vào hoá đơn"],
            ["id" => 113, "ten_chuc_nang" => "Mở bàn mới"],
            ["id" => 114, "ten_chuc_nang" => "Xem món đang chế biến"],
            ["id" => 115, "ten_chuc_nang" => "Đánh dấu món ăn xong"],
            ["id" => 116, "ten_chuc_nang" => "Áp dụng voucher"],
            ["id" => 117, "ten_chuc_nang" => "Hủy voucher"],
            ["id" => 118, "ten_chuc_nang" => "Xem hóa đơn theo bàn"],
            ["id" => 119, "ten_chuc_nang" => "Cập nhật chi tiết hóa đơn"],
            ["id" => 120, "ten_chuc_nang" => "Tìm kiếm khách hàng"],
            ["id" => 121, "ten_chuc_nang" => "Xem chi tiết hóa đơn theo ID hóa đơn"],
            ["id" => 122, "ten_chuc_nang" => "In hoá đơn và thanh toán"],
            ["id" => 123, "ten_chuc_nang" => "Xem thông tin cá nhân"],
            ["id" => 124, "ten_chuc_nang" => "Cập nhật thông tin cá nhân"],
            ["id" => 125, "ten_chuc_nang" => "Đổi mật khẩu cá nhân"],
            ["id" => 126, "ten_chuc_nang" => "Đăng ký ca làm cá nhân"],
            ["id" => 127, "ten_chuc_nang" => "Xem KPI dashboard"],
        ]);
    }
}
