<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\NhanVien;
use App\Models\PhienCa;
use App\Models\CaLamViec;
use App\Models\HoaDon;
use App\Models\Ban;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\PhienCaController;
use App\Http\Controllers\HoaDonController;

echo "=== BẮT ĐẦU KIỂM THỬ NGHIỆP VỤ PHIÊN CA (UC30) ===\n";

// 1. Tìm hoặc tạo nhân viên thu ngân để test
$nhanVien = NhanVien::first();
if (!$nhanVien) {
    echo "LỖI: Không tìm thấy nhân viên nào trong database!\n";
    exit(1);
}
echo "[1] Nhân viên test: " . $nhanVien->ho_va_ten . " (ID: " . $nhanVien->id . ")\n";

// Đóng bất kỳ phiên ca nào đang mở của nhân viên này trước khi test
PhienCa::where('id_nhan_vien', $nhanVien->id)
    ->where('trang_thai', PhienCa::TRANG_THAI_DANG_MO)
    ->update(['trang_thai' => PhienCa::TRANG_THAI_DA_CHOT, 'thoi_gian_dong_ca' => now()]);

// Set auth context
Auth::guard('sanctum')->setUser($nhanVien);

$controller = new PhienCaController();

// 2. Test kiemTraCaHienTai khi chưa mở ca
$req1 = Request::create('/api/admin/phien-ca/ca-hien-tai', 'GET');
$res1 = $controller->kiemTraCaHienTai($req1);
$data1 = json_decode($res1->getContent(), true);
echo "[2] Kiểm tra ca ban đầu: has_active_shift = " . ($data1['has_active_shift'] ? 'true' : 'false') . "\n";
assert($data1['has_active_shift'] === false, "Phải chưa có ca nào mở");

// 3. Test Mở Ca: Tiền đầu ca = 500,000 đ
$ca = CaLamViec::first();
$reqMoCa = Request::create('/api/admin/phien-ca/mo-ca', 'POST', [
    'tien_dau_ca'    => 500000,
    'id_ca_lam_viec' => $ca ? $ca->id : null,
    'ghi_chu'        => 'Test mở ca tự động',
]);
$resMoCa = $controller->moCa($reqMoCa);
$dataMoCa = json_decode($resMoCa->getContent(), true);
echo "[3] Mở ca: " . $dataMoCa['message'] . " | Mã phiên: " . $dataMoCa['data']['ma_phien_ca'] . " | Tiền đầu ca: " . number_format($dataMoCa['data']['tien_dau_ca']) . " đ\n";
assert($dataMoCa['status'] === 1, "Mở ca phải thành công");
$phienCaId = $dataMoCa['data']['id'];

// 4. Test mở ca lần 2 (Phải bị chặn vì đang có ca mở)
$resMoCa2 = $controller->moCa($reqMoCa);
$dataMoCa2 = json_decode($resMoCa2->getContent(), true);
echo "[4] Thử mở ca trùng lặp: " . $dataMoCa2['message'] . " (Status = " . $dataMoCa2['status'] . ")\n";
assert($dataMoCa2['status'] === 0, "Mở ca trùng phải bị chặn");

// 5. Test Tạo Hóa Đơn & Bán Hàng gán vào Phiên Ca
$hoaDonController = new HoaDonController();
$ban = Ban::first();
$banId = $ban ? $ban->id : 1;

// Giả lập mở bàn
$reqMoBan = Request::create("/api/admin/hoa-don/mo-ban/{$banId}", 'GET');
// Đảm bảo bàn trống
HoaDon::where('id_ban', $banId)->where('tinh_trang', 0)->delete();
$resMoBan = $hoaDonController->moBan($banId);
$hoaDonMoi = HoaDon::where('id_ban', $banId)->where('tinh_trang', 0)->latest()->first();

if ($hoaDonMoi) {
    echo "[5] Tạo hóa đơn bán hàng: Mã HĐ = " . $hoaDonMoi->ma_hoa_don . " | id_phien_ca = " . $hoaDonMoi->id_phien_ca . "\n";
    assert($hoaDonMoi->id_phien_ca == $phienCaId, "Hóa đơn phải tự động gắn id_phien_ca của ca đang mở");

    // Giả lập thanh toán tiền mặt 45,000 đ
    $hoaDonMoi->update([
        'tong_tien'              => 45000,
        'tien_truoc_giam'        => 45000,
        'phuong_thuc_thanh_toan' => 1, // Tiền mặt
        'tinh_trang'             => 1, // Đã thanh toán
    ]);
}

// 6. Test Dữ Liệu Chốt Ca Realtime
$reqDuLieu = Request::create("/api/admin/phien-ca/du-lieu-chot-ca/{$phienCaId}", 'GET');
$resDuLieu = $controller->duLieuChotCa($reqDuLieu, $phienCaId);
$dataDuLieu = json_decode($resDuLieu->getContent(), true);
$stats = $dataDuLieu['data'];
echo "[6] Thống kê Realtime ca:\n";
echo "    - Tiền đầu ca: " . number_format($stats['tien_dau_ca']) . " đ\n";
echo "    - Tiền mặt thu được: " . number_format($stats['tong_thu_tien_mat']) . " đ\n";
echo "    - Tiền mặt hệ thống tính trong két: " . number_format($stats['tien_mat_he_thong']) . " đ\n";
echo "    - Số đơn hoàn tất: " . $stats['so_luong_don_hang'] . "\n";
assert($stats['tien_mat_he_thong'] == 545000, "Két tiền hệ thống phải là 545,000 đ");

// 7. Test Chốt Ca & Kiểm Đếm Két Tiền (Giả sử thực đếm = 550,000 đ -> Thừa 5,000 đ)
$reqChotCa = Request::create('/api/admin/phien-ca/chot-ca', 'POST', [
    'id_phien_ca'      => $phienCaId,
    'tien_mat_thuc_te' => 550000,
    'ghi_chu'          => 'Khách bo thêm tiền thừa 5.000 đ',
]);
$resChotCa = $controller->chotCa($reqChotCa);
$dataChotCa = json_decode($resChotCa->getContent(), true);
echo "[7] Chốt ca: " . $dataChotCa['message'] . "\n";
echo "    - Tiền thực đếm: " . number_format($dataChotCa['data']['tien_mat_thuc_te']) . " đ\n";
echo "    - Tiền chênh lệch: " . number_format($dataChotCa['data']['tien_chenh_lech']) . " đ (Thừa 5k)\n";
echo "    - Trạng thái ca: " . ($dataChotCa['data']['trang_thai'] == 2 ? 'ĐÃ CHỐT' : 'CHƯA CHỐT') . "\n";
assert($dataChotCa['data']['tien_chenh_lech'] == 5000, "Chênh lệch phải là +5,000 đ");
assert($dataChotCa['data']['trang_thai'] == 2, "Trạng thái phải là đã chốt (2)");

// 8. Test Lấy Báo Cáo In Z-Report
$resZReport = $controller->zReport($phienCaId);
$dataZReport = json_decode($resZReport->getContent(), true);
echo "[8] Z-Report: Mã " . $dataZReport['data']['phien_ca']['ma_phien_ca'] . " | Thu ngân: " . $dataZReport['data']['nhan_vien']['ho_va_ten'] . " | Tổng doanh thu: " . number_format($dataZReport['data']['phien_ca']['tong_doanh_thu']) . " đ\n";
assert($dataZReport['status'] === 1, "Z-Report phải load thành công");

// 9. Test Admin Xem Lịch Sử Ca
$reqLichSu = Request::create('/api/admin/phien-ca/lich-su', 'GET');
$resLichSu = $controller->lichSu($reqLichSu);
$dataLichSu = json_decode($resLichSu->getContent(), true);
echo "[9] Lịch sử ca Admin: Tổng số ca ghi nhận = " . $dataLichSu['data']['total'] . " | Doanh thu chốt hôm nay = " . number_format($dataLichSu['summary']['doanh_thu_hom_nay']) . " đ\n";
assert($dataLichSu['status'] === 1, "Lịch sử ca phải trả về status 1");

echo "=== TẤT CẢ CÁC BƯỚC KIỂM THỬ ĐÃ THÀNH CÔNG 100%! ===\n";
