<?php

namespace App\Http\Controllers;

use App\Models\Ban;
use App\Http\Requests\ThemMoiBanRequest;
use App\Http\Requests\UpdateBanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BanController extends Controller
{
    public function getData()
    {
        $data = Ban::query()
            ->join('khu_vuc_bans', 'bans.id_khu_vuc_ban', '=', 'khu_vuc_bans.id')
            ->select(
                'bans.id',
                'bans.ten_ban',
                'bans.tinh_trang',
                'bans.id_khu_vuc_ban',
                'khu_vuc_bans.ten_khu_vuc'
            )->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function store(ThemMoiBanRequest $request)
    {
        Ban::create([
            'ten_ban' => $request->ten_ban,
            'tinh_trang' => $request->tinh_trang,
            'id_khu_vuc_ban' => $request->id_khu_vuc_ban,
        ]);

        return response()->json([
            'message' => 'Đã tạo mới bàn thành công!',
            'status' => 1
        ]);
    }

    public function destroy(Request $request)
    {
        $item = Ban::find($request->id);
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

    public function update(UpdateBanRequest $request)
    {
        Ban::where('id', $request->id)->update([
            'ten_ban' => $request->ten_ban,
            'tinh_trang' => $request->tinh_trang,
            'id_khu_vuc_ban' => $request->id_khu_vuc_ban,
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'Cập nhật bàn thành công!'
        ]);
    }

    public function changeStatus(Request $request)
    {
        $ban = Ban::find($request->id);
        if ($ban) {
            $ban->tinh_trang = $ban->tinh_trang == 1 ? 0 : 1;
            $ban->save();
            return response()->json([
                'status' => 1,
                'message' => 'Đã thay đổi trạng thái bàn thành công!'
            ]);
        }
        return response()->json([
            'status' => 0,
            'message' => 'Không tìm thấy dữ liệu!'
        ]);
    }

    public function getBanTrangThai()
    {
        $data = Ban::leftJoin("hoa_dons", function ($join) {
            $join->on("bans.id", "hoa_dons.id_ban")
                ->where("hoa_dons.tinh_trang", 0);
        })
            ->select(
                "bans.id",
                "bans.ten_ban",
                DB::raw("IF(hoa_dons.id is NULL, 0, hoa_dons.id) as tinh_trang_ban")
            )
            ->groupBy("bans.id", "bans.ten_ban", "tinh_trang_ban")
            ->orderBy("bans.id")
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
