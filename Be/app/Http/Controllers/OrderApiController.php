<?php

namespace App\Http\Controllers;

use App\Models\HoaDon;
use App\Models\ChiTietHoaDon;
use App\Models\Ban;
use App\Models\SanPham;
use App\Models\KhachHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderApiController extends Controller
{
    /**
     * Khách quét mã QR tại bàn và tạo đơn hàng (Pay-First)
     */
    public function createQrOrder(Request $request)
    {
        $id_ban = $request->input('id_ban') ?? $request->id_ban;
        if (!$id_ban && ($request->input('table_code') || $request->table_code)) {
            $tableCode = $request->input('table_code') ?? $request->table_code;
            // Chuẩn hóa tên bàn ví dụ: 'BAN04', '04', 'Ban 4' -> tìm id bàn
            $ban = Ban::where('ten_ban', 'like', '%' . preg_replace('/[^0-9]/', '', $tableCode) . '%')->first();
            $id_ban = $ban ? $ban->id : 4;
        }
        if (!$id_ban) {
            $id_ban = 4; // Mặc định bàn 4
        }

        $items = $request->input('items') ?? $request->json('items') ?? [];
        if (is_string($items)) {
            $items = json_decode($items, true) ?? [];
        }
        if (empty($items)) {
            return response()->json([
                'status'  => 0,
                'message' => 'Giỏ hàng của bạn đang trống!'
            ], 400);
        }

        // Khách hàng mặc định hoặc tạo mới nếu có số điện thoại
        $id_khach_hang = 1; // Khách Vãng Lai
        $customerPhone = $request->input('customer_phone') ?? $request->customer_phone;
        $customerName = $request->input('customer_name') ?? $request->customer_name;
        $customerEmail = $request->input('customer_email') ?? $request->customer_email;
        if ($customerPhone) {
            $khach = KhachHang::where('so_dien_thoai', $customerPhone)->first();
            if (!$khach) {
                $cleanPhone = preg_replace('/[^0-9]/', '', $customerPhone);
                $email = $customerEmail ?: ('khach_' . $cleanPhone . '@coffeehub.local');
                $khach = KhachHang::create([
                    'ho_va_ten'     => $customerName ?: ('Khách QR Bàn ' . $id_ban),
                    'so_dien_thoai' => $customerPhone,
                    'email'         => $email,
                    'tinh_trang'    => 1,
                ]);
            }
            $id_khach_hang = $khach->id;
        }

        DB::beginTransaction();
        try {
            // Kiểm tra hoặc tạo hóa đơn mới cho bàn
            $hoaDon = HoaDon::where('id_ban', $id_ban)
                ->where('tinh_trang', 0)
                ->first();

            if (!$hoaDon) {
                $hoaDon = HoaDon::create([
                    'ma_hoa_don'             => 'HD-' . strtoupper(Str::random(6)),
                    'tong_tien'              => 0,
                    'tien_truoc_giam'        => 0,
                    'so_tien_giam'           => 0,
                    'phuong_thuc_thanh_toan' => null,
                    'tinh_trang'             => 0, // 0: Đang mở
                    'id_ban'                 => $id_ban,
                    'id_khach_hang'          => $id_khach_hang,
                    'id_phan_cong_ca_lam'    => 1,
                    'id_voucher'             => null,
                ]);
                $hoaDon->update([
                    'ma_hoa_don' => 'HD-' . (1000 + $hoaDon->id)
                ]);
            }

            // Thêm các món vào chi tiết hóa đơn
            foreach ($items as $item) {
                $sp = SanPham::find($item['id_san_pham'] ?? $item['id']);
                if ($sp) {
                    $so_luong = (int)($item['so_luong'] ?? $item['quantity'] ?? 1);
                    $don_gia = $sp->gia_ban;
                    $ghi_chu = $item['ghi_chu'] ?? $item['note'] ?? null;

                    ChiTietHoaDon::create([
                        'id_chi_tiet_cha' => null,
                        'so_luong'        => $so_luong,
                        'don_gia'         => $don_gia,
                        'ghi_chu'         => $ghi_chu,
                        'tinh_trang'      => 0, // 0: Chưa gửi bếp / Chờ POS duyệt thanh toán
                        'id_hoa_don'      => $hoaDon->id,
                        'id_san_pham'     => $sp->id,
                    ]);
                }
            }

            // Tính tổng tiền hóa đơn
            $tong_tien = ChiTietHoaDon::where('id_hoa_don', $hoaDon->id)
                ->select(DB::raw('SUM(so_luong * don_gia) as tong'))
                ->value('tong') ?? 0;

            $hoaDon->update([
                'tien_truoc_giam' => $tong_tien,
                'tong_tien'       => max(0, $tong_tien - ($hoaDon->so_tien_giam ?? 0))
            ]);

            DB::commit();

            // Link VietQR động thanh toán
            $qr_code_url = sprintf(
                "https://img.vietqr.io/image/MB-0357288636-compact2.png?amount=%d&addInfo=%s&accountName=COFFEEHUB%%20STORE",
                $hoaDon->tong_tien,
                urlencode($hoaDon->ma_hoa_don)
            );

            return response()->json([
                'status'        => 1,
                'message'       => 'Tạo đơn gọi món QR thành công! Vui lòng thanh toán tại bàn hoặc quầy thu ngân.',
                'order_id'      => $hoaDon->id,
                'ma_hoa_don'    => $hoaDon->ma_hoa_don,
                'tong_tien'     => $hoaDon->tong_tien,
                'vietqr_url'    => $qr_code_url,
                'id_ban'        => $id_ban
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 0,
                'message' => 'Lỗi tạo đơn: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Khách hàng kiểm tra trạng thái đơn hàng của mình
     */
    public function getOrderStatus($id)
    {
        $hoaDon = HoaDon::join('bans', 'hoa_dons.id_ban', '=', 'bans.id')
            ->select('hoa_dons.*', 'bans.ten_ban')
            ->where('hoa_dons.id', $id)
            ->first();

        if (!$hoaDon) {
            return response()->json([
                'status'  => 0,
                'message' => 'Không tìm thấy hóa đơn!'
            ], 404);
        }

        $items = ChiTietHoaDon::join('san_phams', 'chi_tiet_hoa_dons.id_san_pham', '=', 'san_phams.id')
            ->select(
                'chi_tiet_hoa_dons.id',
                'san_phams.ten_san_pham',
                'chi_tiet_hoa_dons.so_luong',
                'chi_tiet_hoa_dons.don_gia',
                'chi_tiet_hoa_dons.ghi_chu',
                'chi_tiet_hoa_dons.tinh_trang' // 0: Chờ duyệt, 1: Đang làm, 2: Xong
            )
            ->where('chi_tiet_hoa_dons.id_hoa_don', $id)
            ->get();

        return response()->json([
            'status'     => 1,
            'order'      => $hoaDon,
            'items'      => $items,
            'is_paid'    => (int)$hoaDon->tinh_trang === 1
        ]);
    }

    /**
     * Quầy POS thu ngân: Lấy danh sách các đơn hàng QR đang chờ duyệt thanh toán (Pay-First)
     */
    public function getPendingQrOrders()
    {
        $orders = HoaDon::join('bans', 'hoa_dons.id_ban', '=', 'bans.id')
            ->join('khach_hangs', 'hoa_dons.id_khach_hang', '=', 'khach_hangs.id')
            ->where('hoa_dons.tinh_trang', 0)
            ->select(
                'hoa_dons.id',
                'hoa_dons.ma_hoa_don',
                'hoa_dons.tong_tien',
                'hoa_dons.created_at',
                'bans.ten_ban',
                'khach_hangs.ho_va_ten as ten_khach_hang'
            )
            ->orderByDesc('hoa_dons.id')
            ->get();

        foreach ($orders as $order) {
            $order->items = ChiTietHoaDon::join('san_phams', 'chi_tiet_hoa_dons.id_san_pham', '=', 'san_phams.id')
                ->where('chi_tiet_hoa_dons.id_hoa_don', $order->id)
                ->select('san_phams.ten_san_pham', 'chi_tiet_hoa_dons.so_luong', 'chi_tiet_hoa_dons.don_gia', 'chi_tiet_hoa_dons.tinh_trang')
                ->get();
        }

        return response()->json([
            'status' => 1,
            'data'   => $orders
        ]);
    }

    /**
     * Thu ngân POS duyệt thanh toán Pay-First -> chuyển đơn sang quầy KDS pha chế
     */
    public function approveOrderPayment(Request $request, $id)
    {
        $hoaDon = HoaDon::find($id);
        if (!$hoaDon) {
            return response()->json([
                'status'  => 0,
                'message' => 'Hóa đơn không tồn tại!'
            ], 404);
        }

        $phuongThuc = $request->input('payment_method', 2); // 1: Tiền mặt, 2: VietQR / Chuyển khoản
        if ($phuongThuc === 'VIETQR' || $phuongThuc === 'CHUYEN_KHOAN') {
            $phuongThuc = 2;
        } elseif ($phuongThuc === 'TIEN_MAT' || $phuongThuc === 'CASH') {
            $phuongThuc = 1;
        }

        // Cập nhật hóa đơn
        $hoaDon->update([
            'phuong_thuc_thanh_toan' => $phuongThuc,
            'tinh_trang'             => 1 // Đã thanh toán (Pay-First)
        ]);

        // Chuyển toàn bộ món từ 0 (chưa gửi) sang 1 (chờ pha chế trên KDS)
        ChiTietHoaDon::where('id_hoa_don', $hoaDon->id)
            ->where('tinh_trang', 0)
            ->update(['tinh_trang' => 1]);

        return response()->json([
            'status'     => 1,
            'message'    => 'Duyệt thanh toán Pay-First thành công! Đơn hàng đã được chuyển ngay tới quầy pha chế KDS.',
            'order_id'   => $hoaDon->id,
            'ma_hoa_don' => $hoaDon->ma_hoa_don
        ]);
    }
}
