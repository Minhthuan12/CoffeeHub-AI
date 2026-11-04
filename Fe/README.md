# CoffeeHub Frontend (FE) - Hệ thống Quản lý Quán Cà phê Tích hợp AI
**Đồ án CDIO (SE447 G) – Đại học Duy Tân – Nhóm 7**

---

## 📁 Cấu trúc Thư mục Frontend (Fe):
```
C:\xampp\htdocs\AICoffeeHub\Fe/
├── index.html                   # Giao diện tổng hợp 6 Màn hình cốt lõi (High-Fidelity Prototype)
├── pages/                       # 6 Màn hình chức năng độc lập (Standalone Pages):
│   ├── 01-customer-menu.html    # MH 1: Menu gọi món QR & Trợ lý AI Barista tại bàn
│   ├── 02-pos-cashier.html      # MH 2: POS Thu ngân 2 Màn hình & Bán tại quầy
│   ├── 03-barista-kds.html      # MH 3: KDS Quầy Barista Realtime (< 0.2s)
│   ├── 04-bom-recipes.html      # MH 4: Cấu hình Định lượng Nguyên liệu (BOM)
│   ├── 05-inventory-ai.html     # MH 5: Quản lý Kho & Dự báo Tiêu thụ AI
│   └── 06-manager-report.html   # MH 6: Báo cáo Doanh thu & Copilot Quản lý
├── assets/
│   ├── css/
│   │   └── style.css            # Design System, Font chữ, Custom Scrollbars
│   ├── js/
│   │   ├── api.js               # Kết nối REST API sang Laravel Backend (AICoffeeHub/Be)
│   │   ├── websocket.js         # Kết nối WebSocket Realtime cho KDS
│   │   └── app.js               # Tiện ích chung (Format VNĐ, Toast thông báo)
│   └── images/                  # Thư mục chứa icon & ảnh sản phẩm
├── package.json                 # Cấu hình npm / Vite (nếu muốn bundle code)
└── README.md                    # Hướng dẫn chi tiết
```

---

## 🚀 Cách chạy Frontend:

### Cách 1: Chạy qua XAMPP Apache (Đơn giản nhất, không cần cài npm)
1. Bật **Apache** trên phần mềm **XAMPP Control Panel**.
2. Mở trình duyệt web bất kỳ (Chrome, Cốc Cốc, Edge) và truy cập:
   - **Trang chủ tổng hợp:** [http://localhost/AICoffeeHub/Fe/](http://localhost/AICoffeeHub/Fe/)
   - Hoặc nhấp đúp trực tiếp vào file `index.html` trên máy tính.

### Cách 2: Chạy độc lập từng màn hình
Mở trực tiếp thư mục `pages/`:
- Khách gọi món QR: [http://localhost/AICoffeeHub/Fe/pages/01-customer-menu.html](http://localhost/AICoffeeHub/Fe/pages/01-customer-menu.html)
- Thu ngân POS: [http://localhost/AICoffeeHub/Fe/pages/02-pos-cashier.html](http://localhost/AICoffeeHub/Fe/pages/02-pos-cashier.html)
- Quầy Bar KDS: [http://localhost/AICoffeeHub/Fe/pages/03-barista-kds.html](http://localhost/AICoffeeHub/Fe/pages/03-barista-kds.html)

---

## 🔗 Kết nối sang Backend Laravel (`AICoffeeHub/Be`):
Module `assets/js/api.js` đã được định cấu hình sẵn các hàm gọi API chuẩn:
- Mặc định kết nối tới: `http://127.0.0.1:8000/api` (khi chạy `php artisan serve`)
- Hoặc `http://localhost/AICoffeeHub/Be/public/api` (khi chạy qua XAMPP).
