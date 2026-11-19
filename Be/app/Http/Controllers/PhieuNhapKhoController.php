<?php

namespace App\Http\Controllers;

use App\Models\PhieuNhapKho;
use App\Models\ChiTietPhieuNhap;
use App\Models\NguyenLieu;
use App\Http\Requests\ThemMoiPhieuNhapKhoRequest;
use App\Http\Requests\UpdatePhieuNhapKhoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PhieuNhapKhoController extends Controller
{
    public function getData()
    {
        $data = PhieuNhapKho::Join('nhan_viens', 'phieu_nhap_khos.id_nhan_vien',  'nhan_viens.id')
            ->select(
                'phieu_nhap_khos.*',
                'nhan_viens.ho_va_ten'
            )
            ->orderBy('phieu_nhap_khos.id', 'desc')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function store(ThemMoiPhieuNhapKhoRequest $request)
    {
        $user = Auth::guard('sanctum')->user();
        $id_nhan_vien = $user->id;

        PhieuNhapKho::create([
            'tong_tien' => 0,
            'ghi_chu' => $request->ghi_chu,
            'id_nhan_vien' => $id_nhan_vien,
            'tinh_trang' => 0,
        ]);

        return response()->json([
            'message' => 'Đã tạo mới phiếu nhập kho thành công!',
            'status' => 1
        ]);
    }

    public function chotPhieu(Request $request)
    {
        $phieuNhap = PhieuNhapKho::find($request->id);
        if (!$phieuNhap) {
            return response()->json([
                'status' => 0,
                'message' => 'Phiếu nhập kho không tồn tại!'
            ]);
        }
        DB::table('nguyen_lieus')
            ->join('chi_tiet_phieu_nhaps', 'nguyen_lieus.id', '=', 'chi_tiet_phieu_nhaps.id_nguyen_lieu')
            ->where('chi_tiet_phieu_nhaps.id_phieu_nhap', $phieuNhap->id)
            ->update([
                'nguyen_lieus.so_luong_ton' => DB::raw('nguyen_lieus.so_luong_ton + chi_tiet_phieu_nhaps.so_luong')
            ]);

        $phieuNhap->tinh_trang = 1;
        $phieuNhap->save();

        return response()->json([
            'status' => 1,
            'message' => 'Đã chốt phiếu nhập kho thành công! Số lượng nguyên liệu đã được cộng vào kho.'
        ]);
    }

    public function huyChotPhieu(Request $request)
    {
        $phieuNhap = PhieuNhapKho::find($request->id);

        if ($phieuNhap->tinh_trang == 0) {
            return response()->json([
                'status' => 0,
                'message' => 'Phiếu nhập kho đang là phiếu nháp, chưa chốt!'
            ]);
        }

        // Trừ lại số lượng tồn kho (Hủy chốt)
        DB::table('nguyen_lieus')
            ->join('chi_tiet_phieu_nhaps', 'nguyen_lieus.id', '=', 'chi_tiet_phieu_nhaps.id_nguyen_lieu')
            ->where('chi_tiet_phieu_nhaps.id_phieu_nhap', $phieuNhap->id)
            ->update([
                'nguyen_lieus.so_luong_ton' => DB::raw('nguyen_lieus.so_luong_ton - chi_tiet_phieu_nhaps.so_luong')
            ]);

        $phieuNhap->tinh_trang = 0; // Chuyển lại về Phiếu Nháp
        $phieuNhap->save();

        return response()->json([
            'status' => 1,
            'message' => 'Đã hủy chốt phiếu nhập kho! Đã trừ lại số lượng tồn kho.'
        ]);
    }

    public function destroy(Request $request)
    {
        $item = PhieuNhapKho::find($request->id);
        if ($item) {
            if ($item->tinh_trang == 1) {
                // Nếu phiếu đã chốt, tự động trừ lại tồn kho trước khi xóa
                DB::table('nguyen_lieus')
                    ->join('chi_tiet_phieu_nhaps', 'nguyen_lieus.id', '=', 'chi_tiet_phieu_nhaps.id_nguyen_lieu')
                    ->where('chi_tiet_phieu_nhaps.id_phieu_nhap', $item->id)
                    ->update([
                        'nguyen_lieus.so_luong_ton' => DB::raw('nguyen_lieus.so_luong_ton - chi_tiet_phieu_nhaps.so_luong')
                    ]);
            }
            // Xóa các chi tiết thuộc phiếu nhập này
            ChiTietPhieuNhap::where('id_phieu_nhap', $item->id)->delete();
            $item->delete();
            return response()->json([
                'status' => 1,
                'message' => 'Xóa phiếu nhập kho thành công!'
            ]);
        }
        return response()->json([
            'status' => 0,
            'message' => 'Không tìm thấy dữ liệu!'
        ]);
    }

    public function update(UpdatePhieuNhapKhoRequest $request)
    {
        $phieuNhap = PhieuNhapKho::find($request->id);
        if ($phieuNhap && $phieuNhap->tinh_trang == 1) {
            return response()->json([
                'status' => 0,
                'message' => 'Không thể chỉnh sửa phiếu nhập kho đã được chốt!'
            ]);
        }

        PhieuNhapKho::where('id', $request->id)->update([
            'ghi_chu' => $request->ghi_chu,
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'Cập nhật phiếu nhập kho thành công!'
        ]);
    }
}
