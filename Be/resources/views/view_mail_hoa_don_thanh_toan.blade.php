<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn Thanh Toán Thành Công</title>
</head>

<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px 0; color: #333333;">

    <table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f4f6f9; padding: 20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellspacing="0" cellpadding="0" border="0" style="width: 600px; max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">

                    <!-- HEADER -->
                    <tr>
                        <td style="background-color: #0d6efd; padding: 30px 20px; color: #ffffff; text-align: center;">
                            <div style="width: 56px; height: 56px; background-color: rgba(255,255,255,0.2); border-radius: 50%; display: inline-block; text-align: center; line-height: 56px; margin-bottom: 10px; font-size: 28px;">
                                🧾
                            </div>
                            <h2 style="margin: 0; font-size: 22px; font-weight: 700; color: #ffffff;">Hệ Thống Quản Lý Cafe</h2>
                            <p style="margin: 4px 0 0 0; font-size: 13px; color: rgba(255,255,255,0.85);">Xác Nhận Thanh Toán Hóa Đơn</p>
                        </td>
                    </tr>

                    <!-- BODY -->
                    <tr>
                        <td style="padding: 30px 35px;">

                            <!-- Badge -->
                            <div style="text-align: center; margin-bottom: 20px;">
                                <span style="display: inline-block; background-color: #e6f4ea; color: #1e7e34; border: 1px solid #ceebd5; font-size: 13px; font-weight: bold; padding: 6px 16px; border-radius: 20px;">
                                    ✓ ĐÃ THANH TOÁN THÀNH CÔNG
                                </span>
                            </div>

                            <p style="font-size: 15px; margin: 0 0 5px 0; color: #1e293b;">Kính gửi <b>{{ $data['ho_va_ten'] }}</b>,</p>
                            <p style="font-size: 14px; color: #64748b; line-height: 1.5; margin: 0 0 25px 0;">
                                Cảm ơn quý khách đã đến và trải nghiệm dịch vụ của chúng tôi. Dưới đây là chi tiết hóa đơn thanh toán của quý khách:
                            </p>

                            <!-- THÔNG TIN HÓA ĐƠN -->
                            <table width="100%" cellspacing="0" cellpadding="0" border="0"
                                   style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 25px;">
                                <tr>
                                    <td style="padding: 14px 16px;">
                                        <table width="100%" cellspacing="0" cellpadding="4" border="0" style="font-size: 14px;">
                                            <tr>
                                                <td style="padding: 4px 0; color: #64748b; width: 40%;">Mã Hóa Đơn:</td>
                                                <td style="padding: 4px 0; font-weight: bold; color: #1e293b; text-align: right;">{{ $data['ma_hoa_don'] }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 4px 0; color: #64748b;">Thời gian:</td>
                                                <td style="padding: 4px 0; font-weight: bold; color: #1e293b; text-align: right;">{{ $data['ngay_thanh_toan'] }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 4px 0; color: #64748b;">Phương thức:</td>
                                                <td style="padding: 4px 0; font-weight: bold; color: #1e293b; text-align: right;">
                                                    {{ $data['phuong_thuc_thanh_toan'] == 1 ? 'Tiền mặt' : 'Chuyển khoản' }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- BẢNG CHI TIẾT MÓN -->
                            <h4 style="margin: 25px 0 12px 0; color: #111827; font-size: 16px; font-weight: 700;">Chi Tiết Đơn Hàng</h4>

                            <table width="100%" cellspacing="0" cellpadding="0" border="0"
                                   style="border-collapse: collapse; margin-bottom: 25px; font-size: 14px;">
                                <thead>
                                    <tr style="background-color: #f8f9fa;">
                                        <th style="text-align: left; padding: 12px 14px; color: #212529; font-weight: 700; border-bottom: 1px solid #e2e8f0; width: 46%;">Tên Món</th>
                                        <th style="text-align: center; padding: 12px 8px; color: #212529; font-weight: 700; border-bottom: 1px solid #e2e8f0; width: 14%;">SL</th>
                                        <th style="text-align: right; padding: 12px 14px; color: #212529; font-weight: 700; border-bottom: 1px solid #e2e8f0; width: 20%;">Đơn Giá</th>
                                        <th style="text-align: right; padding: 12px 14px; color: #212529; font-weight: 700; border-bottom: 1px solid #e2e8f0; width: 20%;">Thành Tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['chi_tiet'] as $item)
                                    <tr>
                                        <td style="text-align: left; padding: 12px 14px; color: #212529; border-bottom: 1px solid #f1f5f9;"><b>{{ $item['ten_san_pham'] }}</b></td>
                                        <td style="text-align: center; padding: 12px 8px; color: #212529; border-bottom: 1px solid #f1f5f9;">{{ $item['so_luong'] }}</td>
                                        <td style="text-align: right; padding: 12px 14px; color: #212529; border-bottom: 1px solid #f1f5f9;">{{ number_format($item['don_gia'], 0, ',', '.') }}đ</td>
                                        <td style="text-align: right; padding: 12px 14px; color: #212529; border-bottom: 1px solid #f1f5f9;">{{ number_format($item['so_luong'] * $item['don_gia'], 0, ',', '.') }}đ</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- TỔNG CỘNG -->
                            <table width="100%" cellspacing="0" cellpadding="0" border="0"
                                   style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; margin-top: 20px;">
                                <tr>
                                    <td style="padding: 16px 20px;">
                                        <table width="100%" cellspacing="0" cellpadding="4" border="0" style="font-size: 14px;">
                                            <tr>
                                                <td style="padding: 4px 0; color: #64748b;">Tổng tiền hàng:</td>
                                                <td style="padding: 4px 0; font-weight: 600; color: #334155; text-align: right;">{{ number_format($data['tien_truoc_giam'] ?? $data['tong_tien'], 0, ',', '.') }}đ</td>
                                            </tr>
                                            @if(isset($data['so_tien_giam']) && $data['so_tien_giam'] > 0)
                                            <tr>
                                                <td style="padding: 4px 0; color: #64748b;">Giảm giá (Voucher):</td>
                                                <td style="padding: 4px 0; font-weight: 600; color: #ef4444; text-align: right;">-{{ number_format($data['so_tien_giam'], 0, ',', '.') }}đ</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td style="padding: 12px 0 0 0; font-size: 15px; font-weight: bold; color: #0d6efd; border-top: 2px dashed #cbd5e1;">TỔNG THU:</td>
                                                <td style="padding: 12px 0 0 0; font-size: 19px; font-weight: bold; color: #0d6efd; border-top: 2px dashed #cbd5e1; text-align: right;">{{ number_format($data['tong_tien'], 0, ',', '.') }}đ</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 18px 20px; border-top: 1px solid #e2e8f0; text-align: center;">
                            <p style="margin: 0 0 4px 0; font-size: 13px; font-weight: bold; color: #334155;">Cảm ơn Quý Khách & Hẹn Gặp Lại!</p>
                            <p style="margin: 0; font-size: 12px; color: #94a3b8;">Nếu có bất kỳ thắc mắc nào về hóa đơn này, xin vui lòng liên hệ quầy thu ngân.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>