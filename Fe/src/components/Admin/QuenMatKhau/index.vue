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
                            <h3 class="fw-bold">Quên Mật Khẩu?</h3>
                            <p class="text-muted small">Nhập email đã đăng ký của bạn để nhận mã khôi phục mật khẩu</p>
                        </div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="inputEmailAddress" class="form-label fw-bold">Địa chỉ Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent"><i class="bx bx-envelope"></i></span>
                                    <input v-model="form.email" type="email" class="form-control" id="inputEmailAddress"
                                        placeholder="nhapemail@gmail.com" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-grid">
                                    <button @click="guiYeuCau()" type="button" class="btn btn-primary fw-bold">
                                        <i class="bx bx-paper-plane me-1"></i> Gửi Yêu Cầu
                                    </button>
                                </div>
                            </div>
                            <div class="col-12 text-center mt-3">
                                <router-link to="/admin/login" class="text-decoration-none fw-bold small text-primary">
                                    <i class="bx bx-arrow-back me-1"></i>Quay lại đăng nhập
                                </router-link>
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
    data() {
        return {
            form: {
                email: ''
            }
        }
    },
    methods: {
        guiYeuCau() {
            axios
                .post('http://127.0.0.1:8000/api/quen-mat-khau', this.form)
                .then((res) => {
                    if (res.data.status == 1) {
                        console.log(res.data.data);
                        this.$toast.success(res.data.message);
                        // this.$router.push('/admin/login');
                    } else {
                        this.$toast.error(res.data.message);
                    }
                });
        }
    }
}
</script>

<style></style>
