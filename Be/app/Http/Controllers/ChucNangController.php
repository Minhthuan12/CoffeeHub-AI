<?php

namespace App\Http\Controllers;

use App\Models\ChucNang;
use App\Http\Requests\ThemMoiChucNangRequest;
use App\Http\Requests\UpdateChucNangRequest;
use Illuminate\Http\Request;

class ChucNangController extends Controller
{
    public function getData()
    {

        $data = ChucNang::select(
            'chuc_nangs.id',
            'chuc_nangs.ten_chuc_nang'
        )->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function store(ThemMoiChucNangRequest $request)
    {

        ChucNang::create([
            'ten_chuc_nang' => $request->ten_chuc_nang,
        ]);

        return response()->json([
            'message' => 'Đã tạo mới chức năng thành công!',
            'status' => 1
        ]);
    }

    public function destroy(Request $request)
    {

        $item = ChucNang::find($request->id);
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

    public function update(UpdateChucNangRequest $request)
    {

        ChucNang::where('id', $request->id)->update([
            'ten_chuc_nang' => $request->ten_chuc_nang,
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'Cập nhật chức năng thành công!'
        ]);
    }
}
