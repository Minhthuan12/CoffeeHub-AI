<template>
  <div class="register-page">
    <!-- Decorative coffee beans / glow effects -->
    <div class="register-decor register-decor-1"></div>
    <div class="register-decor register-decor-2"></div>
    <div class="register-decor register-decor-3"></div>

    <div class="register-card">
      <!-- Logo -->
      <div class="register-logo">
        <div class="register-logo-icon">
          <i class="fa-solid fa-mug-hot"></i>
        </div>
        <h1 class="register-title">CoffeeHub</h1>
        <p class="register-subtitle">Đăng ký tài khoản nhân viên mới</p>
      </div>

      <!-- Form -->
      <form @submit.prevent="dangKy()">
        <!-- Họ và tên -->
        <div class="ch-form-group">
          <label class="ch-label" for="reg-name">
            <i class="fa-solid fa-user me-1" style="color:#6F4E37;"></i>
            Họ Và Tên <span class="text-danger">*</span>
          </label>
          <div style="position:relative;">
            <input
              v-model.trim="form.ho_va_ten"
              id="reg-name"
              type="text"
              class="ch-input"
              placeholder="Nguyễn Văn A"
              style="padding-left:40px;"
              required
            >
            <i class="fa-solid fa-id-card" style="position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#c4a882;font-size:15px;"></i>
          </div>
        </div>

        <!-- Email -->
        <div class="ch-form-group">
          <label class="ch-label" for="reg-email">
            <i class="fa-solid fa-envelope me-1" style="color:#6F4E37;"></i>
            Email Đăng Ký <span class="text-danger">*</span>
          </label>
          <div style="position:relative;">
            <input
              v-model.trim="form.email"
              id="reg-email"
              type="email"
              class="ch-input"
              placeholder="example@coffeehub.com"
              style="padding-left:40px;"
              required
            >
            <i class="fa-solid fa-at" style="position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#c4a882;font-size:15px;"></i>
          </div>
        </div>

        <!-- Số điện thoại & Chức vụ -->
        <div class="row g-2">
          <div class="col-md-6">
            <div class="ch-form-group">
              <label class="ch-label" for="reg-phone">
                <i class="fa-solid fa-phone me-1" style="color:#6F4E37;"></i>
                Số Điện Thoại <span class="text-danger">*</span>
              </label>
              <div style="position:relative;">
                <input
                  v-model.trim="form.so_dien_thoai"
                  id="reg-phone"
                  type="tel"
                  class="ch-input"
                  placeholder="0901234567"
                  style="padding-left:38px;"
                  required
                >
                <i class="fa-solid fa-mobile-screen-button" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#c4a882;font-size:14px;"></i>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="ch-form-group">
              <label class="ch-label" for="reg-role">
                <i class="fa-solid fa-briefcase me-1" style="color:#6F4E37;"></i>
                Vị Trí / Chức Vụ
              </label>
              <div style="position:relative;">
                <select
                  v-model="form.id_chuc_vu"
                  id="reg-role"
                  class="ch-input"
                  style="padding-left:38px; cursor:pointer;"
                >
                  <option :value="null">-- Mặc định --</option>
                  <option v-for="cv in listChucVu" :key="cv.id" :value="cv.id">
                    {{ cv.ten_chuc_vu }}
                  </option>
                </select>
                <i class="fa-solid fa-user-tag" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#c4a882;font-size:14px;"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Mật khẩu -->
        <div class="ch-form-group">
          <label class="ch-label" for="reg-password">
            <i class="fa-solid fa-lock me-1" style="color:#6F4E37;"></i>
            Mật Khẩu <span class="text-danger">*</span>
          </label>
          <div style="position:relative;">
            <input
              v-model="form.password"
              id="reg-password"
              :type="showPassword ? 'text' : 'password'"
              class="ch-input"
              placeholder="Tối thiểu 6 ký tự..."
              style="padding-left:40px; padding-right:44px;"
              required
            >
            <i class="fa-solid fa-key" style="position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#c4a882;font-size:15px;"></i>
            <button
              type="button"
              @click="showPassword = !showPassword"
              style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9c7b66;padding:0;font-size:15px;"
            >
              <i :class="showPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
            </button>
          </div>
        </div>

        <!-- Nhập lại mật khẩu -->
        <div class="ch-form-group">
          <label class="ch-label" for="reg-re-password">
            <i class="fa-solid fa-shield-halved me-1" style="color:#6F4E37;"></i>
            Xác Nhận Mật Khẩu <span class="text-danger">*</span>
          </label>
          <div style="position:relative;">
            <input
              v-model="form.re_password"
              id="reg-re-password"
              :type="showRePassword ? 'text' : 'password'"
              class="ch-input"
              placeholder="Nhập lại mật khẩu..."
              style="padding-left:40px; padding-right:44px;"
              required
            >
            <i class="fa-solid fa-check-double" style="position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#c4a882;font-size:15px;"></i>
            <button
              type="button"
              @click="showRePassword = !showRePassword"
              style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9c7b66;padding:0;font-size:15px;"
            >
              <i :class="showRePassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
            </button>
          </div>
        </div>

        <button
          type="submit"
          class="ch-btn ch-btn-primary ch-btn-lg"
          :disabled="loading"
          style="width:100%; font-size:15px; justify-content:center; margin-top:8px;"
        >
          <i v-if="loading" class="fa-solid fa-spinner fa-spin me-1"></i>
          <i v-else class="fa-solid fa-user-plus me-1"></i>
          {{ loading ? 'Đang tạo tài khoản...' : 'Đăng Ký Tài Khoản' }}
        </button>
      </form>

      <!-- Link đăng nhập -->
      <div style="text-align:center; margin-top:20px; padding-top:16px; border-top:1px solid #f0e6dd;">
        <p style="font-size:13.5px; color:#6b5344; margin:0;">
          Đã có tài khoản?
          <router-link to="/admin/login" style="color:#6F4E37; font-weight:700; text-decoration:none; margin-left:4px;">
            Đăng nhập ngay <i class="fa-solid fa-arrow-right fa-xs"></i>
          </router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'DangKyComponent',
  data() {
    return {
      form: {
        ho_va_ten: '',
        email: '',
        so_dien_thoai: '',
        id_chuc_vu: null,
        password: '',
        re_password: ''
      },
      listChucVu: [],
      showPassword: false,
      showRePassword: false,
      loading: false
    };
  },
  mounted() {
    this.layDanhSachChucVu();
    document.body.style.background = 'transparent';
  },
  methods: {
    layDanhSachChucVu() {
      axios
        .get('http://127.0.0.1:8000/api/admin/chuc-vu/public-active')
        .then((res) => {
          if (res.data && res.data.data) {
            this.listChucVu = res.data.data;
          }
        })
        .catch(() => {
          // Bỏ qua lỗi nếu không lấy được danh sách chức vụ, BE sẽ gán mặc định
        });
    },
    dangKy() {
      // Validate client
      if (!this.form.ho_va_ten || !this.form.email || !this.form.so_dien_thoai || !this.form.password) {
        this.$toast.warning("Vui lòng điền đầy đủ các trường bắt buộc!");
        return;
      }

      const phoneRegex = /^(0)[0-9]{9}$/;
      if (!phoneRegex.test(this.form.so_dien_thoai)) {
        this.$toast.warning("Số điện thoại không hợp lệ (10 chữ số, bắt đầu bằng 0)!");
        return;
      }

      if (this.form.password.length < 6) {
        this.$toast.warning("Mật khẩu phải có tối thiểu 6 ký tự!");
        return;
      }

      if (this.form.password !== this.form.re_password) {
        this.$toast.warning("Mật khẩu xác nhận không trùng khớp!");
        return;
      }

      this.loading = true;
      axios
        .post('http://127.0.0.1:8000/api/admin/dang-ky', this.form)
        .then((res) => {
          if (res.data.status == 1) {
            this.$toast.success(res.data.message || "Đăng ký thành công!");
            setTimeout(() => {
              this.$router.push('/admin/login');
            }, 1200);
          } else {
            this.$toast.error(res.data.message || "Đăng ký thất bại!");
          }
        })
        .catch((error) => {
          console.error(error);
          if (error.response && error.response.data) {
            const errData = error.response.data;
            if (errData.errors) {
              const firstKey = Object.keys(errData.errors)[0];
              this.$toast.error(errData.errors[firstKey][0]);
            } else {
              this.$toast.error(errData.message || "Có lỗi xảy ra khi đăng ký!");
            }
          } else {
            this.$toast.error("Không thể kết nối đến máy chủ!");
          }
        })
        .finally(() => {
          this.loading = false;
        });
    }
  }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap');
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css");

.register-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #1a0a00 0%, #3d1f00 40%, #6F4E37 80%, #8B6347 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 30px 20px;
  position: relative;
  overflow: hidden;
  font-family: 'Outfit', sans-serif;
}

.register-decor {
  position: absolute;
  border-radius: 50%;
  pointer-events: none;
}

.register-decor-1 {
  width: 500px;
  height: 500px;
  background: radial-gradient(circle, rgba(245,158,11,0.15) 0%, transparent 70%);
  top: -150px;
  right: -100px;
  animation: float 6s ease-in-out infinite;
}

.register-decor-2 {
  width: 300px;
  height: 300px;
  background: radial-gradient(circle, rgba(245,158,11,0.1) 0%, transparent 70%);
  bottom: -80px;
  left: -80px;
  animation: float 8s ease-in-out infinite reverse;
}

.register-decor-3 {
  width: 200px;
  height: 200px;
  background: radial-gradient(circle, rgba(255,255,255,0.04) 0%, transparent 70%);
  top: 40%;
  left: 15%;
  animation: float 5s ease-in-out infinite 2s;
}

@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-20px); }
}

.register-card {
  background: rgba(255,255,255,0.98);
  border-radius: 24px;
  padding: 36px 36px;
  width: 100%;
  max-width: 500px;
  box-shadow: 0 40px 100px rgba(0,0,0,0.35), 0 0 0 1px rgba(255,255,255,0.1);
  position: relative;
  z-index: 1;
  animation: registerFadeIn 0.5s ease;
}

@keyframes registerFadeIn {
  from { opacity: 0; transform: translateY(30px) scale(0.97); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}

.register-logo { text-align: center; margin-bottom: 24px; }

.register-logo-icon {
  width: 66px;
  height: 66px;
  background: linear-gradient(135deg, #6F4E37, #3d1f00);
  border-radius: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 30px;
  color: #fff;
  margin: 0 auto 12px;
  box-shadow: 0 10px 24px rgba(111,78,55,0.45);
}

.register-title {
  font-size: 26px;
  font-weight: 800;
  color: #3d1f00;
  letter-spacing: -0.5px;
  margin: 0;
}

.register-subtitle {
  font-size: 13px;
  color: #9c7b66;
  margin-top: 4px;
}

.ch-form-group { margin-bottom: 14px; }

.ch-label {
  display: block;
  font-size: 12.5px;
  font-weight: 600;
  color: #5c2f00;
  margin-bottom: 5px;
  letter-spacing: 0.2px;
}

.ch-input {
  width: 100%;
  padding: 10px 14px;
  border: 1.5px solid #e8d5c0;
  border-radius: 10px;
  background: #fffaf7;
  color: #1a0a00;
  font-family: 'Outfit', sans-serif;
  font-size: 14px;
  transition: all 0.2s ease;
  outline: none;
  box-sizing: border-box;
}

.ch-input:focus {
  border-color: #a67c60;
  box-shadow: 0 0 0 3px rgba(111,78,55,0.1);
  background: #fff;
}

.ch-input::placeholder { color: #c4a882; }

.ch-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 12px 24px;
  border-radius: 12px;
  font-family: 'Outfit', sans-serif;
  font-size: 15px;
  font-weight: 700;
  cursor: pointer;
  border: none;
  transition: all 0.2s ease;
  text-decoration: none;
}

.ch-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.ch-btn-primary {
  background: linear-gradient(135deg, #6F4E37, #3d1f00);
  color: #fff;
  box-shadow: 0 6px 20px rgba(111,78,55,0.35);
}

.ch-btn-primary:hover:not(:disabled) {
  background: linear-gradient(135deg, #8B6347, #5c2f00);
  box-shadow: 0 8px 28px rgba(111,78,55,0.45);
  transform: translateY(-2px);
}

.ch-btn-lg { font-size: 15px; padding: 12px 24px; }
</style>
