<template>
    <div class="phien-ca-container p-4">
        <!-- HEADER & TITLE -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h4 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                    <span class="badge-icon-header bg-amber-100 text-amber-700 rounded-3 p-2 d-inline-flex">
                        <i class="fa-solid fa-clock-rotate-left fs-5"></i>
                    </span>
                    Quản Lý Giao Ca & Phiên Bán Hàng (Z-Report)
                </h4>
                <p class="text-secondary small mb-0">
                    Theo dõi lịch sử giao nhận ca, kiểm đếm két tiền mặt, doanh thu VietQR và in báo cáo Z-Report.
                </p>
            </div>
            <div class="d-flex gap-2">
                <button v-if="!activeShift" @click="openMoCaModal" class="btn btn-warning fw-semibold shadow-sm text-dark d-flex align-items-center gap-2 px-3">
                    <i class="fa-solid fa-play"></i>
                    <span>Mở Ca Bán Hàng</span>
                </button>
                <button v-else @click="openChotCaModal(activeShift)" class="btn btn-danger fw-semibold shadow-sm d-flex align-items-center gap-2 px-3">
                    <i class="fa-solid fa-stopwatch"></i>
                    <span>Chốt Ca Hiện Tại (#{{ activeShift.ma_phien_ca }})</span>
                </button>
                <router-link to="/admin/ban-hang" class="btn btn-primary fw-semibold shadow-sm d-flex align-items-center gap-2 px-3">
                    <i class="fa-solid fa-cash-register"></i>
                    <span>Màn Hình POS</span>
                </router-link>
            </div>
        </div>

        <!-- KPI SUMMARY CARDS -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card card-kpi border-0 shadow-sm rounded-4 p-3 h-100 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-secondary small fw-medium">Doanh Thu Đã Chốt Hôm Nay</div>
                            <div class="fs-4 fw-bold text-dark mt-1">{{ formatVND(summary.doanh_thu_hom_nay) }} đ</div>
                        </div>
                        <div class="kpi-icon bg-emerald-50 text-emerald-600 rounded-circle p-3">
                            <i class="fa-solid fa-money-bill-wave fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card card-kpi border-0 shadow-sm rounded-4 p-3 h-100 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-secondary small fw-medium">Ca Đang Mở (Live)</div>
                            <div class="fs-4 fw-bold text-amber-600 mt-1">{{ summary.ca_dang_mo }} ca đang trực</div>
                        </div>
                        <div class="kpi-icon bg-amber-50 text-amber-600 rounded-circle p-3">
                            <i class="fa-solid fa-shop fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card card-kpi border-0 shadow-sm rounded-4 p-3 h-100 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-secondary small fw-medium">Chênh Lệch Két Hôm Nay</div>
                            <div class="fs-4 fw-bold mt-1" :class="summary.chenh_lech_hom_nay === 0 ? 'text-success' : (summary.chenh_lech_hom_nay > 0 ? 'text-primary' : 'text-danger')">
                                {{ formatDiff(summary.chenh_lech_hom_nay) }}
                            </div>
                        </div>
                        <div class="kpi-icon bg-purple-50 text-purple-600 rounded-circle p-3">
                            <i class="fa-solid fa-scale-balanced fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card card-kpi border-0 shadow-sm rounded-4 p-3 h-100 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-secondary small fw-medium">Trạng Thái Thu Ngân Bạn</div>
                            <div class="fw-bold mt-1" :class="activeShift ? 'text-success' : 'text-secondary'">
                                <i :class="activeShift ? 'fa-solid fa-circle-check text-success me-1' : 'fa-solid fa-circle-xmark text-secondary me-1'"></i>
                                {{ activeShift ? `Đang trực ca (#${activeShift.ma_phien_ca})` : 'Chưa mở ca' }}
                            </div>
                        </div>
                        <div class="kpi-icon bg-blue-50 text-blue-600 rounded-circle p-3">
                            <i class="fa-solid fa-user-clock fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTER TOOLBAR -->
        <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label small fw-semibold text-secondary mb-1">Từ ngày</label>
                    <input type="date" v-model="filters.tu_ngay" class="form-control form-control-sm rounded-3">
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label small fw-semibold text-secondary mb-1">Đến ngày</label>
                    <input type="date" v-model="filters.den_ngay" class="form-control form-control-sm rounded-3">
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <label class="form-label small fw-semibold text-secondary mb-1">Thu ngân</label>
                    <select v-model="filters.id_nhan_vien" class="form-select form-select-sm rounded-3">
                        <option value="">Tất cả thu ngân</option>
                        <option v-for="nv in filterOptions.nhan_viens" :key="nv.id" :value="nv.id">{{ nv.ho_va_ten }}</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <label class="form-label small fw-semibold text-secondary mb-1">Trạng thái</label>
                    <select v-model="filters.trang_thai" class="form-select form-select-sm rounded-3">
                        <option value="">Tất cả</option>
                        <option value="1">Đang mở (Live)</option>
                        <option value="2">Đã chốt ca</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-2">
                    <button @click="loadData(1)" class="btn btn-sm btn-dark w-100 rounded-3 fw-semibold">
                        <i class="fa-solid fa-filter me-1"></i>Lọc
                    </button>
                    <button @click="resetFilters" class="btn btn-sm btn-outline-secondary rounded-3" title="Đặt lại">
                        <i class="fa-solid fa-rotate"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- MAIN TABLE -->
        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light table-head-custom">
                        <tr>
                            <th class="ps-3 py-3 text-secondary text-uppercase small">Mã Phiên Ca</th>
                            <th class="py-3 text-secondary text-uppercase small">Thu Ngân</th>
                            <th class="py-3 text-secondary text-uppercase small">Ca Làm</th>
                            <th class="py-3 text-secondary text-uppercase small">Thời Gian Mở / Chốt</th>
                            <th class="py-3 text-secondary text-uppercase small text-end">Tiền Đầu Ca</th>
                            <th class="py-3 text-secondary text-uppercase small text-end">Tiền Mặt Thu</th>
                            <th class="py-3 text-secondary text-uppercase small text-end">VietQR Thu</th>
                            <th class="py-3 text-secondary text-uppercase small text-end">Tổng Doanh Thu</th>
                            <th class="py-3 text-secondary text-uppercase small text-end">Thực Đếm</th>
                            <th class="py-3 text-secondary text-uppercase small text-center">Chênh Lệch</th>
                            <th class="py-3 text-secondary text-uppercase small text-center">Trạng Thái</th>
                            <th class="pe-3 py-3 text-secondary text-uppercase small text-end">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="loading">
                            <td colspan="12" class="text-center py-5 text-secondary">
                                <div class="spinner-border text-amber-500 mb-2" role="status"></div>
                                <div>Đang tải dữ liệu phiên ca...</div>
                            </td>
                        </tr>
                        <tr v-else-if="!listPhienCa.data || listPhienCa.data.length === 0">
                            <td colspan="12" class="text-center py-5 text-secondary">
                                <i class="fa-solid fa-inbox fs-2 text-muted mb-2 d-block"></i>
                                Chưa có phiên ca nào phù hợp với bộ lọc.
                            </td>
                        </tr>
                        <tr v-for="item in listPhienCa.data" :key="item.id">
                            <td class="ps-3">
                                <span class="badge bg-amber-50 text-amber-800 border border-amber-200 fw-bold px-2 py-1">
                                    {{ item.ma_phien_ca }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ item.nhan_vien?.ho_va_ten || '---' }}</div>
                                <div class="text-muted small">{{ item.nhan_vien?.email }}</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ item.ca_lam_viec?.ten_ca || 'Linh hoạt' }}
                                </span>
                            </td>
                            <td>
                                <div class="small">
                                    <span class="text-success"><i class="fa-solid fa-arrow-right-to-bracket me-1"></i>{{ formatDateTime(item.thoi_gian_mo_ca) }}</span>
                                </div>
                                <div class="small text-muted" v-if="item.thoi_gian_dong_ca">
                                    <span class="text-danger"><i class="fa-solid fa-arrow-right-from-bracket me-1"></i>{{ formatDateTime(item.thoi_gian_dong_ca) }}</span>
                                </div>
                                <div class="small text-amber-600 fw-medium" v-else>
                                    <i class="fa-solid fa-spinner fa-spin me-1"></i>Đang phục vụ
                                </div>
                            </td>
                            <td class="text-end fw-medium">{{ formatVND(item.tien_dau_ca) }} đ</td>
                            <td class="text-end text-success fw-medium">{{ formatVND(item.tong_thu_tien_mat) }} đ</td>
                            <td class="text-end text-primary fw-medium">{{ formatVND(item.tong_thu_chuyen_khoan) }} đ</td>
                            <td class="text-end fw-bold text-dark">{{ formatVND(item.tong_doanh_thu) }} đ</td>
                            <td class="text-end fw-semibold">
                                {{ item.tien_mat_thuc_te !== null ? formatVND(item.tien_mat_thuc_te) + ' đ' : '---' }}
                            </td>
                            <td class="text-center">
                                <span v-if="item.trang_thai === 2" class="badge rounded-pill px-2.5 py-1 small fw-bold" :class="getDiffBadge(item.tien_chenh_lech)">
                                    {{ formatDiff(item.tien_chenh_lech) }}
                                </span>
                                <span v-else class="badge bg-light text-muted border">Chờ chốt</span>
                            </td>
                            <td class="text-center">
                                <span v-if="item.trang_thai === 1" class="badge bg-warning text-dark border border-warning px-2.5 py-1">
                                    <i class="fa-solid fa-circle-dot me-1 text-danger"></i>Đang Mở
                                </span>
                                <span v-else class="badge bg-success text-white px-2.5 py-1">
                                    <i class="fa-solid fa-check me-1"></i>Đã Chốt
                                </span>
                            </td>
                            <td class="pe-3 text-end">
                                <div class="btn-group">
                                    <router-link :to="`/admin/phien-ca/z-report/${item.id}`" class="btn btn-sm btn-outline-primary" title="In Z-Report">
                                        <i class="fa-solid fa-print"></i>
                                    </router-link>
                                    <button @click="xemChiTiet(item)" class="btn btn-sm btn-outline-secondary" title="Xem Chi Tiết">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <button v-if="item.trang_thai === 1" @click="openChotCaModal(item)" class="btn btn-sm btn-danger" title="Chốt Ca Này">
                                        <i class="fa-solid fa-stopwatch"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div v-if="listPhienCa.total > listPhienCa.per_page" class="d-flex justify-content-between align-items-center p-3 border-top">
                <div class="text-muted small">
                    Hiển thị {{ listPhienCa.from }} - {{ listPhienCa.to }} trên tổng số {{ listPhienCa.total }} phiên ca
                </div>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-secondary" :disabled="listPhienCa.current_page === 1" @click="loadData(listPhienCa.current_page - 1)">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <button class="btn btn-outline-secondary disabled">{{ listPhienCa.current_page }} / {{ listPhienCa.last_page }}</button>
                    <button class="btn btn-outline-secondary" :disabled="listPhienCa.current_page === listPhienCa.last_page" @click="loadData(listPhienCa.current_page + 1)">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- MODAL: MỞ CA LÀM VIỆC -->
        <div class="modal fade" id="moCaModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header bg-amber-500 text-dark border-0 rounded-top-4">
                        <h5 class="modal-title fw-bold">
                            <i class="fa-solid fa-play me-2"></i>Mở Ca Bán Hàng Mới
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert alert-info small border-0 rounded-3 mb-3">
                            <i class="fa-solid fa-circle-info me-1"></i>
                            Thu ngân cần nhập số tiền mặt có sẵn trong két để hệ thống đối soát chính xác khi giao ca cuối ngày.
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">Ca làm việc đăng ký</label>
                            <select v-model="formMoCa.id_ca_lam_viec" class="form-select rounded-3">
                                <option value="">-- Chọn ca làm việc (hoặc để trống) --</option>
                                <option v-for="ca in filterOptions.ca_lam_viecs" :key="ca.id" :value="ca.id">
                                    {{ ca.ten_ca }} ({{ ca.gio_bat_dau }} - {{ ca.gio_ket_thuc }})
                                </option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">Tiền mặt đầu ca (Két thối tiền ban đầu) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" v-model="formMoCa.tien_dau_ca" class="form-control rounded-start-3" placeholder="Ví dụ: 1000000" min="0" step="10000">
                                <span class="input-group-text rounded-end-3 bg-light fw-bold">VNĐ</span>
                            </div>
                            <div class="small text-muted mt-1">{{ formatVND(formMoCa.tien_dau_ca) }} đ</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">Ghi chú mở ca</label>
                            <textarea v-model="formMoCa.ghi_chu" class="form-control rounded-3" rows="2" placeholder="Tình trạng két, bàn giao từ ca trước (nếu có)..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-3 pt-0">
                        <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" @click="submitMoCa" class="btn btn-warning rounded-3 fw-bold text-dark px-4" :disabled="submitting">
                            <i class="fa-solid fa-check me-1"></i>Xác Nhận Mở Ca
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL: CHỐT CA & KẾT TIỀN (Z-REPORT) -->
        <div class="modal fade" id="chotCaModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header bg-dark text-white border-0 rounded-top-4">
                        <h5 class="modal-title fw-bold">
                            <i class="fa-solid fa-stopwatch me-2 text-warning"></i>
                            Chốt Ca Bán Hàng & Bàn Giao Két Tiền (Z-Report)
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4" v-if="previewChotCa">
                        <!-- BANNER THÔNG TIN PHIÊN CA -->
                        <div class="bg-light p-3 rounded-3 mb-3 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-amber-100 text-amber-800 me-2">{{ previewChotCa.phien_ca?.ma_phien_ca }}</span>
                                <span class="fw-bold">{{ previewChotCa.phien_ca?.nhan_vien?.ho_va_ten }}</span>
                                <span class="text-muted ms-2">({{ previewChotCa.phien_ca?.ca_lam_viec?.ten_ca || 'Ca linh hoạt' }})</span>
                            </div>
                            <div class="small text-muted">
                                Giờ mở: <span class="text-dark fw-semibold">{{ formatDateTime(previewChotCa.phien_ca?.thoi_gian_mo_ca) }}</span>
                            </div>
                        </div>

                        <!-- 3 KHỐI DOANH THU HỆ THỐNG TÍNH -->
                        <div class="row g-2 mb-3">
                            <div class="col-4">
                                <div class="p-2.5 bg-light rounded-3 text-center border">
                                    <div class="small text-secondary">Tiền Đầu Ca (Két)</div>
                                    <div class="fw-bold text-dark fs-6 mt-1">{{ formatVND(previewChotCa.tien_dau_ca) }} đ</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2.5 bg-emerald-50 rounded-3 text-center border border-emerald-100">
                                    <div class="small text-emerald-800">Tiền Mặt Thu Trong Ca</div>
                                    <div class="fw-bold text-emerald-700 fs-6 mt-1">+{{ formatVND(previewChotCa.tong_thu_tien_mat) }} đ</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2.5 bg-blue-50 rounded-3 text-center border border-blue-100">
                                    <div class="small text-blue-800">VietQR Thu Trong Ca</div>
                                    <div class="fw-bold text-blue-700 fs-6 mt-1">+{{ formatVND(previewChotCa.tong_thu_chuyen_khoan) }} đ</div>
                                </div>
                            </div>
                        </div>

                        <!-- KHỐI ĐỐI SOÁT KIỂM ĐẾM TIỀN MẶT -->
                        <div class="card border border-warning rounded-4 p-3 bg-amber-50/30 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-dark">Tiền mặt hệ thống tính phải có trong két:</span>
                                <span class="fs-5 fw-bold text-primary">{{ formatVND(previewChotCa.tien_mat_he_thong) }} đ</span>
                            </div>
                            <div class="small text-muted mb-3">
                                (= {{ formatVND(previewChotCa.tien_dau_ca) }} đ đầu ca + {{ formatVND(previewChotCa.tong_thu_tien_mat) }} đ tiền mặt bán được)
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">
                                    <i class="fa-solid fa-coins text-warning me-1"></i>
                                    Tiền mặt THỰC TẾ kiểm đếm được trong két: <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-lg">
                                    <input type="number" v-model="formChotCa.tien_mat_thuc_te" class="form-control rounded-start-3 fw-bold text-dark fs-5" placeholder="Nhập tổng tiền mặt đếm được" min="0" step="1000">
                                    <span class="input-group-text bg-white fw-bold rounded-end-3">VNĐ</span>
                                </div>
                                <div class="small text-muted mt-1">{{ formatVND(formChotCa.tien_mat_thuc_te) }} đ</div>
                            </div>

                            <!-- KẾT QUẢ CHÊNH LỆCH REALTIME -->
                            <div class="p-3 rounded-3 d-flex justify-content-between align-items-center" :class="calcDiffVerdictClass">
                                <div>
                                    <div class="fw-bold fs-6">{{ calcDiffVerdictTitle }}</div>
                                    <div class="small">{{ calcDiffVerdictDesc }}</div>
                                </div>
                                <div class="fs-4 fw-bold">
                                    {{ formatDiff(calcDiffVal) }}
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">Ghi chú giải trình chênh lệch / Bàn giao ca</label>
                            <textarea v-model="formChotCa.ghi_chu" class="form-control rounded-3" rows="2" placeholder="Giải trình lý do tiền thừa/thiếu, tiền rách, bàn giao ca..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-3 pt-0">
                        <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" @click="submitChotCa" class="btn btn-danger rounded-3 fw-bold px-4" :disabled="submitting">
                            <i class="fa-solid fa-lock me-1"></i>Xác Nhận Chốt Ca & In Z-Report
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL: XEM CHI TIẾT PHIÊN CA -->
        <div class="modal fade" id="chiTietModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-4 border-0 shadow" v-if="selectedItem">
                    <div class="modal-header bg-light border-0 rounded-top-4">
                        <h5 class="modal-title fw-bold text-dark">
                            Chi Tiết Phiên Ca #{{ selectedItem.ma_phien_ca }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <div class="text-secondary small">Thu ngân:</div>
                                <div class="fw-bold">{{ selectedItem.nhan_vien?.ho_va_ten }} ({{ selectedItem.nhan_vien?.email }})</div>
                            </div>
                            <div class="col-6">
                                <div class="text-secondary small">Ca làm việc:</div>
                                <div class="fw-bold">{{ selectedItem.ca_lam_viec?.ten_ca || 'Linh hoạt' }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-secondary small">Thời gian mở ca:</div>
                                <div>{{ formatDateTime(selectedItem.thoi_gian_mo_ca) }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-secondary small">Thời gian chốt ca:</div>
                                <div>{{ selectedItem.thoi_gian_dong_ca ? formatDateTime(selectedItem.thoi_gian_dong_ca) : 'Đang hoạt động' }}</div>
                            </div>
                            <div class="col-4">
                                <div class="text-secondary small">Tiền đầu ca:</div>
                                <div class="fw-bold">{{ formatVND(selectedItem.tien_dau_ca) }} đ</div>
                            </div>
                            <div class="col-4">
                                <div class="text-secondary small">Tổng doanh thu:</div>
                                <div class="fw-bold text-dark">{{ formatVND(selectedItem.tong_doanh_thu) }} đ</div>
                            </div>
                            <div class="col-4">
                                <div class="text-secondary small">Chênh lệch két:</div>
                                <div class="fw-bold" :class="selectedItem.tien_chenh_lech === 0 ? 'text-success' : 'text-danger'">
                                    {{ formatDiff(selectedItem.tien_chenh_lech) }}
                                </div>
                            </div>
                        </div>

                        <div v-if="selectedItem.ghi_chu" class="alert alert-warning small border-0 mb-3">
                            <span class="fw-bold">Ghi chú:</span> {{ selectedItem.ghi_chu }}
                        </div>

                        <div class="text-end">
                            <router-link :to="`/admin/phien-ca/z-report/${selectedItem.id}`" class="btn btn-primary rounded-3 fw-bold">
                                <i class="fa-solid fa-print me-1"></i>Xem & In Báo Cáo Z-Report
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'AdminPhienCaView',
    data() {
        return {
            loading: true,
            submitting: false,
            listPhienCa: { data: [], total: 0, current_page: 1, per_page: 15 },
            activeShift: null,
            summary: {
                doanh_thu_hom_nay: 0,
                ca_dang_mo: 0,
                chenh_lech_hom_nay: 0,
            },
            filters: {
                tu_ngay: '',
                den_ngay: '',
                id_nhan_vien: '',
                trang_thai: '',
                id_ca_lam_viec: '',
            },
            filterOptions: {
                nhan_viens: [],
                ca_lam_viecs: [],
            },
            formMoCa: {
                tien_dau_ca: 0,
                id_ca_lam_viec: '',
                ghi_chu: '',
            },
            formChotCa: {
                id_phien_ca: null,
                tien_mat_thuc_te: 0,
                ghi_chu: '',
            },
            previewChotCa: null,
            selectedItem: null,
        };
    },
    computed: {
        calcDiffVal() {
            if (!this.previewChotCa) return 0;
            const thucTe = Number(this.formChotCa.tien_mat_thuc_te || 0);
            const heThong = Number(this.previewChotCa.tien_mat_he_thong || 0);
            return thucTe - heThong;
        },
        calcDiffVerdictTitle() {
            if (this.calcDiffVal === 0) return '✓ Két tiền khớp 100%';
            if (this.calcDiffVal > 0) return '▲ Thừa tiền trong két';
            return '▼ Thiếu hụt tiền trong két';
        },
        calcDiffVerdictDesc() {
            if (this.calcDiffVal === 0) return 'Số tiền thực tế đếm được hoàn toàn trùng khớp với hệ thống tính.';
            if (this.calcDiffVal > 0) return 'Số tiền mặt thực tế trong két nhiều hơn hệ thống tính.';
            return 'Số tiền mặt thực tế trong két ít hơn hệ thống tính! Vui lòng ghi chú lý do.';
        },
        calcDiffVerdictClass() {
            if (this.calcDiffVal === 0) return 'bg-success text-white';
            if (this.calcDiffVal > 0) return 'bg-primary text-white';
            return 'bg-danger text-white';
        }
    },
    mounted() {
        this.loadActiveShift();
        this.loadData();
    },
    methods: {
        loadActiveShift() {
            const token = localStorage.getItem('xs');
            axios.get('http://127.0.0.1:8000/api/admin/phien-ca/ca-hien-tai', {
                headers: { Authorization: `Bearer ${token}` }
            })
            .then(res => {
                if (res.data.status) {
                    this.activeShift = res.data.data;
                    if (res.data.danh_sach_ca) {
                        this.filterOptions.ca_lam_viecs = res.data.danh_sach_ca;
                    }
                }
            })
            .catch(err => console.error(err));
        },
        loadData(page = 1) {
            this.loading = true;
            const token = localStorage.getItem('xs');
            const params = {
                page,
                tu_ngay: this.filters.tu_ngay || undefined,
                den_ngay: this.filters.den_ngay || undefined,
                id_nhan_vien: this.filters.id_nhan_vien || undefined,
                trang_thai: this.filters.trang_thai || undefined,
                id_ca_lam_viec: this.filters.id_ca_lam_viec || undefined,
            };

            axios.get('http://127.0.0.1:8000/api/admin/phien-ca/lich-su', {
                headers: { Authorization: `Bearer ${token}` },
                params
            })
            .then(res => {
                if (res.data.status) {
                    this.listPhienCa = res.data.data;
                    if (res.data.summary) this.summary = res.data.summary;
                    if (res.data.filters) {
                        this.filterOptions.nhan_viens = res.data.filters.nhan_viens || [];
                        if (!this.filterOptions.ca_lam_viecs.length) {
                            this.filterOptions.ca_lam_viecs = res.data.filters.ca_lam_viecs || [];
                        }
                    }
                }
            })
            .catch(err => console.error(err))
            .finally(() => {
                this.loading = false;
            });
        },
        resetFilters() {
            this.filters = {
                tu_ngay: '',
                den_ngay: '',
                id_nhan_vien: '',
                trang_thai: '',
                id_ca_lam_viec: '',
            };
            this.loadData(1);
        },
        openMoCaModal() {
            this.formMoCa = { tien_dau_ca: 0, id_ca_lam_viec: '', ghi_chu: '' };
            const modalEl = document.getElementById('moCaModal');
            if (modalEl && window.bootstrap) {
                new window.bootstrap.Modal(modalEl).show();
            }
        },
        submitMoCa() {
            this.submitting = true;
            const token = localStorage.getItem('xs');
            axios.post('http://127.0.0.1:8000/api/admin/phien-ca/mo-ca', this.formMoCa, {
                headers: { Authorization: `Bearer ${token}` }
            })
            .then(res => {
                if (res.data.status) {
                    this.$toast?.success(res.data.message || 'Mở ca thành công!');
                    const modalEl = document.getElementById('moCaModal');
                    const modal = window.bootstrap?.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                    this.loadActiveShift();
                    this.loadData(1);
                } else {
                    this.$toast?.error(res.data.message || 'Không thể mở ca');
                }
            })
            .catch(err => {
                this.$toast?.error(err.response?.data?.message || 'Lỗi khi mở phiên ca');
            })
            .finally(() => {
                this.submitting = false;
            });
        },
        openChotCaModal(shift) {
            this.submitting = true;
            const token = localStorage.getItem('xs');
            const shiftId = shift ? shift.id : null;
            axios.get(`http://127.0.0.1:8000/api/admin/phien-ca/du-lieu-chot-ca/${shiftId || ''}`, {
                headers: { Authorization: `Bearer ${token}` }
            })
            .then(res => {
                if (res.data.status) {
                    this.previewChotCa = res.data.data;
                    this.formChotCa = {
                        id_phien_ca: res.data.data.phien_ca.id,
                        tien_mat_thuc_te: res.data.data.tien_mat_he_thong, // Gợi ý mặc định = hệ thống tính
                        ghi_chu: '',
                    };
                    const modalEl = document.getElementById('chotCaModal');
                    if (modalEl && window.bootstrap) {
                        new window.bootstrap.Modal(modalEl).show();
                    }
                } else {
                    this.$toast?.error(res.data.message || 'Không thể lấy dữ liệu ca');
                }
            })
            .catch(err => {
                this.$toast?.error('Lỗi khi tải dữ liệu chốt ca');
            })
            .finally(() => {
                this.submitting = false;
            });
        },
        submitChotCa() {
            this.submitting = true;
            const token = localStorage.getItem('xs');
            axios.post('http://127.0.0.1:8000/api/admin/phien-ca/chot-ca', this.formChotCa, {
                headers: { Authorization: `Bearer ${token}` }
            })
            .then(res => {
                if (res.data.status) {
                    this.$toast?.success(res.data.message || 'Chốt ca thành công!');
                    const modalEl = document.getElementById('chotCaModal');
                    const modal = window.bootstrap?.Modal.getInstance(modalEl);
                    if (modal) modal.hide();

                    this.loadActiveShift();
                    this.loadData(1);

                    // Chuyển hướng ngay sang Z-Report in ấn
                    if (res.data.id_phien_ca) {
                        this.$router.push(`/admin/phien-ca/z-report/${res.data.id_phien_ca}`);
                    }
                } else {
                    this.$toast?.error(res.data.message || 'Chốt ca thất bại');
                }
            })
            .catch(err => {
                this.$toast?.error(err.response?.data?.message || 'Lỗi khi chốt phiên ca');
            })
            .finally(() => {
                this.submitting = false;
            });
        },
        xemChiTiet(item) {
            this.selectedItem = item;
            const modalEl = document.getElementById('chiTietModal');
            if (modalEl && window.bootstrap) {
                new window.bootstrap.Modal(modalEl).show();
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
                minute: '2-digit'
            });
        },
        formatDiff(diff) {
            const val = Number(diff || 0);
            if (val === 0) return '0 đ (Khớp)';
            if (val > 0) return `+${this.formatVND(val)} đ (Thừa)`;
            return `${this.formatVND(val)} đ (Thiếu)`;
        },
        getDiffBadge(diff) {
            const val = Number(diff || 0);
            if (val === 0) return 'bg-success text-white';
            if (val > 0) return 'bg-primary text-white';
            return 'bg-danger text-white';
        }
    }
};
</script>

<style scoped>
.phien-ca-container {
    background-color: #f8fafc;
    min-height: calc(100vh - 70px);
}
.card-kpi {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.card-kpi:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08) !important;
}
.table-head-custom th {
    font-size: 11.5px;
    letter-spacing: 0.5px;
}
</style>
