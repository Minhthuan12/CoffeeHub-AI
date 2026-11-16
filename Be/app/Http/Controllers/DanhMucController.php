<?php

namespace App\Http\Controllers;

use App\Models\DanhMuc;
use App\Http\Requests\ThemMoiDanhMucRequest;
use App\Http\Requests\UpdateDanhMucRequest;
use Illuminate\Http\Request;

class DanhMucController extends Controller
{
    public function getData()
    {
        $data = DanhMuc::select(
            'danh_mucs.id',
            'danh_mucs.ten_danh_muc',
            'danh_mucs.slug_danh_muc',
            'danh_mucs.icon_danh_muc',
            'danh_mucs.tinh_trang',
            'danh_mucs.id_danh_muc_cha'
        )->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function store(ThemMoiDanhMucRequest $request)
    {
        DanhMuc::create([
            'ten_danh_muc' => $request->ten_danh_muc,
            'slug_danh_muc' => $request->slug_danh_muc,
            'icon_danh_muc' => $request->icon_danh_muc,
            'tinh_trang' => $request->tinh_trang,
            'id_danh_muc_cha' => $request->id_danh_muc_cha,
        ]);

        return response()->json([
            'message' => 'Đã tạo mới danh mục thành công!',
            'status' => 1
        ]);
    }

    public function destroy(Request $request)
    {
        $item = DanhMuc::find($request->id);
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

    public function update(UpdateDanhMucRequest $request)
    {
        DanhMuc::where('id', $request->id)->update([
            'ten_danh_muc' => $request->ten_danh_muc,
            'slug_danh_muc' => $request->slug_danh_muc,
            'icon_danh_muc' => $request->icon_danh_muc,
            'tinh_trang' => $request->tinh_trang,
            'id_danh_muc_cha' => $request->id_danh_muc_cha,
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'Cập nhật danh mục thành công!'
        ]);
    }

    public function changeStatus(Request $request)
    {
        $danhMuc = DanhMuc::find($request->id);
        if ($danhMuc) {
            $danhMuc->tinh_trang = $danhMuc->tinh_trang == 1 ? 0 : 1;
            $danhMuc->save();
            return response()->json([
                'status' => 1,
                'message' => 'Đã thay đổi trạng thái danh mục thành công!'
            ]);
        }
        return response()->json([
            'status' => 0,
            'message' => 'Không tìm thấy dữ liệu!'
        ]);
    }
}
