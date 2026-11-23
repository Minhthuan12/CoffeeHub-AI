<template>
    <div class="bill-page-wrapper">
        <!-- Control Bar (hidden on print) -->
        <div class="control-bar">
            <div class="control-container">
                <button @click="quayLai" class="btn-control btn-back">
                    <i class="fa-solid fa-arrow-left"></i>
                    Quay lại
                </button>
                <div class="control-title">Xem Trước Hóa Đơn</div>
                <button @click="inHoaDon" class="btn-control btn-print">
                    <i class="fa-solid fa-print"></i>
                    In Hóa Đơn
                </button>
            </div>
        </div>

        <!-- Thermal Paper Bill -->
        <div class="bill">
            <!-- Header quán -->
            <div class="store-header">
                <div class="store-logo">
                    <i class="fa-solid fa-mug-hot"></i>
                </div>
                <div class="store-name">CAFEHUB</div>
                <div class="store-subtitle">HÓA ĐƠN BÁN HÀNG</div>
                <div class="store-info">
                    Sinh Viên DTU - Đà Nẵng<br />
                    Hotline: 0905.123.456
                </div>
            </div>

            <div class="dash-line"></div>

            <!-- Thông tin hóa đơn -->
            <div class="bill-info">
                <div class="info-row">
                    <span class="info-label">Số HĐ:</span>
                    <span class="info-value">{{ hoa_don?.ma_hoa_don || '---' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ngày bán:</span>
                    <span class="info-value">{{ hoa_don?.created_at ? formatDate(hoa_don.created_at) : '---' }}</span>
                </div>
                <div class="info-row">      
                    <span class="info-label">Thu ngân:</span>
                    <span class="info-value">{{ thong_tin_thu_ngan?.ho_va_ten || '---' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Khách hàng:</span>
                    <span class="info-value">{{ thong_tin_khach_hang?.ho_va_ten || '---' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">SĐT KH:</span>
                    <span class="info-value">{{ thong_tin_khach_hang?.so_dien_thoai || '---' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Bàn:</span>
                    <span class="info-value highlight-ban">{{ thong_tin_ban?.ten_ban || '---' }}</span>
                </div>
            </div>  

            <div class="dash-line"></div>

            <!-- Bảng chi tiết món -->
            <table class="product-table">
                <thead>
                    <tr>
                        <th class="text-left">Tên hàng</th>
                        <th class="text-center">SL</th>
                        <th class="text-right">Đơn giá</th>
                        <th class="text-right">T.Tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(value, index) in thong_tin_chi_tiet_hoa_don" :key="index">
                        <tr>
                            <td class="product-name">
                                <div>{{ value.ten_san_pham }}</div>
                                <div v-if="value.tuy_chon" style="font-size: 11px; font-weight: normal; color: #555; font-style: italic; white-space: normal; word-break: break-word;">
                                    {{ formatTuyChon(value.tuy_chon) }}
                                </div>
                            </td>
                            <td class="text-center">{{ value.so_luong}}</td>
                            <td class="text-right">{{ formatVND(value.don_gia) }}</td>
                            <td class="text-right">{{ formatVND(value.so_luong * value.don_gia) }}</td>
                        </tr>
                    </template>
                </tbody>
            </table>

            <div class="dash-line"></div>

            <!-- Tổng kết -->
            <div class="summary">
                <div class="summary-row">
                    <span>Tổng số lượng</span>
                    <b>{{  tinhTongSoLuong() }}</b>
                </div>
                <div class="summary-row" v-if="hoa_don?.so_tien_giam > 0">
                    <span>Tiền hàng</span>
                    <b>{{ hoa_don?.tien_truoc_giam ? formatVND(hoa_don.tien_truoc_giam) : '---' }} đ</b>
                </div>
                <div class="summary-row" v-if="hoa_don?.so_tien_giam > 0">
                    <span>Giảm giá</span>
                    <b>- {{  hoa_don?.so_tien_giam ? formatVND(hoa_don.so_tien_giam) : '---'  }} đ</b>
                </div>
                <div class="total-row">
                    <span>TỔNG THANH TOÁN</span>
                    <strong>{{  hoa_don?.tong_tien ? formatVND(hoa_don.tong_tien) : '---' }} đ</strong>
                </div>
            </div>

            <div class="dash-line"></div>

            <!-- Hình thức thanh toán -->
            <div class="payment-info">
                <div class="payment-method">Hình thức thanh toán: <strong>Tiền mặt</strong></div>
                <div class="thanks-msg">Cảm ơn quý khách đã sử dụng dịch vụ!</div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <div class="thanks">Xin cảm ơn quý khách!</div>
                <div>Hẹn gặp lại quý khách</div>

                <!-- QR Code Section -->
                <div class="qr-container">
                    <img :src="`https://img.vietqr.io/image/MB-06468111-qr_only.png?amount=${hoa_don?.tong_tien}&addInfo=${hoa_don?.ma_hoa_don}&accountName=VO%20MINH%20THUAN`" alt="QR Code"
                        class="qr-code" />
                    <div class="qr-text">Quét để thanh toán / Phản hồi</div>
                </div>

                <!-- Stylized CSS Barcode -->
                <div class="barcode-container">
                    <div class="barcode-bars">
                        <span class="bar thick"></span>
                        <span class="bar thin"></span>
                        <span class="bar space"></span>
                        <span class="bar medium"></span>
                        <span class="bar thin"></span>
                        <span class="bar space"></span>
                        <span class="bar thick"></span>
                        <span class="bar medium"></span>
                        <span class="bar thin"></span>
                        <span class="bar space"></span>
                        <span class="bar thick"></span>
                        <span class="bar thin"></span>
                        <span class="bar medium"></span>
                        <span class="bar thin"></span>
                        <span class="bar thick"></span>
                    </div>
                    <div class="barcode-label">{{ hoa_don?.ma_hoa_don || '---' }}</div>
                </div>
            </div>
        </div>
    </div>  
</template>

<script>
import axios from 'axios';
import { formatTuyChonText } from '../../../config/tuyChon.js';

export default {
    data() {
        return {
            id_hoa_don : null,
            hoa_don : null, // object
            thong_tin_thu_ngan : null, // object
            thong_tin_khach_hang : null, // object
            thong_tin_ban : null, // object
            thong_tin_chi_tiet_hoa_don : null, // array
        }
    },
    mounted() {
        this.layDuLieu();
    },
    methods: {
        formatTuyChon(tc) {
            return formatTuyChonText(tc);
        },
        layDuLieu() {
            this.id_hoa_don = this.$route.params.id;
            axios
            .get('http://127.0.0.1:8000/api/admin/hoa-don/in-hoa-don/'+ this.id_hoa_don, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('xs')}`
                }
            })
            .then((res) => {
                this.hoa_don = res.data.hoa_don;
                this.thong_tin_thu_ngan = res.data.thong_tin_thu_ngan;
                this.thong_tin_khach_hang = res.data.thong_tin_khach_hang;
                this.thong_tin_ban = res.data.thong_tin_ban;
                this.thong_tin_chi_tiet_hoa_don = res.data.thong_tin_chi_tiet_hoa_don;
            })
            .catch((err) => {
                console.error(err);
            });
        },
        tinhTongSoLuong() {
            let tong = 0;
            if(this.thong_tin_chi_tiet_hoa_don){
                this.thong_tin_chi_tiet_hoa_don.forEach(item => {
                    tong += item.so_luong;
                });
            }
            return tong;
        },  
        inHoaDon() {
            window.print();
            this.$router.push('/admin/ban-hang');
            setTimeout(() => {
                window.close();
            }, 300);
        },
        quayLai() {
            this.$router.push('/admin/ban-hang');
            setTimeout(() => {
                window.close();
            }, 300);
        },
        formatVND(value) {
            return Number(value).toLocaleString('vi-VN');
        },
        formatDate(value) {
            if (!value) return '';
            const date = new Date(value);
            if (isNaN(date.getTime())) return value;
            const d = String(date.getDate()).padStart(2, '0');
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const y = date.getFullYear();
            return `${d}/${m}/${y}`;
        },
        formatDateTime(value) {
            if (!value) return '';
            const date = new Date(value);
            if (isNaN(date.getTime())) return value;
            const d = String(date.getDate()).padStart(2, '0');
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const y = date.getFullYear();
            const h = String(date.getHours()).padStart(2, '0');
            const min = String(date.getMinutes()).padStart(2, '0');
            const sec = String(date.getSeconds()).padStart(2, '0');
            return `${d}/${m}/${y} ${h}:${min}:${sec}`;
        },
    },
}
</script>

<style>
/* Global page styles for previews */
html,
body {
    margin: 0;
    padding: 0;
    width: 100%;
    min-height: 100%;
}

.bill-page-wrapper {
    background-color: #f3f4f6;
    min-height: 100vh;
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    color: #374151;
    box-sizing: border-box;
}

/* Control Bar */
.control-bar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background-color: #ffffff;
    border-bottom: 1px solid #e5e7eb;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    z-index: 1000;
    height: 60px;
}

.control-container {
    max-width: 800px;
    margin: 0 auto;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
}

.control-title {
    font-size: 16px;
    font-weight: 700;
    color: #374151;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-control {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
}

.btn-back {
    background-color: #f3f4f6;
    color: #4b5563;
}

.btn-back:hover {
    background-color: #e5e7eb;
    color: #1f2937;
}

.btn-print {
    background-color: #10b981;
    color: #ffffff;
}

.btn-print:hover {
    background-color: #059669;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
}

/* Thermal Paper Bill */
.bill {
    width: 320px;
    background: #ffffff;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 24px 20px;
    margin: 70px auto 40px auto;
    box-sizing: border-box;
    font-size: 13px;
    line-height: 1.5;
}

.store-header {
    text-align: center;
    margin-bottom: 12px;
}

.store-logo {
    font-size: 24px;
    color: #374151;
    margin-bottom: 4px;
}

.store-name {
    font-size: 18px;
    font-weight: 700;
    letter-spacing: 0.5px;
    color: #1f2937;
}

.store-subtitle {
    margin-top: 4px;
    font-size: 12px;
    font-weight: 600;
    color: #4b5563;
    letter-spacing: 0.5px;
}

.store-info {
    margin-top: 6px;
    font-size: 11px;
    color: #6b7280;
    line-height: 1.4;
}

.dash-line {
    border-top: 1px dashed #9ca3af;
    margin: 12px 0;
}

.bill-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    margin: 4px 0;
    gap: 8px;
}

.info-label {
    color: #4b5563;
    white-space: nowrap;
}

.info-value {
    text-align: right;
    font-weight: 500;
    color: #1f2937;
    padding-right: 8px;
}

.highlight-ban {
    color: #10b981;
    font-weight: 600;
    font-size: 13px;
}

/* Product Table */
.product-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
    margin: 10px 0;
    font-variant-numeric: tabular-nums;
}

.text-right {
    text-align: right;
    padding-right: 8px !important;
}

.text-center {
    text-align: center;
}

.text-left {
    text-align: left;
}

.product-table th {
    padding: 6px 2px;
    border-bottom: 1px solid #4b5563;
    font-weight: 600;
    color: #374151;
    text-transform: uppercase;
    font-size: 11px;
}

.product-table td {
    padding: 6px 2px;
    border-bottom: 1px dashed #e5e7eb;
    vertical-align: top;
    color: #1f2937;
}

.product-table tr:last-child td {
    border-bottom: none;
}

.product-name {
    font-weight: 500;
    word-break: break-word;
}

/* Summary section */
.summary {
    font-size: 13px;
    font-variant-numeric: tabular-nums;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin: 6px 0;
    color: #4b5563;
}

.summary-row b {
    color: #1f2937;
    font-weight: 600;
    padding-right: 8px;
}

.total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #4b5563;
    font-size: 13px;
    font-weight: 600;
    color: #1f2937;
}

.total-row strong {
    font-size: 15px;
    font-weight: 600;
    color: #10b981;
    padding-right: 8px;
}

/* Payment & Thanks */
.payment-info {
    text-align: center;
    font-size: 11px;
    color: #4b5563;
    margin: 12px 0;
}

.payment-method {
    margin-bottom: 4px;
}

.thanks-msg {
    font-style: italic;
}

/* Footer / Barcode */
.footer {
    text-align: center;
    margin-top: 8px;
}

.thanks {
    font-weight: 600;
    font-size: 13px;
    color: #1f2937;
    margin-bottom: 12px;
}

.qr-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 15px;
    margin-bottom: 15px;
}

.qr-code {
    width: 140px;
    height: 140px;
    border: 1px solid #e5e7eb;
    padding: 4px;
    background: #fff;
    border-radius: 4px;
}

.qr-text {
    font-size: 10px;
    color: #4b5563;
    margin-top: 4px;
    font-style: italic;
}

.barcode-container {
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    margin-top: 4px;
}

.barcode-bars {
    display: flex;
    align-items: stretch;
    height: 35px;
    padding: 0 10px;
}

.bar {
    background-color: #000000;
}

.bar.space {
    width: 2px;
    background-color: transparent;
}

.bar.thin {
    width: 1px;
}

.bar.medium {
    width: 2px;
}

.bar.thick {
    width: 4px;
}

.barcode-label {
    font-size: 10px;
    color: #4b5563;
    margin-top: 4px;
    font-family: monospace;
    letter-spacing: 2px;
}

/* Print Styles */
@media print {

    /* Hide all layout elements */
    .header-wrapper,
    .sidebar-wrapper,
    .overlay,
    .back-to-top,
    .page-footer,
    .switcher-wrapper {
        display: none !important;
    }

    .wrapper,
    .page-wrapper,
    .page-content {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }

    /* Hide scrollbars, backgrounds, wrappers */
    html,
    body,
    .bill-page-wrapper {
        background: #ffffff !important;
        padding: 0 !important;
        margin: 0 !important;
        width: 80mm !important;
    }

    .control-bar {
        display: none !important;
    }

    .bill {
        width: 80mm !important;
        border: none !important;
        box-shadow: none !important;
        padding: 10px !important;
        margin: 0 !important;
    }

    /* Keep font colors solid black for printing */
    .store-name,
    .store-subtitle,
    .info-value,
    .total-row,
    .thanks,
    .product-table th,
    .qr-text {
        color: #000000 !important;
    }

    .highlight-ban,
    .total-row strong {
        color: #000000 !important;
    }

    .qr-code {
        border-color: #000000 !important;
    }

    @page {
        margin: 0;
        size: 80mm auto;
    }
}
</style>
