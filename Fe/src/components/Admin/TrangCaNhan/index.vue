<template>
    <div class="container-fluid">
        <div class="main-body">
            <div class="row">
                <!-- Cột trái: Avatar và thông tin tổng quan -->
                <div class="col-lg-4 mb-3">
                    <div class="card shadow-sm rounded-4 border-0">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSv-UXkO5qHu0d7xdT2kAuxjGAMrUErLVoofL0gh6bW9A&s=10"
                                    alt="Admin Avatar" class="rounded-circle p-1 bg-primary mb-2" width="110"
                                    height="110">
                                <div class="mt-2">
                                    <h4 class="fw-bold mb-1">{{ profile.ho_va_ten }}</h4>
                                    <p class="badge bg-primary fs-6 mb-2">{{ profile.ten_chuc_vu || 'Chưa cập nhật' }}
                                    </p>
                                    <div>
                                        <span v-if="profile.tinh_trang == 1" class="badge bg-success">
                                            <i class="bx bx-check-circle me-1"></i>Đang Hoạt Động
                                        </span>
                                        <span v-else class="badge bg-danger">
                                            <i class="bx bx-x-circle me-1"></i>Đã Bị Khóa
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-4">
                            <ul class="list-group list-group-flush">
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center flex-wrap px-0">
                                    <h6 class="mb-0 text-muted"><i class="bx bx-envelope me-2 text-primary"></i>Email
                                    </h6>
                                    <span class="fw-semibold text-dark">{{ profile.email || 'N/A' }}</span>
                                </li>
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center flex-wrap px-0">
                                    <h6 class="mb-0 text-muted"><i class="bx bx-phone me-2 text-success"></i>Số Điện
                                        Thoại</h6>
                                    <span class="fw-semibold text-dark">{{ profile.so_dien_thoai || 'N/A' }}</span>
                                </li>
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center flex-wrap px-0">
                                    <h6 class="mb-0 text-muted"><i class="bx bx-shield-quarter me-2 text-info"></i>Chức
                                        Vụ</h6>
                                    <span class="fw-semibold text-dark">{{ profile.ten_chuc_vu || 'N/A' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Cột phải: Form Cập nhật thông tin & Form Đổi mật khẩu -->
                <div class="col-lg-8">
                    <!-- Form Cập nhật thông tin cá nhân -->
                    <div class="card shadow-sm rounded-4 border-0 mb-4">
                        <div class="card-body p-4">
                            <h5 class="d-flex align-items-center mb-4 fw-bold text-primary">
                                <i class="bx bx-user-circle me-2 fs-4"></i>CẬP NHẬT THÔNG TIN CÁ NHÂN
                            </h5>
                            <div class="row mb-3">
                                <div class="col-sm-3 d-flex align-items-center">
                                    <h6 class="mb-0 fw-semibold">Họ và Tên</h6>
                                </div>
                                <div class="col-sm-9">
                                    <input v-model="profile.ho_va_ten" type="text" class="form-control"
                                        placeholder="Nhập vào họ và tên">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3 d-flex align-items-center">
                                    <h6 class="mb-0 fw-semibold">Email</h6>
                                </div>
                                <div class="col-sm-9">
                                    <input v-model="profile.email" type="email" class="form-control"
                                        placeholder="Nhập địa chỉ email">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3 d-flex align-items-center">
                                    <h6 class="mb-0 fw-semibold">Số Điện Thoại</h6>
                                </div>
                                <div class="col-sm-9">
                                    <input v-model="profile.so_dien_thoai" type="text" class="form-control"
                                        placeholder="Nhập số điện thoại">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3 d-flex align-items-center">
                                    <h6 class="mb-0 fw-semibold">Chức Vụ</h6>
                                </div>
                                <div class="col-sm-9">
                                    <input :value="profile.ten_chuc_vu" type="text" class="form-control bg-light"
                                        disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-9 text-secondary">
                                    <button @click="capNhatThongTin()" class="btn btn-primary px-4">Lưu Thay
                                        Đổi</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Đổi mật khẩu -->
                    <div class="card shadow-sm rounded-4 border-0">
                        <div class="card-body p-4">
                            <h5 class="d-flex align-items-center mb-4 fw-bold text-danger">
                                <i class="bx bx-lock-alt me-2 fs-4"></i>ĐỔI MẬT KHẨU
                            </h5>
                            <div class="row mb-3">
                                <div class="col-sm-3 d-flex align-items-center">
                                    <h6 class="mb-0 fw-semibold">Mật Khẩu Cũ</h6>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input v-model="doi_mat_khau.mat_khau_cu"
                                            :type="showPassOld ? 'text' : 'password'" class="form-control"
                                            placeholder="Nhập mật khẩu cũ">
                                        <button type="button" @click="showPassOld = !showPassOld"
                                            class="input-group-text bg-transparent">
                                            <i class="bx" :class="showPassOld ? 'bx-show' : 'bx-hide'"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3 d-flex align-items-center">
                                    <h6 class="mb-0 fw-semibold">Mật Khẩu Mới</h6>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input v-model="doi_mat_khau.mat_khau_moi"
                                            :type="showPassNew ? 'text' : 'password'" class="form-control"
                                            placeholder="Nhập mật khẩu mới (ít nhất 6 ký tự)">
                                        <button type="button" @click="showPassNew = !showPassNew"
                                            class="input-group-text bg-transparent">
                                            <i class="bx" :class="showPassNew ? 'bx-show' : 'bx-hide'"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3 d-flex align-items-center">
                                    <h6 class="mb-0 fw-semibold">Nhập lại Mật Khẩu Mới</h6>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input v-model="doi_mat_khau.re_mat_khau_moi"
                                            :type="showPassRe ? 'text' : 'password'" class="form-control"
                                            placeholder="Nhập lại mật khẩu mới">
                                        <button type="button" @click="showPassRe = !showPassRe"
                                            class="input-group-text bg-transparent">
                                            <i class="bx" :class="showPassRe ? 'bx-show' : 'bx-hide'"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-9 text-secondary">
                                    <button @click="doiMatKhau()" class="btn btn-danger px-4">Đổi Mật Khẩu</button>
                                </div>
                            </div>
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
    name: 'TrangCaNhan',
    data() {
        return {
            profile: {
            },
            doi_mat_khau: {
            },
            showPassOld: false,
            showPassNew: false,
            showPassRe: false,
        };
    },
    mounted() {
        this.getProfile();
    },
    methods: {
        getProfile() {
            axios
                .get('http://127.0.0.1:8000/api/admin/trang-ca-nhan/thong-tin', {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    if (res.data.status) {
                        this.profile = res.data.data;
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
        },
        capNhatThongTin() {

            axios
                .post('http://127.0.0.1:8000/api/admin/trang-ca-nhan/cap-nhat-thong-tin', this.profile, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.getProfile();
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
        },
        doiMatKhau() {
            axios
                .post('http://127.0.0.1:8000/api/admin/trang-ca-nhan/doi-mat-khau', this.doi_mat_khau, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        console.log('Mật khẩu cũ: ' + res.data.mat_khau_cu + ' && Mật khẩu mới: ' + res.data.mat_khau_moi);
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .catch((error) => {
                    console.error(error);
                    if (error.response && error.response.data) {
                        var list = error.response.data.errors;
                        if (list) {
                            for (const key in list) {
                                this.$toast.error(list[key][0]);
                            }
                        } else {
                            this.$toast.error(error.response.data.message || "Có lỗi xảy ra!");
                        }
                    } else {
                        this.$toast.error("Không thể kết nối đến máy chủ!");
                    }
                });
        },
    },
};
</script>

<style scoped></style>