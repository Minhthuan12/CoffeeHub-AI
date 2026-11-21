<template>
  <div class="animate-fade-in-up">
    <div class="page-title-bar">
      <div>
        <h1 class="page-title"><i class="fa-solid fa-users me-2" style="color:#6F4E37;"></i>Quản Lý Khách Hàng</h1>
        <p class="page-subtitle">Danh sách và quản lý thông tin khách hàng</p>
      </div>
      <div class="page-title-actions">
        <button class="ch-btn ch-btn-success" data-bs-toggle="modal" data-bs-target="#themMoiKhachHangModal">
          <i class="fa-solid fa-user-plus"></i> Thêm Khách Hàng
        </button>
      </div>
    </div>

    <div class="ch-card">
      <div class="ch-card-body" style="padding-top:16px;">
        <div class="ch-search-wrap mb-3" style="max-width:400px;">
          <i class="ch-search-icon fa-solid fa-search"></i>
          <input v-model="key_search" type="text" class="ch-input" placeholder="Tìm kiếm khách hàng...">
        </div>
        <div class="ch-table-wrap">
          <table class="ch-table">
            <thead>
              <tr>
                <th style="width:48px;text-align:center;">#</th>
                <th>Họ và Tên</th>
                <th>Email</th>
                <th>Số Điện Thoại</th>
                <th style="text-align:center;">Trạng Thái</th>
                <th style="text-align:center;">Thao Tác</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(value, index) in list_khach_hang_loc" :key="index">
                <td style="text-align:center;color:#9c7b66;font-weight:600;">{{ index + 1 }}</td>
                <td>
                  <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#10b981,#059669);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:13px;flex-shrink:0;">
                      {{ value.ho_va_ten ? value.ho_va_ten.trim().split(' ').pop().charAt(0).toUpperCase() : 'K' }}
                    </div>
                    <span style="font-weight:600;">{{ value.ho_va_ten }}<span v-if="value.deleted_at" style="color:#ef4444;font-size:11px;margin-left:4px;">(đã xóa)</span></span>
                  </div>
                </td>
                <td style="color:#6b5744;">{{ value.email }}</td>
                <td>{{ value.so_dien_thoai }}</td>
                <td style="text-align:center;">
                  <button v-on:click="changeStatus(value)" :disabled="!!value.deleted_at" :class="value.tinh_trang == 1 ? 'status-toggle active' : 'status-toggle inactive'">
                    <i :class="value.tinh_trang == 1 ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle-pause'"></i>
                    {{ value.tinh_trang == 1 ? 'Hoạt Động' : 'Tạm Tắt' }}
                  </button>
                </td>
                <td style="text-align:center;">
                  <template v-if="!value.deleted_at">
                    <div style="display:flex;gap:6px;justify-content:center;">
                      <button class="ch-btn ch-btn-info ch-btn-sm" data-bs-toggle="modal" data-bs-target="#capNhatKhachHangModal" @click="Object.assign(edit_khach_hang, value)">
                        <i class="fa-solid fa-pen-to-square"></i>
                      </button>
                      <button class="ch-btn ch-btn-danger ch-btn-sm" data-bs-toggle="modal" data-bs-target="#xoaKhachHangModal" @click="Object.assign(delete_khach_hang, value)">
                        <i class="fa-solid fa-trash"></i>
                      </button>
                    </div>
                  </template>
                </td>
              </tr>
              <tr v-if="list_khach_hang_loc.length === 0">
                <td colspan="6"><div class="empty-state"><div class="empty-state-icon"><i class="fa-solid fa-users-slash"></i></div><div class="empty-state-text">Không có khách hàng nào</div></div></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Modal Thêm -->
    <div class="modal fade ch-modal" id="themMoiKhachHangModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="fa-solid fa-user-plus me-2"></i>Thêm Khách Hàng</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="ch-form-group"><label class="ch-label">Họ và Tên</label><input v-model="add_khach_hang.ho_va_ten" type="text" class="ch-input" placeholder="Nhập họ và tên"></div>
            <div class="ch-form-group"><label class="ch-label">Email</label><input v-model="add_khach_hang.email" type="email" class="ch-input" placeholder="example@email.com"></div>
            <div class="ch-form-group"><label class="ch-label">Số Điện Thoại</label><input v-model="add_khach_hang.so_dien_thoai" type="text" class="ch-input" placeholder="0xxx xxx xxx"></div>
            <div class="ch-form-group"><label class="ch-label">Trạng Thái</label><select v-model="add_khach_hang.tinh_trang" class="ch-select"><option value="1">✅ Hoạt Động</option><option value="0">⏸ Tạm Tắt</option></select></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="ch-btn ch-btn-ghost" data-bs-dismiss="modal">Hủy</button>
            <button @click="themKhachHang()" type="button" class="ch-btn ch-btn-success" data-bs-dismiss="modal"><i class="fa-solid fa-plus"></i> Thêm Mới</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Cập nhật -->
    <div class="modal fade ch-modal" id="capNhatKhachHangModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="fa-solid fa-pen-to-square me-2"></i>Cập Nhật Khách Hàng</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="ch-form-group"><label class="ch-label">Họ và Tên</label><input v-model="edit_khach_hang.ho_va_ten" type="text" class="ch-input"></div>
            <div class="ch-form-group"><label class="ch-label">Email</label><input v-model="edit_khach_hang.email" type="email" class="ch-input"></div>
            <div class="ch-form-group"><label class="ch-label">Số Điện Thoại</label><input v-model="edit_khach_hang.so_dien_thoai" type="text" class="ch-input"></div>
            <div class="ch-form-group"><label class="ch-label">Trạng Thái</label><select v-model="edit_khach_hang.tinh_trang" class="ch-select"><option value="1">✅ Hoạt Động</option><option value="0">⏸ Tạm Tắt</option></select></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="ch-btn ch-btn-ghost" data-bs-dismiss="modal">Hủy</button>
            <button @click="updateKhachHang()" type="button" class="ch-btn ch-btn-primary" data-bs-dismiss="modal"><i class="fa-solid fa-floppy-disk"></i> Lưu</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Xóa -->
    <div class="modal fade ch-modal" id="xoaKhachHangModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
          <div class="modal-header danger-header">
            <h5 class="modal-title"><i class="fa-solid fa-triangle-exclamation me-2"></i>Xác Nhận Xóa</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body" style="text-align:center;padding:28px 20px;">
            <div style="width:64px;height:64px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;"><i class="fa-solid fa-trash" style="font-size:24px;color:#ef4444;"></i></div>
            <p style="font-weight:600;color:#1a0a00;">Xóa khách hàng <b>{{ delete_khach_hang.ho_va_ten }}</b>?</p>
          </div>
          <div class="modal-footer" style="justify-content:center;gap:12px;">
            <button type="button" class="ch-btn ch-btn-ghost" data-bs-dismiss="modal">Hủy</button>
            <button @click="deleteKhachHang()" type="button" class="ch-btn ch-btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-trash"></i> Xóa</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: 'KhachHangComponent',
  data() {
    return {
      list_Khach_Hang: [],
      key_search: '',
      add_khach_hang: { ho_va_ten: '', email: '', so_dien_thoai: '', tinh_trang: 1 },
      delete_khach_hang: {},
      edit_khach_hang: {}
    }
  },
  computed: {
    list_khach_hang_loc() {
      if (!this.key_search) return this.list_Khach_Hang;
      return this.list_Khach_Hang.filter(item => item.ho_va_ten && item.ho_va_ten.toLowerCase().includes(this.key_search.toLowerCase().trim()));
    }
  },
  mounted() { this.loadDataKhachHang(); },
  methods: {
    loadDataKhachHang() {
      const token = localStorage.getItem('xs');
      axios.get('http://127.0.0.1:8000/api/admin/khach-hang/get-data', { headers: { 'Authorization': `Bearer ${token}` } })
        .then(res => { this.list_Khach_Hang = res.data.data; });
    },
    themKhachHang() {
      const token = localStorage.getItem('xs');
      axios.post('http://127.0.0.1:8000/api/admin/khach-hang/store', this.add_khach_hang, { headers: { 'Authorization': `Bearer ${token}` } })
        .then(res => {
          if (res.data.status) { this.$toast.success(res.data.message || "Thêm thành công"); this.loadDataKhachHang(); this.add_khach_hang = { ho_va_ten: '', email: '', so_dien_thoai: '', tinh_trang: 1 }; }
          else { this.$toast.error(res.data.message || "Thêm thất bại"); }
        }).catch(err => { this.$toast.error(err.response?.data?.message || "Không thể kết nối máy chủ!"); });
    },
    updateKhachHang() {
      const token = localStorage.getItem('xs');
      axios.post('http://127.0.0.1:8000/api/admin/khach-hang/update', this.edit_khach_hang, { headers: { 'Authorization': `Bearer ${token}` } })
        .then(res => { if (res.data.status == 1) { this.$toast.success(res.data.message); this.loadDataKhachHang(); } else { this.$toast.error(res.data.message); } })
        .catch(err => { this.$toast.error(err.response?.data?.message || "Có lỗi xảy ra!"); });
    },
    deleteKhachHang() {
      const token = localStorage.getItem('xs');
      axios.post('http://127.0.0.1:8000/api/admin/khach-hang/delete', this.delete_khach_hang, { headers: { 'Authorization': `Bearer ${token}` } })
        .then(res => { if (res.data.status == 1) { this.$toast.success(res.data.message); this.loadDataKhachHang(); } else { this.$toast.error(res.data.message); } })
        .catch(err => { this.$toast.error(err.response?.data?.message || "Có lỗi xảy ra!"); });
    },
    changeStatus(value) {
      const token = localStorage.getItem('xs');
      axios.post('http://127.0.0.1:8000/api/admin/khach-hang/change-status', value, { headers: { 'Authorization': `Bearer ${token}` } })
        .then(res => { if (res.data.status == 1) { this.$toast.success(res.data.message); this.loadDataKhachHang(); } else { this.$toast.error(res.data.message); } })
        .catch(err => { this.$toast.error(err.response?.data?.message || "Có lỗi xảy ra!"); });
    }
  }
}
</script>
<style scoped></style>