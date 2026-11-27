import { createRouter, createWebHistory } from "vue-router"; // cài vue-router: npm install vue-router@next --save
import checkLogin from "./checkLogin.js";

const routes = [
  {
    path: "/",
    component: () => import("../components/Admin/BanHang/index.vue"),
    beforeEnter: checkLogin
  },
  {
    path: "/admin/nhan-vien",
    component: () => import("../components/Admin/NhanVien/index.vue"),
    beforeEnter: checkLogin
  },
  {
    path: "/admin/khach-hang",
    component: () => import("../components/Admin/KhachHang/index.vue"),
    beforeEnter: checkLogin
  },
  {
    path: "/admin/hoa-don",
    component: () => import("../components/Admin/HoaDon/index.vue"),
    beforeEnter: checkLogin
  },
  {
    path: "/admin/san-pham",
    component: () => import("../components/Admin/SanPham/index.vue"),
    beforeEnter: checkLogin
  },
  {
    path: "/admin/cong-thuc-mon",
    component: () => import("../components/Admin/CongThucMon/index.vue"),
    beforeEnter: checkLogin
  },
  {
    path: "/admin/nha-cung-cap",
    component: () => import("../components/Admin/NhaCungCap/index.vue"),
    beforeEnter: checkLogin
  },
  {
    path: "/admin/danh-muc",
    component: () => import("../components/Admin/DanhMuc/index.vue"),
    beforeEnter: checkLogin
  },
  {
    path: "/admin/voucher",
    component: () => import("../components/Admin/Voucher/index.vue"),
    beforeEnter: checkLogin
  },
  {
    path: "/admin/nguyen-lieu",
    component: () => import("../components/Admin/NguyenLieu/index.vue"),
    beforeEnter: checkLogin
  },
  {
    path: "/admin/khu-vuc-ban",
    component: () => import("../components/Admin/KhuVucBan/index.vue"),
    beforeEnter: checkLogin
  },
  {
    path: "/admin/ban",
    component: () => import("../components/Admin/Ban/index.vue"),
    beforeEnter: checkLogin
  },
  {
    path: "/admin/ca-lam-viec",
    component: () => import("../components/Admin/CaLamViec/index.vue"),
    beforeEnter: checkLogin
  },
  {
    path: "/admin/phan-cong-ca-lam",
    component: () => import("../components/Admin/PhanCongCaLam/index.vue"),
    beforeEnter: checkLogin
  },
  {
    path: "/admin/phien-ca",
    alias: ["/admin/giao-ca", "/admin/lich-su-ca"],
    component: () => import("../components/Admin/PhienCa/index.vue"),
    beforeEnter: checkLogin
  },
  {
    path: "/admin/phien-ca/z-report/:id",
    component: () => import("../components/Admin/PhienCa/ZReport/index.vue"),
    meta: { layout: 'blank' },
    props: true
  },
  {
    path: "/admin/chuc-vu",
    component: () => import("../components/Admin/ChucVu/index.vue"),
    beforeEnter: checkLogin
  },
  {
    path: "/admin/chuc-nang",
    component: () => import("../components/Admin/ChucNang/index.vue"),
    beforeEnter: checkLogin
  },
  {
    path: "/admin/thong-ke/doanh-thu-theo-ban",
    component: () => import("../components/ThongKe/DoanhThuTheoBan/index.vue"),
    beforeEnter: checkLogin
  },
  {
    path: "/admin/thong-ke/san-pham-da-ban",
    component: () => import("../components/ThongKe/SanPhamDaBan/index.vue"),
    beforeEnter: checkLogin
  },
  {
    path: "/admin/thong-ke/doanh-thu-theo-danh-muc",
    component: () => import("../components/ThongKe/DoanhThuTheoDanhMuc/index.vue"),
    beforeEnter: checkLogin
  },
  {
    path: "/admin/thong-ke/doanh-thu-theo-khu-vuc",
    component: () => import("../components/ThongKe/DoanhThuTheoKhuVuc/index.vue"),
    beforeEnter: checkLogin
  },
  {
    path: "/admin/thong-ke/doanh-thu-theo-ngay",
    component: () => import("../components/ThongKe/DoanhThuTheoNgay/index.vue"),
    beforeEnter: checkLogin
  },
  {
    path: "/admin/dashboard/default",
    component: () => import("../components/Admin/DashBoard/Default/index.vue"),
    beforeEnter: checkLogin
  },
  {
    path: "/admin/login",
    alias: ["/login", "/dang-nhap"],
    component: () => import("../components/Admin/DangNhap/index.vue"),
    meta: { layout: 'blank' }
  },
  {
    path: "/admin/dang-ky",
    alias: ["/dang-ky", "/register"],
    component: () => import("../components/Admin/DangKy/index.vue"),
    meta: { layout: 'blank' }
  },
  {
    path: "/admin/phan-quyen",
    component: () => import("../components/Admin/PhanQuyen/index.vue"),
    beforeEnter: checkLogin
  },
  {
    path: "/admin/ban-hang",
    component: () => import("../components/Admin/BanHang/index.vue"),
    beforeEnter: checkLogin
  },
  {
    path: "/admin/bep-che-bien",
    component: () => import("../components/Admin/BepCheBien/index.vue"),
    beforeEnter: checkLogin
  },
  {
    path: "/admin/in-hoa-don/:id",
    component: () => import("../components/Admin/InHoaDon/index.vue"),
    meta: { layout: 'blank' },
    props: true
  },
  {
    path: "/admin/quen-mat-khau",
    component: () => import("../components/Admin/QuenMatKhau/index.vue"),
    meta: { layout: 'blank' },
  },
  {
    path: "/admin/doi-mat-khau",
    component: () => import("../components/Admin/DoiMatKhau/index.vue"),
    meta: { layout: 'blank' },
  },
  {
    path: "/menu",
    alias: ["/khach-hang/menu", "/order"],
    component: () => import("../components/KhachHang/Menu/index.vue"),
    meta: { layout: 'blank' }
  },
  {
    path: "/admin/trang-ca-nhan",
    component: () => import("../components/Admin/TrangCaNhan/index.vue"),
    beforeEnter: checkLogin
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes: routes,
});

export default router;
