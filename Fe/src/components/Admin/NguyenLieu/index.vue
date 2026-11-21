<template>
  <div class="animate-fade-in-up">
    <div class="page-title-bar">
      <div>
        <h1 class="page-title"><i class="fa-solid fa-leaf me-2" style="color:#6F4E37;"></i>Quản Lý Nguyên Liệu</h1>
        <p class="page-subtitle">Theo dõi và quản lý kho nguyên liệu</p>
      </div>
    </div>
    <div class="row g-3">
      <div class="col-12 col-lg-4">
        <div class="ch-card">
          <div class="ch-card-header">
            <div class="ch-card-title"><i class="fa-solid fa-plus-circle"></i> Thêm Nguyên Liệu</div>
          </div>
          <div class="ch-card-body">
            <div class="ch-form-group"><label class="ch-label">Tên Nguyên Liệu</label><input v-model="add_nguyen_lieu.ten_nguyen_lieu" type="text" class="ch-input" placeholder="Nhập tên nguyên liệu"></div>
            <div class="ch-form-group"><label class="ch-label">Đơn Vị Tính</label><input v-model="add_nguyen_lieu.don_vi_tinh" type="text" class="ch-input" placeholder="kg, lít, hộp..."></div>
            <div class="ch-form-group"><label class="ch-label">Số Lượng Tồn</label><input v-model="add_nguyen_lieu.so_luong_ton" type="number" class="ch-input" placeholder="0"></div>
            <div class="ch-form-group"><label class="ch-label">Giá Nhập</label><input v-model="add_nguyen_lieu.gia_nhap" type="number" class="ch-input" placeholder="0"></div>
            <div class="ch-form-group"><label class="ch-label">Mức Cảnh Báo</label><input v-model="add_nguyen_lieu.muc_canh_bao" type="number" class="ch-input" placeholder="5"></div>
            <div class="ch-form-group"><label class="ch-label">Nhà Cung Cấp</label><select v-model="add_nguyen_lieu.id_nha_cung_cap" class="ch-select"><option value="">-- Chọn NCC --</option><option v-for="(v, k) in list_nha_cung_cap_active" :key="k" :value="v.id">{{ v.ten_nha_cung_cap }}</option></select></div>
            <div class="ch-form-group"><label class="ch-label">Tình Trạng</label><select v-model="add_nguyen_lieu.tinh_trang" class="ch-select"><option value="1">✅ Hoạt Động</option><option value="0">⏸ Tạm Tắt</option></select></div>
          </div>
          <div class="ch-card-footer">
            <button @click="themNguyenLieu()" class="ch-btn ch-btn-primary" style="width:100%;justify-content:center;">
              <i class="fa-solid fa-plus"></i> Thêm Nguyên Liệu
            </button>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-8">
        <div class="ch-card">
          <div class="ch-card-header">
            <div class="ch-card-title"><i class="fa-solid fa-list-ul"></i> Danh Sách Nguyên Liệu <span class="ch-badge ch-badge-coffee ms-2">{{ list_nguyen_lieu.length }}</span></div>
          </div>
          <div class="ch-card-body" style="padding-top:12px;">
            <div class="ch-table-wrap">
              <table class="ch-table">
                <thead>
                  <tr>
                    <th style="width:48px;text-align:center;">#</th>
                    <th>Tên Nguyên Liệu</th>
                    <th>Đơn Vị</th>
                    <th style="text-align:right;">Tồn Kho</th>
                    <th style="text-align:right;">Giá Nhập</th>
                    <th>Nhà CC</th>
                    <th style="text-align:center;">Trạng Thái</th>
                    <th style="text-align:center;">Thao Tác</th>
                  </tr>
                </thead>
                <tbody>
                  <template v-for="(value, index) in list_nguyen_lieu" :key="index">
                    <tr :style="value.so_luong_ton <= value.muc_canh_bao ? 'background:#fff5f5;' : ''">
                      <td style="text-align:center;color:#9c7b66;font-weight:600;">{{ index + 1 }}</td>
                      <td>
                        <span style="font-weight:600;">{{ value.ten_nguyen_lieu }}</span>
                        <span v-if="value.so_luong_ton <= value.muc_canh_bao" class="ch-badge ch-badge-danger ms-2" style="font-size:10px;">⚠ Sắp hết</span>
                      </td>
                      <td><span class="ch-badge ch-badge-coffee">{{ value.don_vi_tinh }}</span></td>
                      <td style="text-align:right;font-weight:600;" :style="value.so_luong_ton <= value.muc_canh_bao ? 'color:#ef4444;' : 'color:#10b981;'">{{ value.so_luong_ton }}</td>
                      <td style="text-align:right;">{{ value.gia_nhap ? new Intl.NumberFormat('vi-VN').format(value.gia_nhap) + ' ₫' : '-' }}</td>
                      <td style="font-size:12px;color:#6b5744;">{{ value.ten_nha_cung_cap || '-' }}</td>
                      <td style="text-align:center;">
                        <button v-on:click="changeStatus(value)" :class="value.tinh_trang == 1 ? 'status-toggle active' : 'status-toggle inactive'">
                          <i :class="value.tinh_trang == 1 ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle-pause'"></i>
                          {{ value.tinh_trang == 1 ? 'Hoạt Động' : 'Tạm Tắt' }}
                        </button>
                      </td>
                      <td style="text-align:center;">
                        <div style="display:flex;gap:6px;justify-content:center;">
                          <button @click="Object.assign(edit_nguyen_lieu, value)" class="ch-btn ch-btn-info ch-btn-sm" data-bs-toggle="modal" data-bs-target="#updateModal"><i class="fa-solid fa-pen-to-square"></i></button>
                          <button @click="Object.assign(delete_nguyen_lieu, value)" class="ch-btn ch-btn-danger ch-btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fa-solid fa-trash"></i></button>
                        </div>
                      </td>
                    </tr>
                  </template>
                  <tr v-if="list_nguyen_lieu.length === 0"><td colspan="8"><div class="empty-state"><div class="empty-state-icon"><i class="fa-solid fa-box-open"></i></div><div class="empty-state-text">Chưa có nguyên liệu nào</div></div></td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Update -->
    <div class="modal fade ch-modal" id="updateModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header"><h5 class="modal-title"><i class="fa-solid fa-pen me-2"></i>Cập Nhật Nguyên Liệu</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body">
            <div class="ch-form-group"><label class="ch-label">Tên Nguyên Liệu</label><input v-model="edit_nguyen_lieu.ten_nguyen_lieu" type="text" class="ch-input"></div>
            <div class="ch-form-group"><label class="ch-label">Đơn Vị Tính</label><input v-model="edit_nguyen_lieu.don_vi_tinh" type="text" class="ch-input"></div>
            <div class="ch-form-group"><label class="ch-label">Số Lượng Tồn</label><input v-model="edit_nguyen_lieu.so_luong_ton" type="number" class="ch-input"></div>
            <div class="ch-form-group"><label class="ch-label">Giá Nhập</label><input v-model="edit_nguyen_lieu.gia_nhap" type="number" class="ch-input"></div>
            <div class="ch-form-group"><label class="ch-label">Mức Cảnh Báo</label><input v-model="edit_nguyen_lieu.muc_canh_bao" type="number" class="ch-input"></div>
            <div class="ch-form-group"><label class="ch-label">Nhà Cung Cấp</label><select v-model="edit_nguyen_lieu.id_nha_cung_cap" class="ch-select"><option value="">-- Chọn NCC --</option><option v-for="(v, k) in list_nha_cung_cap" :key="k" :value="v.id">{{ v.ten_nha_cung_cap }}</option></select></div>
            <div class="ch-form-group"><label class="ch-label">Tình Trạng</label><select v-model="edit_nguyen_lieu.tinh_trang" class="ch-select"><option value="1">✅ Hoạt Động</option><option value="0">⏸ Tạm Tắt</option></select></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="ch-btn ch-btn-ghost" data-bs-dismiss="modal">Hủy</button>
            <button @click="updateNguyenLieu()" type="button" class="ch-btn ch-btn-primary" data-bs-dismiss="modal"><i class="fa-solid fa-floppy-disk"></i> Lưu</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Xóa -->
    <div class="modal fade ch-modal" id="deleteModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
          <div class="modal-header danger-header"><h5 class="modal-title"><i class="fa-solid fa-triangle-exclamation me-2"></i>Xác Nhận Xóa</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body" style="text-align:center;padding:28px 20px;">
            <div style="width:64px;height:64px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;"><i class="fa-solid fa-trash" style="font-size:24px;color:#ef4444;"></i></div>
            <p style="font-weight:600;">Xóa nguyên liệu <b>{{ delete_nguyen_lieu.ten_nguyen_lieu }}</b>?</p>
          </div>
          <div class="modal-footer" style="justify-content:center;gap:12px;">
            <button type="button" class="ch-btn ch-btn-ghost" data-bs-dismiss="modal">Hủy</button>
            <button @click="deleteNguyenLieu()" type="button" class="ch-btn ch-btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-trash"></i> Xóa</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'NguyenLieuComponent',
    data() {
        return {
            list_nguyen_lieu: [],
            list_nha_cung_cap: [],
            list_nha_cung_cap_active: [],
            add_nguyen_lieu: { ten_nguyen_lieu: '', don_vi_tinh: '', so_luong_ton: 0, gia_nhap: 0, muc_canh_bao: 5, id_nha_cung_cap: '', tinh_trang: 1 },
            edit_nguyen_lieu: {},
            delete_nguyen_lieu: {}
        }
    },
    mounted() { this.loadDataNguyenLieu(); this.loadNhaCungCap(); this.loadNhaCungCapActive(); },
    methods: {
        loadDataNguyenLieu() {
            const token = localStorage.getItem('xs');
            axios.get('http://127.0.0.1:8000/api/admin/nguyen-lieu/get-data', { headers: { 'Authorization': `Bearer ${token}` } }).then(res => { this.list_nguyen_lieu = res.data.data; });
        },
        loadNhaCungCap() {
            const token = localStorage.getItem('xs');
            axios.get('http://127.0.0.1:8000/api/admin/nha-cung-cap/get-data', { headers: { 'Authorization': `Bearer ${token}` } }).then(res => { this.list_nha_cung_cap = res.data.data; });
        },
        loadNhaCungCapActive() {
            const token = localStorage.getItem('xs');
            axios.get('http://127.0.0.1:8000/api/admin/nha-cung-cap/get-data-active', { headers: { 'Authorization': `Bearer ${token}` } }).then(res => { this.list_nha_cung_cap_active = res.data.data; });
        },
        themNguyenLieu() {
            const token = localStorage.getItem('xs');
            axios.post('http://127.0.0.1:8000/api/admin/nguyen-lieu/store', this.add_nguyen_lieu, { headers: { 'Authorization': `Bearer ${token}` } })
                .then(res => { if (res.data.status) { this.$toast.success(res.data.message || "Thêm thành công"); this.loadDataNguyenLieu(); this.add_nguyen_lieu = { ten_nguyen_lieu: '', don_vi_tinh: '', so_luong_ton: 0, gia_nhap: 0, muc_canh_bao: 5, id_nha_cung_cap: '', tinh_trang: 1 }; } else { this.$toast.error(res.data.message); } })
                .catch(err => { this.$toast.error(err.response?.data?.message || "Không thể kết nối!"); });
        },
        updateNguyenLieu() {
            const token = localStorage.getItem('xs');
            axios.post('http://127.0.0.1:8000/api/admin/nguyen-lieu/update', this.edit_nguyen_lieu, { headers: { 'Authorization': `Bearer ${token}` } })
                .then(res => { if (res.data.status == 1) { this.$toast.success(res.data.message); this.loadDataNguyenLieu(); } else { this.$toast.error(res.data.message); } })
                .catch(err => { this.$toast.error(err.response?.data?.message || "Có lỗi xảy ra!"); });
        },
        deleteNguyenLieu() {
            const token = localStorage.getItem('xs');
            axios.post('http://127.0.0.1:8000/api/admin/nguyen-lieu/delete', this.delete_nguyen_lieu, { headers: { 'Authorization': `Bearer ${token}` } })
                .then(res => { if (res.data.status == 1) { this.$toast.success(res.data.message); this.loadDataNguyenLieu(); } else { this.$toast.error(res.data.message); } })
                .catch(err => { this.$toast.error(err.response?.data?.message || "Có lỗi xảy ra!"); });
        },
        changeStatus(value) {
            const token = localStorage.getItem('xs');
            axios.post('http://127.0.0.1:8000/api/admin/nguyen-lieu/change-status', value, { headers: { 'Authorization': `Bearer ${token}` } })
                .then(res => { if (res.data.status == 1) { this.$toast.success(res.data.message); this.loadDataNguyenLieu(); } else { this.$toast.error(res.data.message); } })
                .catch(err => { this.$toast.error(err.response?.data?.message || "Có lỗi xảy ra!"); });
        }
    }
}
</script>
<style scoped></style>