<template>
  <div class="login-page">
    <!-- Decorative coffee beans -->
    <div class="login-decor login-decor-1"></div>
    <div class="login-decor login-decor-2"></div>
    <div class="login-decor login-decor-3"></div>

    <div class="login-card">
      <!-- Logo -->
      <div class="login-logo">
        <div class="login-logo-icon">
          <i class="fa-solid fa-mug-hot"></i>
        </div>
        <h1 class="login-title">CoffeeHub</h1>
        <p class="login-subtitle">Hệ thống quản lý quán cà phê</p>
      </div>

      <!-- Form -->
      <form @submit.prevent="dangNhap()">
        <div class="ch-form-group">
          <label class="ch-label" for="login-email">
            <i class="fa-solid fa-envelope me-1" style="color:#6F4E37;"></i>
            Email Đăng Nhập
          </label>
          <div style="position:relative;">
            <input
              v-model="login.email"
              id="login-email"
              type="email"
              class="ch-input"
              placeholder="example@coffeehub.com"
              style="padding-left:40px;"
            >
            <i class="fa-solid fa-at" style="position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#c4a882;font-size:15px;"></i>
          </div>
        </div>

        <div class="ch-form-group">
          <label class="ch-label" for="login-password">
            <i class="fa-solid fa-lock me-1" style="color:#6F4E37;"></i>
            Mật Khẩu
          </label>
          <div style="position:relative;">
            <input
              v-model="login.password"
              id="login-password"
              :type="showPassword ? 'text' : 'password'"
              class="ch-input"
              placeholder="Nhập mật khẩu..."
              style="padding-left:40px; padding-right:44px;"
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

        <div style="display:flex;align-items:center;justify-content:flex-end;margin-bottom:24px;">
          <router-link to="/admin/quen-mat-khau" style="font-size:13px;color:#6F4E37;text-decoration:none;font-weight:600;">
            <i class="fa-solid fa-circle-question me-1"></i> Quên mật khẩu?
          </router-link>
        </div>

        <button type="submit" class="ch-btn ch-btn-primary ch-btn-lg" style="width:100%; font-size:15px; justify-content:center;">
          <i class="fa-solid fa-right-to-bracket"></i>
          Đăng Nhập
        </button>
      </form>

      <div style="text-align:center; margin-top:20px; padding-top:16px; border-top:1px solid #f0e6dd;">
        <p style="font-size:13.5px; color:#6b5344; margin-bottom:12px;">
          Chưa có tài khoản?
          <router-link to="/admin/dang-ky" style="color:#6F4E37; font-weight:700; text-decoration:none; margin-left:4px;">
            Đăng ký ngay <i class="fa-solid fa-arrow-right fa-xs"></i>
          </router-link>
        </p>
        <p style="font-size:12px; color:#9c7b66; margin:0;">
          <i class="fa-solid fa-shield-halved me-1" style="color:#6F4E37;"></i>
          Dữ liệu được bảo mật và mã hóa an toàn
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: 'DangNhapComponent',
  data() {
    return {
      login: {
        email: 'cuong.ho@gmail.com',
        password: '123456'
      },
      showPassword: false
    }
  },
  mounted() {
    this.checkToken();
    document.body.style.background = 'transparent';
  },
  methods: {
    dangNhap() {
      axios
        .post('http://127.0.0.1:8000/api/admin/nhan-vien/login', this.login)
        .then((res) => {
          if (res.data.status == 1) {
            this.$toast.success("Đăng nhập thành công");
            localStorage.setItem('xs', res.data.token);
            this.$router.push("/admin/dashboard/default");
          } else {
            this.$toast.error(res.data.message || "Đăng nhập thất bại");
          }
        })
        .catch((error) => {
          console.error(error);
          if (error.response && error.response.data) {
            this.$toast.error(error.response.data.message || "Có lỗi xảy ra!");
          } else {
            this.$toast.error("Không thể kết nối đến máy chủ!");
          }
        });
    },
    checkToken() {
      const token = localStorage.getItem('xs');
      if (token) {
        axios.get(`http://127.0.0.1:8000/api/check-login`, {
          headers: { 'Authorization': `Bearer ${token}` }
        })
          .then((res) => {
            if (res.data.status == 1) {
              this.$router.push('/admin/dashboard/default');
            } else {
              localStorage.removeItem('xs');
            }
          })
          .catch(() => {
            localStorage.removeItem('xs');
          });
      }
    }
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap');
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css");

/* Login page - Light Theme */
.login-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #FFFDF9 0%, #FFFBEB 40%, #FEF3C7 80%, #FDE68A 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  position: relative;
  overflow: hidden;
  font-family: 'Outfit', sans-serif;
}

.login-decor {
  position: absolute;
  border-radius: 50%;
  pointer-events: none;
}

.login-decor-1 {
  width: 500px;
  height: 500px;
  background: radial-gradient(circle, rgba(245,158,11,0.2) 0%, transparent 70%);
  top: -150px;
  right: -100px;
  animation: float 6s ease-in-out infinite;
}

.login-decor-2 {
  width: 300px;
  height: 300px;
  background: radial-gradient(circle, rgba(217,119,6,0.15) 0%, transparent 70%);
  bottom: -80px;
  left: -80px;
  animation: float 8s ease-in-out infinite reverse;
}

.login-decor-3 {
  width: 200px;
  height: 200px;
  background: radial-gradient(circle, rgba(255,255,255,0.8) 0%, transparent 70%);
  top: 40%;
  left: 15%;
  animation: float 5s ease-in-out infinite 2s;
}

@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-20px); }
}

.login-card {
  background: #ffffff;
  border-radius: 24px;
  padding: 44px 40px;
  width: 100%;
  max-width: 430px;
  box-shadow: 0 20px 60px -10px rgba(180, 83, 9, 0.15), 0 0 0 1px rgba(245, 158, 11, 0.15);
  position: relative;
  z-index: 1;
  animation: loginFadeIn 0.5s ease;
}

@keyframes loginFadeIn {
  from { opacity: 0; transform: translateY(30px) scale(0.97); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}

.login-logo { text-align: center; margin-bottom: 32px; }

.login-logo-icon {
  width: 74px;
  height: 74px;
  background: linear-gradient(135deg, #d97706, #b45309);
  border-radius: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 34px;
  color: #fff;
  margin: 0 auto 14px;
  box-shadow: 0 10px 25px rgba(217, 119, 6, 0.35);
}

.login-title {
  font-size: 28px;
  font-weight: 800;
  color: #1e293b;
  letter-spacing: -0.5px;
  margin: 0;
}

.login-subtitle {
  font-size: 13px;
  color: #64748b;
  margin-top: 4px;
}

.ch-form-group { margin-bottom: 18px; }

.ch-label {
  display: block;
  font-size: 12.5px;
  font-weight: 600;
  color: #334155;
  margin-bottom: 7px;
  letter-spacing: 0.2px;
}

.ch-input {
  width: 100%;
  padding: 11px 14px;
  border: 1.5px solid #e2e8f0;
  border-radius: 12px;
  background: #f8fafc;
  color: #0f172a;
  font-family: 'Outfit', sans-serif;
  font-size: 14px;
  transition: all 0.2s ease;
  outline: none;
  box-sizing: border-box;
}

.ch-input:focus {
  border-color: #d97706;
  box-shadow: 0 0 0 3px rgba(217,119,6,0.15);
  background: #fff;
}

.ch-input::placeholder { color: #94a3b8; }

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

.ch-btn-primary {
  background: linear-gradient(135deg, #d97706, #b45309);
  color: #fff;
  box-shadow: 0 6px 20px rgba(217,119,6,0.35);
}

.ch-btn-primary:hover {
  background: linear-gradient(135deg, #b45309, #92400e);
  box-shadow: 0 8px 28px rgba(217,119,6,0.45);
  transform: translateY(-2px);
}

.ch-btn-lg { font-size: 15px; padding: 13px 24px; }
</style>