<template>
  <div class="animate-fade-in-up">
    <!-- Page Title -->
    <div class="page-title-bar">
      <div>
        <h1 class="page-title"><i class="fa-solid fa-users me-2" style="color:#6F4E37;"></i>Quản Lý Nhân Viên</h1>
        <p class="page-subtitle">Thêm, cập nhật và quản lý thông tin nhân viên</p>
      </div>
    </div>

    <div class="row g-3">
      <!-- Form thêm mới -->
      <div class="col-12 col-lg-4">
        <div class="ch-card">
          <div class="ch-card-header">
            <div class="ch-card-title">
              <i class="fa-solid fa-user-plus"></i>
              Thêm Nhân Viên Mới
            </div>
          </div>
          <div class="ch-card-body">
            <div class="ch-form-group">
              <label class="ch-label">Họ và Tên</label>
              <input v-model="add_nhan_vien.ho_va_ten" type="text" class="ch-input" placeholder="Nhập họ và tên đầy đủ">
            </div>
            <div class="ch-form-group">
              <label class="ch-label">Email</label>
              <input v-model="add_nhan_vien.email" type="email" class="ch-input" placeholder="example@email.com">
            </div>
            <div class="ch-form-group">
              <label class="ch-label">Mật Khẩu</label>
              <input v-model="add_nhan_vien.password" type="password" class="ch-input" placeholder="Tối thiểu 8 ký tự">
            </div>
            <div class="ch-form-group">
              <label class="ch-label">Số Điện Thoại</label>
              <input v-model="add_nhan_vien.so_dien_thoai" type="tel" class="ch-input" placeholder="0xxx xxx xxx">
            </div>
            <div class="ch-form-group">
              <label class="ch-label">Chức Vụ</label>
              <select v-model="add_nhan_vien.id_chuc_vu" class="ch-select">
                <option value="">-- Chọn chức vụ --</option>
                <option v-for="(v, k) in list_chuc_vu_active" :key="k" :value="v.id">{{ v.ten_chuc_vu }}</option>
              </select>
            </div>
            <div class="ch-form-group">
              <label class="ch-label">Tình Trạng</label>
              <select v-model="add_nhan_vien.tinh_trang" class="ch-select">
                <option value="1">✅ Hoạt Động</option>
                <option value="0">⏸ Tạm Tắt</option>
              </select>
            </div>
          </div>
          <div class="ch-card-footer">
            <button @click="themNhanVien()" class="ch-btn ch-btn-primary" style="width:100%; justify-content:center;">
              <i class="fa-solid fa-plus"></i> Thêm Nhân Viên
            </button>
          </div>
        </div>
      </div>

      <!-- Danh sách -->
      <div class="col-12 col-lg-8">
        <div class="ch-card">
          <div class="ch-card-header">
            <div class="ch-card-title">
              <i class="fa-solid fa-list-ul"></i>
              Danh Sách Nhân Viên
              <span class="ch-badge ch-badge-coffee ms-2">{{ list_nhan_vien.length }} người</span>
            </div>
          </div>
          <div class="ch-card-body" style="padding-top:12px;">
            <!-- Search -->
            <div class="ch-search-wrap mb-3">
              <i class="ch-search-icon fa-solid fa-search"></i>
              <input v-model="key_search" type="text" class="ch-input" placeholder="Tìm kiếm nhân viên theo tên...">
            </div>

            <div class="ch-table-wrap">
              <table class="ch-table">
                <thead>
                  <tr>
                    <th style="width:48px; text-align:center;">#</th>
                    <th>Họ và Tên</th>
                    <th>Email</th>
                    <th>Điện Thoại</th>
                    <th>Chức Vụ</th>
                    <th style="text-align:center;">Trạng Thái</th>
                    <th style="text-align:center;">Thao Tác</th>
                  </tr>
                </thead>
                <tbody>
                  <template v-for="(value, index) in list_nhan_vien_loc" :key="index">
                    <tr>
                      <td style="text-align:center; color:#9c7b66; font-weight:600;">{{ index + 1 }}</td>
                      <td>
                        <div style="display:flex; align-items:center; gap:10px;">
                          <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#6F4E37,#3d1f00);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:13px;flex-shrink:0;">
                            {{ value.ho_va_ten ? value.ho_va_ten.trim().split(' ').pop().charAt(0).toUpperCase() : 'N' }}
                          </div>
                          <span style="font-weight:600;">{{ value.ho_va_ten }}</span>
                        </div>
                      </td>
                      <td style="color:#6b5744;">{{ value.email }}</td>
                      <td>{{ value.so_dien_thoai }}</td>
                      <td>
                        <span class="ch-badge ch-badge-coffee">{{ value.ten_chuc_vu }}</span>
                      </td>
                      <td style="text-align:center;">
                        <button
                          v-on:click="changeStatus(value)"
                          :class="value.tinh_trang == 1 ? 'status-toggle active' : 'status-toggle inactive'"
                        >
                          <i :class="value.tinh_trang == 1 ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle-pause'"></i>
                          {{ value.tinh_trang == 1 ? 'Hoạt Động' : 'Tạm Tắt' }}
                        </button>
                      </td>
                      <td style="text-align:center;">
                        <div style="display:flex; gap:6px; justify-content:center;">
                          <button
                            @click="Object.assign(edit_nhan_vien, value)"
                            class="ch-btn ch-btn-info ch-btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#updateModal"
                          >
                            <i class="fa-solid fa-pen-to-square"></i>
                          </button>
                          <button
                            @click="Object.assign(delete_nhan_vien, value)"
                            class="ch-btn ch-btn-danger ch-btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteModal"
                          >
                            <i class="fa-solid fa-trash"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                  </template>
                  <tr v-if="list_nhan_vien_loc.length === 0">
                    <td colspan="7">
                      <div class="empty-state">
                        <div class="empty-state-icon"><i class="fa-solid fa-users-slash"></i></div>
                        <div class="empty-state-text">Không tìm thấy nhân viên nào</div>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Cập nhật -->
    <div class="modal fade ch-modal" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="updateModalLabel">
              <i class="fa-solid fa-pen-to-square me-2"></i>Cập Nhật Nhân Viên
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="ch-form-group">
              <label class="ch-label">Họ và Tên</label>
              <input v-model="edit_nhan_vien.ho_va_ten" type="text" class="ch-input" placeholder="Họ và tên">
            </div>
            <div class="ch-form-group">
              <label class="ch-label">Email</label>
              <input v-model="edit_nhan_vien.email" type="email" class="ch-input" placeholder="Email">
            </div>
            <div class="ch-form-group">
              <label class="ch-label">Số Điện Thoại</label>
              <input v-model="edit_nhan_vien.so_dien_thoai" type="tel" class="ch-input" placeholder="Số điện thoại">
            </div>
            <div class="ch-form-group">
              <label class="ch-label">Chức Vụ</label>
              <select v-model="edit_nhan_vien.id_chuc_vu" class="ch-select">
                <option value="">-- Chọn chức vụ --</option>
                <option v-for="(v, k) in list_chuc_vu" :key="k" :value="v.id">{{ v.ten_chuc_vu }}</option>
              </select>
            </div>
            <div class="ch-form-group">
              <label class="ch-label">Tình Trạng</label>
              <select v-model="edit_nhan_vien.tinh_trang" class="ch-select">
                <option value="1">✅ Hoạt Động</option>
                <option value="0">⏸ Tạm Tắt</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="ch-btn ch-btn-ghost" data-bs-dismiss="modal">Hủy</button>
            <button type="button" v-on:click="updateNhanVien()" class="ch-btn ch-btn-primary" data-bs-dismiss="modal">
              <i class="fa-solid fa-floppy-disk"></i> Lưu Thay Đổi
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Xóa -->
    <div class="modal fade ch-modal" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
          <div class="modal-header danger-header">
            <h5 class="modal-title" id="deleteModalLabel">
              <i class="fa-solid fa-triangle-exclamation me-2"></i>Xác Nhận Xóa
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" style="text-align:center; padding:28px 20px;">
            <div style="width:64px;height:64px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
              <i class="fa-solid fa-trash" style="font-size:24px;color:#ef4444;"></i>
            </div>
            <p style="font-size:14.5px;font-weight:600;color:#1a0a00;margin-bottom:6px;">Bạn có chắc chắn?</p>
            <p style="font-size:13px;color:#9c7b66;margin:0;">Nhân viên <b>{{ delete_nhan_vien.ho_va_ten }}</b> sẽ bị xóa khỏi hệ thống.</p>
          </div>
          <div class="modal-footer" style="justify-content:center; gap:12px;">
            <button type="button" class="ch-btn ch-btn-ghost" data-bs-dismiss="modal">Hủy</button>
            <button @click="deleteNhanVien()" type="button" class="ch-btn ch-btn-danger" data-bs-dismiss="modal">
              <i class="fa-solid fa-trash"></i> Xóa
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
  name: 'NhanVienComponent',
  data() {
    return {
      list_nhan_vien: [],
      list_chuc_vu: [],
      list_chuc_vu_active: [],
      key_search: '',
      add_nhan_vien: {
        ho_va_ten: '',
        email: '',
        password: '',
        so_dien_thoai: '',
        id_chuc_vu: '',
        tinh_trang: 1
      },
      delete_nhan_vien: {},
      edit_nhan_vien: {},
      list_nhan_vien_deleted: []
    }
  },
  computed: {
    list_nhan_vien_loc() {
      if (!this.key_search) {
        return this.list_nhan_vien;
      }
      return this.list_nhan_vien.filter((item) =>
        item.ho_va_ten && item.ho_va_ten.toLowerCase().includes(this.key_search.toLowerCase().trim())
      );
    }
  },
  mounted() {
    this.loadDataNhanVien();
    this.loadChucVu();
  },
  methods: {
    loadDataNhanVien() {
      const token = localStorage.getItem('xs');
      axios
        .get('http://127.0.0.1:8000/api/admin/nhan-vien/get-data', {
          headers: { 'Authorization': `Bearer ${token}` }
        })
        .then((res) => {
          this.list_nhan_vien = res.data.data;
        })
    },
    loadChucVu() {
      const token = localStorage.getItem('xs');
      axios
        .get('http://127.0.0.1:8000/api/admin/chuc-vu/get-data', {
          headers: { 'Authorization': `Bearer ${token}` }
        })
        .then((res) => {
          this.list_chuc_vu = res.data.data;
        });
      axios
        .get('http://127.0.0.1:8000/api/admin/chuc-vu/get-data-active', {
          headers: { 'Authorization': `Bearer ${token}` }
        })
        .then((res) => {
          this.list_chuc_vu_active = res.data.data;
        });
    },
    themNhanVien() {
      const token = localStorage.getItem('xs');
      axios
        .post('http://127.0.0.1:8000/api/admin/nhan-vien/store', this.add_nhan_vien, {
          headers: { 'Authorization': `Bearer ${token}` }
        })
        .then((res) => {
          if (res.data.status) {
            this.$toast.success(res.data.message || "Thêm nhân viên thành công");
            this.loadDataNhanVien();
            this.add_nhan_vien = { ho_va_ten: '', email: '', password: '', so_dien_thoai: '', id_chuc_vu: '', tinh_trang: 1 };
          } else {
            this.$toast.error(res.data.message || "Thêm thất bại");
          }
        })
        .catch((error) => {
          console.error(error);
          this.$toast.error(error.response?.data?.message || "Không thể kết nối đến máy chủ!");
        });
    },
    updateNhanVien() {
      const token = localStorage.getItem('xs');
      axios
        .post('http://127.0.0.1:8000/api/admin/nhan-vien/update', this.edit_nhan_vien, {
          headers: { 'Authorization': `Bearer ${token}` }
        })
        .then((res) => {
          if (res.data.status) {
            this.$toast.success(res.data.message);
            this.loadDataNhanVien();
          } else {
            this.$toast.error(res.data.message || "Cập nhật thất bại");
          }
        });
    },
    deleteNhanVien() {
      const token = localStorage.getItem('xs');
      axios
        .post('http://127.0.0.1:8000/api/admin/nhan-vien/delete', this.delete_nhan_vien, {
          headers: { 'Authorization': `Bearer ${token}` }
        })
        .then((res) => {
          if (res.data.status == 1) {
            this.$toast.success(res.data.message);
            this.loadDataNhanVien();
          } else {
            this.$toast.error(res.data.message);
          }
        })
        .catch((error) => {
          this.$toast.error(error.response?.data?.message || "Không thể kết nối đến máy chủ!");
        });
    },
    changeStatus(value) {
      const token = localStorage.getItem('xs');
      axios
        .post('http://127.0.0.1:8000/api/admin/nhan-vien/change-status', value, {
          headers: { 'Authorization': `Bearer ${token}` }
        })
        .then((res) => {
          if (res.data.status == 1) {
            this.$toast.success(res.data.message);
            this.loadDataNhanVien();
          } else {
            this.$toast.error(res.data.message);
          }
        });
    },
    loadDataNhanVienDeleted() {
      const token = localStorage.getItem('xs');
      axios
        .get('http://127.0.0.1:8000/api/admin/nhan-vien/get-deleted-data', {
          headers: { 'Authorization': `Bearer ${token}` }
        })
        .then((res) => {
          this.list_nhan_vien_deleted = res.data.data;
        })
        .catch((error) => {
          this.$toast.error(error.response?.data?.message || "Có lỗi xảy ra khi tải thùng rác!");
        });
    },
    restoreNhanVien(value) {
      const token = localStorage.getItem('xs');
      axios
        .post('http://127.0.0.1:8000/api/admin/nhan-vien/restore', value, {
          headers: { 'Authorization': `Bearer ${token}` }
        })
        .then((res) => {
          if (res.data.status) {
            this.$toast.success(res.data.message || "Khôi phục nhân viên thành công!");
            this.loadDataNhanVien();
            this.loadDataNhanVienDeleted();
          } else {
            this.$toast.error(res.data.message || "Khôi phục thất bại!");
          }
        })
        .catch((error) => {
          this.$toast.error(error.response?.data?.message || "Có lỗi xảy ra!");
        });
    }
  },
}
</script>
<style scoped></style>