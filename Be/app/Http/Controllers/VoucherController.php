<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Http\Requests\ThemMoiVoucherRequest;
use App\Http\Requests\UpdateVoucherRequest;
use App\Models\ChiTietHoaDon;
use App\Models\HoaDon;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function getData()
    {
        $data = Voucher::select(
            'vouchers.id',
            'vouchers.ma_voucher',
            'vouchers.thoi_gian_bat_dau',
            'vouchers.thoi_gian_ket_thuc',
            'vouchers.loai_giam',
            'vouchers.so_giam_gia',
            'vouchers.so_tien_toi_da',
            'vouchers.don_hang_toi_thieu'
        )->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function store(ThemMoiVoucherRequest $request)
    {
        Voucher::create([
            'ma_voucher' => $request->ma_voucher,
            'thoi_gian_bat_dau' => $request->thoi_gian_bat_dau,
            'thoi_gian_ket_thuc' => $request->thoi_gian_ket_thuc,
            'loai_giam' => $request->loai_giam,
            'so_giam_gia' => $request->so_giam_gia,
            'so_tien_toi_da' => $request->so_tien_toi_da,
            'don_hang_toi_thieu' => $request->don_hang_toi_thieu,
        ]);

        return response()->json([
            'message' => 'Đã tạo mới voucher thành công!',
            'status' => 1
        ]);
    }

    public function destroy(Request $request)
    {
        $item = Voucher::find($request->id);
        if ($item) {
            $item->delete();
            return response()->json([
                'status' => 1,
                'message' => 'Xóa thành công!'
            ]);
        }
        return response()->json([
            'status' => 0,
            'message' => 'Không tìm thấy dữ liệu!'
        ]);
    }

    public function update(UpdateVoucherRequest $request)
    {
        Voucher::where('id', $request->id)->update([
            'ma_voucher' => $request->ma_voucher,
            'thoi_gian_bat_dau' => $request->thoi_gian_bat_dau,
            'thoi_gian_ket_thuc' => $request->thoi_gian_ket_thuc,
            'loai_giam' => $request->loai_giam,
            'so_giam_gia' => $request->so_giam_gia,
            'so_tien_toi_da' => $request->so_tien_toi_da,
            'don_hang_toi_thieu' => $request->don_hang_toi_thieu,
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'Cập nhật voucher thành công!'
        ]);
    }
    public function apDungVoucher(Request $request)
    {
        $ma_voucher = $request->ma_voucher;

        // Tìm hóa đơn đang mở của bàn
        $hoaDon = HoaDon::where('id_ban', $request->id_ban)
            ->where('tinh_trang', 0)
            ->first();

        if (!$hoaDon) {
            return response()->json(['status' => 0, 'message' => 'Bàn này chưa có hóa đơn nào đang mở!']);
        }
        $tong_tien =$hoaDon->tien_truoc_giam;

        if ($tong_tien <= 0) {
            return response()->json(['status' => 0, 'message' => 'Hóa đơn chưa có món nào!']);
        }
        $voucher = Voucher::where('ma_voucher', $ma_voucher)->first();

        if (!$voucher) {
            return response()->json(['status' => 0, 'message' => 'Mã voucher không tồn tại!']);
        }

        $now = now();
        if (!($now >= $voucher->thoi_gian_bat_dau && $now <= $voucher->thoi_gian_ket_thuc)) {
            return response()->json(['status' => 0, 'message' => 'Voucher đã hết hạn hoặc chưa đến thời gian sử dụng!']);
        }

        if ($voucher->don_hang_toi_thieu && $tong_tien < $voucher->don_hang_toi_thieu) {
            return response()->json([
                'status'  => 0,
                'message' => 'Đơn hàng tối thiểu ' . number_format($voucher->don_hang_toi_thieu) . 'đ để áp dụng voucher này!'
            ]);
        }

        if ($voucher->loai_giam == 1) {
            $so_tien_giam =$tong_tien * $voucher->so_giam_gia / 100;
            if ($voucher->so_tien_toi_da && $so_tien_giam > $voucher->so_tien_toi_da) {
                $so_tien_giam = $voucher->so_tien_toi_da;
            }
        } else {
            $so_tien_giam =$voucher->so_giam_gia;
        }
        $so_tien_giam = min($so_tien_giam, $tong_tien);

        $hoaDon->update([
            'id_voucher'   => $voucher->id,
            'so_tien_giam' => $so_tien_giam,
            'tong_tien'    => $tong_tien - $so_tien_giam,
        ]);

        return response()->json([
            'status'       => 1,
            'loai_giam'    => $voucher->loai_giam,
            'so_giam_gia'  => $voucher->so_giam_gia,
            'so_tien_giam' => $so_tien_giam,
            'tien_truoc_giam' => $tong_tien,
            'message'      => 'Áp dụng voucher thành công! Giảm ' . number_format($so_tien_giam) . 'đ',
        ]);
    }

    public function huyVoucher(Request $request)
    {
        $id_ban =$request->id_ban;

        $hoaDon = HoaDon::where('id_ban', $id_ban)
            ->where('tinh_trang', 0)
            ->first();

        if (!$hoaDon) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy hóa đơn!']);
        }

        $hoaDon->update([
            'id_voucher'   => null,
            'so_tien_giam' => 0,
            'tong_tien'    => $hoaDon->tien_truoc_giam,
        ]);

        return response()->json(['status' => 1, 'message' => 'Đã huỷ voucher!']);
    }
}
