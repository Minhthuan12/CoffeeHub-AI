<template>
    <div class="container-fluid">
        <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
            <div class="col mx-auto">
                <div class="mb-4 text-center">
                    <img src="../../../assets/images/logo-img.png" width="180" alt="" />
                </div>
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold">Đổi Mật Khẩu</h3>
                            <p class="text-muted small">Tạo mật khẩu mới an toàn cho tài khoản của bạn</p>
                        </div>
                        <form class="row g-3" @submit.prevent="doiMatKhau()">
                            <div class="col-12">
                                <label class="form-label fw-bold">Mã Bí Mật</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent"><i class="bx bx-key"></i></span>
                                    <input v-model="form.hash_reset" type="text"
                                        class="form-control" placeholder="Nhập mã bí mật từ email" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Mật Khẩu Mới</label>
                                <div class="input-group">
                                    <input v-model="form.password" :type="showPass ? 'text' : 'password'"
                                        class="form-control border-end-0" placeholder="Nhập mật khẩu mới" required>
                                    <button type="button" @click="showPass = !showPass"
                                        class="input-group-text bg-transparent">
                                        <i class="bx" :class="showPass ? 'bx-show' : 'bx-hide'"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Xác Nhận Mật Khẩu Mới</label>
                                <div class="input-group">
                                    <input v-model="form.re_password" :type="showRePass ? 'text' : 'password'"
                                        class="form-control border-end-0" placeholder="Nhập lại mật khẩu mới" required>
                                    <button type="button" @click="showRePass = !showRePass"
                                        class="input-group-text bg-transparent">
                                        <i class="bx" :class="showRePass ? 'bx-show' : 'bx-hide'"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success fw-bold">
                                        <i class="bx bx-check-shield me-1"></i>Xác Nhận Đổi Mật Khẩu
                                    </button>
                                </div>
                            </div>
                            <div class="col-12 text-center mt-3">
                                <router-link to="/admin/login" class="text-decoration-none fw-bold small text-primary">
                                    <i class="bx bx-arrow-back me-1"></i>Quay lại đăng nhập
                                </router-link>
                            </div>
                        </form>
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
            form: {
                hash_reset: '',
                password: '',
                re_password: ''
            },
            showPass: false,
            showRePass: false
        }
    },
    methods: {
        doiMatKhau() {
            axios
                .post('http://127.0.0.1:8000/api/dat-mat-khau-moi', this.form)
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.$router.push('/admin/login');
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
        }
    }
}
</script>

<style scoped></style>
