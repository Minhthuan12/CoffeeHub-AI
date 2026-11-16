<?php

namespace App\Http\Controllers;

use App\Models\ChiTietPhanQuyen;
use Illuminate\Http\Request;
class ChiTietPhanQuyenController extends Controller
{
    public function capQuyen(Request $request)
    {
        $quyen = ChiTietPhanQuyen::where('id_quyen', $request->id_quyen)
            ->where('id_chuc_nang', $request->id_chuc_nang)
            ->first();

        if ($quyen) {
            return response()->json([
                'status' => 0,
                'message' => 'Quyền đã tồn tại',
            ]);
        }
        ChiTietPhanQuyen::create([
            "id_quyen" => $request->id_quyen,
            "id_chuc_nang" => $request->id_chuc_nang
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'Phân quyền thành công',
        ]);
    }

    public function getQuyenDaCap(Request $request)
    {
        $data = ChiTietPhanQuyen::join('chuc_nangs', 'chi_tiet_phan_quyens.id_chuc_nang', 'chuc_nangs.id')
            ->join('chuc_vus', 'chi_tiet_phan_quyens.id_quyen', 'chuc_vus.id')
            ->where("chi_tiet_phan_quyens.id_quyen", $request->id_quyen)
            ->select('chi_tiet_phan_quyens.*', 'chuc_nangs.ten_chuc_nang', 'chuc_vus.ten_chuc_vu as ten_quyen')
            ->get();
        return response()->json([
            'data' => $data,
        ]);
    }
}
