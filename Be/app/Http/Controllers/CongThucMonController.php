<?php

namespace App\Http\Controllers;

use App\Models\CongThucMon;
use App\Http\Requests\ThemMoiCongThucMonRequest;
use App\Http\Requests\UpdateCongThucMonRequest;
use Illuminate\Http\Request;

class CongThucMonController extends Controller
{
    public function getData()
    {
        $data = CongThucMon::join('san_phams', 'cong_thuc_mons.id_san_pham', '=', 'san_phams.id')
            ->join('nguyen_lieus', 'cong_thuc_mons.id_nguyen_lieu', '=', 'nguyen_lieus.id')
            ->select(
                'cong_thuc_mons.id',
                'cong_thuc_mons.so_luong',
                'cong_thuc_mons.don_vi',
                'cong_thuc_mons.id_san_pham',
                'cong_thuc_mons.id_nguyen_lieu',
                'cong_thuc_mons.deleted_at',
                'san_phams.ten_san_pham',
                'nguyen_lieus.ten_nguyen_lieu'
            )->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function store(ThemMoiCongThucMonRequest $request)
    {
        CongThucMon::create([
            'so_luong' => $request->so_luong,
            'don_vi' => $request->don_vi,
            'id_san_pham' => $request->id_san_pham,
            'id_nguyen_lieu' => $request->id_nguyen_lieu,
        ]);

        return response()->json([
            'message' => 'Đã tạo mới công thức món thành công!',
            'status' => 1
        ]);
    }

    public function destroy(Request $request)
    {
        $item = CongThucMon::find($request->id);
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

    public function update(UpdateCongThucMonRequest $request)
    {
        CongThucMon::where('id', $request->id)->update([
            'so_luong' => $request->so_luong,
            'don_vi' => $request->don_vi,
            'id_san_pham' => $request->id_san_pham,
            'id_nguyen_lieu' => $request->id_nguyen_lieu,
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'Cập nhật công thức món thành công!'
        ]);
    }
}
