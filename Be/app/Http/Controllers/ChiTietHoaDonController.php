<?php

namespace App\Http\Controllers;

use App\Models\ChiTietHoaDon;
use App\Http\Requests\ThemMoiChiTietHoaDonRequest;
use App\Models\HoaDon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChiTietHoaDonController extends Controller
{
    public function getData()
    {
        $data = ChiTietHoaDon::select(
            'chi_tiet_hoa_dons.id',
            'chi_tiet_hoa_dons.id_chi_tiet_cha',
            'chi_tiet_hoa_dons.so_luong',
            'chi_tiet_hoa_dons.don_gia',
            'chi_tiet_hoa_dons.ghi_chu',
            'chi_tiet_hoa_dons.id_hoa_don',
            'chi_tiet_hoa_dons.id_san_pham'
        )->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function store(ThemMoiChiTietHoaDonRequest $request)
    {
        ChiTietHoaDon::create([
            'id_chi_tiet_cha' => $request->id_chi_tiet_cha,
            'so_luong' => $request->so_luong,
            'don_gia' => $request->don_gia,
            'ghi_chu' => $request->ghi_chu,
            'id_hoa_don' => $request->id_hoa_don,
            'id_san_pham' => $request->id_san_pham,
        ]);

        return response()->json([
            'message' => 'Đã tạo mới chi tiết hóa đơn thành công!',
            'status' => 1
        ]);
    }

    public function destroy(Request $request)
    {
        $item = ChiTietHoaDon::find($request->id);
        if ($item) {
            if ($item->tinh_trang != 0) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Chỉ được xóa món ăn ở trạng thái Chưa gửi!'
                ]);
            }

            $id_hoa_don = $item->id_hoa_don;
            $item->delete();
            $hoaDon = HoaDon::find($id_hoa_don);
            if ($hoaDon) {
                $tong_tien = ChiTietHoaDon::where('id_hoa_don', $hoaDon->id)
                    ->select(DB::raw('SUM(so_luong * don_gia) as tong'))
                    ->value('tong');

                $hoaDon->update([
                    'tien_truoc_giam' => $tong_tien,
                    'tong_tien'       => $tong_tien
                ]);
            }

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

    public function update(Request $request)
    {
        $item = ChiTietHoaDon::find($request->id);
        if ($item) {
            if ($item->tinh_trang != 0) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Chỉ được sửa món ăn ở trạng thái Chưa gửi!'
                ]);
            }

            $item->update([
                'so_luong' => $request->so_luong,
                'ghi_chu' => $request->ghi_chu,
            ]);
            $hoaDon = HoaDon::find($item->id_hoa_don);
            if ($hoaDon) {
                $tong_tien = ChiTietHoaDon::where('id_hoa_don', $hoaDon->id)
                    ->select(DB::raw('SUM(so_luong * don_gia) as tong'))
                    ->value('tong');

                $hoaDon->update([
                    'tien_truoc_giam' => $tong_tien,
                    'tong_tien'       => $tong_tien
                ]);
            }

            return response()->json([
                'status' => 1,
                'message' => 'Cập nhật thành công!'
            ]);
        }
        return response()->json([
            'status' => 0,
            'message' => 'Không tìm thấy dữ liệu!'
        ]);
    }


    public function getTheoHoaDon($id_hoa_don)
    {
        $hoa_don = HoaDon::join('bans', 'hoa_dons.id_ban', '=', 'bans.id')
            ->join('khach_hangs', 'hoa_dons.id_khach_hang', '=', 'khach_hangs.id')
            ->select(
                'hoa_dons.*',
                'bans.ten_ban',
                'khach_hangs.ho_va_ten'
            )
            ->where('hoa_dons.id', $id_hoa_don)
            ->first();

        $chi_tiet = ChiTietHoaDon::join('san_phams', 'chi_tiet_hoa_dons.id_san_pham', '=', 'san_phams.id')
            ->select(
                'chi_tiet_hoa_dons.*',
                'san_phams.ten_san_pham',
                'san_phams.kich_thuoc'
            )
            ->where('chi_tiet_hoa_dons.id_hoa_don', $id_hoa_don)
            ->get();

        return response()->json([
            'status'   => 1,
            'hoa_don'  => $hoa_don,
            'chi_tiet' => $chi_tiet
        ]);
    }

    public function getTheoBan($id)
    {
        // SELECT chi_tiet_hoa_dons.*, san_phams.ten_san_pham
        // FROM hoa_dons join chi_tiet_hoa_dons on hoa_dons.id = chi_tiet_hoa_dons.id_hoa_don join san_phams on chi_tiet_hoa_dons.id_san_pham = san_phams.id
        // WHERE hoa_dons.id = 4
        $data  = HoaDon::join('chi_tiet_hoa_dons', 'hoa_dons.id', 'chi_tiet_hoa_dons.id_hoa_don')
            ->join('san_phams', 'chi_tiet_hoa_dons.id_san_pham', 'san_phams.id')
            ->select(
                'chi_tiet_hoa_dons.*',
                'san_phams.ten_san_pham'
            )
            ->where('hoa_dons.id_ban', $id)
            ->where('hoa_dons.tinh_trang', 0)
            ->get();
        return response()->json([
            'data'  =>  $data
        ]);
    }

    public function inPhaChe($id)
    {
        $check = HoaDon::where('id_ban', $id)
            ->where('tinh_trang', 0)
            ->first();
        if ($check) {
            ChiTietHoaDon::where('id_hoa_don', $check->id)
                ->where('tinh_trang', 0)
                ->update(
                    ['tinh_trang' => 1]
                );
            return response()->json([
                'status'  =>  1,
                'message' =>  'Đã in phiếu chế biến thành công!'
            ]);
        }
        return response()->json([
            'status'  =>  0,
            'message' =>  'Không tìm thấy hóa đơn chưa thanh toán cho bàn này!'
        ]);
    }

    public function themMon(Request $request)
    {
        $check = HoaDon::where('id_ban', $request->id_hd)
            ->where('tinh_trang', 0)
            ->first();
        if (!$check) {
            return response()->json([
                'status' => 0,
                'message' => 'Bàn này chưa được mở, vui lòng mở bàn trước khi thêm món!'
            ]);
        }
        $find = ChiTietHoaDon::where('id_hoa_don', $check->id)
            ->where('id_san_pham', $request->id_san_pham)
            ->where('tinh_trang', 0)
            ->first();
        if ($find) {
            $find->so_luong = $find->so_luong + $request->so_luong;
            $find->save();
        } else {
            ChiTietHoaDon::create([
                'id_chi_tiet_cha'   => null,
                'so_luong'          => $request->so_luong,
                'don_gia'           => $request->don_gia,
                'ghi_chu'           => $request->ghi_chu,
                'id_hoa_don'        => $check->id,
                'id_san_pham'       => $request->id_san_pham,
            ]);
        }

        $tong_tien = ChiTietHoaDon::where('id_hoa_don', $check->id)
            ->select(DB::raw('SUM(so_luong * don_gia) as tong'))
            ->value('tong');
        $check->update([
            'tien_truoc_giam' => $tong_tien,
            'tong_tien'       => $tong_tien
        ]);

        return response()->json([
            'status'  =>  1,
            'message' =>  'Đã thêm món thành công!',
        ]);
    }
    public function bepCheBien()
    {
        $data = ChiTietHoaDon::join('hoa_dons', 'chi_tiet_hoa_dons.id_hoa_don', 'hoa_dons.id')
            ->join('bans', 'hoa_dons.id_ban', 'bans.id')
            ->join('san_phams', 'chi_tiet_hoa_dons.id_san_pham', 'san_phams.id')
            ->select(
                'bans.ten_ban',
                'san_phams.ten_san_pham',
                'chi_tiet_hoa_dons.so_luong',
                'chi_tiet_hoa_dons.ghi_chu',
                'chi_tiet_hoa_dons.id',
                'chi_tiet_hoa_dons.tinh_trang',
                'chi_tiet_hoa_dons.created_at',
                'chi_tiet_hoa_dons.updated_at'
            )
            ->whereIn('chi_tiet_hoa_dons.tinh_trang', [1, 2])
            ->where('hoa_dons.tinh_trang', 0)
            ->orderBy('chi_tiet_hoa_dons.created_at', 'asc')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }
    public function xongMon(Request $request)
    {
        $row = ChiTietHoaDon::where('id', $request->id)->where('tinh_trang', 1)->first();
        if ($row) {
            $row->tinh_trang = 2;
            $row->save();

            // Tự động trừ kho nguyên liệu theo định lượng BOM (Bill of Materials)
            $deductions = [];
            $recipes = \App\Models\CongThucMon::where('id_san_pham', $row->id_san_pham)->get();
            foreach ($recipes as $rcp) {
                $nl = \App\Models\NguyenLieu::find($rcp->id_nguyen_lieu);
                if ($nl) {
                    $usedAmount = (float)$rcp->so_luong * (int)$row->so_luong;
                    $nl->so_luong_ton = max(0, (float)$nl->so_luong_ton - $usedAmount);
                    $nl->save();
                    $deductions[] = [
                        'ten_nguyen_lieu' => $nl->ten_nguyen_lieu,
                        'so_luong_tru'    => $usedAmount,
                        'ton_hien_tai'    => $nl->so_luong_ton,
                        'don_vi'          => $rcp->don_vi ?: $nl->don_vi_tinh,
                        'canh_bao'        => $nl->so_luong_ton <= $nl->muc_canh_bao
                    ];
                }
            }

            return response()->json([
                'status'     => 1,
                'message'    => 'Đã hoàn thành chế biến & tự động trừ kho nguyên vật liệu (BOM) thành công!',
                'deductions' => $deductions
            ], 200);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Món ăn không tồn tại hoặc đã chế biến xong rồi!'
            ], 200);
        }
    }
}
