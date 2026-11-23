<?php

namespace App\Http\Controllers;

use App\Models\GiaoDich;
use App\Models\HoaDon;
use App\Models\KhachHang;
use App\Models\ChiTietHoaDon;
use App\Jobs\sendMailJob;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GiaoDichController extends Controller
{
    public function getData()
    {
        $payload = [
            'USERNAME' => env('MB_BANK_ACCOUNT', '06468111'),
            'PASSWORD' => env('MB_BANK_PASSWORD', 'Vominhthuan1107@@@'),
            'DAY_BEGIN' => Carbon::now()->format('d/m/Y'),
            'DAY_END' => Carbon::now()->format('d/m/Y'),
            'NUMBER_MB' => env('MB_BANK_ACCOUNT', '06468111'),
        ];

        $response = Http::post('https://api-mb.midstack.io.vn/api/transactions', $payload);
        if ($response['success'] == true) {
            $data = $response['data']['transactionHistoryList'];
            foreach ($data as $key => $value) {
                $check = GiaoDich::where('refNo', $value['refNo'])->first();
                if (!$check) {
                    $ma_hoa_don = null;
                    if (preg_match('/HD(\d+)/', $value['description'], $matches)) {
                        $ma_hoa_don = 'HD-' . $matches[1];
                    }
                    GiaoDich::create([
                        'creditAmount' => $value['creditAmount'],
                        'description' => $value['description'],
                        'refNo' => $value['refNo'],
                        'ma_hoa_don' => $ma_hoa_don,
                    ]);
                    $hoa_don = HoaDon::where('ma_hoa_don', $ma_hoa_don)->first();
                    if ($hoa_don && $hoa_don->tong_tien <= $value['creditAmount']) {
                        $hoa_don->tinh_trang = 1;
                        $hoa_don->phuong_thuc_thanh_toan = 2;
                        $hoa_don->save();

                        // Pay-First VietQR: Chuyển các món chưa duyệt (0) sang quầy Barista pha chế (1)
                        ChiTietHoaDon::where('id_hoa_don', $hoa_don->id)
                            ->where('tinh_trang', 0)
                            ->update(['tinh_trang' => 1]);

                        // Gửi email hóa đơn cho khách hàng sau khi xác nhận chuyển khoản
                        $khachHang = KhachHang::find($hoa_don->id_khach_hang);
                        if ($khachHang && $khachHang->email) {
                            $chiTiet = ChiTietHoaDon::where('id_hoa_don', $hoa_don->id)
                                ->join('san_phams', 'chi_tiet_hoa_dons.id_san_pham', '=', 'san_phams.id')
                                ->select('chi_tiet_hoa_dons.so_luong', 'chi_tiet_hoa_dons.don_gia', 'san_phams.ten_san_pham')
                                ->get()
                                ->toArray();

                            dispatch(new sendMailJob(
                                $khachHang->email,
                                'Xác nhận thanh toán thành công - ' . $hoa_don->ma_hoa_don,
                                'view_mail_hoa_don_thanh_toan',
                                [
                                    'ho_va_ten'              => $khachHang->ho_va_ten,
                                    'ma_hoa_don'             => $hoa_don->ma_hoa_don,
                                    'tong_tien'              => $hoa_don->tong_tien,
                                    'tien_truoc_giam'        => $hoa_don->tien_truoc_giam,
                                    'so_tien_giam'           => $hoa_don->so_tien_giam,
                                    'phuong_thuc_thanh_toan' => 2,
                                    'ngay_thanh_toan'        => now()->format('d/m/Y H:i'),
                                    'chi_tiet'               => $chiTiet,
                                ]
                            ));
                        }
                    }
                }
            }
        }
    }
}
