<template>
    <div class="zreport-page-wrapper">
        <!-- Thanh điều khiển trên màn hình (Ẩn khi in) -->
        <div class="control-bar">
            <div class="control-container">
                <button @click="quayLai" class="btn-control btn-back">
                    <i class="fa-solid fa-arrow-left"></i>
                    Quay lại
                </button>
                <div class="control-title">
                    <i class="fa-solid fa-file-invoice-dollar me-2 text-warning"></i>
                    Báo Cáo Kết Ca Bán Hàng (Z-Report)
                </div>
                <button @click="inBaoCao" class="btn-control btn-print">
                    <i class="fa-solid fa-print"></i>
                    In Z-Report (80mm)
                </button>
            </div>
        </div>

        <!-- Khổ giấy in nhiệt Thermal 80mm -->
        <div class="zreport-paper" id="printable-zreport">
            <!-- Header Thương Hiệu -->
            <div class="store-header">
                <div class="store-logo">
                    <i class="fa-solid fa-mug-hot"></i>
                </div>
                <div class="store-name">COFFEEHUB</div>
                <div class="store-subtitle">BÁO CÁO KẾT CA BÁN HÀNG</div>
                <div class="store-code">(Z-REPORT #{{ phien_ca?.ma_phien_ca || '---' }})</div>
                <div class="store-info">
                    Hệ Thống Quản Lý Cà Phê Thông Minh & AI<br />
                    Hotline: 0905.123.456
                </div>
            </div>

            <div class="dash-line"></div>

            <!-- Thông Tin Phiên Ca -->
            <div class="report-meta">
                <div class="meta-row">
                    <span class="meta-label">Mã phiên ca:</span>
                    <span class="meta-value fw-bold">{{ phien_ca?.ma_phien_ca || '---' }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Thu ngân:</span>
                    <span class="meta-value fw-bold">{{ nhan_vien?.ho_va_ten || '---' }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Ca làm việc:</span>
                    <span class="meta-value">{{ ca_lam_viec?.ten_ca || 'Ca linh hoạt' }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Giờ mở ca:</span>
                    <span class="meta-value">{{ formatDateTime(phien_ca?.thoi_gian_mo_ca) }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Giờ chốt ca:</span>
                    <span class="meta-value">{{ phien_ca?.thoi_gian_dong_ca ? formatDateTime(phien_ca.thoi_gian_dong_ca) : 'Chưa chốt' }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Trạng thái:</span>
                    <span class="meta-value fw-bold" :class="phien_ca?.trang_thai === 2 ? 'text-success' : 'text-danger'">
                        {{ phien_ca?.trang_thai === 2 ? 'ĐÃ ĐÓNG CA' : 'ĐANG MỞ' }}
                    </span>
                </div>
            </div>

            <div class="dash-line"></div>

            <!-- PHẦN 1: QUỸ TIỀN ĐẦU CA -->
            <div class="section-title">1. TIỀN ĐẦU CA (KÉT BAN ĐẦU)</div>
            <div class="stat-box">
                <div class="stat-row">
                    <span>Tiền thối ban đầu:</span>
                    <span class="stat-number">{{ formatVND(phien_ca?.tien_dau_ca) }} đ</span>
                </div>
            </div>

            <div class="dash-line"></div>

            <!-- PHẦN 2: DOANH THU BÁN HÀNG TRONG CA -->
            <div class="section-title">2. DOANH THU PHÁT SINH</div>
            <div class="stat-box">
                <div class="stat-row">
                    <span>• Tiền mặt thu được:</span>
                    <span class="stat-number fw-bold">{{ formatVND(phien_ca?.tong_thu_tien_mat) }} đ</span>
                </div>
                <div class="stat-row">
                    <span>• VietQR / Chuyển khoản:</span>
                    <span class="stat-number fw-bold">{{ formatVND(phien_ca?.tong_thu_chuyen_khoan) }} đ</span>
                </div>
                <div class="double-line"></div>
                <div class="stat-row highlight-row">
                    <span class="fw-bold">TỔNG DOANH THU:</span>
                    <span class="stat-number highlight-number">{{ formatVND(phien_ca?.tong_doanh_thu) }} đ</span>
                </div>
            </div>

            <div class="dash-line"></div>

            <!-- PHẦN 3: THỐNG KÊ ĐƠN HÀNG -->
            <div class="section-title">3. GIAO DỊCH ĐƠN HÀNG</div>
            <div class="stat-box">
                <div class="stat-row">
                    <span>Số đơn hoàn tất:</span>
                    <span class="stat-number text-success fw-bold">{{ phien_ca?.so_luong_don_hang || 0 }} đơn</span>
                </div>
                <div class="stat-row">
                    <span>Số đơn hủy / từ chối:</span>
                    <span class="stat-number text-danger fw-bold">{{ phien_ca?.so_luong_don_huy || 0 }} đơn</span>
                </div>
            </div>

            <!-- PHẦN 4: TOP MÓN BÁN CHẠY TRONG CA -->
            <div v-if="san_pham_ban && san_pham_ban.length > 0">
                <div class="dash-line"></div>
                <div class="section-title">4. SẢN PHẨM BÁN TRONG CA</div>
                <table class="report-table">
                    <thead>
                        <tr>
                            <th class="text-left">Món</th>
                            <th class="text-center">SL</th>
                            <th class="text-right">T.Tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(sp, idx) in san_pham_ban" :key="idx">
                            <td class="text-left">{{ sp.ten_san_pham }}</td>
                            <td class="text-center fw-bold">{{ sp.tong_so_luong }}</td>
                            <td class="text-right">{{ formatVND(sp.tong_tien_mon) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="dash-line"></div>

            <!-- PHẦN 5: ĐỐI SOÁT KÉT TIỀN & CHÊNH LỆCH -->
            <div class="section-title">5. ĐỐI SOÁT TIỀN MẶT CUỐI CA</div>
            <div class="stat-box settlement-box">
                <div class="stat-row">
                    <span>Tiền mặt lý thuyết két có:</span>
                    <span class="stat-number">{{ formatVND(phien_ca?.tien_mat_he_thong) }} đ</span>
                </div>
                <div class="stat-hint text-muted">(Tiền đầu ca + Tiền mặt thu được)</div>

                <div class="stat-row mt-1">
                    <span class="fw-bold">Tiền mặt thực tế đếm được:</span>
                    <span class="stat-number fw-bold">{{ formatVND(phien_ca?.tien_mat_thuc_te) }} đ</span>
                </div>

                <div class="double-line"></div>

                <div class="stat-row diff-row">
                    <span class="fw-bold">CHÊNH LỆCH TIỀN:</span>
                    <span class="stat-number fw-bold" :class="getDiffClass(phien_ca?.tien_chenh_lech)">
                        {{ formatDiff(phien_ca?.tien_chenh_lech) }}
                    </span>
                </div>
                <div class="diff-verdict" :class="getDiffBadgeClass(phien_ca?.tien_chenh_lech)">
                    {{ getDiffVerdict(phien_ca?.tien_chenh_lech) }}
                </div>
            </div>

            <!-- Ghi chú giải trình nếu có -->
            <div v-if="phien_ca?.ghi_chu" class="note-section">
                <div class="note-label">Ghi chú bàn giao:</div>
                <div class="note-content">{{ phien_ca.ghi_chu }}</div>
            </div>

            <div class="dash-line"></div>

            <!-- CHỮ KÝ BÀN GIAO -->
            <div class="signatures">
                <div class="sig-box">
                    <div class="sig-title">THU NGÂN BÀN GIAO</div>
                    <div class="sig-subtitle">(Ký và ghi rõ họ tên)</div>
                    <div class="sig-space"></div>
                    <div class="sig-name">{{ nhan_vien?.ho_va_ten || '................................' }}</div>
                </div>
                <div class="sig-box">
                    <div class="sig-title">QUẢN LÝ / NHẬN CA</div>
                    <div class="sig-subtitle">(Ký và ghi rõ họ tên)</div>
                    <div class="sig-space"></div>
                    <div class="sig-name">................................</div>
                </div>
            </div>

            <!-- Footer -->
            <div class="store-footer">
                Thời gian in phiếu: {{ nowStr }}<br />
                Phần mềm quản lý CoffeeHub AI • All rights reserved
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'ZReportPrintView',
    data() {
        return {
            phien_ca: null,
            nhan_vien: null,
            ca_lam_viec: null,
            san_pham_ban: [],
            loading: true,
            nowStr: '',
        };
    },
    mounted() {
        this.nowStr = new Date().toLocaleString('vi-VN');
        const id = this.$route.params.id;
        if (id) {
            this.loadZReport(id);
        }
    },
    methods: {
        loadZReport(id) {
            const token = localStorage.getItem('xs');
            axios.get(`http://127.0.0.1:8000/api/admin/phien-ca/z-report/${id}`, {
                headers: { Authorization: `Bearer ${token}` }
            })
            .then(res => {
                if (res.data.status) {
                    this.phien_ca = res.data.data.phien_ca;
                    this.nhan_vien = res.data.data.nhan_vien;
                    this.ca_lam_viec = res.data.data.ca_lam_viec;
                    this.san_pham_ban = res.data.data.san_pham_ban || [];
                } else {
                    this.$toast?.error(res.data.message || 'Không thể tải Z-Report');
                }
            })
            .catch(err => {
                console.error(err);
                this.$toast?.error('Lỗi khi tải báo cáo Z-Report');
            })
            .finally(() => {
                this.loading = false;
            });
        },
        inBaoCao() {
            window.print();
        },
        quayLai() {
            if (window.history.length > 1) {
                this.$router.back();
            } else {
                this.$router.push('/admin/phien-ca');
            }
        },
        formatVND(num) {
            if (num === null || num === undefined) return '0';
            return Number(num).toLocaleString('vi-VN');
        },
        formatDateTime(dt) {
            if (!dt) return '---';
            const d = new Date(dt);
            return d.toLocaleString('vi-VN', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        },
        formatDiff(diff) {
            const val = Number(diff || 0);
            if (val === 0) return '0 đ (Khớp)';
            if (val > 0) return `+${this.formatVND(val)} đ`;
            return `${this.formatVND(val)} đ`;
        },
        getDiffClass(diff) {
            const val = Number(diff || 0);
            if (val === 0) return 'text-success';
            if (val > 0) return 'text-primary';
            return 'text-danger';
        },
        getDiffBadgeClass(diff) {
            const val = Number(diff || 0);
            if (val === 0) return 'verdict-exact';
            if (val > 0) return 'verdict-surplus';
            return 'verdict-deficit';
        },
        getDiffVerdict(diff) {
            const val = Number(diff || 0);
            if (val === 0) return '✓ KÉT TIỀN KHỚP 100%';
            if (val > 0) return `▲ THỪA TIỀN TRONG KÉT (+${this.formatVND(val)} đ)`;
            return `▼ THIẾU HỤT TIỀN TRONG KÉT (${this.formatVND(val)} đ)`;
        }
    }
};
</script>

<style scoped>
.zreport-page-wrapper {
    background-color: #f1f5f9;
    min-height: 100vh;
    padding-bottom: 50px;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
}

/* Control Bar */
.control-bar {
    position: sticky;
    top: 0;
    z-index: 1000;
    background: #0f172a;
    color: #fff;
    padding: 12px 24px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}
.control-container {
    max-width: 600px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.control-title {
    font-weight: 700;
    font-size: 15px;
    display: flex;
    align-items: center;
}
.btn-control {
    border: none;
    border-radius: 6px;
    padding: 8px 16px;
    font-weight: 600;
    font-size: 13px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
}
.btn-back {
    background: #334155;
    color: #f8fafc;
}
.btn-back:hover {
    background: #475569;
}
.btn-print {
    background: #2563eb;
    color: #fff;
}
.btn-print:hover {
    background: #1d4ed8;
}

/* Thermal Paper */
.zreport-paper {
    width: 80mm;
    margin: 24px auto;
    background: #ffffff;
    padding: 18px 14px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border-radius: 4px;
    color: #000;
    font-size: 12px;
    line-height: 1.4;
}

.store-header {
    text-align: center;
}
.store-logo {
    font-size: 24px;
    color: #d97706;
    margin-bottom: 4px;
}
.store-name {
    font-size: 18px;
    font-weight: 900;
    letter-spacing: 1.5px;
}
.store-subtitle {
    font-size: 13px;
    font-weight: 800;
    margin-top: 2px;
}
.store-code {
    font-size: 11px;
    font-weight: 600;
    color: #475569;
}
.store-info {
    font-size: 10.5px;
    color: #475569;
    margin-top: 4px;
}

.dash-line {
    border-top: 1px dashed #64748b;
    margin: 10px 0;
}
.double-line {
    border-top: 2px double #0f172a;
    margin: 6px 0;
}

.report-meta {
    font-size: 11.5px;
}
.meta-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 3px;
}
.meta-label {
    color: #475569;
}
.meta-value {
    color: #0f172a;
}

.section-title {
    font-weight: 800;
    font-size: 11.5px;
    text-transform: uppercase;
    margin-bottom: 6px;
    color: #0f172a;
}

.stat-box {
    font-size: 12px;
}
.stat-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 4px;
}
.stat-number {
    font-family: 'Consolas', 'Courier New', monospace;
    font-size: 12.5px;
}
.stat-hint {
    font-size: 10px;
    margin-top: -2px;
    margin-bottom: 4px;
}

.highlight-row {
    font-size: 13px;
    color: #0f172a;
}
.highlight-number {
    font-size: 14px;
    font-weight: 800;
    color: #d97706;
}

.report-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 11px;
    margin: 4px 0;
}
.report-table th {
    border-bottom: 1px solid #cbd5e1;
    padding: 3px 0;
    font-size: 10.5px;
    color: #475569;
}
.report-table td {
    padding: 4px 0;
    border-bottom: 1px dotted #f1f5f9;
}

.settlement-box {
    background: #f8fafc;
    border: 1px dashed #cbd5e1;
    border-radius: 4px;
    padding: 8px 10px;
}
.diff-row {
    font-size: 12.5px;
    padding-top: 4px;
}
.diff-verdict {
    text-align: center;
    font-weight: 800;
    font-size: 11px;
    padding: 4px;
    border-radius: 4px;
    margin-top: 6px;
}
.verdict-exact {
    background: #ecfdf5;
    color: #047857;
}
.verdict-surplus {
    background: #eff6ff;
    color: #1d4ed8;
}
.verdict-deficit {
    background: #fef2f2;
    color: #b91c1c;
}

.note-section {
    margin-top: 8px;
    background: #fffbeb;
    border-left: 3px solid #d97706;
    padding: 6px 8px;
    font-size: 11px;
}
.note-label {
    font-weight: 700;
    color: #92400e;
}
.note-content {
    color: #78350f;
    font-style: italic;
}

.signatures {
    display: flex;
    justify-content: space-between;
    margin: 16px 0 10px 0;
    text-align: center;
}
.sig-box {
    width: 48%;
}
.sig-title {
    font-weight: 800;
    font-size: 10px;
}
.sig-subtitle {
    font-size: 9px;
    color: #64748b;
}
.sig-space {
    height: 38px;
}
.sig-name {
    font-size: 10.5px;
    font-weight: 600;
    border-top: 1px dotted #94a3b8;
    padding-top: 4px;
}

.store-footer {
    text-align: center;
    font-size: 9.5px;
    color: #64748b;
    margin-top: 14px;
}

/* @media print: Khổ 80mm tối ưu cho máy in hóa đơn */
@media print {
    body {
        background: #fff !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .control-bar {
        display: none !important;
    }
    .zreport-page-wrapper {
        background: #fff !important;
        padding: 0 !important;
    }
    .zreport-paper {
        width: 100% !important;
        max-width: 80mm !important;
        margin: 0 auto !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        padding: 4px 6px !important;
    }
}
</style>
