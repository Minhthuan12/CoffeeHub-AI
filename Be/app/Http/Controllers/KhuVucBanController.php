<?php

namespace App\Http\Controllers;

use App\Models\KhuVucBan;
use App\Http\Requests\ThemMoiKhuVucBanRequest;
use App\Http\Requests\UpdateKhuVucBanRequest;
use Illuminate\Http\Request;

class KhuVucBanController extends Controller
{
    public function getData()
    {
        $data = KhuVucBan::select(
            'khu_vuc_bans.id',
            'khu_vuc_bans.ten_khu_vuc',
            'khu_vuc_bans.mo_ta'
        )->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function store(ThemMoiKhuVucBanRequest $request)
    {
        KhuVucBan::create([
            'ten_khu_vuc' => $request->ten_khu_vuc,
            'mo_ta' => $request->mo_ta,
        ]);

        return response()->json([
            'message' => 'Đã tạo mới khu vực bàn thành công!',
            'status' => 1
        ]);
    }

    public function destroy(Request $request)
    {
        $khuVuc = KhuVucBan::find($request->id);
        if ($khuVuc) {
            $khuVuc->delete();
            return response()->json([
                'status' => 1,
                'message' => 'Xóa khu vực bàn thành công!'
            ]);
        }
        return response()->json([
            'status' => 0,
            'message' => 'Không tìm thấy khu vực bàn!'
        ]);
    }

    public function update(UpdateKhuVucBanRequest $request)
    {
        KhuVucBan::where('id', $request->id)->update([
            'ten_khu_vuc' => $request->ten_khu_vuc,
            'mo_ta' => $request->mo_ta,
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'Cập nhật khu vực bàn thành công!'
        ]);
    }
}
