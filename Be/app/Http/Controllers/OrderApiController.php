<?php

namespace App\Http\Controllers;

use App\Models\HoaDon;
use App\Models\ChiTietHoaDon;
use App\Models\Ban;
use App\Models\SanPham;
use App\Models\KhachHang;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderApiController extends Controller
{
    /**
     * Khách quét mã QR tại bàn và tạo đơn hàng (Pay-First - UC09, UC15, UC16, UC17, UC18)
     */
    public function createQrOrder(Request $request)
    {
        $id_ban = $request->input('id_ban') ?? $request->id_ban;
        if (!$id_ban && ($request->input('table_code') || $request->table_code)) {
            $tableCode = $request->input('table_code') ?? $request->table_code;
            $ban = Ban::where('ten_ban', 'like', '%' . preg_replace('/[^0-9]/', '', $tableCode) . '%')->first();
            $id_ban = $ban ? $ban->id : 4;
        }
        if (!$id_ban) {
            $id_ban = 4; // Mặc định bàn 4 nếu không chỉ định
        }

        // Kiểm tra bàn hợp lệ
        $banCheck = Ban::find($id_ban);
        if (!$banCheck) {
            return response()->json([
                'status'  => 0,
                'message' => 'Bàn này không tồn tại trong hệ thống!'
            ], 404);
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

        // Lấy cấu hình giá Size và Topping từ server config/tuychon.php (KHÔNG tin giá client gửi lên)
        $sizeCfg    = config('tuychon.sizes', []);
        $daCfg      = config('tuychon.da', []);
        $duongCfg   = config('tuychon.duong', []);
        $toppingCfg = config('tuychon.toppings', []);

        // Khách hàng mặc định hoặc tạo mới nếu có số điện thoại
        $id_khach_hang = 1; // Khách Vãng Lai
        $customerPhone = $request->input('customer_phone') ?? $request->customer_phone;
        $customerName  = $request->input('customer_name') ?? $request->customer_name;
        $customerEmail = $request->input('customer_email') ?? $request->customer_email;
        if ($customerPhone) {
            $khach = KhachHang::where('so_dien_thoai', $customerPhone)->first();
            if (!$khach) {
                $cleanPhone = preg_replace('/[^0-9]/', '', $customerPhone);
                $email = $customerEmail ?: ('khach_' . $cleanPhone . '@coffeehub.local');
                $khach = KhachHang::create([
                    'ho_va_ten'     => $customerName ?: ('Khách QR Bàn ' . $banCheck->ten_ban),
                    'so_dien_thoai' => $customerPhone,
                    'email'         => $email,
                    'tinh_trang'    => 1,
                ]);
            }
            $id_khach_hang = $khach->id;
        }

        // Xác định phương thức thanh toán: 1: Tiền mặt, 2: VietQR động
        $phuongThucStr = strtoupper($request->input('phuong_thuc') ?? $request->input('payment_method') ?? 'VIETQR');
        $isCash = in_array($phuongThucStr, ['TIEN_MAT', 'CASH', '1']);
        $phuongThuc = $isCash ? 1 : 2;

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
                    'phuong_thuc_thanh_toan' => $phuongThuc,
                    'tinh_trang'             => 0, // 0: Chờ xác nhận Pay-First
                    'id_ban'                 => $id_ban,
                    'id_khach_hang'          => $id_khach_hang,
                    'id_phan_cong_ca_lam'    => 1,
                    'id_voucher'             => null,
                ]);
                $hoaDon->update([
                    'ma_hoa_don' => 'HD-' . (1000 + $hoaDon->id)
                ]);
            } else {
                $hoaDon->update([
                    'phuong_thuc_thanh_toan' => $phuongThuc
                ]);
            }

            // Thêm các món vào chi tiết hóa đơn kèm tính toán giá từ Backend
            foreach ($items as $item) {
                $sp = SanPham::find($item['id_san_pham'] ?? $item['id']);
                if (!$sp) continue;

                $so_luong = max(1, (int)($item['so_luong'] ?? $item['quantity'] ?? 1));
                $basePrice = (int)$sp->gia_ban;

                // Xử lý tùy chọn (tuy_chon)
                $rawOption = $item['tuy_chon'] ?? $item['options'] ?? null;
                if (is_string($rawOption)) {
                    $rawOption = json_decode($rawOption, true) ?: [];
                }

                $sizeSnapshot = null;
                $daSnapshot = null;
                $duongSnapshot = null;
                $toppingSnapshots = [];
                $phuThuSize = 0;
                $phuThuToppings = 0;

                if (!empty($rawOption)) {
                    // 1. Size
                    $szKey = is_array($rawOption['size'] ?? null)
                        ? ($rawOption['size']['ma'] ?? 'S')
                        : ($rawOption['size'] ?? 'S');
                    $szKey = strtoupper($szKey);
                    if (isset($sizeCfg[$szKey])) {
                        $phuThuSize = (int)$sizeCfg[$szKey]['gia'];
                        $sizeSnapshot = [
                            'ma'  => $szKey,
                            'ten' => $sizeCfg[$szKey]['ten'],
                            'gia' => $phuThuSize,
                        ];
                    } else {
                        $sizeSnapshot = ['ma' => 'S', 'ten' => 'Size Nhỏ (S)', 'gia' => 0];
                    }

                    // 2. Đá
                    $daKey = is_array($rawOption['da'] ?? null)
                        ? ($rawOption['da']['ma'] ?? '100%')
                        : ($rawOption['da'] ?? '100%');
                    if (isset($daCfg[$daKey])) {
                        $daSnapshot = [
                            'ma'  => $daKey,
                            'ten' => $daCfg[$daKey]['ten'],
                            'gia' => 0,
                        ];
                    } else {
                        $daSnapshot = ['ma' => '100%', 'ten' => '100% Đá (Bình thường)', 'gia' => 0];
                    }

                    // 3. Đường
                    $duongKey = is_array($rawOption['duong'] ?? null)
                        ? ($rawOption['duong']['ma'] ?? '100%')
                        : ($rawOption['duong'] ?? '100%');
                    if (isset($duongCfg[$duongKey])) {
                        $duongSnapshot = [
                            'ma'  => $duongKey,
                            'ten' => $duongCfg[$duongKey]['ten'],
                            'gia' => 0,
                        ];
                    } else {
                        $duongSnapshot = ['ma' => '100%', 'ten' => '100% Đường (Chuẩn)', 'gia' => 0];
                    }

                    // 4. Toppings
                    $reqToppings = $rawOption['toppings'] ?? [];
                    if (is_array($reqToppings)) {
                        foreach ($reqToppings as $top) {
                            $topId = is_array($top) ? ($top['id'] ?? '') : $top;
                            if (isset($toppingCfg[$topId])) {
                                $topPrice = (int)$toppingCfg[$topId]['gia'];
                                $phuThuToppings += $topPrice;
                                $toppingSnapshots[] = [
                                    'id'  => $topId,
                                    'ten' => $toppingCfg[$topId]['ten'],
                                    'gia' => $topPrice,
                                ];
                            }
                        }
                    }
                } else {
                    $sizeSnapshot  = ['ma' => 'S', 'ten' => 'Size Nhỏ (S)', 'gia' => 0];
                    $daSnapshot    = ['ma' => '100%', 'ten' => '100% Đá (Bình thường)', 'gia' => 0];
                    $duongSnapshot = ['ma' => '100%', 'ten' => '100% Đường (Chuẩn)', 'gia' => 0];
                }

                // Đơn giá tính lại từ server
                $donGiaTinhLai = $basePrice + $phuThuSize + $phuThuToppings;

                $tuyChonSnapshot = [
                    'size'     => $sizeSnapshot,
                    'da'       => $daSnapshot,
                    'duong'    => $duongSnapshot,
                    'toppings' => $toppingSnapshots,
                    'phu_thu'  => $phuThuSize + $phuThuToppings,
                ];

                // Ghi chú thân thiện người đọc
                $textParts = [];
                if ($sizeSnapshot['ma'] !== 'S') $textParts[] = 'Size ' . $sizeSnapshot['ma'];
                if ($daSnapshot['ma'] !== '100%') $textParts[] = $daSnapshot['ten'];
                if ($duongSnapshot['ma'] !== '100%') $textParts[] = $duongSnapshot['ten'];
                if (!empty($toppingSnapshots)) {
                    $textParts[] = '+ ' . implode(', ', array_column($toppingSnapshots, 'ten'));
                }
                $extraText = implode(' • ', $textParts);
                $userGhiChu = trim($item['ghi_chu'] ?? $item['note'] ?? '');
                $ghiChuLuu = $userGhiChu ?: null;

                ChiTietHoaDon::create([
                    'id_chi_tiet_cha' => null,
                    'so_luong'        => $so_luong,
                    'don_gia'         => $donGiaTinhLai,
                    'ghi_chu'         => $ghiChuLuu,
                    'tuy_chon'        => $tuyChonSnapshot,
                    'tinh_trang'      => 0, // 0: Chờ duyệt Pay-First
                    'id_hoa_don'      => $hoaDon->id,
                    'id_san_pham'     => $sp->id,
                ]);
            }

            // Tính tổng tiền toàn hóa đơn
            $tong_tien_truoc_giam = ChiTietHoaDon::where('id_hoa_don', $hoaDon->id)
                ->select(DB::raw('SUM(so_luong * don_gia) as tong'))
                ->value('tong') ?? 0;

            // Xử lý mã giảm giá Voucher nếu khách gửi kèm (UC18)
            $soTienGiam = 0;
            $idVoucher = null;
            $maVoucher = trim($request->input('ma_voucher') ?? $request->input('voucher_code') ?? '');
            if ($maVoucher) {
                $voucher = Voucher::where('ma_voucher', $maVoucher)->first();
                $now = now();
                if ($voucher && ($now >= $voucher->thoi_gian_bat_dau && $now <= $voucher->thoi_gian_ket_thuc)) {
                    if (!$voucher->don_hang_toi_thieu || $tong_tien_truoc_giam >= $voucher->don_hang_toi_thieu) {
                        if ($voucher->loai_giam == 1) {
                            $soTienGiam = $tong_tien_truoc_giam * $voucher->so_giam_gia / 100;
                            if ($voucher->so_tien_toi_da && $soTienGiam > $voucher->so_tien_toi_da) {
                                $soTienGiam = $voucher->so_tien_toi_da;
                            }
                        } else {
                            $soTienGiam = $voucher->so_giam_gia;
                        }
                        $soTienGiam = min($soTienGiam, $tong_tien_truoc_giam);
                        $idVoucher = $voucher->id;
                    }
                }
            }

            $tongTienSauGiam = max(0, $tong_tien_truoc_giam - $soTienGiam);

            $hoaDon->update([
                'id_voucher'      => $idVoucher,
                'so_tien_giam'    => $soTienGiam,
                'tien_truoc_giam' => $tong_tien_truoc_giam,
                'tong_tien'       => $tongTienSauGiam
            ]);

            DB::commit();

            // Sinh URL VietQR động theo chuẩn Napas 247
            $mbAccount = env('MB_BANK_ACCOUNT', '06468111');
            $mbName = env('MB_ACCOUNT_NAME', 'VO MINH THUAN');
            $qr_code_url = sprintf(
                "https://img.vietqr.io/image/MB-%s-compact2.png?amount=%d&addInfo=%s&accountName=%s",
                $mbAccount,
                $hoaDon->tong_tien,
                urlencode($hoaDon->ma_hoa_don),
                urlencode($mbName)
            );

            $msg = $isCash
                ? 'Yêu cầu gọi món và thu tiền mặt tại bàn đã được ghi nhận! Thu ngân sẽ tới bàn phục vụ bạn ngay.'
                : 'Tạo đơn gọi món QR thành công! Vui lòng quét mã VietQR để thanh toán Pay-First.';

            return response()->json([
                'status'          => 1,
                'message'         => $msg,
                'order_id'        => $hoaDon->id,
                'ma_hoa_don'      => $hoaDon->ma_hoa_don,
                'tien_truoc_giam' => $hoaDon->tien_truoc_giam,
                'so_tien_giam'    => $hoaDon->so_tien_giam,
                'tong_tien'       => $hoaDon->tong_tien,
                'phuong_thuc'     => $isCash ? 'TIEN_MAT' : 'VIETQR',
                'vietqr_url'      => $qr_code_url,
                'id_ban'          => $id_ban,
                'ten_ban'         => $banCheck->ten_ban,
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
     * Khách hàng kiểm tra trạng thái đơn hàng của mình (UC19)
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
                'chi_tiet_hoa_dons.tuy_chon',
                'chi_tiet_hoa_dons.tinh_trang' // 0: Chờ duyệt, 1: Đang làm, 2: Xong
            )
            ->where('chi_tiet_hoa_dons.id_hoa_don', $id)
            ->get();

        // Xác định bước tiến trình (Timeline step)
        // 1: Tiếp nhận / Chờ duyệt thanh toán
        // 2: Thu ngân đã duyệt / Đang pha chế KDS
        // 3: Pha chế xong / Sẵn sàng phục vụ
        $allDone = $items->count() > 0 && $items->every(fn($item) => (int)$item->tinh_trang === 2);
        $anyMaking = $items->some(fn($item) => (int)$item->tinh_trang === 1);

        $step = 1;
        if ((int)$hoaDon->tinh_trang === 1) {
            $step = $allDone ? 3 : 2;
        }

        return response()->json([
            'status'     => 1,
            'order'      => $hoaDon,
            'items'      => $items,
            'step'       => $step,
            'is_paid'    => (int)$hoaDon->tinh_trang === 1
        ]);
    }

    /**
     * Quầy POS thu ngân: Lấy danh sách các đơn hàng QR đang chờ duyệt thanh toán (Pay-First - UC26)
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
                'hoa_dons.so_tien_giam',
                'hoa_dons.tien_truoc_giam',
                'hoa_dons.phuong_thuc_thanh_toan',
                'hoa_dons.created_at',
                'bans.ten_ban',
                'khach_hangs.ho_va_ten as ten_khach_hang'
            )
            ->orderByDesc('hoa_dons.id')
            ->get();

        foreach ($orders as $order) {
            $order->items = ChiTietHoaDon::join('san_phams', 'chi_tiet_hoa_dons.id_san_pham', '=', 'san_phams.id')
                ->where('chi_tiet_hoa_dons.id_hoa_don', $order->id)
                ->select(
                    'san_phams.ten_san_pham',
                    'chi_tiet_hoa_dons.so_luong',
                    'chi_tiet_hoa_dons.don_gia',
                    'chi_tiet_hoa_dons.ghi_chu',
                    'chi_tiet_hoa_dons.tuy_chon',
                    'chi_tiet_hoa_dons.tinh_trang'
                )
                ->get();
        }

        return response()->json([
            'status' => 1,
            'data'   => $orders
        ]);
    }

    /**
     * Thu ngân POS duyệt thanh toán Pay-First -> chuyển đơn sang quầy KDS pha chế (UC27)
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

        $phuongThuc = $request->input('payment_method', 2);
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

    /**
     * Quầy Barista (KDS): Lấy danh sách hàng đợi pha chế thời gian thực (UC28)
     */
    public function getKdsQueue()
    {
        $items = ChiTietHoaDon::join('hoa_dons', 'chi_tiet_hoa_dons.id_hoa_don', '=', 'hoa_dons.id')
            ->join('bans', 'hoa_dons.id_ban', '=', 'bans.id')
            ->join('san_phams', 'chi_tiet_hoa_dons.id_san_pham', '=', 'san_phams.id')
            ->select(
                'chi_tiet_hoa_dons.id',
                'chi_tiet_hoa_dons.id_hoa_don',
                'chi_tiet_hoa_dons.id_san_pham',
                'chi_tiet_hoa_dons.so_luong',
                'chi_tiet_hoa_dons.ghi_chu',
                'chi_tiet_hoa_dons.tuy_chon',
                'chi_tiet_hoa_dons.tinh_trang',
                'chi_tiet_hoa_dons.created_at',
                'hoa_dons.ma_hoa_don',
                'bans.ten_ban',
                'san_phams.ten_san_pham',
                'san_phams.kich_thuoc',
                'san_phams.hinh_anh'
            )
            ->where('chi_tiet_hoa_dons.tinh_trang', 1) // 1: Đang chờ pha chế
            ->orderBy('chi_tiet_hoa_dons.created_at', 'asc')
            ->get();

        foreach ($items as $item) {
            $item->recipes = \App\Models\CongThucMon::join('nguyen_lieus', 'cong_thuc_mons.id_nguyen_lieu', '=', 'nguyen_lieus.id')
                ->where('cong_thuc_mons.id_san_pham', $item->id_san_pham)
                ->select(
                    'nguyen_lieus.ten_nguyen_lieu',
                    'cong_thuc_mons.so_luong as dinh_luong',
                    'cong_thuc_mons.don_vi',
                    'nguyen_lieus.so_luong_ton'
                )
                ->get();
        }

        return response()->json([
            'status' => 1,
            'data'   => $items
        ]);
    }

    /**
     * Barista bắt đầu pha chế món
     */
    public function startMakingOrder($id)
    {
        $item = ChiTietHoaDon::find($id);
        if (!$item) {
            return response()->json(['status' => 0, 'message' => 'Món không tồn tại!'], 404);
        }

        return response()->json([
            'status'  => 1,
            'message' => 'Đã chuyển sang trạng thái đang pha chế!'
        ]);
    }

    /**
     * Barista hoàn tất ly nước -> Tự động kích hoạt trừ kho nguyên vật liệu BOM
     */
    public function completeOrder($id)
    {
        $item = ChiTietHoaDon::find($id);
        if (!$item) {
            return response()->json(['status' => 0, 'message' => 'Món không tồn tại!'], 404);
        }

        $item->tinh_trang = 2; // Đã hoàn thành
        $item->save();

        // Tự động trừ kho theo định lượng BOM
        $deductions = [];
        $recipes = \App\Models\CongThucMon::where('id_san_pham', $item->id_san_pham)->get();
        foreach ($recipes as $rcp) {
            $nl = \App\Models\NguyenLieu::find($rcp->id_nguyen_lieu);
            if ($nl) {
                $usedAmount = (float)$rcp->so_luong * (int)$item->so_luong;
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
            'message'    => 'Pha chế hoàn tất! Đã tự động trừ kho nguyên liệu theo định lượng BOM.',
            'deductions' => $deductions
        ]);
    }

    /**
     * Lấy danh sách định lượng công thức món (BOM) & Tính giá vốn (COGS), Tỷ suất lợi nhuận
     */
    public function getBomRecipes()
    {
        $products = SanPham::where('tinh_trang', 1)->get();
        $results = [];

        foreach ($products as $sp) {
            $recipes = \App\Models\CongThucMon::join('nguyen_lieus', 'cong_thuc_mons.id_nguyen_lieu', '=', 'nguyen_lieus.id')
                ->where('cong_thuc_mons.id_san_pham', $sp->id)
                ->select(
                    'cong_thuc_mons.id',
                    'nguyen_lieus.ten_nguyen_lieu',
                    'cong_thuc_mons.so_luong',
                    'cong_thuc_mons.don_vi',
                    'nguyen_lieus.don_vi_tinh'
                )
                ->get();

            // Ước tính giá vốn nguyên liệu (COGS) trung bình 30% giá bán
            $cogs = round($sp->gia_ban * 0.3);
            $margin = $sp->gia_ban > 0 ? round((($sp->gia_ban - $cogs) / $sp->gia_ban) * 100, 1) : 0;

            $results[] = [
                'id_san_pham'       => $sp->id,
                'ten_san_pham'      => $sp->ten_san_pham,
                'kich_thuoc'        => $sp->kich_thuoc,
                'gia_ban'           => $sp->gia_ban,
                'gia_von'           => $cogs,
                'ty_suat_loi_nhuan' => $margin,
                'recipes'           => $recipes
            ];
        }

        return response()->json([
            'status' => 1,
            'data'   => $results
        ]);
    }

    /**
     * Lấy danh sách tồn kho nguyên vật liệu
     */
    public function getInventoryItems()
    {
        $items = \App\Models\NguyenLieu::leftJoin('nha_cung_caps', 'nguyen_lieus.id_nha_cung_cap', '=', 'nha_cung_caps.id')
            ->select(
                'nguyen_lieus.id',
                'nguyen_lieus.ten_nguyen_lieu',
                'nguyen_lieus.don_vi_tinh',
                'nguyen_lieus.so_luong_ton',
                'nguyen_lieus.muc_canh_bao',
                'nguyen_lieus.tinh_trang',
                'nha_cung_caps.ten_nha_cung_cap as nha_cung_cap'
            )
            ->get();

        foreach ($items as $item) {
            $item->canh_bao_ton = $item->so_luong_ton <= $item->muc_canh_bao;
        }

        return response()->json([
            'status' => 1,
            'data'   => $items
        ]);
    }
}
