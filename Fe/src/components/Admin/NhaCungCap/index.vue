<template>
  <div class="animate-fade-in-up">
    <div class="page-title-bar">
      <div>
        <h1 class="page-title"><i class="fa-solid fa-truck me-2" style="color:#6F4E37;"></i>Quản Lý Nhà Cung Cấp</h1>
        <p class="page-subtitle">Quản lý danh sách nhà cung cấp nguyên liệu</p>
      </div>
    </div>
    <div class="row g-3">
      <div class="col-12 col-lg-4">
        <div class="ch-card">
          <div class="ch-card-header">
            <div class="ch-card-title"><i class="fa-solid fa-building-circle-arrow-right"></i> Thêm Nhà Cung Cấp</div>
          </div>
          <div class="ch-card-body">
            <div class="ch-form-group"><label class="ch-label">Tên Nhà Cung Cấp</label><input v-model="add_nha_cung_cap.ten_nha_cung_cap" type="text" class="ch-input" placeholder="Nhập tên nhà cung cấp"></div>
            <div class="ch-form-group"><label class="ch-label">Email</label><input v-model="add_nha_cung_cap.email" type="email" class="ch-input" placeholder="example@email.com"></div>
            <div class="ch-form-group"><label class="ch-label">Người Liên Hệ</label><input v-model="add_nha_cung_cap.nguoi_lien_he" type="text" class="ch-input" placeholder="Tên người liên hệ"></div>
            <div class="ch-form-group"><label class="ch-label">Số Điện Thoại</label><input v-model="add_nha_cung_cap.so_dien_thoai" type="tel" class="ch-input" placeholder="0xxx xxx xxx"></div>
            <div class="ch-form-group"><label class="ch-label">Địa Chỉ</label><textarea v-model="add_nha_cung_cap.dia_chi" class="ch-textarea" rows="2" placeholder="Nhập địa chỉ"></textarea></div>
            <div class="ch-form-group"><label class="ch-label">Tình Trạng</label><select v-model="add_nha_cung_cap.tinh_trang" class="ch-select"><option value="1">✅ Hoạt Động</option><option value="0">⏸ Tạm Tắt</option></select></div>
          </div>
          <div class="ch-card-footer">
            <button @click="themNhaCungCap()" class="ch-btn ch-btn-primary" style="width:100%;justify-content:center;">
              <i class="fa-solid fa-plus"></i> Thêm Nhà Cung Cấp
            </button>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-8">
        <div class="ch-card">
          <div class="ch-card-header">
            <div class="ch-card-title"><i class="fa-solid fa-list-ul"></i> Danh Sách Nhà Cung Cấp <span class="ch-badge ch-badge-coffee ms-2">{{ list_nha_cung_cap.length }}</span></div>
          </div>
          <div class="ch-card-body" style="padding-top:12px;">
            <div class="ch-search-wrap mb-3" style="max-width:350px;">
              <i class="ch-search-icon fa-solid fa-search"></i>
              <input v-model="key_search" type="text" class="ch-input" placeholder="Tìm kiếm nhà cung cấp...">
            </div>
            <div class="ch-table-wrap">
              <table class="ch-table">
                <thead>
                  <tr>
                    <th style="width:48px;text-align:center;">#</th>
                    <th>Tên Nhà CC</th>
                    <th>Email</th>
                    <th>Người Liên Hệ</th>
                    <th>Điện Thoại</th>
                    <th style="text-align:center;">Trạng Thái</th>
                    <th style="text-align:center;">Thao Tác</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(value, index) in list_nha_cung_cap_loc" :key="index">
                    <td style="text-align:center;color:#9c7b66;font-weight:600;">{{ index + 1 }}</td>
                    <td style="font-weight:600;">{{ value.ten_nha_cung_cap }}</td>
                    <td style="color:#6b5744;font-size:13px;">{{ value.email }}</td>
                    <td>{{ value.nguoi_lien_he }}</td>
                    <td>{{ value.so_dien_thoai }}</td>
                    <td style="text-align:center;">
                      <button v-on:click="changeStatus(value)" :class="value.tinh_trang == 1 ? 'status-toggle active' : 'status-toggle inactive'">
                        <i :class="value.tinh_trang == 1 ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle-pause'"></i>
                        {{ value.tinh_trang == 1 ? 'Hoạt Động' : 'Tạm Tắt' }}
                      </button>
                    </td>
                    <td style="text-align:center;">
                      <div style="display:flex;gap:6px;justify-content:center;">
                        <button @click="Object.assign(edit_nha_cung_cap, value)" class="ch-btn ch-btn-info ch-btn-sm" data-bs-toggle="modal" data-bs-target="#updateModal"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button @click="Object.assign(delete_nha_cung_cap, value)" class="ch-btn ch-btn-danger ch-btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fa-solid fa-trash"></i></button>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="list_nha_cung_cap_loc.length === 0"><td colspan="7"><div class="empty-state"><div class="empty-state-icon"><i class="fa-solid fa-truck"></i></div><div class="empty-state-text">Chưa có nhà cung cấp nào</div></div></td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade ch-modal" id="updateModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header"><h5 class="modal-title"><i class="fa-solid fa-pen me-2"></i>Cập Nhật Nhà Cung Cấp</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body">
            <div class="ch-form-group"><label class="ch-label">Tên Nhà Cung Cấp</label><input v-model="edit_nha_cung_cap.ten_nha_cung_cap" type="text" class="ch-input"></div>
            <div class="ch-form-group"><label class="ch-label">Email</label><input v-model="edit_nha_cung_cap.email" type="email" class="ch-input"></div>
            <div class="ch-form-group"><label class="ch-label">Người Liên Hệ</label><input v-model="edit_nha_cung_cap.nguoi_lien_he" type="text" class="ch-input"></div>
            <div class="ch-form-group"><label class="ch-label">Số Điện Thoại</label><input v-model="edit_nha_cung_cap.so_dien_thoai" type="tel" class="ch-input"></div>
            <div class="ch-form-group"><label class="ch-label">Địa Chỉ</label><textarea v-model="edit_nha_cung_cap.dia_chi" class="ch-textarea" rows="2"></textarea></div>
            <div class="ch-form-group"><label class="ch-label">Tình Trạng</label><select v-model="edit_nha_cung_cap.tinh_trang" class="ch-select"><option value="1">✅ Hoạt Động</option><option value="0">⏸ Tạm Tắt</option></select></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="ch-btn ch-btn-ghost" data-bs-dismiss="modal">Hủy</button>
            <button @click="updateNhaCungCap()" type="button" class="ch-btn ch-btn-primary" data-bs-dismiss="modal"><i class="fa-solid fa-floppy-disk"></i> Lưu</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade ch-modal" id="deleteModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
          <div class="modal-header danger-header"><h5 class="modal-title"><i class="fa-solid fa-triangle-exclamation me-2"></i>Xác Nhận Xóa</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body" style="text-align:center;padding:28px 20px;">
            <div style="width:64px;height:64px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;"><i class="fa-solid fa-trash" style="font-size:24px;color:#ef4444;"></i></div>
            <p style="font-weight:600;">Xóa nhà cung cấp <b>{{ delete_nha_cung_cap.ten_nha_cung_cap }}</b>?</p>
          </div>
          <div class="modal-footer" style="justify-content:center;gap:12px;">
            <button type="button" class="ch-btn ch-btn-ghost" data-bs-dismiss="modal">Hủy</button>
            <button @click="deleteNhaCungCap()" type="button" class="ch-btn ch-btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-trash"></i> Xóa</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
    name: 'NhaCungCapComponent',
    data() {
        return {
            list_nha_cung_cap: [],
            key_search: '',
            add_nha_cung_cap: { ten_nha_cung_cap: '', email: '', nguoi_lien_he: '', so_dien_thoai: '', dia_chi: '', tinh_trang: 1 },
            edit_nha_cung_cap: {},
            delete_nha_cung_cap: {}
        }
    },
    computed: {
        list_nha_cung_cap_loc() {
            if (!this.key_search) return this.list_nha_cung_cap;
            return this.list_nha_cung_cap.filter(item => item.ten_nha_cung_cap && item.ten_nha_cung_cap.toLowerCase().includes(this.key_search.toLowerCase().trim()));
        }
    },
    mounted() { this.loadNhaCungCap(); },
    methods: {
        loadNhaCungCap() {
            const token = localStorage.getItem('xs');
            axios.get('http://127.0.0.1:8000/api/admin/nha-cung-cap/get-data', { headers: { 'Authorization': `Bearer ${token}` } }).then(res => { this.list_nha_cung_cap = res.data.data; });
        },
        themNhaCungCap() {
            const token = localStorage.getItem('xs');
            axios.post('http://127.0.0.1:8000/api/admin/nha-cung-cap/store', this.add_nha_cung_cap, { headers: { 'Authorization': `Bearer ${token}` } })
                .then(res => { if (res.data.status) { this.$toast.success(res.data.message || "Thêm thành công"); this.loadNhaCungCap(); this.add_nha_cung_cap = { ten_nha_cung_cap: '', email: '', nguoi_lien_he: '', so_dien_thoai: '', dia_chi: '', tinh_trang: 1 }; } else { this.$toast.error(res.data.message); } })
                .catch(err => { this.$toast.error(err.response?.data?.message || "Không thể kết nối!"); });
        },
        updateNhaCungCap() {
            const token = localStorage.getItem('xs');
            axios.post('http://127.0.0.1:8000/api/admin/nha-cung-cap/update', this.edit_nha_cung_cap, { headers: { 'Authorization': `Bearer ${token}` } })
                .then(res => { if (res.data.status == 1) { this.$toast.success(res.data.message); this.loadNhaCungCap(); } else { this.$toast.error(res.data.message); } })
                .catch(err => { this.$toast.error(err.response?.data?.message || "Có lỗi xảy ra!"); });
        },
        deleteNhaCungCap() {
            const token = localStorage.getItem('xs');
            axios.post('http://127.0.0.1:8000/api/admin/nha-cung-cap/delete', this.delete_nha_cung_cap, { headers: { 'Authorization': `Bearer ${token}` } })
                .then(res => { if (res.data.status == 1) { this.$toast.success(res.data.message); this.loadNhaCungCap(); } else { this.$toast.error(res.data.message); } })
                .catch(err => { this.$toast.error(err.response?.data?.message || "Có lỗi xảy ra!"); });
        },
        changeStatus(value) {
            const token = localStorage.getItem('xs');
            axios.post('http://127.0.0.1:8000/api/admin/nha-cung-cap/change-status', value, { headers: { 'Authorization': `Bearer ${token}` } })
                .then(res => { if (res.data.status == 1) { this.$toast.success(res.data.message); this.loadNhaCungCap(); } else { this.$toast.error(res.data.message); } })
                .catch(err => { this.$toast.error(err.response?.data?.message || "Có lỗi xảy ra!"); });
        }
    }
}
</script>
<style scoped></style>