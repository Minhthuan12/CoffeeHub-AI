<template>
    <div class="d-flex flex-column overflow-hidden" style="height: 85vh;">

        <!-- TAB NAVIGATION: POS TẠI QUẦY vs MÀN HÌNH 2 ĐƠN QR TẠI BÀN (UC26) -->
        <div class="d-flex align-items-center justify-content-between px-3 pt-2 pb-2 border-bottom bg-white shadow-xs">
            <div class="custom-segmented-control">
                <button
                    type="button"
                    class="custom-tab-btn"
                    :class="{ 'active': activeTab === 'POS' }"
                    @click="activeTab = 'POS'"
                >
                    <i class="fa-solid fa-cash-register me-2"></i>POS Bán Hàng Tại Quầy
                </button>
                <button
                    type="button"
                    class="custom-tab-btn position-relative"
                    :class="{ 'active': activeTab === 'QR_MONITOR' }"
                    @click="activeTab = 'QR_MONITOR'"
                >
                    <i class="fa-solid fa-qrcode me-2"></i>Màn hình 2: Đơn QR Tại Bàn
                    <span v-if="pendingQrOrders.length > 0" class="pulse-badge-danger ms-2">
                        {{ pendingQrOrders.length }}
                    </span>
                </button>
            </div>

            <div class="d-flex align-items-center gap-2">
                <!-- THÔNG TIN PHIÊN CA (UC30) -->
                <div v-if="activeShift" class="d-flex align-items-center gap-2 bg-amber-50 border border-amber-200 rounded-pill px-3 py-1 shadow-xs">
                    <span class="live-dot" style="background: #10b981;"></span>
                    <span class="small fw-bold text-dark">
                        {{ activeShift.ma_phien_ca }}
                        <span class="text-secondary fw-normal d-none d-md-inline">({{ activeShift.ca_lam_viec?.ten_ca || 'Ca linh hoạt' }})</span>
                    </span>
                    <button @click="openPosChotCaModal" class="btn btn-sm btn-danger rounded-pill px-2.5 py-0.5 fw-bold" style="font-size: 11px;">
                        <i class="fa-solid fa-stopwatch me-1"></i>Chốt Ca
                    </button>
                </div>
                <div v-else class="d-flex align-items-center gap-2 bg-danger-subtle border border-danger-subtle rounded-pill px-3 py-1 shadow-xs">
                    <span class="live-dot" style="background: #ef4444; animation: none;"></span>
                    <span class="small fw-bold text-danger">Chưa Mở Ca</span>
                    <button @click="openPosMoCaDirect" class="btn btn-sm btn-warning text-dark rounded-pill px-2.5 py-0.5 fw-bold" style="font-size: 11px;">
                        <i class="fa-solid fa-play me-1"></i>Mở Ca
                    </button>
                </div>

                <button
                    class="btn btn-sm rounded-pill px-3 py-1.5 fw-semibold d-flex align-items-center gap-2 border shadow-none"
                    :class="soundEnabled ? 'bg-success-subtle text-success border-success-subtle' : 'bg-light text-secondary border-light-subtle'"
                    @click="soundEnabled = !soundEnabled"
                    title="Bật/Tắt âm thanh chuông báo khi có đơn QR mới"
                >
                    <span class="live-dot" :style="soundEnabled ? 'background: #10b981;' : 'background: #94a3b8; animation: none;'"></span>
                    <i class="fa-solid" :class="soundEnabled ? 'fa-bell text-success' : 'fa-bell-slash text-muted'"></i>
                    <span class="small fw-bold">{{ soundEnabled ? 'Chuông: Bật' : 'Chuông: Tắt' }}</span>
                </button>
                <button
                    v-if="activeTab === 'QR_MONITOR'"
                    class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1.5 fw-bold d-flex align-items-center gap-1.5 shadow-xs"
                    :disabled="isManualRefreshing"
                    @click="handleManualRefresh"
                >
                    <i class="fa-solid fa-arrows-rotate" :class="{ 'fa-spin': isManualRefreshing }"></i>
                    <span>Làm mới</span>
                </button>
            </div>
        </div>

        <!-- OVERLAY CHẶN BÁN HÀNG NẾU CHƯA MỞ CA (UC30) -->
        <div v-if="!activeShift && !checkingShift" class="pos-blocker-container">
            <div class="pos-blocker-backdrop"></div>
            <div class="pos-blocker-card shadow-lg">
                <div class="pos-blocker-icon bg-amber-100 text-amber-600 rounded-circle mx-auto d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-lock fs-2"></i>
                </div>
                <h4 class="fw-bold text-dark mt-3 mb-1">Chưa Mở Ca Bán Hàng!</h4>
                <p class="text-secondary small mb-3">
                    Thu ngân cần nhập số tiền mặt có sẵn trong két để mở ca làm việc trước khi thực hiện bán hàng tại quầy POS.
                </p>
                <div class="bg-light p-3 rounded-3 mb-3 text-start border">
                    <div class="mb-2">
                        <label class="form-label small fw-bold text-dark mb-1">Ca làm việc hôm nay:</label>
                        <select v-model="posMoCaForm.id_ca_lam_viec" class="form-select form-select-sm rounded-3">
                            <option value="">-- Ca làm linh hoạt --</option>
                            <option v-for="c in posDanhSachCa" :key="c.id" :value="c.id">
                                {{ c.ten_ca }} ({{ c.gio_bat_dau }} - {{ c.gio_ket_thuc }})
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label small fw-bold text-dark mb-1">Tiền mặt bàn giao đầu ca (trong két) *:</label>
                        <div class="input-group input-group-sm">
                            <input type="number" v-model="posMoCaForm.tien_dau_ca" class="form-control rounded-start-3 fw-bold fs-6" placeholder="0" min="0" step="10000">
                            <span class="input-group-text bg-white fw-bold">VNĐ</span>
                        </div>
                        <div class="small text-muted mt-1">{{ formatVND(posMoCaForm.tien_dau_ca) }} đ</div>
                    </div>
                    <div class="mt-2">
                        <label class="form-label small fw-bold text-dark mb-1">Ghi chú (nếu có):</label>
                        <input type="text" v-model="posMoCaForm.ghi_chu" class="form-control form-control-sm rounded-3" placeholder="Tình trạng két, tiền lẻ...">
                    </div>
                </div>
                <button @click="submitPosMoCa" class="btn btn-warning w-100 fw-bold text-dark rounded-3 py-2 shadow-sm" :disabled="submittingMoCa">
                    <span v-if="submittingMoCa" class="spinner-border spinner-border-sm me-1"></span>
                    <i v-else class="fa-solid fa-play me-2"></i>
                    Bắt Đầu Ca Bán Hàng Ngay
                </button>
            </div>
        </div>

        <!-- TAB 1: POS BÁN HÀNG TẠI QUẦY -->
        <div v-if="activeTab === 'POS'" class="d-flex flex-column flex-grow-1 overflow-hidden p-2">
            <!-- TOP ROW -->
            <div class="mb-2 pb-2 border-bottom">
                <div class="row g-2 align-items-center">

                    <!-- Tìm kiếm bàn -->
                    <div class="col-lg-3">
                    <div class="position-relative">
                        <input v-model="tim_ban" type="text" class="form-control ps-5" placeholder="tìm kiếm bàn"> <span
                            class="position-absolute top-50 product-show translate-middle-y"><i
                                class="bx bx-search"></i></span>
                    </div>
                </div>

                <!-- Thông tin bàn + nhân viên -->
                <div class="col-lg-5 d-flex align-items-center gap-2">
                    <span class="fw-bold text-danger fs-5 ms-2">{{ getTenBanChon() }}</span>
                    <div
                        class="d-flex align-items-center gap-1 badge bg-primary-subtle text-primary border border-primary border-opacity-25 px-2 py-1 rounded-3 small">
                        <i class="fa-solid fa-user me-1"></i>
                        <span class="fw-bold">{{ user_login.ho_va_ten || 'Nhân viên' }}</span>
                        <span class="text-muted mx-1">|</span>
                        <span>{{ user_login.ten_chuc_vu || 'Chưa có chức vụ' }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-1 ms-auto">
                        <label class="text-nowrap mb-0 text-muted small">Vào: {{ formatDateTime(created_at_hoa_don) ||
                            'Chưa mở bàn' }}</label>
                    </div>
                </div>

                <!-- Tìm kiếm sản phẩm -->
                <div class="col-lg-4">
                    <div class="position-relative">
                        <input v-model="tim_san_pham" type="text" class="form-control ps-5"
                            placeholder="tìm kiếm sản phẩm"> <span
                            class="position-absolute top-50 product-show translate-middle-y"><i
                                class="bx bx-search"></i></span>
                    </div>
                </div>

            </div>
        </div>

        <!-- BOTTOM ROW: 3 CỘT -->
        <div class="row g-2 flex-grow-1 overflow-hidden">

            <!-- ====== CỘT 1: SƠ ĐỒ BÀN ====== -->
            <div class="col-lg-3 d-flex flex-column h-100 overflow-hidden">
                <div class="card flex-grow-1 overflow-hidden d-flex flex-column">
                    <div class="card-header py-2 fw-bold">
                        <i class="fa-solid fa-table text-primary me-2"></i>Sơ Đồ Bàn
                    </div>

                    <!-- Filter tabs -->
                    <div class="p-1 border-bottom">
                        <ul class="nav nav-tabs nav-primary justify-content-center mb-0" role="tablist">
                            <li v-on:click="chon(2)" class="nav-item w-25" role="presentation">
                                <a class="nav-link p-1 text-center" :class="{ 'active': dang_chon === 2 }" role="tab"
                                    style="cursor:pointer">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bx bx-grid-alt font-18"></i>
                                        <span class="d-none d-lg-inline small">Tất cả</span>
                                    </div>
                                </a>
                            </li>
                            <li v-on:click="chon(1)" class="nav-item w-25" role="presentation">
                                <a class="nav-link p-1 text-center" :class="{ 'active': dang_chon === 1 }" role="tab"
                                    style="cursor:pointer">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bx bx-check-circle font-18 text-success"></i>
                                        <span class="d-none d-lg-inline small">Trống</span>
                                    </div>
                                </a>
                            </li>
                            <li v-on:click="chon(0)" class="nav-item w-25" role="presentation">
                                <a class="nav-link p-1 text-center" :class="{ 'active': dang_chon === 0 }" role="tab"
                                    style="cursor:pointer">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bx bx-coffee font-18 text-warning"></i>
                                        <span class="d-none d-lg-inline small">Có Khách</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Danh sách bàn -->
                    <div class="flex-grow-1 overflow-auto p-2">
                        <div class="row g-2">
                            <div v-for="(value, index) in filteredBan" :key="index" class="col-3">
                                <div v-if="Number(value.tinh_trang_ban) === 0"
                                    class="text-center p-2 rounded-3 d-flex flex-column align-items-center justify-content-center  text-success "
                                    style="min-height:72px;" v-on:dblclick="moBan(value.id)">
                                    <i class="fa-solid fa-utensils fa-3x"></i>
                                    <h6 class="fw-bold mb-0 small mt-2">{{ value.ten_ban }}</h6>
                                </div>
                                <div v-else
                                    class="text-center p-2 rounded-3 d-flex flex-column align-items-center justify-content-center  bg-warning-subtle text-warning "
                                    style="min-height:72px; " v-on:click="layChiTiet(value.id)">
                                    <i class="fa-solid fa-utensils fa-3x"></i>
                                    <h6 class="fw-bold mb-0 small mt-2">{{ value.ten_ban }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ====== CỘT 2: HÓA ĐƠN CHI TIẾT ====== -->
            <div class="col-lg-5 d-flex flex-column h-100 overflow-hidden">
                <div class="card flex-grow-1 d-flex flex-column overflow-hidden">
                    <div class="card-header py-2 fw-bold">
                        <i class="fa-solid fa-receipt text-primary me-2"></i>Hóa Đơn Chi Tiết
                        <span class="text-danger" v-if="id_ban_chon">- {{ getTenBanChon() }}</span>
                    </div>

                    <!-- Bảng chi tiết -->
                    <div class="flex-grow-1 overflow-auto">
                        <table class="table table-bordered table-sm table-hover text-center align-middle mb-0">
                            <thead class="table-primary sticky-top">
                                <tr>
                                    <th style="width:30px">#</th>
                                    <th class="text-start">Tên Món</th>
                                    <th style="width:90px">Ghi Chú</th>
                                    <th style="width:35px">SL</th>
                                    <th style="width:80px">Đơn Giá</th>
                                    <th style="width:90px">Thành Tiền</th>
                                    <th style="width:80px">Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(val, index) in list_mon_chi_tiet" :key="index">
                                    <td class="small">{{ index + 1 }}</td>
                                    <td class="text-start small" style="max-width: 180px; word-break: break-word; white-space: normal;">
                                        <div class="fw-bold">{{ val.ten_san_pham }}</div>
                                        <div v-if="val.tuy_chon" class="d-flex flex-wrap gap-1 mt-1">
                                            <span v-for="b in getBadges(val.tuy_chon)" :key="b.text" class="badge" :class="b.class" style="font-size: 10px; font-weight: 500; padding: 2px 5px;">
                                                {{ b.text }}
                                            </span>
                                        </div>
                                        <div class="mt-1">
                                            <span v-if="val.tinh_trang === 0" class="badge bg-secondary">Chưa gửi</span>
                                            <span v-else-if="val.tinh_trang === 1" class="badge bg-warning text-dark">Đang pha chế</span>
                                            <span v-else-if="val.tinh_trang === 2" class="badge bg-success"><i class="fa-solid fa-check me-1"></i>Đã xong</span>
                                        </div>
                                    </td>
                                    <td class="text-start text-muted small" style="max-width: 120px; word-break: break-word; white-space: normal;">
                                        {{ formatGhiChu(val) }}
                                    </td>
                                    <td class="fw-bold">{{ val.so_luong }}</td>
                                    <td class="text-end fw-bold small">{{ formatVND(val.don_gia) }}đ</td>
                                    <td class="text-end text-primary fw-bold small">{{ formatVND(val.so_luong *
                                        val.don_gia) }}đ</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-around">
                                            <button v-on:click="Object.assign(mon_edit, val)"
                                                class="btn btn-outline-primary btn-sm me-2" type="button"
                                                data-bs-toggle="modal" data-bs-target="#suaMonModal">
                                                <i class="ms-2 fa-regular fa-pen-to-square"></i>
                                            </button>
                                            <button v-on:click="Object.assign(mon_delete, val)"
                                                class="btn btn-outline-danger btn-sm" type="button"
                                                data-bs-toggle="modal" data-bs-target="#xoaMonModal">
                                                <i class="ms-2 fa-regular fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Panel thanh toán -->
                    <div class="card-footer bg-light p-2">

                        <!-- Khách hàng & Voucher -->
                        <div class="row g-2 mb-2 pb-2 border-bottom">
                            <div class="col-6">
                                <label class="fw-bold text-muted small mb-1 d-block">Khách Hàng (SĐT)</label>
                                <div class="input-group input-group-sm">
                                    <input v-model="tim_sdt" type="text" class="form-control" placeholder="Nhập SĐT...">
                                    <button v-if="tinh_trang_tim_kiem_sdt" v-on:click="timKH()"
                                        class="btn btn-primary px-2" title="Tìm kiếm">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                    <button v-else class="btn btn-success px-2" data-bs-toggle="modal"
                                        data-bs-target="#themMoiKhachHangModal" title="Thêm mới khách hàng">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>
                                <div class="mt-1 small">
                                    <span class="text-success fw-bold"><i class="fa-solid fa-circle-check me-1"></i>{{
                                        khach_hang }}</span>

                                </div>
                            </div>
                            <div class="col-6">
                                <label class="fw-bold text-muted small mb-1 d-block">Mã Voucher</label>
                                <div class="input-group input-group-sm">
                                    <input v-model="ma_voucher_nhap" type="text" class="form-control"
                                        placeholder="Nhập mã..." :disabled="!!voucher_da_ap">
                                    <button v-if="!voucher_da_ap" class="btn btn-success px-2 text-white"
                                        @click="apDungVoucher()">Áp dụng</button>
                                    <button v-else class="btn btn-danger px-2 text-white" @click="huyVoucher()"
                                        title="Huỷ voucher">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                                <div class="mt-1 small">
                                    <span v-if="voucher_da_ap" class="text-success fw-bold">
                                        <i class="fa-solid fa-circle-check me-1"></i>
                                        Giảm: {{ formatVND(voucher_da_ap.so_tien_giam) }}đ
                                    </span>
                                    <span v-else class="text-muted">Chưa áp dụng</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tổng tiền & Hành động -->
                        <div class="row g-2">
                            <div class="col-6 border-end pe-2 d-flex flex-column gap-2">
                                <div class="d-flex align-items-center gap-1">
                                    <span class="fw-bold text-muted small text-nowrap" style="width:62px">Tổng tiền
                                    </span>
                                    <input type="text" class="form-control form-control-sm text-end fw-bold"
                                        :value="formatVND(list_mon_chi_tiet.reduce((sum, val) => sum + val.so_luong * val.don_gia, 0)) + 'đ'">
                                </div>
                                <div class="d-flex align-items-center gap-1">
                                    <span class="fw-bold text-muted small text-nowrap" style="width:62px">Giảm
                                        Giá </span>
                                    <input type="text" class="form-control form-control-sm text-center fw-bold"
                                        :value="voucher_da_ap ? (voucher_da_ap.loai_giam === 1 ? voucher_da_ap.so_giam_gia + '%' : 'cố định') : '0%'"
                                        style="width:65px">
                                    <input type="text" class="form-control form-control-sm text-end fw-bold"
                                        :value="formatVND(voucher_da_ap ? voucher_da_ap.so_tien_giam : 0) + 'đ'">
                                </div>
                                <div class="d-flex align-items-center gap-1">
                                    <span class="fw-bold text-muted small text-nowrap" style="width:62px">Ph. Thức
                                    </span>
                                    <select v-model="phuong_thuc_thanh_toan" class="form-select form-select-sm fw-bold">
                                        <option :value="1">Tiền mặt</option>
                                        <option :value="2">Chuyển khoản</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6 d-flex flex-column gap-2">
                                <button v-on:click="inPhaChe(id_hoa_don_chon)"
                                    :disabled="!id_ban_chon || !id_hoa_don_chon.id || list_mon_chi_tiet.length === 0"
                                    class="btn btn-sm btn-primary w-100 fw-bold">
                                    <i class="fa-solid fa-mug-hot me-1"></i>In Pha Chế
                                </button>
                                <a v-on:click="thanhToan(id_hoa_don_chon.id)"
                                    :href="id_hoa_don_chon.id ? `/admin/in-hoa-don/${id_hoa_don_chon.id}` : 'javascript:;'"
                                    :target="id_hoa_don_chon.id ? '_blank' : '_self'"
                                    class="btn btn-sm btn-dark w-100 fw-bold"
                                    :class="{ 'disabled': !id_ban_chon || !id_hoa_don_chon.id || list_mon_chi_tiet.length === 0 }">
                                    <i class="fa-solid fa-print me-1"></i>In Hóa Đơn và Thanh Toán
                                </a>
                                <button type="button"
                                    class="btn btn-sm btn-success w-100 fw-bold"
                                    :disabled="!id_ban_chon || !id_hoa_don_chon.id || list_mon_chi_tiet.length === 0"
                                    data-bs-toggle="modal"
                                    data-bs-target="#xacNhanThanhToanModal">
                                    <i class="fa-solid fa-circle-check me-1"></i>Xác Nhận Thanh Toán
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- ====== CỘT 3: THỰC ĐƠN ====== -->
            <div class="col-lg-4 d-flex flex-column h-100 overflow-hidden">
                <div class="card flex-grow-1 d-flex flex-column overflow-hidden">
                    <div class="card-header py-2 fw-bold">
                        <i class="fa-solid fa-utensils text-primary me-2"></i>Thực Đơn
                    </div>

                    <div class="flex-grow-1 overflow-auto">
                        <table class="table table-sm table-hover table-bordered align-middle mb-0">
                            <thead class="table-primary sticky-top">
                                <tr class="">
                                    <th class="text-start ps-2">Tên Món</th>
                                    <th class="text-center">Size</th>
                                    <th class="text-end pe-2">Đơn Giá</th>
                                    <th class="text-center">Thêm</th>
                                </tr>
                            </thead>
                            <tbody class="">
                                <tr v-for="(val, index) in filteredSanPham" :key="index" style="cursor:pointer">
                                    <td class="ps-2">{{ val.ten_san_pham }}</td>
                                    <td class="text-center text-muted">{{ val.kich_thuoc }}</td>
                                    <td class="text-end pe-2 text-primary fw-bold">{{ formatVND(val.gia_ban) }}</td>
                                    <td class="text-center">
                                        <button v-on:click="Object.assign(mon_dang_chon, val)"
                                            class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#themMonModal">
                                            <i class="ms-1 fa-solid fa-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        </div>

        <!-- ========================================================================= -->
        <!-- TAB 2: MÀN HÌNH 2 - GIÁM SÁT & DUYỆT ĐƠN HÀNG QR TẠI BÀN (UC26, UC27, UC17) -->
        <!-- ========================================================================= -->
        <div v-else-if="activeTab === 'QR_MONITOR'" class="d-flex flex-column flex-grow-1 overflow-auto p-3" style="background-color: #f8fafc;">
            <!-- Header bar -->
            <div class="screen2-header-card d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="screen2-icon-box">
                        <i class="fa-solid fa-qrcode fs-5"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-0.5">
                            <h6 class="mb-0 fw-bolder text-dark font-15">Màn hình 2: Giám sát đơn gọi món QR tại bàn</h6>
                            <span class="live-pill">
                                <span class="live-dot"></span>
                                REALTIME
                            </span>
                        </div>
                        <span class="text-muted smaller">
                            Pay-First Mode • Tự động nhận diện thanh toán • Đồng bộ trực tiếp quầy pha chế Barista KDS
                        </span>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <!-- Filter pills -->
                    <div class="d-flex gap-1.5">
                        <button
                            type="button"
                            class="filter-pill-btn"
                            :class="{ 'active': filterQrType === 'ALL' }"
                            @click="filterQrType = 'ALL'"
                        >
                            Tất cả ({{ pendingQrOrders.length }})
                        </button>
                        <button
                            type="button"
                            class="filter-pill-btn"
                            :class="{ 'active': filterQrType === 'CASH' }"
                            @click="filterQrType = 'CASH'"
                        >
                            <i class="fa-solid fa-hand-holding-dollar text-warning me-1"></i>Tiền mặt ({{ countCashOrders }})
                        </button>
                        <button
                            type="button"
                            class="filter-pill-btn"
                            :class="{ 'active': filterQrType === 'VIETQR' }"
                            @click="filterQrType = 'VIETQR'"
                        >
                            <i class="fa-solid fa-qrcode text-primary me-1"></i>VietQR ({{ countVietQrOrders }})
                        </button>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="filteredPendingQrOrders.length === 0" class="card border-0 shadow-sm rounded-4 text-center py-5 bg-white my-auto">
                <div class="card-body py-5">
                    <div class="bg-primary-subtle text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fa-solid fa-mug-hot fa-2x opacity-75"></i>
                    </div>
                    <h5 class="fw-bold mb-1 text-dark">Hiện không có đơn QR nào đang chờ duyệt</h5>
                    <p class="text-muted small mb-0" style="max-width: 480px; margin: auto;">
                        Khi khách hàng quét mã QR tại bàn và gửi yêu cầu, đơn hàng sẽ lập tức hiển thị tại đây kèm chuông báo âm thanh.
                    </p>
                </div>
            </div>

            <!-- Grid Cards -->
            <div v-else class="row g-3">
                <div v-for="order in filteredPendingQrOrders" :key="order.id" class="col-xl-4 col-lg-6 col-md-12">
                    <div class="card border-0 shadow-sm rounded-4 h-100 bg-white p-3 d-flex flex-column hover-lift">
                        <!-- 1. Header: Bàn & Mã HĐ & Giờ -->
                        <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-dark rounded-3 px-2.5 py-1.5 font-14 fw-bold">
                                    <i class="fa-solid fa-chair me-1 text-warning"></i>{{ order.ten_ban }}
                                </span>
                                <span class="badge bg-light text-secondary border font-13 fw-semibold">
                                    #{{ order.ma_hoa_don }}
                                </span>
                            </div>
                            <span class="text-muted small">
                                <i class="fa-regular fa-clock me-1"></i>{{ formatTimeAgo(order.created_at) || formatTimeOnly(order.created_at) }}
                            </span>
                        </div>

                        <!-- 2. Tag Trạng Thái Thanh Toán (Gọn gàng 1 dòng thanh lịch) -->
                        <div v-if="order.yeu_cau_tien_mat == 1" class="d-flex align-items-center gap-2 px-2.5 py-1.5 rounded-3 bg-warning-subtle text-warning-emphasis border border-warning-subtle small fw-bold mb-2">
                            <i class="fa-solid fa-hand-holding-dollar text-warning fs-6"></i>
                            <span>Thu tiền mặt tại bàn (UC17)</span>
                            <span class="ms-auto badge bg-warning text-dark font-11">Chưa thu tiền</span>
                        </div>
                        <div v-else-if="order.phuong_thuc_thanh_toan == 2" class="d-flex align-items-center gap-2 px-2.5 py-1.5 rounded-3 small fw-bold mb-2" :class="order.is_vietqr_paid ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-primary-subtle text-primary border border-primary-subtle'">
                            <i :class="order.is_vietqr_paid ? 'fa-solid fa-circle-check fs-6 text-success' : 'fa-solid fa-qrcode fs-6 text-primary'"></i>
                            <span>{{ order.is_vietqr_paid ? 'Đã khớp tiền VietQR tự động' : 'Chờ khách quét mã VietQR' }}</span>
                            <span class="ms-auto badge" :class="order.is_vietqr_paid ? 'bg-success text-white font-11' : 'bg-primary text-white font-11'">
                                {{ order.is_vietqr_paid ? 'Đã thanh toán' : 'Chờ chuyển khoản' }}
                            </span>
                        </div>

                        <!-- 3. Khách hàng & Số lượng món -->
                        <div class="d-flex justify-content-between align-items-center text-muted small mb-2 px-1">
                            <span><i class="fa-regular fa-user me-1.5 text-secondary"></i><strong>{{ order.ten_khach_hang || 'Khách vãng lai' }}</strong></span>
                            <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-2 py-0.5">{{ (order.items || []).length }} món</span>
                        </div>

                        <!-- 4. Danh sách món (Thực đơn dạng bill nhà hàng cao cấp) -->
                        <div class="flex-grow-1 overflow-auto py-2 my-1 border-top border-bottom" style="max-height: 220px;">
                            <div v-for="(it, i) in order.items" :key="i" class="py-1.5 px-1" :class="{ 'border-bottom border-light': i < order.items.length - 1 }">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-light text-dark border font-12 fw-bold px-2 py-1">x{{ it.so_luong }}</span>
                                        <span class="fw-bold text-dark font-14">{{ it.ten_san_pham }}</span>
                                    </div>
                                    <span class="fw-bold text-primary font-14">{{ formatVND(it.don_gia * it.so_luong) }}đ</span>
                                </div>
                                <!-- Tùy chọn thu gọn dạng bullet sạch sẽ -->
                                <div v-if="it.tuy_chon" class="text-secondary smaller ps-4 mt-1">
                                    <i class="fa-solid fa-arrow-turn-down-right me-1 text-muted"></i>{{ formatTuyChon(it.tuy_chon) }}
                                </div>
                                <!-- Ghi chú nếu có -->
                                <div v-if="it.ghi_chu && !isSameOption(it)" class="text-danger smaller fst-italic ps-4 mt-0.5">
                                    <i class="fa-regular fa-comment-dots me-1"></i>{{ it.ghi_chu }}
                                </div>
                            </div>
                        </div>

                        <!-- 5. Tổng thanh toán (Sạch sẽ, rõ ràng, không viền đứt thừa) -->
                        <div class="pt-2">
                            <div v-if="order.so_tien_giam > 0" class="d-flex justify-content-between small text-muted mb-1 px-1">
                                <span>Tiền món: {{ formatVND(order.tien_truoc_giam) }}đ</span>
                                <span class="text-success fw-bold">-{{ formatVND(order.so_tien_giam) }}đ</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3 px-1">
                                <span class="fw-bold text-secondary font-13 text-uppercase" style="letter-spacing: 0.5px;">Tổng thanh toán:</span>
                                <span class="fs-4 fw-bolder text-danger">{{ formatVND(order.tong_tien) }}<span class="fs-6 fw-bold ms-0.5">đ</span></span>
                            </div>

                            <!-- 6. Nút thao tác cân xứng 4 / 8 -->
                            <div class="row g-2">
                                <div class="col-4">
                                    <button
                                        type="button"
                                        class="btn btn-outline-danger w-100 py-2 fw-bold rounded-3 font-13"
                                        @click="openRejectModal(order)"
                                    >
                                        <i class="fa-solid fa-xmark me-1"></i>Từ chối
                                    </button>
                                </div>
                                <div class="col-8">
                                    <button
                                        type="button"
                                        class="btn w-100 py-2 fw-bold rounded-3 font-13 shadow-xs"
                                        :class="order.yeu_cau_tien_mat == 1 ? 'btn-warning text-dark' : 'btn-success text-white'"
                                        @click="openApproveModal(order)"
                                    >
                                        <i class="fa-solid me-1.5" :class="order.yeu_cau_tien_mat == 1 ? 'fa-hand-holding-dollar text-danger' : 'fa-check'"></i>
                                        <span>{{ order.yeu_cau_tien_mat == 1 ? 'Thu tiền & Duyệt' : 'Duyệt đơn' }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Thêm Món  -->
        <div class="modal fade" id="themMonModal" tabindex="-1" aria-labelledby="themMonModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow border-0">
                    <div class="modal-header bg-primary text-white py-2">
                        <h5 class="modal-title fs-6" id="themMonModalLabel">
                            <i class="fa-solid fa-cart-plus me-2"></i>Thêm Món Vào Hóa Đơn
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Tên sản phẩm -->
                        <div class="mb-3">
                            <h5 class="fw-bold text-center text-primary">{{ mon_dang_chon ?
                                mon_dang_chon.ten_san_pham : '' }}</h5>
                        </div>
                        <!-- Nhập số lượng -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Số lượng</label>
                            <input v-model="mon_dang_chon.so_luong" type="number" class="form-control" min="1">
                        </div>

                        <!-- Nhập ghi chú -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ghi chú</label>
                            <input v-model="mon_dang_chon.ghi_chu" type="text" class="form-control" placeholder="...">
                        </div>
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                        <button v-on:click="themMonVaoHoaDon()" type="button" class="btn btn-primary btn-sm px-3"
                            data-bs-dismiss="modal">Xác nhận thêm</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Sửa Món -->
        <div class="modal fade" id="suaMonModal" tabindex="-1" aria-labelledby="suaMonModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow border-0" v-if="mon_edit">
                    <div class="modal-header bg-warning text-dark py-2">
                        <h5 class="modal-title fs-6 fw-bold" id="suaMonModalLabel">
                            <i class="fa-solid fa-pen-to-square me-2"></i>Sửa Chi Tiết Món Ăn
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Tên món ăn -->
                        <div class="mb-3">
                            <h5 class="fw-bold text-center text-warning">{{ mon_edit.ten_san_pham }}</h5>
                        </div>
                        <!-- Nhập số lượng -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Số lượng</label>
                            <input v-model="mon_edit.so_luong" type="number" class="form-control" min="1">
                        </div>
                        <!-- Nhập ghi chú -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ghi chú</label>
                            <input v-model="mon_edit.ghi_chu" type="text" class="form-control" placeholder="...">
                        </div>
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                        <button v-on:click="suaMonChiTiet()" type="button"
                            class="btn btn-warning btn-sm px-3 text-dark fw-bold" data-bs-dismiss="modal">Cập
                            nhật</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Xóa Món -->
        <div class="modal fade" id="xoaMonModal" tabindex="-1" aria-labelledby="xoaMonModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow border-0" v-if="mon_delete">
                    <div class="modal-header bg-danger text-white py-2">
                        <h5 class="modal-title fs-6 fw-bold" id="xoaMonModalLabel">
                            <i class="fa-solid fa-trash-can me-2"></i>Xóa Món Ăn Khỏi Hóa Đơn
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p class="mb-0 fs-5">Bạn có chắc chắn muốn xóa món <strong class="text-danger">{{
                            mon_delete.ten_san_pham }}</strong> khỏi hóa đơn?</p>
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
                        <button v-on:click="xoaMonChiTiet()" type="button" class="btn btn-danger btn-sm px-3"
                            data-bs-dismiss="modal">Xác nhận xóa</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Thêm Mới Khách Hàng -->
        <div class="modal fade" id="themMoiKhachHangModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow border-0">
                    <div class="modal-header bg-success text-white py-2">
                        <h5 class="modal-title fs-6 fw-bold">
                            <i class="fa-solid fa-user-plus me-2"></i>Thêm Mới Khách Hàng
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label fw-bold">Số Điện Thoại <span class="text-danger">*</span></label>
                            <input v-model="create_khach_hang.so_dien_thoai" type="text" class="form-control"
                                placeholder="Nhập số điện thoại">
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold">Họ Và Tên <span class="text-danger">*</span></label>
                            <input v-model="create_khach_hang.ho_va_ten" type="text" class="form-control"
                                placeholder="Nhập họ và tên">
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                            <input v-model="create_khach_hang.email" type="email" class="form-control"
                                placeholder="Nhập email">
                        </div>
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                        <button v-on:click="themKhachHang()" type="button" class="btn btn-success btn-sm px-3">
                            Thêm Mới
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Xác Nhận Thanh Toán -->
        <div class="modal fade" id="xacNhanThanhToanModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow border-0">
                    <div class="modal-header bg-success text-white py-2">
                        <h5 class="modal-title fs-6 fw-bold">
                            <i class="fa-solid fa-circle-check me-2"></i>Xác Nhận Thanh Toán
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-light border mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Bàn:</span>
                                <span class="fw-bold text-primary">{{ getTenBanChon() }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2" v-if="id_hoa_don_chon.id">
                                <span class="text-muted">Mã hóa đơn:</span>
                                <span class="fw-bold">#{{ id_hoa_don_chon.id }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Khách hàng:</span>
                                <span class="fw-bold text-success">{{ khach_hang || 'Khách vãng lai' }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Phương thức:</span>
                                <span class="badge" :class="phuong_thuc_thanh_toan === 1 ? 'bg-primary' : 'bg-info'">
                                    {{ phuong_thuc_thanh_toan === 1 ? 'Tiền mặt' : 'Chuyển khoản' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-2" v-if="voucher_da_ap">
                                <span class="text-muted">Voucher giảm:</span>
                                <span class="fw-bold text-danger">-{{ formatVND(voucher_da_ap.so_tien_giam) }}đ</span>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold">Số tiền thanh toán:</span>
                                <span class="fs-5 fw-bold text-danger">
                                    {{ formatVND(Math.max(0, list_mon_chi_tiet.reduce((sum, val) => sum + val.so_luong * val.don_gia, 0) - (voucher_da_ap ? voucher_da_ap.so_tien_giam : 0))) }}đ
                                </span>
                            </div>
                        </div>
                        <p class="text-center text-muted mb-0 small">
                            <i class="fa-solid fa-circle-info me-1 text-primary"></i>
                            Xác nhận bàn này đã thanh toán xong? Bàn sẽ được cập nhật về trạng thái <strong>Trống</strong>.
                        </p>
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
                        <button v-on:click="xacNhanThanhToan()" type="button" class="btn btn-success btn-sm px-3 fw-bold"
                            data-bs-dismiss="modal">
                            <i class="fa-solid fa-check me-1"></i>Xác Nhận Đã Thanh Toán
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL DUYỆT ĐƠN QR (UC27) -->
        <div class="modal fade" id="approveQrOrderModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content luxury-modal-content" v-if="selectedQrOrder">
                    <!-- Modal Header -->
                    <div class="luxury-modal-header bg-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="modal-header-icon icon-emerald">
                                <i class="fa-solid fa-cash-register"></i>
                            </div>
                            <div>
                                <h5 class="fw-bolder text-dark mb-0.5">Xác Nhận & Duyệt Đơn Hàng</h5>
                                <div class="d-flex align-items-center gap-2 small text-muted">
                                    <span class="badge bg-primary-subtle text-primary fw-bold font-12">{{ selectedQrOrder.ten_ban }}</span>
                                    <span>•</span>
                                    <span class="fw-semibold">Mã HĐ: #{{ selectedQrOrder.ma_hoa_don }}</span>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body p-4 bg-white">
                        <!-- Hero Amount Box -->
                        <div class="modal-amount-hero">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge rounded-pill px-3 py-1.5 font-12 fw-bold" :class="selectedQrOrder.yeu_cau_tien_mat == 1 ? 'bg-warning-subtle text-warning-emphasis border border-warning-subtle' : 'bg-primary-subtle text-primary border border-primary-subtle'">
                                    <i class="fa-solid me-1" :class="selectedQrOrder.yeu_cau_tien_mat == 1 ? 'fa-hand-holding-dollar' : 'fa-qrcode'"></i>
                                    {{ selectedQrOrder.yeu_cau_tien_mat == 1 ? 'Thanh toán tiền mặt tại bàn' : 'Thanh toán VietQR động' }}
                                </span>
                                <span class="text-muted small">
                                    <i class="fa-regular fa-clock me-1"></i>{{ formatTimeOnly(selectedQrOrder.created_at) }}
                                </span>
                            </div>

                            <div class="hero-label">Số tiền cần thu từ khách</div>
                            <div class="hero-amount">
                                {{ formatVND(selectedQrOrder.tong_tien) }} <span style="font-size: 20px; font-weight: 700;">đ</span>
                            </div>
                            <div class="text-muted smaller mt-1">
                                Khách hàng: <strong>{{ selectedQrOrder.ten_khach_hang || 'Khách tại bàn' }}</strong>
                            </div>
                        </div>

                        <!-- Cờ tương tác xác nhận thu tiền mặt (UC17) -->
                        <div
                            v-if="selectedQrOrder.yeu_cau_tien_mat == 1"
                            class="cash-confirm-interactive-card"
                            :class="{ 'is-confirmed': cashConfirmed }"
                            @click="cashConfirmed = !cashConfirmed"
                        >
                            <div class="custom-checkbox-disc">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold font-13" :class="cashConfirmed ? 'text-success' : 'text-warning-emphasis'">
                                    {{ cashConfirmed ? 'ĐÃ THU ĐỦ TIỀN MẶT - SẴN SÀNG DUYỆT' : 'THU TIỀN MẶT TẠI BÀN (BẮT BUỘC)' }}
                                </div>
                                <div class="smaller text-secondary">
                                    {{ cashConfirmed ? 'Đã nhận đủ ' + formatVND(selectedQrOrder.tong_tien) + 'đ từ khách hàng. Bạn có thể bấm Duyệt.' : 'Bấm vào đây để xác nhận bạn đã thu đủ ' + formatVND(selectedQrOrder.tong_tien) + 'đ tiền mặt từ khách.' }}
                                </div>
                            </div>
                            <i class="fa-solid" :class="cashConfirmed ? 'fa-circle-check text-success fs-4' : 'fa-hand-holding-dollar text-warning fs-4 animate__animated animate__pulse animate__infinite'"></i>
                        </div>

                        <!-- Danh sách món tóm tắt -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small fw-bold text-secondary text-uppercase" style="letter-spacing: 0.5px;">Chi tiết món ({{ (selectedQrOrder.items || []).length }})</span>
                                <span class="smaller text-muted">Kiểm tra trước khi chuyển bếp</span>
                            </div>
                            <div class="d-flex flex-column gap-1.5 p-2.5 rounded-3 bg-light border border-light-subtle" style="max-height: 160px; overflow-y: auto;">
                                <div v-for="(it, idx) in selectedQrOrder.items" :key="idx" class="d-flex justify-content-between align-items-center small py-1 border-bottom border-light">
                                    <div>
                                        <span class="badge bg-dark rounded-pill me-1.5 px-1.5">x{{ it.so_luong }}</span>
                                        <strong class="text-dark">{{ it.ten_san_pham }}</strong>
                                        <span v-if="it.tuy_chon" class="text-muted smaller ms-1.5">({{ formatTuyChon(it.tuy_chon) }})</span>
                                    </div>
                                    <span class="fw-bold text-primary">{{ formatVND(it.don_gia * it.so_luong) }}đ</span>
                                </div>
                            </div>
                        </div>

                        <!-- Luồng KDS & In Bill -->
                        <div class="p-2.5 rounded-3 border d-flex align-items-center gap-2.5 bg-emerald-subtle border-emerald-subtle">
                            <div class="bg-success text-white rounded-circle p-1.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px;">
                                <i class="fa-solid fa-mug-hot font-12"></i>
                            </div>
                            <div class="smaller text-success-emphasis">
                                Khi duyệt: Đơn chuyển trực tiếp xuống <strong>Quầy Barista KDS</strong> để pha chế (tự động trừ kho định lượng) và tự động mở tab <strong>In hóa đơn</strong>.
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="luxury-modal-footer bg-light px-4 py-3 d-flex justify-content-end align-items-center gap-2 border-top">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-semibold" data-bs-dismiss="modal">
                            Hủy bỏ
                        </button>

                        <!-- Khi đơn yêu cầu tiền mặt mà thu ngân CHƯA bấm xác nhận thu tiền -->
                        <button
                            v-if="selectedQrOrder.yeu_cau_tien_mat == 1 && !cashConfirmed"
                            type="button"
                            class="btn rounded-pill px-4 py-2.5 fw-bold d-flex align-items-center gap-2 border shadow-none"
                            style="background-color: #e2e8f0 !important; color: #475569 !important; border-color: #cbd5e1 !important; cursor: not-allowed; font-size: 13.5px;"
                            disabled
                        >
                            <i class="fa-solid fa-lock text-secondary"></i>
                            <span>Chưa xác nhận thu tiền mặt</span>
                        </button>

                        <!-- Khi ĐÃ sẵn sàng duyệt (đã xác nhận tiền mặt HOẶC là đơn VietQR) -->
                        <button
                            v-else
                            type="button"
                            class="btn rounded-pill px-4 py-2.5 fw-bold d-flex align-items-center gap-2 text-white shadow"
                            style="background: linear-gradient(135deg, #10b981, #059669) !important; border: none !important; cursor: pointer; font-size: 13.5px;"
                            :disabled="approvingOrder"
                            @click="confirmApproveOrder"
                        >
                            <span v-if="approvingOrder" class="spinner-border spinner-border-sm text-white"></span>
                            <template v-else>
                                <i class="fa-solid fa-circle-check text-white fs-6"></i>
                                <span class="text-white fw-bold">{{ approvingOrder ? 'Đang duyệt đơn...' : 'Xác Nhận Duyệt & In Hóa Đơn' }}</span>
                            </template>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL TỪ CHỐI ĐƠN QR (UC27) -->
        <div class="modal fade" id="rejectQrOrderModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content luxury-modal-content" v-if="selectedRejectOrder">
                    <!-- Modal Header -->
                    <div class="luxury-modal-header bg-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="modal-header-icon icon-crimson">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <div>
                                <h5 class="fw-bolder text-dark mb-0.5">Từ Chối Đơn Hàng QR</h5>
                                <div class="d-flex align-items-center gap-2 small text-muted">
                                    <span class="badge bg-danger-subtle text-danger fw-bold font-12">{{ selectedRejectOrder.ten_ban }}</span>
                                    <span>•</span>
                                    <span class="fw-semibold">Mã HĐ: #{{ selectedRejectOrder.ma_hoa_don }}</span>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body p-4 bg-white">
                        <div class="alert alert-danger bg-danger-subtle border-danger border-opacity-25 rounded-3 p-3 mb-3 small text-danger d-flex align-items-start gap-2">
                            <i class="fa-solid fa-circle-info fs-5 mt-0.5 flex-shrink-0"></i>
                            <div>
                                Bạn đang từ chối đơn hàng của <strong>{{ selectedRejectOrder.ten_ban }}</strong> (Tổng: <strong class="text-danger">{{ formatVND(selectedRejectOrder.tong_tien) }}đ</strong>). Sau khi từ chối, đơn sẽ bị hủy khỏi hệ thống.
                            </div>
                        </div>

                        <label class="form-label small fw-bold text-dark mb-2">Chọn nhanh lý do từ chối:</label>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <button
                                v-for="r in ['Hết nguyên liệu pha chế', 'Khách đổi ý hủy bàn', 'Bàn chưa có khách ngồi', 'Quán đang quá tải']"
                                :key="r"
                                type="button"
                                class="btn btn-sm rounded-pill px-3 py-1.5 fw-semibold transition-all"
                                :class="rejectReason === r ? 'btn-danger text-white shadow-xs' : 'btn-outline-secondary'"
                                @click="rejectReason = r"
                            >
                                {{ r }}
                            </button>
                        </div>

                        <label class="form-label small fw-bold text-dark mb-1">Ghi chú lý do chi tiết:</label>
                        <textarea
                            v-model="rejectReason"
                            rows="2"
                            class="form-control rounded-3"
                            placeholder="Nhập lý do cụ thể..."
                        ></textarea>
                    </div>

                    <!-- Modal Footer -->
                    <div class="luxury-modal-footer bg-light px-4 py-3 d-flex justify-content-end align-items-center gap-2 border-top">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-semibold" data-bs-dismiss="modal">
                            Quay lại
                        </button>
                        <button
                            type="button"
                            class="btn rounded-pill px-4 py-2.5 fw-bold text-white shadow-sm d-flex align-items-center gap-2"
                            :style="(!rejectReason || rejectReason.trim() === '') ? 'background-color: #fca5a5 !important; border: 1px solid #fca5a5 !important; color: #ffffff !important; cursor: not-allowed; font-size: 13.5px;' : 'background: linear-gradient(135deg, #ef4444, #dc2626) !important; border: none !important; cursor: pointer; box-shadow: 0 4px 14px rgba(220, 38, 38, 0.35) !important; font-size: 13.5px;'"
                            :disabled="rejectingOrder || !rejectReason || rejectReason.trim() === ''"
                            @click="confirmRejectOrder"
                        >
                            <span v-if="rejectingOrder" class="spinner-border spinner-border-sm text-white"></span>
                            <template v-else>
                                <i class="fa-solid fa-xmark text-white fs-6"></i>
                                <span class="text-white fw-bold">{{ rejectingOrder ? 'Đang xử lý...' : 'Xác Nhận Từ Chối' }}</span>
                            </template>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL CHỐT CA NGAY TẠI POS (UC30) -->
        <div class="modal fade" id="posChotCaModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header bg-dark text-white border-0 rounded-top-4">
                        <h5 class="modal-title fw-bold">
                            <i class="fa-solid fa-stopwatch me-2 text-warning"></i>
                            Chốt Ca Bán Hàng & Bàn Giao Két Tiền (Z-Report)
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4" v-if="posChotCaData">
                        <div class="bg-light p-3 rounded-3 mb-3 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-amber-100 text-amber-800 me-2">{{ posChotCaData.phien_ca?.ma_phien_ca }}</span>
                                <span class="fw-bold">{{ posChotCaData.phien_ca?.nhan_vien?.ho_va_ten }}</span>
                                <span class="text-muted ms-2">({{ posChotCaData.phien_ca?.ca_lam_viec?.ten_ca || 'Ca linh hoạt' }})</span>
                            </div>
                            <div class="small text-muted">
                                Giờ mở: <span class="text-dark fw-semibold">{{ formatDateTime(posChotCaData.phien_ca?.thoi_gian_mo_ca) }}</span>
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-4">
                                <div class="p-2.5 bg-light rounded-3 text-center border">
                                    <div class="small text-secondary">Tiền Đầu Ca (Két)</div>
                                    <div class="fw-bold text-dark fs-6 mt-1">{{ formatVND(posChotCaData.tien_dau_ca) }} đ</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2.5 bg-emerald-50 rounded-3 text-center border border-emerald-100">
                                    <div class="small text-emerald-800">Tiền Mặt Thu Được</div>
                                    <div class="fw-bold text-emerald-700 fs-6 mt-1">+{{ formatVND(posChotCaData.tong_thu_tien_mat) }} đ</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2.5 bg-blue-50 rounded-3 text-center border border-blue-100">
                                    <div class="small text-blue-800">VietQR Thu Được</div>
                                    <div class="fw-bold text-blue-700 fs-6 mt-1">+{{ formatVND(posChotCaData.tong_thu_chuyen_khoan) }} đ</div>
                                </div>
                            </div>
                        </div>

                        <div class="card border border-warning rounded-4 p-3 bg-amber-50/30 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-dark">Tiền mặt hệ thống tính phải có trong két:</span>
                                <span class="fs-5 fw-bold text-primary">{{ formatVND(posChotCaData.tien_mat_he_thong) }} đ</span>
                            </div>
                            <div class="small text-muted mb-3">
                                (= {{ formatVND(posChotCaData.tien_dau_ca) }} đ đầu ca + {{ formatVND(posChotCaData.tong_thu_tien_mat) }} đ tiền mặt thu)
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">
                                    <i class="fa-solid fa-coins text-warning me-1"></i>
                                    Tiền mặt THỰC TẾ kiểm đếm được trong két: <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-lg">
                                    <input type="number" v-model="posChotCaForm.tien_mat_thuc_te" class="form-control rounded-start-3 fw-bold text-dark fs-5" placeholder="Nhập tiền mặt thực đếm" min="0" step="1000">
                                    <span class="input-group-text bg-white fw-bold rounded-end-3">VNĐ</span>
                                </div>
                                <div class="small text-muted mt-1">{{ formatVND(posChotCaForm.tien_mat_thuc_te) }} đ</div>
                            </div>

                            <div class="p-3 rounded-3 d-flex justify-content-between align-items-center" :class="calcPosDiffVerdictClass">
                                <div>
                                    <div class="fw-bold fs-6">{{ calcPosDiffVerdictTitle }}</div>
                                    <div class="small">{{ calcPosDiffVerdictDesc }}</div>
                                </div>
                                <div class="fs-4 fw-bold">
                                    {{ formatDiff(calcPosDiffVal) }}
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">Ghi chú bàn giao ca / Giải trình chênh lệch</label>
                            <textarea v-model="posChotCaForm.ghi_chu" class="form-control rounded-3" rows="2" placeholder="Ghi chú bàn giao..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-3 pt-0">
                        <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" @click="submitPosChotCa" class="btn btn-danger rounded-3 fw-bold px-4" :disabled="submittingChotCa">
                            <span v-if="submittingChotCa" class="spinner-border spinner-border-sm me-1"></span>
                            <i v-else class="fa-solid fa-lock me-1"></i>
                            Xác Nhận Chốt Ca & In Z-Report
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

</template>

<script>
import axios from 'axios';
import { formatTuyChonText, getTuyChonBadges } from '../../../config/tuyChon.js';
export default {
    data() {
        return {
            tim_sdt: "",
            tinh_trang_tim_kiem_sdt: true,
            list_san_pham: [],
            list_ban: [],
            tim_san_pham: "",
            tim_ban: "",
            dang_chon: 2,
            id_ban_chon: null,
            id_hoa_don_chon: {},
            list_mon_chi_tiet: [],
            mon_dang_chon: {
                so_luong: 1,
                ghi_chu: ""
            },
            ma_voucher_nhap: "",
            voucher_da_ap: null,
            mon_edit: {},
            mon_delete: {},
            khach_hang: null,
            create_khach_hang: {},
            user_login: {
            },
            created_at_hoa_don: null,
            phuong_thuc_thanh_toan: 1,
            syncChannel: null,
            onStorageChange: null,
            autoRefreshTimer: null,

            // Màn hình 2: Giám sát đơn QR tại bàn (UC26, UC27, UC17)
            activeTab: 'POS',
            filterQrType: 'ALL',
            isManualRefreshing: false,
            pendingQrOrders: [],
            previousPendingCount: 0,
            soundEnabled: true,
            qrPollingTimer: null,
            selectedQrOrder: null,
            selectedRejectOrder: null,
            rejectReason: 'Hết nguyên liệu pha chế',
            cashConfirmed: false,
            approvingOrder: false,
            rejectingOrder: false,

            // Nghiệp vụ phiên ca & giao ca (UC30)
            activeShift: null,
            checkingShift: true,
            submittingMoCa: false,
            submittingChotCa: false,
            posDanhSachCa: [],
            posMoCaForm: {
                tien_dau_ca: 0,
                id_ca_lam_viec: '',
                ghi_chu: '',
            },
            posChotCaData: null,
            posChotCaForm: {
                id_phien_ca: null,
                tien_mat_thuc_te: 0,
                ghi_chu: '',
            }
        }
    },
    mounted() {
        this.checkActiveShift();
        this.loadDataSanPham();
        this.loadDataBan();
        this.layThongTinUser();
        this.fetchPendingQrOrders();

        // Đồng bộ thời gian thực với Bếp Chế Biến qua BroadcastChannel
        if (typeof BroadcastChannel !== 'undefined') {
            this.syncChannel = new BroadcastChannel('coffeehub_sync');
            this.syncChannel.onmessage = (event) => {
                this.fetchPendingQrOrders();
                if (event.data && (event.data.type === 'CHEF_XONG_MON' || event.data.type === 'BEP_UPDATE' || event.data.type === 'BILL_PAID' || event.data.type === 'QR_ORDER_NEW')) {
                    if (this.id_ban_chon) {
                        this.reloadChiTietMon(this.id_ban_chon);
                    }
                    this.loadDataBan();
                }
            };
        }

        // Lắng nghe sự kiện đồng bộ qua localStorage (hỗ trợ đa tab)
        this.onStorageChange = (e) => {
            if (e.key === 'coffeehub_sync_time') {
                if (this.id_ban_chon) {
                    this.reloadChiTietMon(this.id_ban_chon);
                }
                this.loadDataBan();
                this.fetchPendingQrOrders();
            }
        };
        window.addEventListener('storage', this.onStorageChange);

        // Polling tự động kiểm tra cập nhật mỗi 2.5 giây (hỗ trợ trường hợp khác thiết bị)
        this.autoRefreshTimer = setInterval(() => {
            if (this.id_ban_chon) {
                this.reloadChiTietMon(this.id_ban_chon);
            }
            this.loadDataBan();
        }, 2500);

        // Polling danh sách đơn QR chờ duyệt mỗi 3 giây (UC26)
        this.qrPollingTimer = setInterval(() => {
            this.fetchPendingQrOrders();
        }, 3000);
    },
    beforeUnmount() {
        if (this.syncChannel) {
            this.syncChannel.close();
        }
        if (this.onStorageChange) {
            window.removeEventListener('storage', this.onStorageChange);
        }
        if (this.autoRefreshTimer) {
            clearInterval(this.autoRefreshTimer);
        }
        if (this.qrPollingTimer) {
            clearInterval(this.qrPollingTimer);
        }
    },
    computed: {
        calcPosDiffVal() {
            if (!this.posChotCaData) return 0;
            const thucTe = Number(this.posChotCaForm.tien_mat_thuc_te || 0);
            const heThong = Number(this.posChotCaData.tien_mat_he_thong || 0);
            return thucTe - heThong;
        },
        calcPosDiffVerdictTitle() {
            if (this.calcPosDiffVal === 0) return '✓ Két tiền khớp 100%';
            if (this.calcPosDiffVal > 0) return '▲ Thừa tiền trong két';
            return '▼ Thiếu hụt tiền trong két';
        },
        calcPosDiffVerdictDesc() {
            if (this.calcPosDiffVal === 0) return 'Số tiền thực tế đếm được hoàn toàn trùng khớp với hệ thống tính.';
            if (this.calcPosDiffVal > 0) return 'Số tiền mặt thực tế trong két nhiều hơn hệ thống tính.';
            return 'Số tiền mặt thực tế trong két ít hơn hệ thống tính! Vui lòng ghi chú lý do.';
        },
        calcPosDiffVerdictClass() {
            if (this.calcPosDiffVal === 0) return 'bg-success text-white';
            if (this.calcPosDiffVal > 0) return 'bg-primary text-white';
            return 'bg-danger text-white';
        },
        filteredSanPham() {
            if (this.tim_san_pham.trim() === "") {
                return this.list_san_pham;
            }
            return this.list_san_pham.filter((san_pham) =>
                san_pham.ten_san_pham.toLowerCase().includes(this.tim_san_pham.toLowerCase()) ||
                san_pham.ma_code.toLowerCase().includes(this.tim_san_pham.toLowerCase())
            );
        },
        filteredBan() {
            let list = this.list_ban;

            if (this.dang_chon !== 2) {
                list = list.filter((ban) => {
                    if (this.dang_chon === 1) {
                        return Number(ban.tinh_trang_ban) === 0;
                    } else {
                        return Number(ban.tinh_trang_ban) !== 0;
                    }
                });
            }

            if (this.tim_ban.trim() === "") {
                return list;
            }

            return list.filter((ban) =>
                ban.ten_ban.toLowerCase().includes(this.tim_ban.trim().toLowerCase())
            );
        },
        filteredPendingQrOrders() {
            if (this.filterQrType === 'CASH') {
                return this.pendingQrOrders.filter(o => o.yeu_cau_tien_mat == 1);
            }
            if (this.filterQrType === 'VIETQR') {
                return this.pendingQrOrders.filter(o => o.phuong_thuc_thanh_toan == 2);
            }
            return this.pendingQrOrders;
        },
        countCashOrders() {
            return this.pendingQrOrders.filter(o => o.yeu_cau_tien_mat == 1).length;
        },
        countVietQrOrders() {
            return this.pendingQrOrders.filter(o => o.phuong_thuc_thanh_toan == 2).length;
        },
    },
    watch: {
        activeTab(newVal) {
            if (newVal === 'QR_MONITOR') {
                this.fetchPendingQrOrders();
            } else if (newVal === 'POS') {
                this.loadDataBan();
            }
        },
        tim_sdt() {
            this.tinh_trang_tim_kiem_sdt = true;
        },
        tim_san_pham(val) {
            if (val.trim() === "") {
                return;
            }
            let found = this.list_san_pham.find((value) =>
                value.ma_code.toLowerCase() == val.toLowerCase());
            if (found) {
                this.mon_dang_chon = { ...found, so_luong: 1, ghi_chu: "" };
                this.themMonVaoHoaDon();
                this.tim_san_pham = "";
            }
        },
    },
    methods: {
        checkActiveShift() {
            this.checkingShift = true;
            const token = localStorage.getItem('xs');
            axios.get('http://127.0.0.1:8000/api/admin/phien-ca/ca-hien-tai', {
                headers: { Authorization: `Bearer ${token}` }
            })
            .then(res => {
                if (res.data.status) {
                    this.activeShift = res.data.data;
                    this.posDanhSachCa = res.data.danh_sach_ca || [];
                }
            })
            .catch(err => console.error(err))
            .finally(() => {
                this.checkingShift = false;
            });
        },
        openPosMoCaDirect() {
            this.posMoCaForm = { tien_dau_ca: 0, id_ca_lam_viec: '', ghi_chu: '' };
        },
        submitPosMoCa() {
            this.submittingMoCa = true;
            const token = localStorage.getItem('xs');
            axios.post('http://127.0.0.1:8000/api/admin/phien-ca/mo-ca', this.posMoCaForm, {
                headers: { Authorization: `Bearer ${token}` }
            })
            .then(res => {
                if (res.data.status) {
                    this.$toast?.success(res.data.message || 'Mở ca thành công!');
                    this.activeShift = res.data.data;
                } else {
                    this.$toast?.error(res.data.message || 'Không thể mở ca');
                }
            })
            .catch(err => {
                this.$toast?.error(err.response?.data?.message || 'Lỗi khi mở phiên ca');
            })
            .finally(() => {
                this.submittingMoCa = false;
            });
        },
        openPosChotCaModal() {
            this.submittingChotCa = true;
            const token = localStorage.getItem('xs');
            axios.get('http://127.0.0.1:8000/api/admin/phien-ca/du-lieu-chot-ca', {
                headers: { Authorization: `Bearer ${token}` }
            })
            .then(res => {
                if (res.data.status) {
                    this.posChotCaData = res.data.data;
                    this.posChotCaForm = {
                        id_phien_ca: res.data.data.phien_ca.id,
                        tien_mat_thuc_te: res.data.data.tien_mat_he_thong,
                        ghi_chu: '',
                    };
                    const modalEl = document.getElementById('posChotCaModal');
                    if (modalEl && window.bootstrap) {
                        new window.bootstrap.Modal(modalEl).show();
                    }
                } else {
                    this.$toast?.error(res.data.message || 'Không tìm thấy dữ liệu ca hiện tại');
                }
            })
            .catch(err => {
                this.$toast?.error('Lỗi khi tải dữ liệu chốt ca');
            })
            .finally(() => {
                this.submittingChotCa = false;
            });
        },
        submitPosChotCa() {
            this.submittingChotCa = true;
            const token = localStorage.getItem('xs');
            axios.post('http://127.0.0.1:8000/api/admin/phien-ca/chot-ca', this.posChotCaForm, {
                headers: { Authorization: `Bearer ${token}` }
            })
            .then(res => {
                if (res.data.status) {
                    this.$toast?.success(res.data.message || 'Chốt ca thành công!');
                    const modalEl = document.getElementById('posChotCaModal');
                    const modal = window.bootstrap?.Modal.getInstance(modalEl);
                    if (modal) modal.hide();

                    const idChot = res.data.id_phien_ca;
                    this.activeShift = null;

                    // Mở Z-Report
                    if (idChot) {
                        this.$router.push(`/admin/phien-ca/z-report/${idChot}`);
                    }
                } else {
                    this.$toast?.error(res.data.message || 'Chốt ca thất bại');
                }
            })
            .catch(err => {
                this.$toast?.error(err.response?.data?.message || 'Lỗi khi chốt phiên ca');
            })
            .finally(() => {
                this.submittingChotCa = false;
            });
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
        formatTuyChon(tc) {
            return formatTuyChonText(tc);
        },
        getBadges(tc) {
            return getTuyChonBadges(tc);
        },
        formatGhiChu(item) {
            if (!item.ghi_chu || item.ghi_chu.trim() === '') return '-';
            let text = item.ghi_chu.trim();
            text = text.replace(/\s*\([^)]*(Size|Đá|Đường|Trân châu|Thạch|Cheese)[^)]*\)/gi, '').trim();
            if (item.tuy_chon && (text.startsWith('Size ') || text.startsWith('+ ') || text.includes(' • '))) {
                return '-';
            }
            return text || '-';
        },
        reloadChiTietMon(id) {
            if (!id) return;
            axios
                .get(`http://127.0.0.1:8000/api/admin/chi-tiet-hoa-don/get-theo-ban/${id}`, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    if (res.data && res.data.data) {
                        this.list_mon_chi_tiet = res.data.data;
                    }
                })
                .catch(() => {});
        },
        resetChiTiet() {
            this.id_ban_chon = null;
            this.id_hoa_don_chon = {};
            this.list_mon_chi_tiet = [];
            this.voucher_da_ap = null;
            this.ma_voucher_nhap = "";
            this.khach_hang = null;
            this.created_at_hoa_don = null;
            this.tim_sdt = "";
            this.tinh_trang_tim_kiem_sdt = true;
            this.phuong_thuc_thanh_toan = 1;
        },
        xacNhanThanhToan() {
            if (!this.id_ban_chon || !this.id_hoa_don_chon.id) {
                this.$toast.error('Vui lòng chọn bàn có hóa đơn cần thanh toán!');
                return;
            }
            if (this.list_mon_chi_tiet.length === 0) {
                this.$toast.error('Bàn này chưa có món nào để thanh toán!');
                return;
            }

            let payload = {
                'id_hoa_don': this.id_hoa_don_chon.id,
                'id_ban': this.id_ban_chon,
                'phuong_thuc_thanh_toan': this.phuong_thuc_thanh_toan
            };
            axios
                .post('http://127.0.0.1:8000/api/admin/hoa-don/xac-nhan-thanh-toan', payload, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message || 'Xác nhận thanh toán thành công!');
                        this.loadDataBan();
                        this.resetChiTiet();
                        if (typeof BroadcastChannel !== 'undefined') {
                            const bc = new BroadcastChannel('coffeehub_sync');
                            bc.postMessage({ type: 'BILL_PAID', id_ban: payload.id_ban });
                            bc.close();
                        }
                        localStorage.setItem('coffeehub_sync_time', Date.now());
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .catch((err) => {
                    this.$toast.error('Đã xảy ra lỗi khi xác nhận thanh toán!');
                });
        },
        thanhToan() {
            let payload = {
                'id_hoa_don': this.id_hoa_don_chon.id,
                'phuong_thuc_thanh_toan': this.phuong_thuc_thanh_toan
            };
            axios
                .post('http://127.0.0.1:8000/api/admin/hoa-don/thanh-toan', payload, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.loadDataBan();
                        if (this.phuong_thuc_thanh_toan === 1) {
                            this.resetChiTiet();
                        }
                    } else {
                        this.$toast.error(res.data.message);
                    }
                });
        },
        timKH() {
            let payload = {
                'so_dien_thoai': this.tim_sdt,
                'id_hoa_don': this.id_hoa_don_chon.id
            };
            axios.post('http://127.0.0.1:8000/api/admin/khach-hang/tim-khach-hang', payload, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('xs')}`
                }
            })
                .then((res) => {
                    if (res.data.status) {
                        this.tinh_trang_tim_kiem_sdt = true;
                        this.$toast.success(res.data.message);
                        this.getHoaDonInfo(this.id_ban_chon);
                    } else {
                        this.tinh_trang_tim_kiem_sdt = false;
                        this.$toast.error(res.data.message);
                        this.create_khach_hang = {
                            so_dien_thoai: this.tim_sdt,
                            ho_va_ten: '',
                            email: '',
                            tinh_trang: 1
                        };
                    }
                });
        },
        themKhachHang() {
            this.create_khach_hang.id_hoa_don = this.id_hoa_don_chon.id;
            axios.post('http://127.0.0.1:8000/api/admin/khach-hang/store', this.create_khach_hang, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('xs')}`
                }
            })
                .then((res) => {
                    if (res.data.status) {
                        this.tinh_trang_tim_kiem_sdt = true;
                        this.$toast.success(res.data.message);

                        this.getHoaDonInfo(this.id_ban_chon);
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })

        },

        moBan(id_ban) {
            axios
                .get(`http://127.0.0.1:8000/api/admin/hoa-don/mo-ban/${id_ban}`, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.id_ban_chon = res.data.id_ban;
                        this.layChiTiet(this.id_ban_chon);
                        this.loadDataBan();
                    } else {
                        this.$toast.error(res.data.message);
                    }
                });
        },
        loadDataSanPham() {
            axios
                .get('http://127.0.0.1:8000/api/admin/san-pham/get-data-active', {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    this.list_san_pham = res.data.data;
                });
        },
        loadDataBan() {
            axios
                .get('http://127.0.0.1:8000/api/admin/ban/ban-hang/get-data', {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    this.list_ban = res.data.data;
                });
        },
        layThongTinUser() {
            axios
                .get('http://127.0.0.1:8000/api/check-login', {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    if (res.data.status === 1 && res.data.user) {
                        this.user_login = res.data.user;
                    }
                });
        },
        chon(value) {
            this.dang_chon = value;
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
        layChiTiet(id) {
            this.id_ban_chon = id;
            axios
                .get(`http://127.0.0.1:8000/api/admin/chi-tiet-hoa-don/get-theo-ban/${id}`, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    this.list_mon_chi_tiet = res.data.data;
                });
            this.getHoaDonInfo(id);
        },

        getHoaDonInfo(id) {
            axios
                .get(`http://127.0.0.1:8000/api/admin/hoa-don/get-hoa-don/${id}`, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    if (res.data.status) {
                        this.id_hoa_don_chon.id = res.data.id_hoa_don;
                        this.voucher_da_ap = res.data.voucher;
                        this.ma_voucher_nhap = res.data.voucher ? res.data.voucher.ma_voucher : "";
                        this.khach_hang = res.data.khach_hang;
                        this.created_at_hoa_don = res.data.created_at;
                    } else {
                        this.$toast.error(res.data.message);
                    }
                });
        },

        inPhaChe() {
            axios
                .get(`http://127.0.0.1:8000/api/admin/chi-tiet-hoa-don/in-pha-che/${this.id_ban_chon}`, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.layChiTiet(this.id_ban_chon);
                        if (typeof BroadcastChannel !== 'undefined') {
                            const bc = new BroadcastChannel('coffeehub_sync');
                            bc.postMessage({ type: 'IN_PHA_CHE', id_ban: this.id_ban_chon });
                            bc.close();
                        }
                        localStorage.setItem('coffeehub_sync_time', Date.now());
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
        },

        themMonVaoHoaDon() {
            let payload = {
                id_hd: this.id_ban_chon,
                id_san_pham: this.mon_dang_chon.id,
                so_luong: this.mon_dang_chon.so_luong,
                don_gia: this.mon_dang_chon.gia_ban,
                ghi_chu: this.mon_dang_chon.ghi_chu
            };
            axios
                .post('http://127.0.0.1:8000/api/admin/chi-tiet-hoa-don/them-mon', payload, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    if (res.data.status == 1) {
                        this.layChiTiet(this.id_ban_chon);
                        this.$toast.success(res.data.message);
                    } else {
                        this.$toast.error(res.data.message);
                    }
                });
        },
        getTenBanChon() {
            const ban = this.list_ban.find(b => b.id === this.id_ban_chon);
            return ban ? ban.ten_ban : 'Chưa chọn bàn';
        },

        apDungVoucher() {
            if (!this.id_ban_chon) {
                this.$toast.error('Vui lòng chọn bàn trước!');
                return;
            }
            if (!this.ma_voucher_nhap.trim()) return;

            axios
                .post('http://127.0.0.1:8000/api/admin/voucher/ap-dung', {
                    ma_voucher: this.ma_voucher_nhap.trim(),
                    id_ban: this.id_ban_chon,
                }, {
                    headers: { 'Authorization': `Bearer ${localStorage.getItem('xs')}` }
                })
                .then((res) => {
                    if (res.data.status) {
                        this.voucher_da_ap = res.data;
                        this.$toast.success(res.data.message);
                        this.getHoaDonInfo(this.id_ban_chon);
                    } else {
                        this.$toast.error(res.data.message);
                    }
                });
        },

        huyVoucher() {
            if (!this.id_ban_chon) {
                this.$toast.error('Vui lòng chọn bàn trước!');
                return;
            }
            axios
                .post('http://127.0.0.1:8000/api/admin/voucher/huy-voucher', {
                    id_ban: this.id_ban_chon
                }, {
                    headers: { 'Authorization': `Bearer ${localStorage.getItem('xs')}` }
                })
                .then((res) => {
                    if (res.data.status) {
                        this.voucher_da_ap = null;
                        this.ma_voucher_nhap = '';
                        this.$toast.success('Đã huỷ voucher!');
                        this.getHoaDonInfo(this.id_ban_chon);
                    } else {
                        this.$toast.error(res.data.message);
                    }
                });
        },

        suaMonChiTiet() {
            axios
                .post('http://127.0.0.1:8000/api/admin/chi-tiet-hoa-don/update', this.mon_edit, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.layChiTiet(this.id_ban_chon);
                    } else {
                        this.$toast.error(res.data.message);
                    }
                });
        },

        xoaMonChiTiet() {
            axios
                .post('http://127.0.0.1:8000/api/admin/chi-tiet-hoa-don/delete', this.mon_delete, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.layChiTiet(this.id_ban_chon);
                    } else {
                        this.$toast.error(res.data.message);
                    }
                });
        },

        // =========================================================================
        // MÀN HÌNH 2: CÁC METHOD GIÁM SÁT & DUYỆT ĐƠN HÀNG QR (UC26, UC27, UC17)
        // =========================================================================
        playNotificationBeep() {
            if (!this.soundEnabled) return;
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) return;
                const ctx = new AudioCtx();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
                osc.frequency.setValueAtTime(880, ctx.currentTime + 0.12); // A5
                gain.gain.setValueAtTime(0.2, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.35);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.35);
            } catch (e) {}
        },

        fetchPendingQrOrders() {
            axios.get('http://127.0.0.1:8000/api/pos/orders/pending', {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('xs')}`
                }
            })
            .then((res) => {
                if (res.data && res.data.status) {
                    const list = res.data.data || [];
                    // Bắn âm thanh chuông báo khi có đơn hàng mới (UC26)
                    if (list.length > this.previousPendingCount && this.previousPendingCount >= 0) {
                        this.playNotificationBeep();
                        if (list.length > 0) {
                            const newest = list[0];
                            this.$toast?.info(`Có đơn đặt món QR mới tại ${newest.ten_ban}!`);
                        }
                    }
                    this.previousPendingCount = list.length;
                    this.pendingQrOrders = list;
                }
            })
            .catch(() => {});
        },

        openApproveModal(order) {
            this.selectedQrOrder = order;
            this.cashConfirmed = (order.yeu_cau_tien_mat != 1);
            const el = document.getElementById('approveQrOrderModal');
            if (el && window.bootstrap) {
                const modal = new window.bootstrap.Modal(el);
                modal.show();
            }
        },

        confirmApproveOrder() {
            if (!this.selectedQrOrder) return;
            if (this.selectedQrOrder.yeu_cau_tien_mat == 1 && !this.cashConfirmed) {
                this.$toast?.warning('Vui lòng tích xác nhận đã thu đủ tiền mặt từ khách hàng!');
                return;
            }

            this.approvingOrder = true;
            const orderId = this.selectedQrOrder.id;
            const isCash = this.selectedQrOrder.yeu_cau_tien_mat == 1;

            axios.post(`http://127.0.0.1:8000/api/pos/orders/${orderId}/approve`, {
                payment_method: isCash ? 'TIEN_MAT' : 'VIETQR'
            }, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('xs')}`
                }
            })
            .then((res) => {
                if (res.data && res.data.status) {
                    this.$toast?.success(res.data.message || 'Duyệt đơn Pay-First và chuyển bếp thành công!');

                    // Đóng modal
                    const el = document.getElementById('approveQrOrderModal');
                    if (el && window.bootstrap) {
                        const m = window.bootstrap.Modal.getInstance(el);
                        if (m) m.hide();
                    }

                    // Tự động mở tab in hóa đơn (UC27)
                    window.open(`/admin/in-hoa-don/${orderId}`, '_blank');

                    // Đồng bộ realtime sang KDS Bếp
                    if (typeof BroadcastChannel !== 'undefined') {
                        const bc = new BroadcastChannel('coffeehub_sync');
                        bc.postMessage({ type: 'BILL_PAID', id_ban: this.selectedQrOrder.id_ban });
                        bc.close();
                    }
                    localStorage.setItem('coffeehub_sync_time', Date.now());

                    this.fetchPendingQrOrders();
                    this.loadDataBan();
                } else {
                    this.$toast?.error(res.data.message || 'Lỗi duyệt đơn!');
                }
            })
            .catch((err) => {
                this.$toast?.error(err.response?.data?.message || 'Có lỗi xảy ra khi duyệt đơn!');
            })
            .finally(() => {
                this.approvingOrder = false;
            });
        },

        openRejectModal(order) {
            this.selectedRejectOrder = order;
            this.rejectReason = 'Hết nguyên liệu pha chế';
            const el = document.getElementById('rejectQrOrderModal');
            if (el && window.bootstrap) {
                const modal = new window.bootstrap.Modal(el);
                modal.show();
            }
        },

        confirmRejectOrder() {
            if (!this.selectedRejectOrder) return;
            if (!this.rejectReason || this.rejectReason.trim() === '') {
                this.$toast?.warning('Vui lòng nhập lý do từ chối!');
                return;
            }

            this.rejectingOrder = true;
            const orderId = this.selectedRejectOrder.id;

            axios.post(`http://127.0.0.1:8000/api/pos/orders/${orderId}/reject`, {
                ly_do: this.rejectReason
            }, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('xs')}`
                }
            })
            .then((res) => {
                if (res.data && res.data.status) {
                    this.$toast?.success(res.data.message || 'Đã từ chối đơn hàng thành công!');

                    const el = document.getElementById('rejectQrOrderModal');
                    if (el && window.bootstrap) {
                        const m = window.bootstrap.Modal.getInstance(el);
                        if (m) m.hide();
                    }

                    this.fetchPendingQrOrders();
                    this.loadDataBan();
                } else {
                    this.$toast?.error(res.data.message || 'Lỗi từ chối đơn!');
                }
            })
            .catch((err) => {
                this.$toast?.error(err.response?.data?.message || 'Có lỗi xảy ra khi từ chối đơn!');
            })
            .finally(() => {
                this.rejectingOrder = false;
            });
        },

        isSameOption(item) {
            if (!item.ghi_chu) return false;
            let text = item.ghi_chu.trim();
            return (item.tuy_chon && (text.startsWith('Size ') || text.startsWith('+ ') || text.includes(' • ')));
        },

        handleManualRefresh() {
            this.isManualRefreshing = true;
            this.fetchPendingQrOrders();
            setTimeout(() => {
                this.isManualRefreshing = false;
            }, 600);
        },

        formatTimeAgo(timeStr) {
            if (!timeStr) return '';
            const d = new Date(timeStr);
            if (isNaN(d.getTime())) return '';
            const diffSec = Math.floor((Date.now() - d.getTime()) / 1000);
            if (diffSec < 45) return 'Vừa xong';
            const diffMin = Math.floor(diffSec / 60);
            if (diffMin < 60) return `${diffMin} phút trước`;
            const diffHour = Math.floor(diffMin / 60);
            return `${diffHour} giờ trước`;
        },

        formatTimeOnly(timeStr) {
            if (!timeStr) return '';
            const d = new Date(timeStr);
            if (isNaN(d.getTime())) return '';
            const h = String(d.getHours()).padStart(2, '0');
            const m = String(d.getMinutes()).padStart(2, '0');
            return `${h}:${m}`;
        },
    }
}
</script>

<style scoped>
/* =======================================================
   COFFEEHUB ADMIN - MODERN MÀN HÌNH 2 & LUXURY MODALS STYLES
   ======================================================= */

/* Tab navigation */
.custom-segmented-control {
    background: #f1f5f9;
    padding: 4px;
    border-radius: 9999px;
    display: inline-flex;
    gap: 4px;
}
.custom-tab-btn {
    border: none;
    background: transparent;
    padding: 8px 18px;
    border-radius: 9999px;
    font-weight: 700;
    font-size: 13.5px;
    color: #64748b;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    cursor: pointer;
}
.custom-tab-btn:hover {
    color: #1e293b;
}
.custom-tab-btn.active {
    background: #ffffff;
    color: #0d6efd;
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.12), 0 1px 3px rgba(0, 0, 0, 0.05);
}

/* Badge pulsating red animation */
.pulse-badge-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #ffffff;
    font-size: 11px;
    font-weight: 800;
    padding: 2px 8px;
    border-radius: 9999px;
    box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
    animation: badge-pulse-anim 1.8s infinite;
}
@keyframes badge-pulse-anim {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
    70% { transform: scale(1.05); box-shadow: 0 0 0 8px rgba(239, 68, 68, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
}

/* Modern Header Card */
.screen2-header-card {
    background: #ffffff;
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 16px;
    box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.03);
    padding: 14px 20px;
    margin-bottom: 20px;
}
.screen2-icon-box {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}
.live-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 9999px;
    background: #ecfdf5;
    color: #059669;
    border: 1px solid rgba(16, 185, 129, 0.2);
}
.live-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #10b981;
    animation: live-blink 1.4s infinite;
}
@keyframes live-blink {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.4; transform: scale(0.8); }
}

/* Filter pills */
.filter-pill-btn {
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #64748b;
    border-radius: 9999px;
    padding: 5px 14px;
    font-size: 12.5px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}
.filter-pill-btn:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #1e293b;
}
.filter-pill-btn.active {
    background: #0f172a;
    border-color: #0f172a;
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.2);
}

/* =======================================================
   LUXURY POS TICKET CARD DESIGN
   ======================================================= */
.pos-ticket-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.02);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
}
.pos-ticket-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 28px -4px rgba(0, 0, 0, 0.08), 0 2px 6px rgba(0, 0, 0, 0.04);
    border-color: #cbd5e1;
}

/* Header */
.ticket-header {
    padding: 16px 18px 12px 18px;
    background: #ffffff;
    border-bottom: 1px solid #f1f5f9;
}
.table-badge {
    background: #0f172a;
    color: #ffffff;
    font-weight: 800;
    font-size: 13.5px;
    padding: 5px 12px;
    border-radius: 8px;
    letter-spacing: 0.2px;
    display: inline-flex;
    align-items: center;
}
.order-code-tag {
    background: #f8fafc;
    color: #475569;
    border: 1px solid #e2e8f0;
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    font-size: 13px;
    font-weight: 700;
    padding: 4px 8px;
    border-radius: 6px;
}
.ticket-time {
    color: #94a3b8;
    font-size: 12px;
    font-weight: 600;
}

/* Status Notice Banner */
.ticket-status-card {
    margin-top: 10px;
    padding: 10px 12px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.status-cash {
    background: #fffbeb;
    border: 1px solid #fef3c7;
    border-left: 3px solid #f59e0b;
}
.status-cash .status-icon {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    background: #f59e0b;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
}
.status-cash .status-heading {
    color: #92400e;
    font-size: 12.5px;
    font-weight: 800;
    margin-bottom: 1px;
}
.status-cash .status-sub {
    color: #b45309;
    font-size: 11px;
    line-height: 1.3;
}

.status-vietqr-paid {
    background: #ecfdf5;
    border: 1px solid #d1fae5;
    border-left: 3px solid #10b981;
}
.status-vietqr-paid .status-icon {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    background: #10b981;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
}
.status-vietqr-paid .status-heading {
    color: #065f46;
    font-size: 12.5px;
    font-weight: 800;
    margin-bottom: 1px;
}
.status-vietqr-paid .status-sub {
    color: #047857;
    font-size: 11px;
    line-height: 1.3;
}

.status-vietqr-wait {
    background: #eff6ff;
    border: 1px solid #dbeafe;
    border-left: 3px solid #3b82f6;
}
.status-vietqr-wait .status-icon {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    background: #3b82f6;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
}
.status-vietqr-wait .status-heading {
    color: #1e40af;
    font-size: 12.5px;
    font-weight: 800;
    margin-bottom: 1px;
}
.status-vietqr-wait .status-sub {
    color: #1d4ed8;
    font-size: 11px;
    line-height: 1.3;
}

/* Meta line */
.ticket-meta {
    padding: 10px 18px 6px 18px;
    font-size: 12.5px;
}
.customer-info {
    font-weight: 600;
    color: #334155;
}
.item-count-chip {
    background: #f1f5f9;
    color: #475569;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 12px;
}

/* Items List */
.ticket-items {
    padding: 6px 18px;
    max-height: 220px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.ticket-item-tile {
    background: #fafaf9;
    border: 1px solid #f5f5f4;
    border-radius: 10px;
    padding: 9px 12px;
    transition: background 0.15s;
}
.ticket-item-tile:hover {
    background: #f5f5f4;
}
.qty-badge {
    background: #e7e5e4;
    color: #1c1917;
    font-weight: 800;
    font-size: 11.5px;
    padding: 2px 6px;
    border-radius: 5px;
}
.item-title {
    font-weight: 700;
    font-size: 13.5px;
    color: #1c1917;
}
.item-price {
    font-weight: 800;
    font-size: 13.5px;
    color: #0f172a;
}
.item-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    margin-top: 6px;
}
.opt-tag {
    background: #ffffff;
    color: #57534e;
    border: 1px solid #e7e5e4;
    font-size: 11px;
    font-weight: 600;
    padding: 2px 7px;
    border-radius: 5px;
}
.item-note {
    margin-top: 6px;
    padding: 4px 8px;
    background: #fffbeb;
    border-left: 2px solid #f59e0b;
    border-radius: 0 6px 6px 0;
    font-size: 11.5px;
    font-style: italic;
    color: #92400e;
}

/* Footer */
.ticket-footer {
    padding: 12px 18px 16px 18px;
    background: #ffffff;
    border-top: 1px solid #f1f5f9;
    margin-top: auto;
}
.border-dashed {
    border-style: dashed !important;
}
.total-caption {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
}
.total-figure {
    font-size: 22px;
    font-weight: 900;
    color: #dc2626;
    letter-spacing: -0.5px;
}
.total-figure .currency {
    font-size: 15px;
    font-weight: 700;
    margin-left: 2px;
}

/* Buttons */
.btn-ticket-reject {
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #64748b;
    font-weight: 700;
    font-size: 13px;
    padding: 9px 16px;
    border-radius: 10px;
    transition: all 0.2s;
    cursor: pointer;
}
.btn-ticket-reject:hover {
    background: #fee2e2;
    border-color: #fca5a5;
    color: #dc2626;
}
.btn-ticket-approve {
    border: none;
    font-weight: 800;
    font-size: 13.5px;
    padding: 9px 18px;
    border-radius: 10px;
    color: #ffffff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
}
.approve-cash {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    box-shadow: 0 4px 12px rgba(217, 119, 6, 0.28);
}
.approve-cash:hover {
    background: linear-gradient(135deg, #d97706, #b45309);
    box-shadow: 0 6px 16px rgba(217, 119, 6, 0.38);
    transform: translateY(-1px);
}
.approve-vietqr {
    background: linear-gradient(135deg, #10b981, #059669);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.28);
}
.approve-vietqr:hover {
    background: linear-gradient(135deg, #059669, #047857);
    box-shadow: 0 6px 16px rgba(16, 185, 129, 0.38);
    transform: translateY(-1px);
}

.btn-approve-cash,
.btn-approve-vietqr {
    background: linear-gradient(135deg, #10b981, #059669) !important;
    border: none !important;
    color: #ffffff !important;
    box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35) !important;
}
.btn-approve-cash:hover,
.btn-approve-vietqr:hover {
    background: linear-gradient(135deg, #059669, #047857) !important;
    box-shadow: 0 6px 18px rgba(16, 185, 129, 0.45) !important;
    transform: translateY(-1px);
}

/* =======================================================
   LUXURY MODALS STYLES
   ======================================================= */
.luxury-modal-content {
    border-radius: 24px;
    border: none;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    overflow: hidden;
    background: #ffffff;
}
.luxury-modal-header {
    padding: 20px 24px 16px 24px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.modal-header-icon {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}
.icon-emerald {
    background: #ecfdf5;
    color: #059669;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
}
.icon-crimson {
    background: #fef2f2;
    color: #dc2626;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.15);
}

/* Modal Amount Box */
.modal-amount-hero {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 18px 20px;
    text-align: center;
    margin-bottom: 16px;
    position: relative;
}
.hero-label {
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #64748b;
    margin-bottom: 4px;
}
.hero-amount {
    font-size: 32px;
    font-weight: 900;
    color: #dc2626;
    letter-spacing: -0.5px;
    line-height: 1.2;
}

/* Interactive Cash Confirm Card */
.cash-confirm-interactive-card {
    background: #fffbeb;
    border: 2px dashed #f59e0b;
    border-radius: 16px;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    gap: 14px;
    cursor: pointer;
    user-select: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    margin-bottom: 16px;
}
.cash-confirm-interactive-card:hover {
    background: #fef3c7;
    transform: translateY(-1px);
}
.cash-confirm-interactive-card.is-confirmed {
    background: #ecfdf5;
    border: 2px solid #10b981;
    box-shadow: 0 4px 14px rgba(16, 185, 129, 0.15);
}
.custom-checkbox-disc {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: 2px solid #f59e0b;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    color: transparent;
    transition: all 0.2s;
    flex-shrink: 0;
}
.is-confirmed .custom-checkbox-disc {
    background: #10b981;
    border-color: #10b981;
    color: #ffffff;
    transform: scale(1.08);
}
.w-40 { width: 40%; }
.w-60 { width: 60%; }
.font-11 { font-size: 11px; }
.font-12 { font-size: 12px; }
.font-13 { font-size: 13px; }
.font-14 { font-size: 14px; }
.font-15 { font-size: 15px; }
.smaller { font-size: 0.8rem; }

/* Pos Shift Blocker (UC30) */
.pos-blocker-container {
    position: absolute;
    top: 52px;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1040;
    display: flex;
    align-items: center;
    justify-content: center;
}
.pos-blocker-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(4px);
}
.pos-blocker-card {
    position: relative;
    z-index: 1041;
    background: #ffffff;
    max-width: 460px;
    width: 90%;
    border-radius: 18px;
    padding: 30px 24px;
    text-align: center;
}
.pos-blocker-icon {
    width: 64px;
    height: 64px;
}
</style>