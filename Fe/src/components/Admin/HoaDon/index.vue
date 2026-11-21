<template>
    <div>
        <div class="container-fluid">
            <div class="row ">
                <!-- BẢNG BÊN TRÁI (DANH SÁCH HÓA ĐƠN) -->
                <div class="col-lg-8 col-md-12">
                    <div class="card rounded-3 border-top border-0 border-3 border-info shadow-sm">
                        <div class="card-header bg-white py-2">
                            <div class="row align-items-center g-2">
                                <div class="col-auto">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Từ Ngày</span>
                                        <input type="date" class="form-control" v-model="loc_ngay.tu_ngay"
                                            style="max-width:130px;">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Đến Ngày</span>
                                        <input type="date" class="form-control" v-model="loc_ngay.den_ngay"
                                            style="max-width:130px;">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-primary btn-sm fw-bold px-3" @click="timKiem()">
                                        <i class="bx bx-search-alt-2"></i> Tìm Kiếm
                                    </button>
                                </div>
                                <div class="col text-end">
                                    <span class="text-danger fw-bold fs-5 text-nowrap">{{ formatPrice(tongDoanhThu) }}
                                        đ</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body ">
                            <!-- BẢNG DANH SÁCH HÓA ĐƠN -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-sm align-middle mb-0">
                                    <thead class="table-primary text-center">
                                        <tr>
                                            <th class="">#</th>
                                            <th class="">Mã HĐ</th>
                                            <th class="">Ngày Bán</th>
                                            <th class="">Bàn</th>
                                            <th class="">Ca</th>
                                            <th class="">L.T.Toán</th>
                                            <th class="">Thanh Toán</th>
                                            <th class="">T.Trước giảm</th>
                                            <th class="">Giảm</th>
                                            <th class="">T.Tiền</th>
                                            <th class="">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(value, index) in filteredHoaDon" :key="index" class=""
                                            :class="{ 'table-info': selected_id === value.id }">
                                            <th class="">{{ index + 1 }}</th>
                                            <td class=""><b>{{ value.ma_hoa_don }}</b></td>
                                            <td class="">{{ formatDate(value.created_at) }}</td>
                                            <td class=" text-primary fw-bold">{{ value.ten_ban }}</td>
                                            <td class="">{{ value.ten_ca || 'N/A' }}</td>
                                            <td class="">
                                                {{ value.phuong_thuc_thanh_toan == 1 ? 'Tiền Mặt' :
                                                    (value.phuong_thuc_thanh_toan == 2 ? 'Chuyển Khoản' : 'Chưa chọn') }}
                                            </td>
                                            <td class="text-center">
                                                <span v-if="value.tinh_trang == 1" class="badge bg-success w-100">Đã
                                                    thanh
                                                    toán</span>
                                                <span v-else class="badge bg-warning text-dark w-100">Chưa thanh
                                                    toán</span>
                                            </td>
                                            <td class=" text-end">{{ formatPrice(value.tien_truoc_giam) }}</td>
                                            <td class=" text-end text-danger">{{ formatPrice(value.so_tien_giam) }}
                                            </td>
                                            <td class=" text-end fw-bold text-primary">{{
                                                formatPrice(value.tong_tien) }}</td>
                                            <td class="">
                                                <div class="d-flex justify-content-center gap-1">
                                                    <button class="btn btn-info btn-sm text-white py-0 "
                                                        @click="xemChiTiet(value)" title="Xem Chi Tiết"><i
                                                            class="fa-solid fa-file-invoice-dollar"></i></button>
                                                    <router-link
                                                        :to="{ path: '/admin/in-hoa-don', query: { id: value.id } }"
                                                        class="btn btn-warning btn-sm py-0" title="In Hóa Đơn"><i
                                                            class="fa-solid fa-print text-light"></i></router-link>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="filteredHoaDon.length === 0">
                                            <td colspan="11" class="text-center text-muted py-3">Không có hóa đơn nào.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BẢNG BÊN PHẢI (CHI TIẾT & BIỂU MẪU CẬP NHẬT) -->
                <div class="col-lg-4 col-md-12">
                    <div class="card rounded-3 border-top border-0 border-3 border-info shadow-sm">
                        <div class="card-header bg-white py-2">
                            <div class="row g-2 align-items-center">
                                <div class="col-6">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">vào</span>
                                        <input type="text" class="form-control"
                                            :value="formatDateTime(list_thong_tin_hoa_don_chi_tiet.created_at)">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">ra</span>
                                        <input type="text" class="form-control"
                                            :value="list_thong_tin_hoa_don_chi_tiet.tinh_trang == 1 ? formatDateTime(list_thong_tin_hoa_don_chi_tiet.updated_at) : 'Chưa ra'">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-2">
                            <!-- Bảng danh sách hàng của hóa đơn -->
                            <div class="table-responsive mb-2">
                                <table class="table table-bordered table-sm mb-0">
                                    <thead class="table-secondary text-center">
                                        <tr>
                                            <th>#</th>
                                            <th>Tên Hàng</th>
                                            <th>Size</th>
                                            <th>S.Lượng</th>
                                            <th>Đ.Giá</th>
                                            <th>G.Chú</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(value, index) in list_chi_tiet" :key="index" class="align-middle">
                                            <td class="text-center">{{ index + 1 }}</td>
                                            <td><b>{{ value.ten_san_pham }}</b></td>
                                            <td class="text-center">{{ value.kich_thuoc }}</td>
                                            <td class="text-center text-primary fw-bold">{{ value.so_luong }}</td>
                                            <td class="text-end">{{ formatPrice(value.don_gia) }}</td>
                                            <td><small class="text-muted">{{ value.ghi_chu }}</small></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Biểu mẫu cập nhật — 2 cột -->
                            <div class="row g-1">
                                <!-- Cột trái: thông tin -->
                                <div class="col-6">
                                    <div class="mb-1">
                                        <label class="form-label fw-bold mb-0">Mã Hóa Đơn</label>
                                        <input type="text" class="form-control form-control-sm"
                                            :value="list_thong_tin_hoa_don_chi_tiet.ma_hoa_don">
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label fw-bold mb-0">Bàn</label>
                                        <input type="text" class="form-control form-control-sm"
                                            :value="list_thong_tin_hoa_don_chi_tiet.ten_ban">
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label fw-bold mb-0">Loại Thanh Toán</label>
                                        <select class="form-select form-select-sm"
                                            :value="list_thong_tin_hoa_don_chi_tiet.phuong_thuc_thanh_toan" disabled>
                                            <option :value="null">Chưa chọn</option>
                                            <option :value="1">Tiền Mặt</option>
                                            <option :value="2">Chuyển Khoản</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Cột phải: thông tin -->
                                <div class="col-6">
                                    <div class="mb-1">
                                        <label class="form-label fw-bold mb-0">Tiền Trước Giảm</label>
                                        <input type="text" class="form-control form-control-sm text-end"
                                            :value="formatPrice(list_thong_tin_hoa_don_chi_tiet.tien_truoc_giam)">
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label fw-bold mb-0">Tiền Giảm Giá</label>
                                        <input type="text" class="form-control form-control-sm text-end"
                                            :value="formatPrice(list_thong_tin_hoa_don_chi_tiet.so_tien_giam)">
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label fw-bold mb-0">Thành Tiền</label>
                                        <input type="text"
                                            class="form-control form-control-sm text-end fw-bold text-danger"
                                            :value="formatPrice(list_thong_tin_hoa_don_chi_tiet.tong_tien)">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3 justify-content-end">
                                <div class="col-6">
                                    <button class="btn btn-danger btn-sm fw-bold w-100" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal">Xóa</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h1 class="modal-title text-white fs-5">
                            Xóa Hóa Đơn
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger" role="alert">
                            Bạn có chắc chắn muốn xóa hóa đơn <b class="text-danger">{{
                                list_thong_tin_hoa_don_chi_tiet.ma_hoa_don }}</b> không?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Đóng
                        </button>
                        <button type="button" class="btn btn-danger fw-bold" @click="xoaHoaDon()"
                            data-bs-dismiss="modal">Xác Nhận Xóa</button>
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
            list_hoa_don: [],
            list_chi_tiet: [],
            list_thong_tin_hoa_don_chi_tiet: {},
            selected_id: null,
            loc_ngay: {
                tu_ngay: '',
                den_ngay: ''
            }
        }
    },
    mounted() {
        this.loadDataHoaDon();
    },
    computed: {
        filteredHoaDon() {
            if (!this.loc_ngay.tu_ngay || !this.loc_ngay.den_ngay) {
                return this.list_hoa_don;
            }
            const start = new Date(this.loc_ngay.tu_ngay + 'T00:00:00');
            const end = new Date(this.loc_ngay.den_ngay + 'T23:59:59');

            return this.list_hoa_don.filter(value => {
                const itemDate = new Date(value.created_at);
                return itemDate >= start && itemDate <= end;
            });
        },
        tongDoanhThu() {
            return this.filteredHoaDon.reduce((sum, value) => {
                return value.tinh_trang == 1 ? sum + Number(value.tong_tien) : sum;
            }, 0);
        }
    },
    methods: {
        loadDataHoaDon() {
            axios
                .get('http://127.0.0.1:8000/api/admin/hoa-don/get-data', {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    this.list_hoa_don = res.data.data;
                })
        },
        xemChiTiet(value) {
            this.selected_id = value.id;
            axios
                .get(`http://127.0.0.1:8000/api/admin/chi-tiet-hoa-don/get-theo-hoa-don/${value.id}`, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    if (res.data.status) {
                        this.list_thong_tin_hoa_don_chi_tiet = res.data.hoa_don;
                        this.list_chi_tiet = res.data.chi_tiet;
                    }
                })
                .catch((err) => {
                    console.error(err);
                });
        },
        xoaHoaDon() {
            axios
                .post('http://127.0.0.1:8000/api/admin/hoa-don/delete', this.list_thong_tin_hoa_don_chi_tiet, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.list_thong_tin_hoa_don_chi_tiet = {};
                        this.list_chi_tiet = [];
                        this.selected_id = null;
                        this.loadDataHoaDon();
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .catch((err) => {
                    console.error(err);
                });
        },
        formatPrice(value) {
            if (value === null || value === undefined) return '0';
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
    }
}
</script>