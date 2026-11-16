<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn Thanh Toán</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px 0; color: #333333;">

    <table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f4f6f9; padding: 20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellspacing="0" cellpadding="0" border="0" style="width: 600px; max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">

                    <!-- HEADER -->
                    <tr>
                        <td style="background-color: #16a34a; padding: 30px 20px; color: #ffffff; text-align: center;">
                            <div style="width: 56px; height: 56px; background-color: rgba(255,255,255,0.2); border-radius: 50%; display: inline-block; text-align: center; line-height: 56px; margin-bottom: 10px; font-size: 28px;">
                                ✅
                            </div>
                            <h2 style="margin: 0; font-size: 22px; font-weight: 700; color: #ffffff;">Hệ Thống Quản Lý Cafe</h2>
                            <p style="margin: 4px 0 0 0; font-size: 13px; color: rgba(255,255,255,0.85);">Xác Nhận Thanh Toán Thành Công</p>
                        </td>
                    </tr>

                    <!-- BODY -->
                    <tr>
                        <td style="padding: 30px 35px;">
                            <p style="font-size: 16px; margin: 0 0 6px 0; color: #1e293b;">Chào bạn, <b>{{ $data['ho_va_ten'] }}</b></p>
                            <p style="font-size: 14px; color: #475569; line-height: 1.6; margin: 0 0 25px 0;">
                                Cảm ơn bạn đã sử dụng dịch vụ! Đây là thông tin hóa đơn của bạn.
                            </p>

                            <!-- THÔNG TIN HÓA ĐƠN -->
                            <table width="100%" cellspacing="0" cellpadding="0" border="0"
                                   style="background-color: #f8fafc; border-radius: 10px; padding: 16px; margin-bottom: 20px; border: 1px solid #e2e8f0;">
                                <tr>
                                    <td style="padding: 6px 16px;">
                                        <table width="100%" cellspacing="0" cellpadding="4" border="0">
                                            <tr>
                                                <td style="font-size: 13px; color: #64748b; width: 50%;">Mã hóa đơn:</td>
                                                <td style="font-size: 13px; color: #1e293b; font-weight: 600; text-align: right;">{{ $data['ma_hoa_don'] }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 13px; color: #64748b;">Phương thức:</td>
                                                <td style="font-size: 13px; color: #1e293b; font-weight: 600; text-align: right;">
                                                    {{ $data['phuong_thuc_thanh_toan'] == 1 ? 'Tiền mặt' : 'Chuyển khoản' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 13px; color: #64748b;">Ngày thanh toán:</td>
                                                <td style="font-size: 13px; color: #1e293b; font-weight: 600; text-align: right;">{{ $data['ngay_thanh_toan'] }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- CHI TIẾT SẢN PHẨM -->
                            <p style="font-size: 14px; font-weight: 700; color: #1e293b; margin: 0 0 10px 0;">Chi tiết đơn hàng:</p>
                            <table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0;">
                                <!-- Header -->
                                <tr style="background-color: #1e293b;">
                                    <td style="padding: 10px 14px; font-size: 12px; color: #ffffff; font-weight: 600;">Sản phẩm</td>
                                    <td style="padding: 10px 8px; font-size: 12px; color: #ffffff; font-weight: 600; text-align: center;">SL</td>
                                    <td style="padding: 10px 14px; font-size: 12px; color: #ffffff; font-weight: 600; text-align: right;">Đơn giá</td>
                                    <td style="padding: 10px 14px; font-size: 12px; color: #ffffff; font-weight: 600; text-align: right;">Thành tiền</td>
                                </tr>
                                <!-- Rows -->
                                @foreach($data['chi_tiet'] as $index => $item)
                                <tr style="background-color: {{ $index % 2 === 0 ? '#ffffff' : '#f8fafc' }};">
                                    <td style="padding: 10px 14px; font-size: 13px; color: #334155;">{{ $item['ten_san_pham'] }}</td>
                                    <td style="padding: 10px 8px; font-size: 13px; color: #334155; text-align: center;">{{ $item['so_luong'] }}</td>
                                    <td style="padding: 10px 14px; font-size: 13px; color: #334155; text-align: right;">{{ number_format($item['don_gia'], 0, ',', '.') }}đ</td>
                                    <td style="padding: 10px 14px; font-size: 13px; color: #334155; text-align: right;">{{ number_format($item['so_luong'] * $item['don_gia'], 0, ',', '.') }}đ</td>
                                </tr>
                                @endforeach
                                <!-- Tổng tiền -->
                                @if(isset($data['so_tien_giam']) && $data['so_tien_giam'] > 0)
                                <tr style="background-color: #f0fdf4;">
                                    <td colspan="3" style="padding: 10px 14px; font-size: 13px; color: #64748b; text-align: right;">Giảm giá:</td>
                                    <td style="padding: 10px 14px; font-size: 13px; color: #dc2626; font-weight: 600; text-align: right;">-{{ number_format($data['so_tien_giam'], 0, ',', '.') }}đ</td>
                                </tr>
                                @endif
                                <tr style="background-color: #f0fdf4;">
                                    <td colspan="3" style="padding: 12px 14px; font-size: 15px; color: #1e293b; font-weight: 700; text-align: right; border-top: 2px solid #e2e8f0;">Tổng cộng:</td>
                                    <td style="padding: 12px 14px; font-size: 15px; color: #16a34a; font-weight: 700; text-align: right; border-top: 2px solid #e2e8f0;">{{ number_format($data['tong_tien'], 0, ',', '.') }}đ</td>
                                </tr>
                            </table>

                            <p style="font-size: 12px; color: #94a3b8; line-height: 1.5; margin: 20px 0 0 0;">
                                Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của chúng tôi!<br>
                                <i>* Đây là email tự động, vui lòng không trả lời trực tiếp email này.</i>
                            </p>
                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 18px 20px; border-top: 1px solid #e2e8f0; text-align: center;">
                            <p style="margin: 0 0 3px 0; font-size: 13px; font-weight: bold; color: #334155;">Trân trọng,</p>
                            <p style="margin: 0; font-size: 12px; color: #64748b;">Hệ Thống Quản Lý Cafe</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
