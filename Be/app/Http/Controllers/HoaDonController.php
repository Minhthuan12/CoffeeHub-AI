<?php

namespace App\Http\Controllers;

use App\Models\HoaDon;
use App\Models\Ban;
use App\Models\ChiTietHoaDon;
use App\Models\KhachHang;
use App\Models\SanPham;
use App\Http\Requests\ThemMoiHoaDonRequest;
use App\Http\Requests\UpdateHoaDonRequest;
use App\Models\Voucher;
use App\Jobs\sendMailJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HoaDonController extends Controller
{
    public function getData()
    {
        $data = HoaDon::leftJoin('khach_hangs', 'hoa_dons.id_khach_hang', '=', 'khach_hangs.id')
            ->leftJoin('bans', 'hoa_dons.id_ban', '=', 'bans.id')
            ->leftJoin('phan_cong_ca_lams', 'hoa_dons.id_phan_cong_ca_lam', '=', 'phan_cong_ca_lams.id')
            ->leftJoin('ca_lam_viecs', 'phan_cong_ca_lams.id_ca_lam_viec', '=', 'ca_lam_viecs.id')
            ->select(
                'hoa_dons.id',
                'hoa_dons.ma_hoa_don',
                'hoa_dons.tong_tien',
                'hoa_dons.tien_truoc_giam',
                'hoa_dons.so_tien_giam',
                'hoa_dons.phuong_thuc_thanh_toan',
                'hoa_dons.tinh_trang',
                'hoa_dons.id_ban',
                'hoa_dons.id_khach_hang',
                'hoa_dons.id_phan_cong_ca_lam',
                'hoa_dons.id_voucher',
                'hoa_dons.created_at',
                'hoa_dons.updated_at',
                'khach_hangs.ho_va_ten',
                'bans.ten_ban',
                'ca_lam_viecs.ten_ca'
            )
            ->orderByDesc('hoa_dons.id')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function store(ThemMoiHoaDonRequest $request)
    {
        HoaDon::create([
            'ma_hoa_don' => $request->ma_hoa_don,
            'tong_tien' => $request->tong_tien,
            'tien_truoc_giam' => $request->tien_truoc_giam,
            'so_tien_giam' => $request->so_tien_giam,
            'phuong_thuc_thanh_toan' => $request->phuong_thuc_thanh_toan,
            'tinh_trang' => $request->tinh_trang,
            'id_ban' => $request->id_ban,
            'id_khach_hang' => $request->id_khach_hang,
            'id_phan_cong_ca_lam' => $request->id_phan_cong_ca_lam,
            'id_voucher' => $request->id_voucher,
        ]);

        return response()->json([
            'message' => 'Đã tạo mới hóa đơn thành công!',
            'status' => 1
        ]);
    }

    public function update(UpdateHoaDonRequest $request)
    {
        HoaDon::where('id', $request->id)->update([
            'ma_hoa_don' => $request->ma_hoa_don,
            'tong_tien' => $request->tong_tien,
            'tien_truoc_giam' => $request->tien_truoc_giam,
            'so_tien_giam' => $request->so_tien_giam,
            'phuong_thuc_thanh_toan' => $request->phuong_thuc_thanh_toan,
            'tinh_trang' => $request->tinh_trang,
            'id_ban' => $request->id_ban,
            'id_khach_hang' => $request->id_khach_hang,
            'id_phan_cong_ca_lam' => $request->id_phan_cong_ca_lam,
            'id_voucher' => $request->id_voucher,
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'Cập nhật hóa đơn thành công!'
        ]);
    }

    public function changeStatus(Request $request)
    {
        $hoaDon = HoaDon::find($request->id);
        if ($hoaDon) {
            $hoaDon->tinh_trang = $hoaDon->tinh_trang == 1 ? 0 : 1;
            $hoaDon->save();
            return response()->json([
                'status' => 1,
                'message' => 'Đã thay đổi trạng thái hóa đơn thành công!'
            ]);
        }
        return response()->json([
            'status' => 0,
            'message' => 'Không tìm thấy dữ liệu!'
        ]);
    }
    public function moBan($id_ban)
    {
        $ban = Ban::find($id_ban);
        if ($ban) {
            $check = HoaDon::join('bans', 'bans.id', 'hoa_dons.id_ban')
                ->where('hoa_dons.tinh_trang', 0)
                ->where('bans.id', $id_ban)
                ->first();
            if ($check) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Bàn này đang có người sử dụng!'
                ]);
            }
            $hoaDon = HoaDon::create([
                'ma_hoa_don' => \Illuminate\Support\Str::uuid(),
                'tong_tien' => 0,
                'tien_truoc_giam' => 0,
                'so_tien_giam' => 0,
                'phuong_thuc_thanh_toan' => null,
                'tinh_trang' => 0,
                'id_ban' => $id_ban,
                'id_khach_hang' => 1,
                'id_phan_cong_ca_lam' => 1,
                'id_voucher' => null,
            ]);
            $hoaDon->update([
                'ma_hoa_don' => "HD-" . (1000 + $hoaDon->id)
            ]);

            return response()->json([
                'status' => 1,
                'message' => 'Đã mở bàn thành công!',
                'id_ban' => $id_ban
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Bàn này không tồn tại!'
            ]);
        }
    }

    public function getHoaDonTheoBan($id_ban)
    {
        $hoaDon = HoaDon::leftjoin('khach_hangs', 'hoa_dons.id_khach_hang', 'khach_hangs.id')
            ->select('hoa_dons.*', 'khach_hangs.ho_va_ten')
            ->where('hoa_dons.id_ban', $id_ban)
            ->where('hoa_dons.tinh_trang', 0)
            ->first();

        if (!$hoaDon) {
            return response()->json([
                'status' => 0,
                'message' => 'Bàn này chưa có hóa đơn đang mở!'
            ]);
        }

        $resVoucher = null;
        if ($hoaDon->id_voucher) {
            $voucher = Voucher::find($hoaDon->id_voucher);
            if ($voucher) {
                $resVoucher = [
                    'so_tien_giam' => $hoaDon->so_tien_giam,
                    'loai_giam' => $voucher->loai_giam,
                    'so_giam_gia' => $voucher->so_giam_gia,
                    'ma_voucher' => $voucher->ma_voucher
                ];
            }
        }

        return response()->json([
            'status' => 1,
            'id_hoa_don' => $hoaDon->id,
            'tien_truoc_giam' => $hoaDon->tien_truoc_giam,
            'so_tien_giam' => $hoaDon->so_tien_giam,
            'tong_tien' => $hoaDon->tong_tien,
            'voucher' => $resVoucher,
            'khach_hang' => $hoaDon->ho_va_ten,
            'created_at' => $hoaDon->created_at
        ]);
    }

    public function destroy(Request $request)
    {
        $hoaDon = HoaDon::find($request->id);
        if ($hoaDon) {
            ChiTietHoaDon::where('id_hoa_don', $hoaDon->id)->delete();
            $hoaDon->delete();

            return response()->json([
                'status' => 1,
                'message' => 'Hủy hóa đơn thành công!'
            ]);
        }

        return response()->json([
            'status' => 0,
            'message' => 'Không tìm thấy hóa đơn cần hủy!'
        ]);
    }

    public function inHoaDon($id_hoa_don)
    {
        $hoaDon = HoaDon::where('id', $id_hoa_don)->first();
        $thongTinThuNgan = HoaDon::join('phan_cong_ca_lams', 'hoa_dons.id_phan_cong_ca_lam', 'phan_cong_ca_lams.id')
            ->join('nhan_viens', 'phan_cong_ca_lams.id_nhan_vien', 'nhan_viens.id')
            ->select('nhan_viens.ho_va_ten')
            ->where('hoa_dons.id', $id_hoa_don)
            ->first();
        $thongTinKhachHang = HoaDon::join('khach_hangs', 'hoa_dons.id_khach_hang', 'khach_hangs.id')
            ->select('khach_hangs.ho_va_ten', 'khach_hangs.so_dien_thoai')
            ->where('hoa_dons.id', $id_hoa_don)
            ->first();
        $thongTinBan = HoaDon::join('bans', 'hoa_dons.id_ban', 'bans.id')
            ->select('bans.ten_ban')
            ->where('hoa_dons.id', $id_hoa_don)
            ->first();
        $thongTinChiTietHoaDon = ChiTietHoaDon::join('san_phams', 'chi_tiet_hoa_dons.id_san_pham', 'san_phams.id')
            ->select('san_phams.ten_san_pham', 'chi_tiet_hoa_dons.so_luong', 'chi_tiet_hoa_dons.don_gia', 'chi_tiet_hoa_dons.tuy_chon', 'chi_tiet_hoa_dons.ghi_chu')
            ->where('chi_tiet_hoa_dons.id_hoa_don', $id_hoa_don)
            ->get();
        return response()->json([
            'hoa_don' => $hoaDon,
            'thong_tin_thu_ngan' => $thongTinThuNgan,
            'thong_tin_khach_hang' => $thongTinKhachHang,
            'thong_tin_ban' => $thongTinBan,
            'thong_tin_chi_tiet_hoa_don' => $thongTinChiTietHoaDon
        ]);
    }

    public function thanhToan(Request $request)
    {
        $hoaDon = HoaDon::find($request->id_hoa_don);
        if ($hoaDon) {
            if ($request->phuong_thuc_thanh_toan == 1) {
                $hoaDon->update([
                    'tinh_trang' => 1,
                    'phuong_thuc_thanh_toan' => $request->phuong_thuc_thanh_toan
                ]);

                // Pay-First: Chuyển các món chưa duyệt (0) sang quầy Barista pha chế (1)
                ChiTietHoaDon::where('id_hoa_don', $hoaDon->id)
                    ->where('tinh_trang', 0)
                    ->update(['tinh_trang' => 1]);

                $khachHang = KhachHang::find($hoaDon->id_khach_hang);

                if ($khachHang && $khachHang->email) {
                    $chiTiet = ChiTietHoaDon::where('id_hoa_don', $hoaDon->id)
                        ->join('san_phams', 'chi_tiet_hoa_dons.id_san_pham', '=', 'san_phams.id')
                        ->select('chi_tiet_hoa_dons.so_luong', 'chi_tiet_hoa_dons.don_gia', 'san_phams.ten_san_pham')
                        ->get()
                        ->toArray();


                    dispatch(new sendMailJob(
                        $khachHang->email,
                        'Xác nhận thanh toán thành công - ' . $hoaDon->ma_hoa_don,
                        'view_mail_hoa_don_thanh_toan',
                        [
                            'ho_va_ten'             => $khachHang->ho_va_ten,
                            'ma_hoa_don'            => $hoaDon->ma_hoa_don,
                            'tong_tien'             => $hoaDon->tong_tien,
                            'so_tien_giam'          => $hoaDon->so_tien_giam,
                            'phuong_thuc_thanh_toan' => $request->phuong_thuc_thanh_toan,
                            'ngay_thanh_toan'       => now()->format('d/m/Y H:i'),
                            'chi_tiet'              => $chiTiet,
                        ]
                    ));
                }

                return response()->json([
                    'status' => 1,
                    'message' => 'Thanh toán thành công!'
                ]);
            } else {
                // Chuyển khoản: GiaoDichController sẽ tự xác nhận và cập nhật sau
                return response()->json([
                    'status' => 1,
                    'message' => 'In hóa đơn thành công!'
                ]);
            }
        }

        return response()->json([
            'status' => 0,
            'message' => 'Hóa đơn không tồn tại!'
        ]);
    }

    public function xacNhanThanhToan(Request $request)
    {
        $hoaDon = null;
        if ($request->has('id_hoa_don') && !empty($request->id_hoa_don)) {
            $hoaDon = HoaDon::find($request->id_hoa_don);
        }
        if (!$hoaDon && $request->has('id_ban') && !empty($request->id_ban)) {
            $hoaDon = HoaDon::where('id_ban', $request->id_ban)
                ->where('tinh_trang', 0)
                ->first();
        }

        if ($hoaDon) {
            $phuongThuc = $request->phuong_thuc_thanh_toan ?? ($hoaDon->phuong_thuc_thanh_toan ?? 1);

            $tong_tien_mon = ChiTietHoaDon::where('id_hoa_don', $hoaDon->id)
                ->select(DB::raw('SUM(so_luong * don_gia) as tong'))
                ->value('tong') ?? 0;
            $so_tien_giam = $hoaDon->so_tien_giam ?? 0;
            $tong_tien = max(0, $tong_tien_mon - $so_tien_giam);

            $hoaDon->update([
                'tien_truoc_giam'        => $tong_tien_mon,
                'tong_tien'              => $tong_tien,
                'tinh_trang'             => 1,
                'phuong_thuc_thanh_toan' => $phuongThuc
            ]);

            // Pay-First: Chuyển các món chưa duyệt (0) sang quầy Barista pha chế (1), không ép sang đã xong (2)
            ChiTietHoaDon::where('id_hoa_don', $hoaDon->id)
                ->where('tinh_trang', 0)
                ->update(['tinh_trang' => 1]);

            // Gửi email hóa đơn cho khách hàng nếu có email
            $khachHang = KhachHang::find($hoaDon->id_khach_hang);
            if ($khachHang && $khachHang->email) {
                $chiTiet = ChiTietHoaDon::where('id_hoa_don', $hoaDon->id)
                    ->join('san_phams', 'chi_tiet_hoa_dons.id_san_pham', '=', 'san_phams.id')
                    ->select('chi_tiet_hoa_dons.so_luong', 'chi_tiet_hoa_dons.don_gia', 'san_phams.ten_san_pham')
                    ->get()
                    ->toArray();

                try {
                    dispatch(new sendMailJob(
                        $khachHang->email,
                        'Xác nhận thanh toán thành công - ' . $hoaDon->ma_hoa_don,
                        'view_mail_hoa_don_thanh_toan',
                        [
                            'ho_va_ten'             => $khachHang->ho_va_ten,
                            'ma_hoa_don'            => $hoaDon->ma_hoa_don,
                            'tong_tien'             => $hoaDon->tong_tien,
                            'so_tien_giam'          => $hoaDon->so_tien_giam,
                            'phuong_thuc_thanh_toan' => $phuongThuc,
                            'ngay_thanh_toan'       => now()->format('d/m/Y H:i'),
                            'chi_tiet'              => $chiTiet,
                        ]
                    ));
                } catch (\Exception $e) {
                    Log::error('Gửi mail xác nhận thanh toán lỗi: ' . $e->getMessage());
                }
            }

            return response()->json([
                'status' => 1,
                'message' => 'Xác nhận thanh toán thành công!'
            ]);
        }

        return response()->json([
            'status' => 0,
            'message' => 'Hóa đơn không tồn tại hoặc đã được thanh toán!'
        ]);
    }
}
