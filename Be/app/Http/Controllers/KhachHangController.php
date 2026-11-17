<?php

namespace App\Http\Controllers;

use App\Models\KhachHang;
use App\Http\Requests\ThemMoiKhachHangRequest;
use App\Http\Requests\UpdateKhachHangRequest;
use App\Models\HoaDon;
use Illuminate\Http\Request;

class KhachHangController extends Controller
{

    public function getData()
    {
        $data = KhachHang::select(
            'khach_hangs.id',
            'khach_hangs.email',
            'khach_hangs.ho_va_ten',
            'khach_hangs.so_dien_thoai',
            'khach_hangs.tinh_trang',
            'khach_hangs.deleted_at'
        )->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function store(ThemMoiKhachHangRequest $request)
    {
        $kh = KhachHang::create([
            'email' => $request->email,
            'ho_va_ten' => $request->ho_va_ten,
            'so_dien_thoai' => $request->so_dien_thoai,
            'tinh_trang' => $request->tinh_trang ?? 1,
        ]);

        if ($request->id_hoa_don) {
            $hd = HoaDon::find($request->id_hoa_don);
            if ($hd) {
                $hd->id_khach_hang = $kh->id;
                $hd->save();
            }
        }

        return response()->json([
            'message' => 'Đã tạo mới khách hàng thành công!',
            'status' => 1
        ]);
    }

    public function destroy(Request $request)
    {
        $item = KhachHang::find($request->id);
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

    public function update(UpdateKhachHangRequest $request)
    {
        KhachHang::where('id', $request->id)->update([
            'email' => $request->email,
            'ho_va_ten' => $request->ho_va_ten,
            'so_dien_thoai' => $request->so_dien_thoai,
            'tinh_trang' => $request->tinh_trang,
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'Cập nhật khách hàng thành công!'
        ]);
    }

    public function changeStatus(Request $request)
    {
        $khachHang = KhachHang::find($request->id);
        if ($khachHang) {
            $khachHang->tinh_trang = $khachHang->tinh_trang == 1 ? 0 : 1;
            $khachHang->save();
            return response()->json([
                'status' => 1,
                'message' => 'Đã thay đổi trạng thái khách hàng thành công!'
            ]);
        }
        return response()->json([
            'status' => 0,
            'message' => 'Không tìm thấy dữ liệu!'
        ]);
    }
    public function timKhachHang(Request $request)
    {
        $kh = KhachHang::where('so_dien_thoai', $request->so_dien_thoai)->first();
        $hd = HoaDon::where('id', $request->id_hoa_don)->first();
        if ($kh && $hd) {
            $hd->id_khach_hang = $kh->id;
            $hd->save();
            return response()->json([
                'status' => 1,
                'message' => 'Tìm thấy & đã gán khách hàng: ' . $kh->ho_va_ten
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Không tìm thấy khách hàng này!'
            ]);
        }
    }
}
