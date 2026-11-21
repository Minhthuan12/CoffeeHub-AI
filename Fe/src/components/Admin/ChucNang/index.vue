<template>
  <div class="animate-fade-in-up">
    <div class="page-title-bar">
      <div>
        <h1 class="page-title"><i class="fa-solid fa-shield-halved me-2" style="color:#6F4E37;"></i>Quản Lý Chức Năng</h1>
        <p class="page-subtitle">Quản lý danh sách chức năng trong hệ thống</p>
      </div>
    </div>
    <div class="row g-3">
      <div class="col-12 col-lg-4">
        <div class="ch-card">
          <div class="ch-card-header">
            <div class="ch-card-title"><i class="fa-solid fa-plus-circle"></i> Thêm Chức Năng</div>
          </div>
          <div class="ch-card-body">
            <div class="ch-form-group"><label class="ch-label">Tên Chức Năng</label><input v-model="add_chuc_nang.ten_chuc_nang" type="text" class="ch-input" placeholder="VD: Quản lý sản phẩm, Xem báo cáo..."></div>
          </div>
          <div class="ch-card-footer">
            <button @click="themChucNang()" class="ch-btn ch-btn-primary" style="width:100%;justify-content:center;">
              <i class="fa-solid fa-plus"></i> Thêm Chức Năng
            </button>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-8">
        <div class="ch-card">
          <div class="ch-card-header">
            <div class="ch-card-title"><i class="fa-solid fa-list-ul"></i> Danh Sách Chức Năng <span class="ch-badge ch-badge-coffee ms-2">{{ list_chuc_nang.length }}</span></div>
          </div>
          <div class="ch-card-body" style="padding-top:12px;">
            <div class="ch-table-wrap">
              <table class="ch-table">
                <thead>
                  <tr>
                    <th style="width:48px;text-align:center;">#</th>
                    <th>Tên Chức Năng</th>
                    <th style="text-align:center;">Thao Tác</th>
                  </tr>
                </thead>
                <tbody>
                  <template v-for="(value, index) in list_chuc_nang" :key="index">
                    <tr>
                      <td style="text-align:center;color:#9c7b66;font-weight:600;">{{ index + 1 }}</td>
                      <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                          <div style="width:34px;height:34px;border-radius:8px;background:linear-gradient(135deg,#8b5cf6,#6d28d9);display:flex;align-items:center;justify-content:center;color:#fff;flex-shrink:0;">
                            <i class="fa-solid fa-key" style="font-size:13px;"></i>
                          </div>
                          <span style="font-weight:600;">{{ value.ten_chuc_nang }}</span>
                        </div>
                      </td>
                      <td style="text-align:center;">
                        <div style="display:flex;gap:6px;justify-content:center;">
                          <button @click="Object.assign(edit_chuc_nang, value)" class="ch-btn ch-btn-info ch-btn-sm" data-bs-toggle="modal" data-bs-target="#updateModal"><i class="fa-solid fa-pen-to-square"></i></button>
                          <button @click="Object.assign(delete_chuc_nang, value)" class="ch-btn ch-btn-danger ch-btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fa-solid fa-trash"></i></button>
                        </div>
                      </td>
                    </tr>
                  </template>
                  <tr v-if="list_chuc_nang.length === 0"><td colspan="3"><div class="empty-state"><div class="empty-state-icon"><i class="fa-solid fa-shield-halved"></i></div><div class="empty-state-text">Chưa có chức năng nào</div></div></td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade ch-modal" id="updateModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header"><h5 class="modal-title"><i class="fa-solid fa-pen me-2"></i>Cập Nhật Chức Năng</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body">
            <div class="ch-form-group"><label class="ch-label">Tên Chức Năng</label><input v-model="edit_chuc_nang.ten_chuc_nang" type="text" class="ch-input"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="ch-btn ch-btn-ghost" data-bs-dismiss="modal">Hủy</button>
            <button @click="updateChucNang()" type="button" class="ch-btn ch-btn-primary" data-bs-dismiss="modal"><i class="fa-solid fa-floppy-disk"></i> Lưu</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade ch-modal" id="deleteModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
          <div class="modal-header danger-header"><h5 class="modal-title"><i class="fa-solid fa-triangle-exclamation me-2"></i>Xác Nhận Xóa</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body" style="text-align:center;padding:28px 20px;">
            <div style="width:64px;height:64px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;"><i class="fa-solid fa-trash" style="font-size:24px;color:#ef4444;"></i></div>
            <p style="font-weight:600;">Xóa chức năng <b>{{ delete_chuc_nang.ten_chuc_nang }}</b>?</p>
          </div>
          <div class="modal-footer" style="justify-content:center;gap:12px;">
            <button type="button" class="ch-btn ch-btn-ghost" data-bs-dismiss="modal">Hủy</button>
            <button @click="deleteChucNang()" type="button" class="ch-btn ch-btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-trash"></i> Xóa</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
    name: 'ChucNangComponent',
    data() {
        return {
            list_chuc_nang: [],
            add_chuc_nang: { ten_chuc_nang: '' },
            edit_chuc_nang: {},
            delete_chuc_nang: {}
        }
    },
    mounted() { this.loadDataChucNang(); },
    methods: {
        loadDataChucNang() {
            const token = localStorage.getItem('xs');
            axios.get('http://127.0.0.1:8000/api/admin/chuc-nang/get-data', { headers: { 'Authorization': `Bearer ${token}` } }).then(res => { this.list_chuc_nang = res.data.data; });
        },
        themChucNang() {
            const token = localStorage.getItem('xs');
            axios.post('http://127.0.0.1:8000/api/admin/chuc-nang/store', this.add_chuc_nang, { headers: { 'Authorization': `Bearer ${token}` } })
                .then(res => { if (res.data.status) { this.$toast.success(res.data.message || "Thêm thành công"); this.loadDataChucNang(); this.add_chuc_nang = { ten_chuc_nang: '' }; } else { this.$toast.error(res.data.message); } })
                .catch(err => { this.$toast.error(err.response?.data?.message || "Không thể kết nối!"); });
        },
        updateChucNang() {
            const token = localStorage.getItem('xs');
            axios.post('http://127.0.0.1:8000/api/admin/chuc-nang/update', this.edit_chuc_nang, { headers: { 'Authorization': `Bearer ${token}` } })
                .then(res => { if (res.data.status == 1) { this.$toast.success(res.data.message); this.loadDataChucNang(); } else { this.$toast.error(res.data.message); } })
                .catch(err => { this.$toast.error(err.response?.data?.message || "Có lỗi xảy ra!"); });
        },
        deleteChucNang() {
            const token = localStorage.getItem('xs');
            axios.post('http://127.0.0.1:8000/api/admin/chuc-nang/delete', this.delete_chuc_nang, { headers: { 'Authorization': `Bearer ${token}` } })
                .then(res => { if (res.data.status == 1) { this.$toast.success(res.data.message); this.loadDataChucNang(); } else { this.$toast.error(res.data.message); } })
                .catch(err => { this.$toast.error(err.response?.data?.message || "Có lỗi xảy ra!"); });
        }
    }
}
</script>
<style scoped></style>