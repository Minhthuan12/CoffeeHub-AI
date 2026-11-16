<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khôi Phục Mật Khẩu</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f1f5f9; margin: 0; padding: 40px 0; color: #334155; line-height: 1.6;">

    <table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f1f5f9; padding: 20px 0;">
        <tr>
            <td align="center">
                <!-- MAIN CARD -->
                <table width="600" cellspacing="0" cellpadding="0" border="0" style="width: 600px; max-width: 600px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);">

                    <!-- HEADER BANNER -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); padding: 36px 30px; text-align: center; color: #ffffff;">
                            <div style="width: 60px; height: 60px; background-color: rgba(255, 255, 255, 0.2); border-radius: 50%; display: inline-block; text-align: center; line-height: 60px; margin-bottom: 12px; font-size: 28px;">
                                🔑
                            </div>
                            <h2 style="margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px; color: #ffffff;">HỆ THỐNG DZ</h2>
                            <p style="margin: 6px 0 0 0; font-size: 14px; color: rgba(255, 255, 255, 0.9); font-weight: 500;">Yêu Cầu Đặt Lại Mật Khẩu Tài Khoản</p>
                        </td>
                    </tr>

                    <!-- BODY CONTENT -->
                    <tr>
                        <td style="padding: 36px 40px;">
                            <p style="font-size: 16px; margin: 0 0 16px 0; color: #1e293b;">
                                Xin chào <b>{{ $data['ho_va_ten'] ?? 'bạn' }}</b>,
                            </p>
                            <p style="font-size: 14px; color: #475569; margin: 0 0 24px 0;">
                                Chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn. Vui lòng sử dụng <b>Mã bí mật</b> bên dưới hoặc nhấn trực tiếp vào nút để đổi mật khẩu:
                            </p>

                            <!-- MÃ BÍ MẬT BOX -->
                            <div style="background-color: #f0f7ff; border: 2px dashed #6366f1; border-radius: 12px; padding: 18px 20px; margin-bottom: 28px; text-align: center;">
                                <div style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #4338ca; margin-bottom: 6px;">Mã Bí Mật Của Bạn</div>
                                <div style="font-family: 'Courier New', Courier, monospace; font-size: 20px; font-weight: 800; color: #3730a3; letter-spacing: 2px; word-break: break-all;">
                                    {{ $data['ma_bi_mat'] }}
                                </div>
                            </div>

                            <!-- BUTTON CTA -->
                            <div style="text-align: center; margin: 30px 0 32px 0;">
                                <a href="{{ $data['link'] }}" target="_blank"
                                   style="display: inline-block; background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); color: #ffffff; text-decoration: none; padding: 14px 36px; border-radius: 50px; font-weight: 700; font-size: 15px; box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);">
                                    🚀 ĐỔI MẬT KHẨU NGAY
                                </a>
                            </div>

                            <!-- DIRECT LINK BACKUP -->
                            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 16px; margin-bottom: 24px;">
                                <p style="font-size: 12px; color: #64748b; margin: 0 0 6px 0; font-weight: 600;">
                                    Hoặc copy đường dẫn dưới đây dán vào trình duyệt:
                                </p>
                                <a href="{{ $data['link'] }}" target="_blank"
                                   style="color: #4f46e5; font-size: 13px; font-weight: 600; text-decoration: underline; word-break: break-all;">
                                    {{ $data['link'] }}
                                </a>
                            </div>

                            <!-- SECURITY WARNING -->
                            <div style="border-left: 4px solid #f59e0b; background-color: #fffbeb; padding: 12px 16px; border-radius: 4px; margin-bottom: 8px;">
                                <p style="font-size: 12px; color: #b45309; margin: 0; font-weight: 500;">
                                    ⚠️ <b>Lưu ý an toàn:</b> Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này hoặc liên hệ với quản trị viên. Không chia sẻ mã bí mật này cho bất kỳ ai.
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 20px; border-top: 1px solid #f1f5f9; text-align: center;">
                            <p style="margin: 0 0 4px 0; font-size: 13px; font-weight: 700; color: #475569;">Trân trọng,</p>
                            <p style="margin: 0; font-size: 12px; color: #64748b; font-weight: 600;">Đội ngũ phát triển Hệ Thống DZ</p>
                            <p style="margin: 8px 0 0 0; font-size: 11px; color: #94a3b8;">Đây là email tự động, vui lòng không trả lời trực tiếp email này.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>