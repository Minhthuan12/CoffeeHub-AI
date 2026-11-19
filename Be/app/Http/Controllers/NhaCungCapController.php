<?php

namespace App\Http\Controllers;

use App\Models\NhaCungCap;
use App\Http\Requests\ThemMoiNhaCungCapRequest;
use App\Http\Requests\UpdateNhaCungCapRequest;
use Illuminate\Http\Request;

class NhaCungCapController extends Controller
{
    public function getData()
    {
        $data = NhaCungCap::select(
            'nha_cung_caps.id',
            'nha_cung_caps.ten_nha_cung_cap',
            'nha_cung_caps.email',
            'nha_cung_caps.nguoi_lien_he',
            'nha_cung_caps.so_dien_thoai',
            'nha_cung_caps.dia_chi',
            'nha_cung_caps.tinh_trang'
        )->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function getDataActive()
    {
        $data = NhaCungCap::select(
            'nha_cung_caps.id',
            'nha_cung_caps.ten_nha_cung_cap',
            'nha_cung_caps.email',
            'nha_cung_caps.nguoi_lien_he',
            'nha_cung_caps.so_dien_thoai',
            'nha_cung_caps.dia_chi',
            'nha_cung_caps.tinh_trang'
        )->where('tinh_trang', 1)->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function store(ThemMoiNhaCungCapRequest $request)
    {
        NhaCungCap::create([
            
            'ten_nha_cung_cap' => $request->ten_nha_cung_cap,
            'email' => $request->email,
            'nguoi_lien_he' => $request->nguoi_lien_he,
            'so_dien_thoai' => $request->so_dien_thoai,
            'dia_chi' => $request->dia_chi,
            'tinh_trang' => $request->tinh_trang,
        ]);

        return response()->json([
            'message' => 'Đã tạo mới nhà cung cấp thành công!',
            'status' => 1
        ]);
    }

    public function destroy(Request $request)
    {
        $item = NhaCungCap::find($request->id);
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

    public function update(UpdateNhaCungCapRequest $request)
    {
        NhaCungCap::where('id', $request->id)->update([
            'ten_nha_cung_cap' => $request->ten_nha_cung_cap,
            'email' => $request->email,
            'nguoi_lien_he' => $request->nguoi_lien_he,
            'so_dien_thoai' => $request->so_dien_thoai,
            'dia_chi' => $request->dia_chi,
            'tinh_trang' => $request->tinh_trang,
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'Cập nhật nhà cung cấp thành công!'
        ]);
    }

    public function changeStatus(Request $request)
    {
        $nhaCungCap = NhaCungCap::find($request->id);
        if ($nhaCungCap) {
            $nhaCungCap->tinh_trang = $nhaCungCap->tinh_trang == 1 ? 0 : 1;
            $nhaCungCap->save();
            return response()->json([
                'status' => 1,
                'message' => 'Đã thay đổi trạng thái nhà cung cấp thành công!'
            ]);
        }
        return response()->json([
            'status' => 0,
            'message' => 'Không tìm thấy dữ liệu!'
        ]);
    }
}
