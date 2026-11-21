<template>
    <div class="container-fluid py-3">
        <!-- THANH CHỌN TUẦN -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center"
                        style="width: 42px; height: 42px;">
                        <i class="fa-solid fa-calendar-week fs-5"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold text-dark">Quản Lý & Đăng Ký Lịch Làm Việc</h5>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 bg-light p-2 rounded-pill border">
                    <button @click="changeWeek(-1)"
                        class="btn btn-sm btn-white rounded-pill shadow-sm px-3 fw-semibold">
                        <i class="fa-solid fa-chevron-left me-1"></i> Tuần trước
                    </button>
                    <button @click="currentWeek()" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold">
                        Tuần này
                    </button>
                    <span class="px-3 fw-bold text-dark">
                        {{ week_days[0]?.date_str }} — {{ week_days[6]?.date_str }}
                    </span>
                    <button @click="changeWeek(1)" class="btn btn-sm btn-white rounded-pill shadow-sm px-3 fw-semibold">
                        Tuần sau <i class="fa-solid fa-chevron-right ms-1"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- BLOCK 1: LỊCH LÀM CÁ NHÂN -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="fa-solid fa-user-clock text-primary me-2"></i> Lịch Làm Cá Nhân
                </h5>
                <span class="badge bg-light-primary text-primary px-3 py-2 rounded-pill fw-semibold">
                    Nhấn vào các ô để Đăng ký (X) / Hủy ca
                </span>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-bordered align-middle text-center mb-0">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th style="width: 120px;" class="py-3">Buổi</th>
                            <th v-for="(day, index) in week_days" :key="index" class="py-2">
                                <div class="fw-bold">{{ day.day_name }}</div>
                                <div class="small opacity-75 fw-normal">{{ day.date_str }}</div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="ca in list_ca_lam" :key="ca.id">
                            <td class="fw-bold bg-light text-dark py-3">{{ ca.ten_ca }}</td>
                            <td v-for="(day, dIdx) in week_days" :key="dIdx" class="py-3" style="cursor: pointer;"
                                @click="toggleDangKyCaNhan(day.full_date, ca.id)">
                                <div v-if="isDangKyCaNhan(day.full_date, ca.id)"
                                    class="bg-primary text-white rounded-circle shadow-sm mx-auto d-flex align-items-center justify-content-center fw-bold fs-6"
                                    style="width: 36px; height: 36px;">
                                    X
                                </div>
                                <div v-else
                                    class="border border-secondary rounded-circle mx-auto d-flex align-items-center justify-content-center text-muted"
                                    style="width: 36px; height: 36px; border-style: dashed !important;">
                                    <i class="fa-solid fa-plus opacity-25"></i>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- BLOCK 2: LỊCH LÀM CÔNG TY -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="fa-solid fa-users-rectangle text-primary me-2"></i> Lịch Làm Công Ty
                </h5>
                <span class="text-secondary small">Tổng hợp nhân viên đăng ký & phân công theo từng ca</span>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-bordered align-top mb-0">
                    <thead>
                        <tr class="bg-primary text-white text-center">
                            <th style="width: 120px;" class="py-3">Buổi</th>
                            <th v-for="(day, index) in week_days" :key="index" class="py-2">
                                <div class="fw-bold">{{ day.day_name }}</div>
                                <div class="small opacity-75 fw-normal">{{ day.date_str }}</div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="ca in list_ca_lam" :key="ca.id">
                            <td class="fw-bold bg-light text-dark text-center align-middle py-3">{{ ca.ten_ca }}</td>
                            <td v-for="(day, dIdx) in week_days" :key="dIdx" class="p-2 bg-white position-relative"
                                style="min-height: 90px;">
                                <div class="d-flex flex-wrap gap-1 mb-2">
                                    <span v-for="(nv, nvIdx) in getListNhanVienCompany(day.full_date, ca.id)"
                                        :key="nvIdx" class="badge rounded-pill text-white px-2 py-1 small shadow-sm"
                                        :style="{ backgroundColor: getBadgeColor(nv.ho_va_ten) }" :title="nv.ho_va_ten">
                                        {{ getShortName(nv.ho_va_ten) }}
                                    </span>
                                </div>
                                <div
                                    class="d-flex align-items-center justify-content-between mt-auto pt-1 border-top border-light small text-secondary">
                                    <span class="fw-bold">SL: {{ getListNhanVienCompany(day.full_date, ca.id).length
                                    }}</span>
                                    <button @click="openModalPhanCong(day.full_date, ca)"
                                        class="btn btn-sm p-0 text-primary border-0">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- CHÚ THÍCH (LEGEND) -->
        <div class="card border-0 shadow-sm rounded-4 p-3">
            <div class="d-flex align-items-center gap-4 text-secondary small">
                <span class="fw-bold text-dark">Chú thích:</span>
                <span class="d-flex align-items-center gap-2">
                    <span
                        class="badge rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center"
                        style="width: 22px; height: 22px;">X</span>
                    <span>= Đi làm</span>
                </span>
            </div>
        </div>

        <!-- MODAL PHÂN CÔNG QUẢN LÝ -->
        <div class="modal fade" id="modalPhanCong" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold text-dark">
                            Phân Công Ca Làm — {{ selected_modal.ca_name }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-3">
                        <p class="text-secondary small mb-3">Ngày làm: <b class="text-primary">{{
                            selected_modal.date_str }}</b></p>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">Chọn danh sách Nhân viên:</label>
                            <div class="list-group overflow-auto" style="max-height: 300px;">
                                <label v-for="nv in list_nhan_vien_all" :key="nv.id"
                                    class="list-group-item d-flex align-items-center gap-2 cursor-pointer">
                                    <input type="checkbox" :value="nv.id" v-model="selected_modal.selected_nv_ids"
                                        class="form-check-input flex-shrink-0">
                                    <span class="fw-semibold text-dark">{{ nv.ho_va_ten }}</span>
                                    <span class="badge bg-light text-secondary ms-auto small">{{ nv.ten_chuc_vu
                                        }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">Đóng</button>
                        <button type="button" @click="saveModalPhanCong()"
                            class="btn btn-primary rounded-pill px-4 fw-bold">
                            Lưu phân công
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'

export default {
    name: 'PhanCongCaLam',
    data() {
        return {
            current_monday: null,
            week_days: [],
            list_ca_lam: [
                { id: 1, ten_ca: 'Sáng' },
                { id: 2, ten_ca: 'Chiều' },
                { id: 3, ten_ca: 'Tối' }
            ],
            list_nhan_vien_all: [],
            lich_ca_nhan_set: new Set(),
            lich_cong_ty_map: {},
            selected_modal: {
                full_date: '',
                date_str: '',
                ca_id: null,
                ca_name: '',
                selected_nv_ids: []
            },
            badge_colors: ['#3498DB', '#20c997', '#2ECC71', '#9B59B6', '#F39C12', '#1ABC9C', '#D35400', '#34495E']
        }
    },
    mounted() {
        this.initWeek(new Date());
        this.loadDataCaLam();
        this.loadDataNhanVien();
        this.loadLichTuan();
    },
    methods: {
        initWeek(referenceDate) {
            const date = new Date(referenceDate);
            const day = date.getDay();
            const diff = date.getDate() - day + (day === 0 ? -6 : 1); // Get Monday
            const monday = new Date(date.setDate(diff));
            this.current_monday = monday;

            const days = [];
            const dayNames = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ Nhật'];
            for (let i = 0; i < 7; i++) {
                const d = new Date(monday);
                d.setDate(monday.getDate() + i);
                const year = d.getFullYear();
                const month = String(d.getMonth() + 1).padStart(2, '0');
                const dateNum = String(d.getDate()).padStart(2, '0');
                days.push({
                    day_name: dayNames[i],
                    date_str: `${dateNum}/${month}/${year}`,
                    full_date: `${year}-${month}-${dateNum}`
                });
            }
            this.week_days = days;
        },

        changeWeek(offset) {
            const newDate = new Date(this.current_monday);
            newDate.setDate(newDate.getDate() + offset * 7);
            this.initWeek(newDate);
            this.loadLichTuan();
        },

        currentWeek() {
            this.initWeek(new Date());
            this.loadLichTuan();
        },

        loadDataCaLam() {
            axios.get('http://127.0.0.1:8000/api/admin/ca-lam-viec/get-data', {
                headers: { 'Authorization': `Bearer ${localStorage.getItem('xs')}` }
            }).then(res => {
                if (res.data.data && res.data.data.length) {
                    this.list_ca_lam = res.data.data;
                }
            }).catch(err => console.error(err));
        },

        loadDataNhanVien() {
            axios.get('http://127.0.0.1:8000/api/admin/nhan-vien/get-data', {
                headers: { 'Authorization': `Bearer ${localStorage.getItem('xs')}` }
            }).then(res => {
                if (res.data.data) {
                    this.list_nhan_vien_all = res.data.data;
                }
            }).catch(err => console.error(err));
        },

        loadLichTuan() {
            const tu_ngay = this.week_days[0]?.full_date;
            const den_ngay = this.week_days[6]?.full_date;

            axios.post('http://127.0.0.1:8000/api/admin/phan-cong-ca-lam/lich-tuan', { tu_ngay, den_ngay }, {
                headers: { 'Authorization': `Bearer ${localStorage.getItem('xs')}` }
            }).then(res => {
                if (res.data.status == 1 || res.data.status) {
                    // Populate personal shift set & company shift map
                    const caNhan = new Set();
                    const congTy = {};

                    if (res.data.lich_ca_nhan) {
                        res.data.lich_ca_nhan.forEach(item => {
                            caNhan.add(`${item.ngay_lam}_${item.id_ca_lam_viec}`);
                        });
                    }
                    if (res.data.lich_cong_ty) {
                        res.data.lich_cong_ty.forEach(item => {
                            const key = `${item.ngay_lam}_${item.id_ca_lam_viec}`;
                            if (!congTy[key]) congTy[key] = [];
                            congTy[key].push(item);
                        });
                    }
                    this.lich_ca_nhan_set = caNhan;
                    this.lich_cong_ty_map = congTy;
                }
            }).catch(err => console.error(err));
        },

        isDangKyCaNhan(full_date, ca_id) {
            return this.lich_ca_nhan_set.has(`${full_date}_${ca_id}`);
        },

        toggleDangKyCaNhan(full_date, ca_id) {
            const key = `${full_date}_${ca_id}`;
            const isReg = this.lich_ca_nhan_set.has(key);

            axios.post('http://127.0.0.1:8000/api/admin/phan-cong-ca-lam/dang-ky-ca-nhan', {
                ngay_lam: full_date,
                id_ca_lam_viec: ca_id,
                dang_ky: !isReg
            }, {
                headers: { 'Authorization': `Bearer ${localStorage.getItem('xs')}` }
            }).then(res => {
                if (res.data.status == 1) {
                    if (!isReg) {
                        this.lich_ca_nhan_set.add(key);
                        this.$toast?.success("Đã đăng ký ca làm thành công!");
                    } else {
                        this.lich_ca_nhan_set.delete(key);
                        this.$toast?.info("Đã hủy đăng ký ca làm!");
                    }
                    this.loadLichTuan();
                } else {
                    this.$toast?.error(res.data.message || "Có lỗi xảy ra!");
                }
            }).catch(err => {
                console.error(err);
                this.$toast?.error("Lỗi kết nối máy chủ!");
            });
        },

        getListNhanVienCompany(full_date, ca_id) {
            const key = `${full_date}_${ca_id}`;
            return this.lich_cong_ty_map[key] || [];
        },

        getShortName(fullName) {
            if (!fullName) return '';
            const parts = fullName.trim().split(/\s+/);
            return parts[parts.length - 1];
        },

        getBadgeColor(name) {
            if (!name) return '#3498DB';
            let hash = 0;
            for (let i = 0; i < name.length; i++) {
                hash = name.charCodeAt(i) + ((hash << 5) - hash);
            }
            const index = Math.abs(hash) % this.badge_colors.length;
            return this.badge_colors[index];
        },

        openModalPhanCong(full_date, ca) {
            const dayObj = this.week_days.find(d => d.full_date === full_date);
            const currentList = this.getListNhanVienCompany(full_date, ca.id);
            this.selected_modal = {
                full_date,
                date_str: dayObj ? `${dayObj.day_name} (${dayObj.date_str})` : full_date,
                ca_id: ca.id,
                ca_name: ca.ten_ca,
                selected_nv_ids: currentList.map(item => item.id_nhan_vien)
            };
            const modalEl = document.getElementById('modalPhanCong');
            if (modalEl) {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            }
        },

        saveModalPhanCong() {
            axios.post('http://127.0.0.1:8000/api/admin/phan-cong-ca-lam/phan-cong-cong-ty', {
                ngay_lam: this.selected_modal.full_date,
                id_ca_lam_viec: this.selected_modal.ca_id,
                id_nhan_vien_list: this.selected_modal.selected_nv_ids
            }, {
                headers: { 'Authorization': `Bearer ${localStorage.getItem('xs')}` }
            }).then(res => {
                if (res.data.status == 1) {
                    this.$toast?.success("Cập nhật phân công ca làm thành công!");
                    const modalEl = document.getElementById('modalPhanCong');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                    this.loadLichTuan();
                } else {
                    this.$toast?.error(res.data.message || "Có lỗi xảy ra!");
                }
            }).catch(err => {
                console.error(err);
                this.$toast?.error("Có lỗi xảy ra khi lưu phân công!");
            });
        }
    }
}
</script>

<style scoped></style>