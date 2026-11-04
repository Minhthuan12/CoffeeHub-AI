# ☕ CoffeeHub - Hệ thống Quản lý Quán Cà phê Tích hợp Trí tuệ Nhân tạo (AI)
> **Đồ án CDIO (Mã môn: SE447 G) – Trường Đại học Duy Tân**  
> **Nhóm thực hiện:** Nhóm 7  
> **Giảng viên hướng dẫn:** ThS. Trần Thị Thanh Lan

---

## 📖 Giới thiệu Dự án
**CoffeeHub** là giải pháp phần mềm quản trị toàn diện cho các cơ sở kinh doanh dịch vụ đồ uống (F&B), tiên phong kết hợp quy trình vận hành tinh gọn chuẩn **Pay-First** (Thanh toán trước khi pha chế) và **Trí tuệ Nhân tạo (Google Gemini AI)**:
* **Khách hàng tại bàn:** Quét mã QR đặt món trực tiếp, trò chuyện với **Trợ lý AI Barista** tư vấn đồ uống theo khẩu vị và thời tiết, thanh toán tức thì qua **VietQR động**.
* **Thu ngân (POS):** Vận hành trên mô hình 2 màn hình, giám sát đơn QR bàn và duyệt thanh toán an toàn, chống bùng đơn.
* **Quầy Bar (KDS Realtime):** Màn hình hiển thị pha chế thời gian thực (<0.2s) qua WebSocket, loại bỏ hoàn toàn hóa đơn giấy.
* **Quản lý Kho & Định lượng (BOM):** Tự động trừ kho nguyên vật liệu chính xác theo từng ly đồ uống bán ra khi Barista bấm hoàn tất.
* **Quản lý & Báo cáo AI:** Bảng điều khiển KPI trực quan và **Trợ lý AI Copilot** hỗ trợ chủ quán dự báo lượng tiêu thụ 3 ngày tới.

---

## 📁 Cấu trúc Thư mục Dự án
```
AICoffeeHub/
├── Be/             # Backend API xây dựng trên nền tảng Laravel (PHP)
│   ├── app/        # Controllers, Models, Services
│   ├── database/   # Migrations (17 bảng CSDL chuẩn 3NF), Seeders
│   └── routes/     # Định nghĩa API Endpoints (/api)
├── Fe/             # Frontend Web App tương tác cao cấp (HTML5, TailwindCSS, JS)
│   ├── index.html  # Hub điều phối trung tâm chuyển đổi 6 Màn hình cốt lõi
│   ├── pages/      # 6 Màn hình chức năng độc lập (Customer QR, POS, KDS, BOM, Kho, Báo cáo)
│   └── assets/     # Style Design System, REST API Client, WebSocket Manager
```

---

## 🚀 Hướng dẫn Cài đặt & Chạy Dự án

### 1. Chạy Backend (Laravel)
```bash
cd Be
composer install
cp .env.example .env
php artisan key:generate

# Cấu hình CSDL MySQL trong file .env
# php artisan migrate
php artisan serve
```
Backend sẽ khởi chạy tại: `http://127.0.0.1:8000`

### 2. Chạy Frontend (FE)
* **Cách 1 (XAMPP Apache):** Bật Apache trên XAMPP Control Panel và truy cập `http://localhost/AICoffeeHub/Fe/`
* **Cách 2:** Mở trực tiếp file `Fe/index.html` trên trình duyệt bất kỳ.

---

## 👥 Thành viên Nhóm 7:
1. **Võ Minh Thuận** – MSSV: 29211123134 (Nhóm trưởng)
2. **Phạm Vũ Hòa** – MSSV: 29211152101
3. **Trần Thanh Tùng** – MSSV: 29214542179
4. **Bùi Vĩnh Luận** – MSSV: 29211228593
5. **Nguyễn Văn Lộc**
