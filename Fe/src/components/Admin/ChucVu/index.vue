<template>
  <div class="animate-fade-in-up">
    <div class="page-title-bar">
      <div>
        <h1 class="page-title"><i class="fa-solid fa-briefcase me-2" style="color:#6F4E37;"></i>Quản Lý Chức Vụ</h1>
        <p class="page-subtitle">Thêm và quản lý các chức vụ trong hệ thống</p>
      </div>
    </div>
    <div class="row g-3">
      <!-- Form thêm mới -->
      <div class="col-12 col-lg-4">
        <div class="ch-card">
          <div class="ch-card-header">
            <div class="ch-card-title"><i class="fa-solid fa-plus-circle"></i> Thêm Chức Vụ Mới</div>
          </div>
          <div class="ch-card-body">
            <div class="ch-form-group"><label class="ch-label">Tên Chức Vụ</label><input v-model="add_chuc_vu.ten_chuc_vu" type="text" class="ch-input" placeholder="VD: Quản lý, Nhân viên..."></div>
            <div class="ch-form-group"><label class="ch-label">Slug</label><input v-model="add_chuc_vu.slug_chuc_vu" type="text" class="ch-input" placeholder="tu-dong-tao" readonly style="background:#f8f4ef;"></div>
            <div class="ch-form-group"><label class="ch-label">Tình Trạng</label><select v-model="add_chuc_vu.tinh_trang" class="ch-select"><option value="1">✅ Hoạt Động</option><option value="0">⏸ Tạm Tắt</option></select></div>
          </div>
          <div class="ch-card-footer">
            <button @click="themChucVu()" class="ch-btn ch-btn-primary" style="width:100%;justify-content:center;">
              <i class="fa-solid fa-plus"></i> Thêm Chức Vụ
            </button>
          </div>
        </div>
      </div>

      <!-- Danh sách -->
      <div class="col-12 col-lg-8">
        <div class="ch-card">
          <div class="ch-card-header">
            <div class="ch-card-title"><i class="fa-solid fa-list-ul"></i> Danh Sách Chức Vụ <span class="ch-badge ch-badge-coffee ms-2">{{ list_chuc_vu.length }}</span></div>
          </div>
          <div class="ch-card-body" style="padding-top:12px;">
            <div class="ch-table-wrap">
              <table class="ch-table">
                <thead>
                  <tr>
                    <th style="width:48px;text-align:center;">#</th>
                    <th>Tên Chức Vụ</th>
                    <th>Slug</th>
                    <th style="text-align:center;">Tình Trạng</th>
                    <th style="text-align:center;">Thao Tác</th>
                  </tr>
                </thead>
                <tbody>
                  <template v-for="(value, index) in list_chuc_vu" :key="index">
                    <tr>
                      <td style="text-align:center;color:#9c7b66;font-weight:600;">{{ index + 1 }}</td>
                      <td style="font-weight:600;">{{ value.ten_chuc_vu }}</td>
                      <td><code style="background:#f8f4ef;padding:2px 8px;border-radius:4px;font-size:12px;color:#6F4E37;">{{ value.slug_chuc_vu }}</code></td>
                      <td style="text-align:center;">
                        <button v-on:click="changeStatus(value)" :class="value.tinh_trang == 1 ? 'status-toggle active' : 'status-toggle inactive'">
                          <i :class="value.tinh_trang == 1 ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle-pause'"></i>
                          {{ value.tinh_trang == 1 ? 'Hoạt Động' : 'Tạm Tắt' }}
                        </button>
                      </td>
                      <td style="text-align:center;">
                        <div style="display:flex;gap:6px;justify-content:center;">
                          <button @click="Object.assign(edit_chuc_vu, value)" class="ch-btn ch-btn-info ch-btn-sm" data-bs-toggle="modal" data-bs-target="#updateModal"><i class="fa-solid fa-pen-to-square"></i></button>
                          <button @click="Object.assign(delete_chuc_vu, value)" class="ch-btn ch-btn-danger ch-btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fa-solid fa-trash"></i></button>
                        </div>
                      </td>
                    </tr>
                  </template>
                  <tr v-if="list_chuc_vu.length === 0"><td colspan="5"><div class="empty-state"><div class="empty-state-icon"><i class="fa-solid fa-briefcase"></i></div><div class="empty-state-text">Chưa có chức vụ nào</div></div></td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Cập nhật -->
    <div class="modal fade ch-modal" id="updateModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header"><h5 class="modal-title"><i class="fa-solid fa-pen me-2"></i>Cập Nhật Chức Vụ</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body">
            <div class="ch-form-group"><label class="ch-label">Tên Chức Vụ</label><input v-model="edit_chuc_vu.ten_chuc_vu" type="text" class="ch-input"></div>
            <div class="ch-form-group"><label class="ch-label">Slug</label><input v-model="edit_chuc_vu.slug_chuc_vu" type="text" class="ch-input" style="background:#f8f4ef;" readonly></div>
            <div class="ch-form-group"><label class="ch-label">Tình Trạng</label><select v-model="edit_chuc_vu.tinh_trang" class="ch-select"><option value="1">✅ Hoạt Động</option><option value="0">⏸ Tạm Tắt</option></select></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="ch-btn ch-btn-ghost" data-bs-dismiss="modal">Hủy</button>
            <button @click="updateChucVu()" type="button" class="ch-btn ch-btn-primary" data-bs-dismiss="modal"><i class="fa-solid fa-floppy-disk"></i> Lưu</button>
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
            <p style="font-weight:600;">Xóa chức vụ <b>{{ delete_chuc_vu.ten_chuc_vu }}</b>?</p>
          </div>
          <div class="modal-footer" style="justify-content:center;gap:12px;">
            <button type="button" class="ch-btn ch-btn-ghost" data-bs-dismiss="modal">Hủy</button>
            <button @click="deleteChucVu()" type="button" class="ch-btn ch-btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-trash"></i> Xóa</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: 'ChucVuComponent',
  data() {
    return {
      list_chuc_vu: [],
      add_chuc_vu: { ten_chuc_vu: '', slug_chuc_vu: '', tinh_trang: 1 },
      edit_chuc_vu: {},
      delete_chuc_vu: {}
    }
  },
  watch: {
    'add_chuc_vu.ten_chuc_vu'(v) { this.add_chuc_vu.slug_chuc_vu = this.toSlug(v); },
    'edit_chuc_vu.ten_chuc_vu'(v) { this.edit_chuc_vu.slug_chuc_vu = this.toSlug(v); }
  },
  mounted() { this.loadDataChucVu(); },
  methods: {
    toSlug(str) {
      return str.toLowerCase().replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g,"a").replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g,"e").replace(/ì|í|ị|ỉ|ĩ/g,"i").replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g,"o").replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g,"u").replace(/ỳ|ý|ỵ|ỷ|ỹ/g,"y").replace(/đ/g,"d").replace(/[^a-z0-9 -]/g,"").replace(/\s+/g,"-").replace(/-+/g,"-").trim();
    },
    loadDataChucVu() {
      const token = localStorage.getItem('xs');
      axios.get('http://127.0.0.1:8000/api/admin/chuc-vu/get-data', { headers: { 'Authorization': `Bearer ${token}` } }).then(res => { this.list_chuc_vu = res.data.data; });
    },
    themChucVu() {
      const token = localStorage.getItem('xs');
      axios.post('http://127.0.0.1:8000/api/admin/chuc-vu/store', this.add_chuc_vu, { headers: { 'Authorization': `Bearer ${token}` } })
        .then(res => { if (res.data.status) { this.$toast.success(res.data.message || "Thêm thành công"); this.loadDataChucVu(); this.add_chuc_vu = { ten_chuc_vu: '', slug_chuc_vu: '', tinh_trang: 1 }; } else { this.$toast.error(res.data.message); } })
        .catch(err => { this.$toast.error(err.response?.data?.message || "Không thể kết nối!"); });
    },
    updateChucVu() {
      const token = localStorage.getItem('xs');
      axios.post('http://127.0.0.1:8000/api/admin/chuc-vu/update', this.edit_chuc_vu, { headers: { 'Authorization': `Bearer ${token}` } })
        .then(res => { if (res.data.status == 1) { this.$toast.success(res.data.message); this.loadDataChucVu(); } else { this.$toast.error(res.data.message); } })
        .catch(err => { this.$toast.error(err.response?.data?.message || "Có lỗi xảy ra!"); });
    },
    deleteChucVu() {
      const token = localStorage.getItem('xs');
      axios.post('http://127.0.0.1:8000/api/admin/chuc-vu/delete', this.delete_chuc_vu, { headers: { 'Authorization': `Bearer ${token}` } })
        .then(res => { if (res.data.status == 1) { this.$toast.success(res.data.message); this.loadDataChucVu(); } else { this.$toast.error(res.data.message); } })
        .catch(err => { this.$toast.error(err.response?.data?.message || "Có lỗi xảy ra!"); });
    },
    changeStatus(value) {
      const token = localStorage.getItem('xs');
      axios.post('http://127.0.0.1:8000/api/admin/chuc-vu/change-status', value, { headers: { 'Authorization': `Bearer ${token}` } })
        .then(res => { if (res.data.status == 1) { this.$toast.success(res.data.message); this.loadDataChucVu(); } else { this.$toast.error(res.data.message); } })
        .catch(err => { this.$toast.error(err.response?.data?.message || "Có lỗi xảy ra!"); });
    }
  }
}
</script>
<style scoped></style>