<?php

namespace App\Http\Controllers;

use App\Models\CaLamViec;
use App\Models\HoaDon;
use App\Models\NhanVien;
use App\Models\PhienCa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PhienCaController extends Controller
{
    /**
     * Thu ngân / Nhân viên: Kiểm tra xem có phiên ca nào đang mở hay không.
     * Dùng cho POS để chặn bán hàng nếu chưa mở ca.
     */
    public function kiemTraCaHienTai(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return response()->json([
                'status'  => 0,
                'message' => 'Bạn chưa đăng nhập!',
            ], 401);
        }

        $phienCa = PhienCa::with(['caLamViec', 'nhanVien:id,ho_va_ten,email'])
            ->where('id_nhan_vien', $user->id)
            ->where('trang_thai', PhienCa::TRANG_THAI_DANG_MO)
            ->latest()
            ->first();

        // Lấy danh sách ca làm việc đang hoạt động để thu ngân chọn khi mở ca
        $danhSachCa = CaLamViec::where('tinh_trang', 1)->get();

        return response()->json([
            'status'            => 1,
            'has_active_shift'  => (bool)$phienCa,
            'data'              => $phienCa,
            'danh_sach_ca'      => $danhSachCa,
            'user'              => [
                'id'        => $user->id,
                'ho_va_ten' => $user->ho_va_ten,
                'email'     => $user->email,
            ],
        ]);
    }

    /**
     * Mở ca làm việc mới.
     * Ràng buộc: Một nhân viên chỉ có 1 ca đang mở tại 1 thời điểm.
     */
    public function moCa(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return response()->json([
                'status'  => 0,
                'message' => 'Bạn chưa đăng nhập!',
            ], 401);
        }

        // Kiểm tra xem đã có ca nào đang mở chưa
        $caDangMo = PhienCa::where('id_nhan_vien', $user->id)
            ->where('trang_thai', PhienCa::TRANG_THAI_DANG_MO)
            ->first();

        if ($caDangMo) {
            return response()->json([
                'status'  => 0,
                'message' => 'Bạn đang có phiên ca đang mở (' . $caDangMo->ma_phien_ca . '). Vui lòng chốt ca trước khi mở ca mới!',
                'data'    => $caDangMo,
            ]);
        }

        $tienDauCa = floatval($request->input('tien_dau_ca', 0));
        if ($tienDauCa < 0) {
            return response()->json([
                'status'  => 0,
                'message' => 'Số tiền đầu ca trong két không được âm!',
            ]);
        }

        $idCaLamViec = $request->input('id_ca_lam_viec');
        $ghiChu = $request->input('ghi_chu');

        // Tạo mã phiên ca tự động: PC-YYYYMMDD-XXXX
        $todayStr = Carbon::today()->format('Ymd');
        $countToday = PhienCa::whereDate('created_at', Carbon::today())->count();
        $maPhienCa = 'PC-' . $todayStr . '-' . str_pad($countToday + 1, 4, '0', STR_PAD_LEFT);

        $phienCa = PhienCa::create([
            'ma_phien_ca'           => $maPhienCa,
            'id_nhan_vien'          => $user->id,
            'id_ca_lam_viec'        => $idCaLamViec,
            'thoi_gian_mo_ca'       => Carbon::now(),
            'tien_dau_ca'           => $tienDauCa,
            'tong_thu_tien_mat'     => 0,
            'tong_thu_chuyen_khoan' => 0,
            'tong_doanh_thu'        => 0,
            'tien_mat_he_thong'     => $tienDauCa,
            'tien_chenh_lech'       => 0,
            'so_luong_don_hang'     => 0,
            'so_luong_don_huy'      => 0,
            'ghi_chu'               => $ghiChu,
            'trang_thai'            => PhienCa::TRANG_THAI_DANG_MO,
        ]);

        return response()->json([
            'status'  => 1,
            'message' => 'Mở phiên ca thành công! Chúc bạn một ngày làm việc hiệu quả.',
            'data'    => $phienCa->load('caLamViec'),
        ]);
    }

    /**
     * Lấy dữ liệu tạm tính / realtime của phiên ca để chuẩn bị chốt ca.
     */
    public function duLieuChotCa(Request $request, $id = null)
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return response()->json(['status' => 0, 'message' => 'Chưa đăng nhập!'], 401);
        }

        if ($id) {
            $phienCa = PhienCa::with(['caLamViec', 'nhanVien'])->find($id);
        } else {
            $phienCa = PhienCa::with(['caLamViec', 'nhanVien'])
                ->where('id_nhan_vien', $user->id)
                ->where('trang_thai', PhienCa::TRANG_THAI_DANG_MO)
                ->latest()
                ->first();
        }

        if (!$phienCa) {
            return response()->json([
                'status'  => 0,
                'message' => 'Không tìm thấy phiên ca đang hoạt động!',
            ]);
        }

        // Truy vấn các hóa đơn gắn với phiên ca này
        // Lưu ý: hóa đơn có thể link qua id_phien_ca, hoặc nếu hóa đơn cũ chưa kịp gán id_phien_ca thì fallback qua thoi_gian_mo_ca của ca đó
        $queryHoaDon = HoaDon::where(function ($q) use ($phienCa) {
            $q->where('hoa_dons.id_phien_ca', $phienCa->id)
              ->orWhere(function ($sub) use ($phienCa) {
                  $sub->whereNull('hoa_dons.id_phien_ca')
                      ->where('hoa_dons.created_at', '>=', $phienCa->thoi_gian_mo_ca);
                  if ($phienCa->thoi_gian_dong_ca) {
                      $sub->where('hoa_dons.created_at', '<=', $phienCa->thoi_gian_dong_ca);
                  }
              });
        });

        // 1. Tiền mặt: phuong_thuc_thanh_toan = 1 và tinh_trang = 1 (Đã thanh toán)
        $tongThuTienMat = (clone $queryHoaDon)
            ->where('tinh_trang', HoaDon::TRANG_THAI_DA_THANH_TOAN)
            ->where('phuong_thuc_thanh_toan', 1)
            ->sum('tong_tien');

        // 2. Chuyển khoản / VietQR: phuong_thuc_thanh_toan = 2 và tinh_trang = 1
        $tongThuChuyenKhoan = (clone $queryHoaDon)
            ->where('tinh_trang', HoaDon::TRANG_THAI_DA_THANH_TOAN)
            ->where('phuong_thuc_thanh_toan', 2)
            ->sum('tong_tien');

        // 3. Đơn thanh toán thành công
        $soLuongDonHang = (clone $queryHoaDon)
            ->where('tinh_trang', HoaDon::TRANG_THAI_DA_THANH_TOAN)
            ->count();

        // 4. Đơn hủy / từ chối (tinh_trang = 2 hoặc có ly_do_huy)
        $soLuongDonHuy = (clone $queryHoaDon)
            ->where('tinh_trang', 2)
            ->count();

        $tongDoanhThu = $tongThuTienMat + $tongThuChuyenKhoan;
        $tienMatHeThong = $phienCa->tien_dau_ca + $tongThuTienMat;

        // Lấy 10 hóa đơn mới nhất trong ca để preview
        $hoaDons = (clone $queryHoaDon)
            ->leftJoin('bans', 'hoa_dons.id_ban', '=', 'bans.id')
            ->select('hoa_dons.*', 'bans.ten_ban')
            ->orderBy('hoa_dons.id', 'desc')
            ->take(15)
            ->get();

        return response()->json([
            'status' => 1,
            'data'   => [
                'phien_ca'              => $phienCa,
                'tien_dau_ca'           => (float)$phienCa->tien_dau_ca,
                'tong_thu_tien_mat'     => (float)$tongThuTienMat,
                'tong_thu_chuyen_khoan' => (float)$tongThuChuyenKhoan,
                'tong_doanh_thu'        => (float)$tongDoanhThu,
                'tien_mat_he_thong'     => (float)$tienMatHeThong,
                'so_luong_don_hang'     => $soLuongDonHang,
                'so_luong_don_huy'      => $soLuongDonHuy,
                'danh_sach_hoa_don'     => $hoaDons,
            ],
        ]);
    }

    /**
     * Chốt phiên ca: Nhận tiền mặt thực đếm, tính chênh lệch và đóng ca.
     */
    public function chotCa(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return response()->json(['status' => 0, 'message' => 'Chưa đăng nhập!'], 401);
        }

        $idPhienCa = $request->input('id_phien_ca');
        if ($idPhienCa) {
            $phienCa = PhienCa::find($idPhienCa);
        } else {
            $phienCa = PhienCa::where('id_nhan_vien', $user->id)
                ->where('trang_thai', PhienCa::TRANG_THAI_DANG_MO)
                ->latest()
                ->first();
        }

        if (!$phienCa) {
            return response()->json([
                'status'  => 0,
                'message' => 'Không tìm thấy phiên ca đang hoạt động để chốt!',
            ]);
        }

        if ($phienCa->trang_thai == PhienCa::TRANG_THAI_DA_CHOT) {
            return response()->json([
                'status'  => 0,
                'message' => 'Phiên ca này đã được chốt trước đó!',
            ]);
        }

        $tienMatThucTe = floatval($request->input('tien_mat_thuc_te', 0));
        $ghiChu = $request->input('ghi_chu', $phienCa->ghi_chu);

        // Tính toán các chỉ số tài chính của ca
        $queryHoaDon = HoaDon::where(function ($q) use ($phienCa) {
            $q->where('hoa_dons.id_phien_ca', $phienCa->id)
              ->orWhere(function ($sub) use ($phienCa) {
                  $sub->whereNull('hoa_dons.id_phien_ca')
                      ->where('hoa_dons.created_at', '>=', $phienCa->thoi_gian_mo_ca);
                  if ($phienCa->thoi_gian_dong_ca) {
                      $sub->where('hoa_dons.created_at', '<=', $phienCa->thoi_gian_dong_ca);
                  }
              });
        });

        $tongThuTienMat = (clone $queryHoaDon)
            ->where('tinh_trang', HoaDon::TRANG_THAI_DA_THANH_TOAN)
            ->where('phuong_thuc_thanh_toan', 1)
            ->sum('tong_tien');

        $tongThuChuyenKhoan = (clone $queryHoaDon)
            ->where('tinh_trang', HoaDon::TRANG_THAI_DA_THANH_TOAN)
            ->where('phuong_thuc_thanh_toan', 2)
            ->sum('tong_tien');

        $soLuongDonHang = (clone $queryHoaDon)
            ->where('tinh_trang', HoaDon::TRANG_THAI_DA_THANH_TOAN)
            ->count();

        $soLuongDonHuy = (clone $queryHoaDon)
            ->where('tinh_trang', 2)
            ->count();

        $tongDoanhThu = $tongThuTienMat + $tongThuChuyenKhoan;
        $tienMatHeThong = $phienCa->tien_dau_ca + $tongThuTienMat;
        $tienChenhLech = $tienMatThucTe - $tienMatHeThong;

        // Cập nhật tất cả các hóa đơn trong ca mà chưa gắn id_phien_ca
        HoaDon::whereNull('id_phien_ca')
            ->where('created_at', '>=', $phienCa->thoi_gian_mo_ca)
            ->update(['id_phien_ca' => $phienCa->id]);

        $phienCa->update([
            'thoi_gian_dong_ca'     => Carbon::now(),
            'tong_thu_tien_mat'     => $tongThuTienMat,
            'tong_thu_chuyen_khoan' => $tongThuChuyenKhoan,
            'tong_doanh_thu'        => $tongDoanhThu,
            'tien_mat_he_thong'     => $tienMatHeThong,
            'tien_mat_thuc_te'      => $tienMatThucTe,
            'tien_chenh_lech'       => $tienChenhLech,
            'so_luong_don_hang'     => $soLuongDonHang,
            'so_luong_don_huy'      => $soLuongDonHuy,
            'ghi_chu'               => $ghiChu,
            'trang_thai'            => PhienCa::TRANG_THAI_DA_CHOT,
        ]);

        return response()->json([
            'status'       => 1,
            'message'      => 'Chốt phiên ca và kết tiền thành công!',
            'id_phien_ca'  => $phienCa->id,
            'data'         => $phienCa->load(['caLamViec', 'nhanVien']),
        ]);
    }

    /**
     * Dữ liệu chi tiết Z-Report in khổ 80mm
     */
    public function zReport($id)
    {
        $phienCa = PhienCa::with(['caLamViec', 'nhanVien'])->find($id);
        if (!$phienCa) {
            return response()->json([
                'status'  => 0,
                'message' => 'Không tìm thấy phiên ca!',
            ], 404);
        }

        // Thống kê phân loại danh mục sản phẩm bán trong ca
        $sanPhamBan = DB::table('chi_tiet_hoa_dons')
            ->join('hoa_dons', 'chi_tiet_hoa_dons.id_hoa_don', '=', 'hoa_dons.id')
            ->join('san_phams', 'chi_tiet_hoa_dons.id_san_pham', '=', 'san_phams.id')
            ->where('hoa_dons.id_phien_ca', $phienCa->id)
            ->where('hoa_dons.tinh_trang', HoaDon::TRANG_THAI_DA_THANH_TOAN)
            ->select(
                'san_phams.ten_san_pham',
                DB::raw('SUM(chi_tiet_hoa_dons.so_luong) as tong_so_luong'),
                DB::raw('SUM(chi_tiet_hoa_dons.so_luong * chi_tiet_hoa_dons.don_gia) as tong_tien_mon')
            )
            ->groupBy('san_phams.id', 'san_phams.ten_san_pham')
            ->orderByDesc('tong_so_luong')
            ->get();

        return response()->json([
            'status' => 1,
            'data'   => [
                'phien_ca'     => $phienCa,
                'nhan_vien'    => $phienCa->nhanVien,
                'ca_lam_viec'  => $phienCa->caLamViec,
                'san_pham_ban' => $sanPhamBan,
            ],
        ]);
    }

    /**
     * Admin / Quản lý: Xem lịch sử tất cả các phiên ca, lọc theo thời gian, nhân viên, trạng thái.
     */
    public function lichSu(Request $request)
    {
        $query = PhienCa::with(['nhanVien:id,ho_va_ten,email,so_dien_thoai', 'caLamViec:id,ten_ca,gio_bat_dau,gio_ket_thuc'])
            ->orderBy('id', 'desc');

        if ($request->filled('id_nhan_vien')) {
            $query->where('id_nhan_vien', $request->id_nhan_vien);
        }

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        if ($request->filled('id_ca_lam_viec')) {
            $query->where('id_ca_lam_viec', $request->id_ca_lam_viec);
        }

        if ($request->filled('tu_ngay')) {
            $query->whereDate('thoi_gian_mo_ca', '>=', $request->tu_ngay);
        }

        if ($request->filled('den_ngay')) {
            $query->whereDate('thoi_gian_mo_ca', '<=', $request->den_ngay);
        }

        $list = $query->paginate(15);

        // Tổng hợp thống kê nhanh cho Admin
        $tongDoanhThuHomNay = PhienCa::whereDate('thoi_gian_mo_ca', Carbon::today())
            ->where('trang_thai', PhienCa::TRANG_THAI_DA_CHOT)
            ->sum('tong_doanh_thu');

        $tongCaDangMo = PhienCa::where('trang_thai', PhienCa::TRANG_THAI_DANG_MO)->count();

        $tongChenhLechHomNay = PhienCa::whereDate('thoi_gian_mo_ca', Carbon::today())
            ->where('trang_thai', PhienCa::TRANG_THAI_DA_CHOT)
            ->sum('tien_chenh_lech');

        $nhanViens = NhanVien::select('id', 'ho_va_ten')->get();
        $caLamViecs = CaLamViec::select('id', 'ten_ca')->get();

        return response()->json([
            'status' => 1,
            'data'   => $list,
            'summary' => [
                'doanh_thu_hom_nay'     => (float)$tongDoanhThuHomNay,
                'ca_dang_mo'            => $tongCaDangMo,
                'chenh_lech_hom_nay'    => (float)$tongChenhLechHomNay,
            ],
            'filters' => [
                'nhan_viens'    => $nhanViens,
                'ca_lam_viecs'  => $caLamViecs,
            ],
        ]);
    }
}
