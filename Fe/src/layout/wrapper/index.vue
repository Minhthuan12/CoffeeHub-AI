<template>
  <div class="admin-wrapper" :class="{ 'sidebar-collapsed': isCollapsed, 'mobile-open': mobileOpen }">
    <!-- Overlay mobile -->
    <div class="admin-overlay" :class="{ 'show': mobileOpen }" @click="mobileOpen = false"></div>

    <!-- SIDEBAR -->
    <aside class="admin-sidebar" :class="{ 'sidebar-open': mobileOpen }">
      <!-- Brand & Toggle -->
      <div class="sidebar-brand-container">
        <router-link to="/admin/dashboard/default" class="sidebar-brand" :title="isCollapsed ? 'CoffeeHub Admin' : ''">
          <div class="sidebar-brand-icon">
            <i class="fa-solid fa-mug-hot"></i>
          </div>
          <div class="sidebar-brand-text">
            <span class="sidebar-brand-name">CoffeeHub</span>
            <span class="sidebar-brand-sub">Admin Panel</span>
          </div>
        </router-link>
        <!-- Nút thu phóng ngay trên sidebar cho desktop -->
        <button 
          class="sidebar-toggle-btn d-none d-lg-flex" 
          @click="toggleSidebar" 
          :title="isCollapsed ? 'Phóng ra menu' : 'Thu vào menu'"
          aria-label="Thu phóng thanh menu"
        >
          <i :class="isCollapsed ? 'fa-solid fa-chevron-right' : 'fa-solid fa-chevron-left'"></i>
        </button>
      </div>

      <!-- Navigation -->
      <nav class="sidebar-nav">
        <!-- Dashboard -->
        <div class="sidebar-section-label">Tổng Quan</div>
        <div class="sidebar-nav-item">
          <router-link class="sidebar-nav-link" to="/admin/dashboard/default" @click="closeOnMobile" title="Dashboard">
            <i class="nav-icon fa-solid fa-chart-pie"></i>
            <span class="nav-label">Dashboard</span>
          </router-link>
        </div>

        <!-- Trí Tuệ Nhân Tạo (AI Hub) -->
        <div class="sidebar-section-label">Trí Tuệ Nhân Tạo</div>
        <div class="sidebar-nav-item" :class="{ open: openMenu === 'ai' }">
          <button class="sidebar-nav-link d-flex align-items-center" @click="toggleMenu('ai')" title="Trí Tuệ Nhân Tạo AI">
            <i class="nav-icon fa-solid fa-wand-magic-sparkles text-amber-500"></i>
            <span class="nav-label">Trợ Lý AI & Copilot</span>
            <span class="badge bg-amber-100 text-amber-800 rounded-pill px-2 py-0.5 ms-1" style="font-size: 10px;">Mới</span>
            <i class="nav-chevron fa-solid fa-chevron-right ms-auto"></i>
          </button>
          <div class="sidebar-submenu">
            <router-link class="sidebar-submenu-link" to="/admin/dashboard/default" @click="closeOnMobile">Thống Kê AI Barista</router-link>
            <router-link class="sidebar-submenu-link" to="/admin/dashboard/default" @click="closeOnMobile">AI Copilot Dự Báo Kho</router-link>
          </div>
        </div>

        <!-- Quản lý người dùng -->
        <div class="sidebar-section-label">Người Dùng</div>
        <div class="sidebar-nav-item" :class="{ open: openMenu === 'users' }">
          <button class="sidebar-nav-link" @click="toggleMenu('users')" title="Quản Lý Người Dùng">
            <i class="nav-icon fa-solid fa-users-gear"></i>
            <span class="nav-label">Quản Lý Người Dùng</span>
            <i class="nav-chevron fa-solid fa-chevron-right"></i>
          </button>
          <div class="sidebar-submenu">
            <router-link class="sidebar-submenu-link" to="/admin/nhan-vien" @click="closeOnMobile">Nhân Viên</router-link>
            <router-link class="sidebar-submenu-link" to="/admin/khach-hang" @click="closeOnMobile">Khách Hàng</router-link>
            <router-link class="sidebar-submenu-link" to="/admin/nha-cung-cap" @click="closeOnMobile">Nhà Cung Cấp</router-link>
            <router-link class="sidebar-submenu-link" to="/admin/chuc-vu" @click="closeOnMobile">Chức Vụ</router-link>
            <router-link class="sidebar-submenu-link" to="/admin/chuc-nang" @click="closeOnMobile">Chức Năng</router-link>
            <router-link class="sidebar-submenu-link" to="/admin/phan-quyen" @click="closeOnMobile">Phân Quyền</router-link>
          </div>
        </div>

        <!-- Quản lý bàn -->
        <div class="sidebar-section-label">Không Gian</div>
        <div class="sidebar-nav-item" :class="{ open: openMenu === 'ban' }">
          <button class="sidebar-nav-link" @click="toggleMenu('ban')" title="Quản Lý Bàn">
            <i class="nav-icon fa-solid fa-chair"></i>
            <span class="nav-label">Quản Lý Bàn</span>
            <i class="nav-chevron fa-solid fa-chevron-right"></i>
          </button>
          <div class="sidebar-submenu">
            <router-link class="sidebar-submenu-link" to="/admin/ban" @click="closeOnMobile">Bàn</router-link>
            <router-link class="sidebar-submenu-link" to="/admin/khu-vuc-ban" @click="closeOnMobile">Khu Vực Bàn</router-link>
          </div>
        </div>

        <!-- Quản lý sản phẩm -->
        <div class="sidebar-section-label">Sản Phẩm</div>
        <div class="sidebar-nav-item" :class="{ open: openMenu === 'sanpham' }">
          <button class="sidebar-nav-link" @click="toggleMenu('sanpham')" title="Quản Lý Sản Phẩm">
            <i class="nav-icon fa-solid fa-mug-saucer"></i>
            <span class="nav-label">Quản Lý Sản Phẩm</span>
            <i class="nav-chevron fa-solid fa-chevron-right"></i>
          </button>
          <div class="sidebar-submenu">
            <router-link class="sidebar-submenu-link" to="/admin/san-pham" @click="closeOnMobile">Sản Phẩm</router-link>
            <router-link class="sidebar-submenu-link" to="/admin/cong-thuc-mon" @click="closeOnMobile">Công Thức Món</router-link>
            <router-link class="sidebar-submenu-link" to="/admin/danh-muc" @click="closeOnMobile">Danh Mục</router-link>
            <router-link class="sidebar-submenu-link" to="/admin/nguyen-lieu" @click="closeOnMobile">Nguyên Liệu</router-link>
          </div>
        </div>

        <!-- Ca làm -->
        <div class="sidebar-section-label">Lịch Làm Việc</div>
        <div class="sidebar-nav-item" :class="{ open: openMenu === 'calam' }">
          <button class="sidebar-nav-link" @click="toggleMenu('calam')" title="Quản Lý Ca Làm">
            <i class="nav-icon fa-solid fa-calendar-days"></i>
            <span class="nav-label">Quản Lý Ca Làm</span>
            <i class="nav-chevron fa-solid fa-chevron-right"></i>
          </button>
          <div class="sidebar-submenu">
            <router-link class="sidebar-submenu-link" to="/admin/ca-lam-viec" @click="closeOnMobile">Ca Làm Việc</router-link>
            <router-link class="sidebar-submenu-link" to="/admin/phan-cong-ca-lam" @click="closeOnMobile">Phân Công Ca Làm</router-link>
            <router-link class="sidebar-submenu-link" to="/admin/phien-ca" @click="closeOnMobile">Phiên Ca & Z-Report</router-link>
          </div>
        </div>

        <!-- Kinh doanh -->
        <div class="sidebar-section-label">Kinh Doanh</div>
        <div class="sidebar-nav-item">
          <router-link class="sidebar-nav-link" to="/admin/hoa-don" @click="closeOnMobile" title="Hóa Đơn">
            <i class="nav-icon fa-solid fa-file-invoice-dollar"></i>
            <span class="nav-label">Hóa Đơn</span>
          </router-link>
        </div>
        <div class="sidebar-nav-item">
          <router-link class="sidebar-nav-link" to="/admin/voucher" @click="closeOnMobile" title="Voucher">
            <i class="nav-icon fa-solid fa-ticket"></i>
            <span class="nav-label">Voucher</span>
          </router-link>
        </div>
        <div class="sidebar-nav-item" :class="{ open: openMenu === 'banhang' }">
          <button class="sidebar-nav-link" @click="toggleMenu('banhang')" title="Bán Hàng">
            <i class="nav-icon fa-solid fa-cash-register"></i>
            <span class="nav-label">Bán Hàng</span>
            <i class="nav-chevron fa-solid fa-chevron-right"></i>
          </button>
          <div class="sidebar-submenu">
            <router-link class="sidebar-submenu-link" to="/admin/ban-hang" @click="closeOnMobile">Thu Ngân (POS)</router-link>
            <router-link class="sidebar-submenu-link" to="/admin/bep-che-bien" @click="closeOnMobile">Bếp Chế Biến</router-link>
            <router-link class="sidebar-submenu-link" to="/admin/phien-ca" @click="closeOnMobile">Giao Ca & Kết Tiền</router-link>
          </div>
        </div>

        <!-- Thống kê -->
        <div class="sidebar-section-label">Báo Cáo</div>
        <div class="sidebar-nav-item" :class="{ open: openMenu === 'thongke' }">
          <button class="sidebar-nav-link" @click="toggleMenu('thongke')" title="Thống Kê">
            <i class="nav-icon fa-solid fa-chart-line"></i>
            <span class="nav-label">Thống Kê</span>
            <i class="nav-chevron fa-solid fa-chevron-right"></i>
          </button>
          <div class="sidebar-submenu">
            <router-link class="sidebar-submenu-link" to="/admin/thong-ke/doanh-thu-theo-ban" @click="closeOnMobile">Doanh Thu Theo Bàn</router-link>
            <router-link class="sidebar-submenu-link" to="/admin/thong-ke/san-pham-da-ban" @click="closeOnMobile">Sản Phẩm Đã Bán</router-link>
            <router-link class="sidebar-submenu-link" to="/admin/thong-ke/doanh-thu-theo-danh-muc" @click="closeOnMobile">Theo Danh Mục</router-link>
            <router-link class="sidebar-submenu-link" to="/admin/thong-ke/doanh-thu-theo-khu-vuc" @click="closeOnMobile">Theo Khu Vực</router-link>
            <router-link class="sidebar-submenu-link" to="/admin/thong-ke/doanh-thu-theo-ngay" @click="closeOnMobile">Theo Ngày</router-link>
          </div>
        </div>
      </nav>

      <!-- Sidebar Footer User -->
      <div class="sidebar-footer">
        <router-link to="/admin/trang-ca-nhan" class="sidebar-user" :title="profile.ho_va_ten || 'Hồ Sơ Cá Nhân'">
          <img src="../../assets/images/avatars/avatar-2.png" class="sidebar-avatar" alt="Avatar">
          <div class="sidebar-user-info">
            <div class="sidebar-user-name">{{ profile.ho_va_ten || 'Admin' }}</div>
            <div class="sidebar-user-role">{{ profile.ten_chuc_vu || 'Quản Trị Viên' }}</div>
          </div>
          <i class="fa-solid fa-angle-right" style="color:rgba(255,255,255,0.3); font-size:11px;"></i>
        </router-link>
      </div>
    </aside>

    <!-- MAIN AREA -->
    <div class="admin-main">
      <!-- Header -->
      <header class="admin-header">
        <!-- Nút thu vào phóng ra navbar menu -->
        <button 
          class="header-hamburger" 
          @click="toggleSidebar" 
          :class="{ 'is-collapsed': isCollapsed }"
          :title="isCollapsed ? 'Phóng ra menu (Mở rộng thanh bên)' : 'Thu vào menu (Thu gọn thanh bên)'"
          aria-label="Thu phóng thanh menu"
        >
          <i :class="isCollapsed ? 'fa-solid fa-bars-staggered' : 'fa-solid fa-bars'"></i>
        </button>

        <!-- Search -->
        <div class="header-search">
          <div class="header-search-wrap">
            <i class="header-search-icon fa-solid fa-search"></i>
            <input type="text" class="header-search-input" placeholder="Tìm kiếm chức năng, đơn hàng...">
          </div>
        </div>

        <div class="header-spacer"></div>

        <!-- Actions -->
        <div class="header-actions">
          <div class="dropdown">
            <a class="header-action-btn dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fa-solid fa-bell"></i>
              <span class="header-badge">3</span>
            </a>
            <div class="dropdown-menu dropdown-menu-end" style="min-width:280px; max-height:360px; overflow-y:auto;">
              <div style="padding:12px 16px; border-bottom:1px solid #f0e6dd;">
                <p style="font-weight:700; font-size:14px; margin:0; color:#3d1f00;">Thông Báo</p>
              </div>
              <a class="dropdown-item" href="javascript:;" style="padding:10px 16px;">
                <div style="display:flex;gap:10px;align-items:flex-start;">
                  <div style="width:36px;height:36px;border-radius:10px;background:#fef3c7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fa-solid fa-file-invoice-dollar" style="color:#d97706;font-size:14px;"></i>
                  </div>
                  <div>
                    <div style="font-weight:600;font-size:13px;color:#1a0a00;">Hóa đơn mới</div>
                    <div style="font-size:11.5px;color:#9c7b66;">Bàn 5 vừa thanh toán</div>
                  </div>
                </div>
              </a>
            </div>
          </div>

          <!-- User dropdown -->
          <div class="dropdown">
            <button class="header-user dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="../../assets/images/avatars/avatar-2.png" class="header-user-avatar" alt="Avatar">
              <span class="header-user-name d-none d-md-block">{{ profile.ho_va_ten || 'Admin' }}</span>
              <i class="fa-solid fa-angle-down" style="font-size:11px;color:#9c7b66;"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <router-link to="/admin/trang-ca-nhan" class="dropdown-item">
                  <i class="fa-solid fa-user me-2" style="color:#6F4E37;width:16px;"></i> Hồ Sơ Cá Nhân
                </router-link>
              </li>
              <li>
                <router-link to="/admin/doi-mat-khau" class="dropdown-item">
                  <i class="fa-solid fa-key me-2" style="color:#f59e0b;width:16px;"></i> Đổi Mật Khẩu
                </router-link>
              </li>
              <li><hr class="dropdown-divider" style="margin:6px 8px;"></li>
              <li>
                <a href="javascript:;" class="dropdown-item" @click="logout()" style="color:#ef4444;">
                  <i class="fa-solid fa-right-from-bracket me-2" style="width:16px;"></i> Đăng Xuất
                </a>
              </li>
            </ul>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="admin-content">
        <router-view></router-view>
      </main>

      <!-- Footer -->
      <footer style="padding:12px 24px; border-top:1px solid rgba(111,78,55,0.08); background:#fff; text-align:center;">
        <span style="font-size:12px; color:#9c7b66;">© 2025 <b style="color:#6F4E37;">CoffeeHub</b> Admin Panel. Bản quyền thuộc về CoffeeHub.</span>
      </footer>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import "../../assets/js/bootstrap.bundle.min.js";
import "../../assets/js/jquery.min.js";
import "../../assets/plugins/simplebar/js/simplebar.min.js";
import "../../assets/plugins/metismenu/js/metisMenu.min.js";
import "../../assets/js/index.js";
import "../../assets/js/app.js";

export default {
  name: 'AdminLayout',
  data() {
    return {
      isCollapsed: false,
      mobileOpen: false,
      openMenu: null,
      profile: {}
    };
  },
  mounted() {
    this.getProfile();
    this.autoOpenMenu();
    try {
      this.isCollapsed = localStorage.getItem('admin_sidebar_collapsed') === 'true';
    } catch (e) {}
  },
  watch: {
    $route() {
      this.autoOpenMenu();
    }
  },
  methods: {
    toggleSidebar() {
      if (window.innerWidth < 992) {
        this.mobileOpen = !this.mobileOpen;
      } else {
        this.isCollapsed = !this.isCollapsed;
        try {
          localStorage.setItem('admin_sidebar_collapsed', this.isCollapsed ? 'true' : 'false');
        } catch (e) {}
      }
    },
    toggleMenu(name) {
      if (this.isCollapsed && window.innerWidth >= 992) {
        // If sidebar is collapsed and user clicks a menu category, expand and open it
        this.isCollapsed = false;
        try {
          localStorage.setItem('admin_sidebar_collapsed', 'false');
        } catch (e) {}
        this.openMenu = name;
      } else {
        this.openMenu = this.openMenu === name ? null : name;
      }
    },
    closeOnMobile() {
      if (window.innerWidth < 992) {
        this.mobileOpen = false;
      }
    },
    autoOpenMenu() {
      const path = this.$route.path;
      if (['/admin/nhan-vien', '/admin/khach-hang', '/admin/nha-cung-cap', '/admin/chuc-vu', '/admin/chuc-nang', '/admin/phan-quyen'].some(p => path.startsWith(p))) {
        this.openMenu = 'users';
      } else if (['/admin/ban', '/admin/khu-vuc-ban'].some(p => path.startsWith(p))) {
        this.openMenu = 'ban';
      } else if (['/admin/san-pham', '/admin/cong-thuc-mon', '/admin/danh-muc', '/admin/nguyen-lieu'].some(p => path.startsWith(p))) {
        this.openMenu = 'sanpham';
      } else if (['/admin/ca-lam-viec', '/admin/phan-cong-ca-lam', '/admin/phien-ca'].some(p => path.startsWith(p))) {
        this.openMenu = 'calam';
      } else if (['/admin/ban-hang', '/admin/bep-che-bien'].some(p => path.startsWith(p))) {
        this.openMenu = 'banhang';
      } else if (path.startsWith('/admin/thong-ke')) {
        this.openMenu = 'thongke';
      }
    },
    getProfile() {
      axios.get('http://127.0.0.1:8000/api/admin/trang-ca-nhan/thong-tin', {
        headers: { 'Authorization': `Bearer ${localStorage.getItem('xs')}` }
      })
      .then((res) => {
        if (res.data.status) {
          this.profile = res.data.data;
        }
      })
      .catch(() => {});
    },
    logout() {
      localStorage.removeItem('xs');
      this.$router.push('/admin/login');
    }
  }
};
</script>

<style>
@import "../../assets/plugins/vectormap/jquery-jvectormap-2.0.2.css";
@import "../../assets/plugins/simplebar/css/simplebar.css";
@import "../../assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css";
@import "../../assets/plugins/metismenu/css/metisMenu.min.css";
@import "../../assets/css/bootstrap.min.css";
@import "../../assets/css/bootstrap-extended.css";
@import "../../assets/css/app.css";
@import "../../assets/css/icons.css";
@import "../../assets/css/admin-theme.css";
@import url("https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap");
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css");

body {
  margin: 0 !important;
  display: block !important;
  place-items: unset !important;
  font-family: 'Outfit', sans-serif !important;
  background: #f8f4ef !important;
}

#app {
  max-width: unset !important;
  margin: 0 !important;
  padding: 0 !important;
  text-align: left !important;
}

.c-toast-container--top {
  top: 70px !important;
  left: calc(var(--sidebar-width) + 16px) !important;
  transition: left 0.3s ease !important;
}

/* Dropdown toggle no caret override */
.dropdown-toggle::after {
  display: none !important;
}

/* Đảm bảo Sidebar cuộn mượt mà không bị tràn màn hình */
.admin-sidebar {
  height: 100vh !important;
  height: 100dvh !important;
  max-height: 100vh !important;
  bottom: 0 !important;
  display: flex !important;
  flex-direction: column !important;
}

.sidebar-nav {
  flex: 1 1 auto !important;
  min-height: 0 !important;
  overflow-y: auto !important;
  overflow-x: hidden !important;
  overscroll-behavior: contain !important;
  -webkit-overflow-scrolling: touch !important;
}
</style>