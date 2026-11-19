<?php

namespace App\Http\Controllers;

use App\Models\ChiTietPhieuNhap;
use App\Models\PhieuNhapKho;
use App\Http\Requests\ThemMoiChiTietPhieuNhapRequest;
use Illuminate\Http\Request;

class ChiTietPhieuNhapController extends Controller
{
    public function getData()
    {
        $data = ChiTietPhieuNhap::select(
            'chi_tiet_phieu_nhaps.id',
            'chi_tiet_phieu_nhaps.so_luong',
            'chi_tiet_phieu_nhaps.don_gia',
            'chi_tiet_phieu_nhaps.thanh_tien',
            'chi_tiet_phieu_nhaps.id_nguyen_lieu',
            'chi_tiet_phieu_nhaps.id_phieu_nhap'
        )->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function getTheoPhieuNhap($id)
    {
        $phieu_nhap = PhieuNhapKho::leftJoin('nhan_viens', 'phieu_nhap_khos.id_nhan_vien', '=', 'nhan_viens.id')
            ->select(
                'phieu_nhap_khos.*',
                'nhan_viens.ho_va_ten'
            )
            ->where('phieu_nhap_khos.id', $id)
            ->first();

        if (!$phieu_nhap) {
            return response()->json([
                'status' => 0,
                'message' => 'Phiếu nhập không tồn tại!'
            ]);
        }

        $chi_tiet = ChiTietPhieuNhap::join('nguyen_lieus', 'chi_tiet_phieu_nhaps.id_nguyen_lieu', '=', 'nguyen_lieus.id')
            ->select(
                'chi_tiet_phieu_nhaps.*',
                'nguyen_lieus.ten_nguyen_lieu'
            )
            ->where('chi_tiet_phieu_nhaps.id_phieu_nhap', $id)
            ->get();

        return response()->json([
            'status'     => 1,
            'phieu_nhap' => $phieu_nhap,
            'chi_tiet'   => $chi_tiet
        ]);
    }

    public function store(ThemMoiChiTietPhieuNhapRequest $request)
    {
        $phieu = PhieuNhapKho::find($request->id_phieu_nhap);
        if ($phieu && $phieu->tinh_trang == 1) {
            return response()->json([
                'status' => 0,
                'message' => 'Phiếu nhập kho này đã được chốt, không thể thêm chi tiết!'
            ]);
        }

        $thanh_tien = $request->thanh_tien ?? ($request->so_luong * $request->don_gia);
        ChiTietPhieuNhap::create([
            'so_luong' => $request->so_luong,
            'don_gia' => $request->don_gia,
            'thanh_tien' => $thanh_tien,
            'id_nguyen_lieu' => $request->id_nguyen_lieu,
            'id_phieu_nhap' => $request->id_phieu_nhap,
        ]);

        $tong_tien = ChiTietPhieuNhap::where('id_phieu_nhap', $request->id_phieu_nhap)->sum('thanh_tien');
        PhieuNhapKho::where('id', $request->id_phieu_nhap)->update(['tong_tien' => $tong_tien]);

        return response()->json([
            'message' => 'Đã tạo mới chi tiết phiếu nhập thành công!',
            'status' => 1
        ]);
    }

    public function destroy(Request $request)
    {
        $item = ChiTietPhieuNhap::find($request->id);
        if ($item) {
            $phieu = PhieuNhapKho::find($item->id_phieu_nhap);
            if ($phieu && $phieu->tinh_trang == 1) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Phiếu nhập kho này đã được chốt, không thể xóa chi tiết!'
                ]);
            }

            $id_phieu_nhap = $item->id_phieu_nhap;
            $item->delete();

            $tong_tien = ChiTietPhieuNhap::where('id_phieu_nhap', $id_phieu_nhap)->sum('thanh_tien');
            PhieuNhapKho::where('id', $id_phieu_nhap)->update(['tong_tien' => $tong_tien]);

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
}
