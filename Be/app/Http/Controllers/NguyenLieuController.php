<?php

namespace App\Http\Controllers;

use App\Models\NguyenLieu;
use App\Http\Requests\ThemMoiNguyenLieuRequest;
use App\Http\Requests\UpdateNguyenLieuRequest;
use Illuminate\Http\Request;

class NguyenLieuController extends Controller
{
    public function getData()
    {
        $data = NguyenLieu::select(
            'nguyen_lieus.id',
            'nguyen_lieus.ten_nguyen_lieu',
            'nguyen_lieus.don_vi_tinh',
            'nguyen_lieus.so_luong_ton',
            'nguyen_lieus.muc_canh_bao',
            'nguyen_lieus.tinh_trang',
            'nguyen_lieus.id_nha_cung_cap'
        )->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function getDataActive()
    {
        $data = NguyenLieu::select(
            'nguyen_lieus.id',
            'nguyen_lieus.ten_nguyen_lieu',
            'nguyen_lieus.don_vi_tinh',
            'nguyen_lieus.so_luong_ton',
            'nguyen_lieus.muc_canh_bao',
            'nguyen_lieus.tinh_trang',
            'nguyen_lieus.id_nha_cung_cap'
        )->where('tinh_trang', 1)->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function store(ThemMoiNguyenLieuRequest $request)
    {
        NguyenLieu::create([
            'ten_nguyen_lieu' => $request->ten_nguyen_lieu,
            'don_vi_tinh' => $request->don_vi_tinh,
            'so_luong_ton' => $request->so_luong_ton,
            'muc_canh_bao' => $request->muc_canh_bao,
            'gia_nhap' => $request->gia_nhap,
            'tinh_trang' => $request->tinh_trang,
            'id_nha_cung_cap' => $request->id_nha_cung_cap,
        ]);

        return response()->json([
            'message' => 'Đã tạo mới nguyên liệu thành công!',
            'status' => 1
        ]);
    }

    public function destroy(Request $request)
    {
        $item = NguyenLieu::find($request->id);
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

    public function update(UpdateNguyenLieuRequest $request)
    {
        NguyenLieu::where('id', $request->id)->update([
            'ten_nguyen_lieu' => $request->ten_nguyen_lieu,
            'don_vi_tinh' => $request->don_vi_tinh,
            'so_luong_ton' => $request->so_luong_ton,
            'muc_canh_bao' => $request->muc_canh_bao,
            'gia_nhap' => $request->gia_nhap,
            'tinh_trang' => $request->tinh_trang,
            'id_nha_cung_cap' => $request->id_nha_cung_cap,
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'Cập nhật nguyên liệu thành công!'
        ]);
    }

    public function changeStatus(Request $request)
    {
        $nguyenLieu = NguyenLieu::find($request->id);
        if ($nguyenLieu) {
            $nguyenLieu->tinh_trang = $nguyenLieu->tinh_trang == 1 ? 0 : 1;
            $nguyenLieu->save();
            return response()->json([
                'status' => 1,
                'message' => 'Đã thay đổi trạng thái nguyên liệu thành công!'
            ]);
        }
        return response()->json([
            'status' => 0,
            'message' => 'Không tìm thấy dữ liệu!'
        ]);
    }
}
