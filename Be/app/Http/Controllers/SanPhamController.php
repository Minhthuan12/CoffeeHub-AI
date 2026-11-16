<?php

namespace App\Http\Controllers;

use App\Models\SanPham;
use App\Http\Requests\ThemMoiSanPhamRequest;
use App\Http\Requests\UpdateSanPhamRequest;
use Illuminate\Http\Request;

class SanPhamController extends Controller
{
    public function getData()
    {
        $data = SanPham::select(
            'san_phams.id',
            'san_phams.ten_san_pham',
            'san_phams.slug_san_pham',
            'san_phams.ma_code',
            'san_phams.mo_ta',
            'san_phams.kich_thuoc',
            'san_phams.gia_ban',
            'san_phams.hinh_anh',
            'san_phams.tinh_trang',
            'san_phams.id_danh_muc'
        )->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function getDataActive()
    {
        $data = SanPham::where('tinh_trang', 1)
            ->select(
                'san_phams.id',
                'san_phams.ten_san_pham',
                'san_phams.slug_san_pham',
                'san_phams.ma_code',
                'san_phams.mo_ta',
                'san_phams.kich_thuoc',
                'san_phams.gia_ban',
                'san_phams.hinh_anh',
                'san_phams.tinh_trang',
                'san_phams.id_danh_muc'
            )->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function store(ThemMoiSanPhamRequest $request)
    {
        SanPham::create([
            'ten_san_pham' => $request->ten_san_pham,
            'slug_san_pham' => $request->slug_san_pham,
            'ma_code' => $request->ma_code,
            'mo_ta' => $request->mo_ta,
            'kich_thuoc' => $request->kich_thuoc,
            'gia_ban' => $request->gia_ban,
            'hinh_anh' => $request->hinh_anh,
            'tinh_trang' => $request->tinh_trang,
            'id_danh_muc' => $request->id_danh_muc,
        ]);

        return response()->json([
            'message' => 'Đã tạo mới sản phẩm thành công!',
            'status' => 1
        ]);
    }

    public function destroy(Request $request)
    {
        $item = SanPham::find($request->id);
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

    public function update(UpdateSanPhamRequest $request)
    {
        SanPham::where('id', $request->id)->update([
            'ten_san_pham' => $request->ten_san_pham,
            'slug_san_pham' => $request->slug_san_pham,
            'ma_code' => $request->ma_code,
            'mo_ta' => $request->mo_ta,
            'kich_thuoc' => $request->kich_thuoc,
            'gia_ban' => $request->gia_ban,
            'hinh_anh' => $request->hinh_anh,
            'tinh_trang' => $request->tinh_trang,
            'id_danh_muc' => $request->id_danh_muc,
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'Cập nhật sản phẩm thành công!'
        ]);
    }

    public function changeStatus(Request $request)
    {
        $sanPham = SanPham::find($request->id);
        if ($sanPham) {
            $sanPham->tinh_trang = $sanPham->tinh_trang == 1 ? 0 : 1;
            $sanPham->save();
            return response()->json([
                'status' => 1,
                'message' => 'Đã thay đổi trạng thái sản phẩm thành công!'
            ]);
        }
        return response()->json([
            'status' => 0,
            'message' => 'Không tìm thấy dữ liệu!'
        ]);
    }
}
