<?php

namespace App\Http\Controllers;

use App\Models\ChucVu;
use App\Http\Requests\ThemMoiChucVuRequest;
use App\Http\Requests\UpdateChucVuRequest;
use Illuminate\Http\Request;
class ChucVuController extends Controller
{
    public function getData()
    {
        
        $data = ChucVu::query()
            ->select(
                'chuc_vus.id',
                'chuc_vus.ten_chuc_vu',
                'chuc_vus.slug_chuc_vu',
                'chuc_vus.tinh_trang'
            )
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function getDataActive()
    {
        
        $data = ChucVu::select(
            'chuc_vus.id',
            'chuc_vus.ten_chuc_vu',
            'chuc_vus.slug_chuc_vu',
            'chuc_vus.tinh_trang'
        )
            ->where('tinh_trang', 1)
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function store(ThemMoiChucVuRequest $request)
    {
        
        ChucVu::create([
            'ten_chuc_vu' => $request->ten_chuc_vu,
            'slug_chuc_vu' => $request->slug_chuc_vu,
            'tinh_trang' => $request->tinh_trang,
        ]);

        return response()->json([
            'message' => 'Đã tạo mới chức vụ thành công!',
            'status' => 1
        ]);
    }

    public function destroy(Request $request)
    {
        
        $item = ChucVu::find($request->id);
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

    public function update(UpdateChucVuRequest $request)
    {
        
        ChucVu::where('id', $request->id)->update([
            'ten_chuc_vu' => $request->ten_chuc_vu,
            'slug_chuc_vu' => $request->slug_chuc_vu,
            'tinh_trang' => $request->tinh_trang,
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'Cập nhật chức vụ thành công!'
        ]);
    }

    public function changeStatus(Request $request)
    {
        
        $chucVu = ChucVu::find($request->id);
        if ($chucVu) {
            $chucVu->tinh_trang = $chucVu->tinh_trang == 1 ? 0 : 1;
            $chucVu->save();
            return response()->json([
                'status' => 1,
                'message' => 'Đã thay đổi trạng thái chức vụ thành công!'
            ]);
        }
        return response()->json([
            'status' => 0,
            'message' => 'Không tìm thấy dữ liệu!'
        ]);
    }

}
