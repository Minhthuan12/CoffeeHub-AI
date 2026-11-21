<template>
    <div class="d-flex flex-column overflow-hidden" style="height: 80vh;">

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
                                    <td class="text-start small">
                                        <div class="fw-bold">{{ val.ten_san_pham }}</div>
                                        <span v-if="val.tinh_trang === 0" class="badge bg-secondary">Chưa gửi</span>
                                        <span v-else-if="val.tinh_trang === 1" class="badge bg-warning text-dark">Đang
                                            pha chế</span>
                                        <span v-else-if="val.tinh_trang === 2" class="badge bg-success"><i
                                                class="fa-solid fa-check me-1"></i>Đã xong</span>
                                    </td>
                                    <td class="text-start text-muted small">{{ val.ghi_chu || '-' }}</td>
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

    </div>

</template>

<script>
import axios from 'axios';
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
            autoRefreshTimer: null
        }
    },
    mounted() {
        this.loadDataSanPham();
        this.loadDataBan();
        this.layThongTinUser();

        // Đồng bộ thời gian thực với Bếp Chế Biến qua BroadcastChannel
        if (typeof BroadcastChannel !== 'undefined') {
            this.syncChannel = new BroadcastChannel('coffeehub_sync');
            this.syncChannel.onmessage = (event) => {
                if (event.data && (event.data.type === 'CHEF_XONG_MON' || event.data.type === 'BEP_UPDATE')) {
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
    },
    computed: {
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
    },
    watch: {
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
    }
}
</script>

<style></style>