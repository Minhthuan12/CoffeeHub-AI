<template>
  <div class="animate-fade-in-up">
    <div class="page-title-bar">
      <div>
        <h1 class="page-title"><i class="fa-solid fa-clock-rotate-left me-2" style="color:#6F4E37;"></i>Quản Lý Ca Làm Việc</h1>
        <p class="page-subtitle">Quản lý ca làm việc cho nhân viên</p>
      </div>
    </div>
    <div class="row g-3">
      <div class="col-12 col-lg-4">
        <div class="ch-card">
          <div class="ch-card-header">
            <div class="ch-card-title"><i class="fa-solid fa-plus-circle"></i> Thêm Ca Làm Việc</div>
          </div>
          <div class="ch-card-body">
            <div class="ch-form-group"><label class="ch-label">Tên Ca</label><input v-model="add_ca.ten_ca" type="text" class="ch-input" placeholder="VD: Ca Sáng, Ca Chiều..."></div>
            <div class="ch-form-group">
              <label class="ch-label">Giờ Bắt Đầu</label>
              <input v-model="add_ca.gio_bat_dau" type="time" class="ch-input">
            </div>
            <div class="ch-form-group">
              <label class="ch-label">Giờ Kết Thúc</label>
              <input v-model="add_ca.gio_ket_thuc" type="time" class="ch-input">
            </div>
            <div class="ch-form-group"><label class="ch-label">Tình Trạng</label><select v-model="add_ca.tinh_trang" class="ch-select"><option value="1">✅ Hoạt Động</option><option value="0">⏸ Tạm Tắt</option></select></div>
          </div>
          <div class="ch-card-footer">
            <button @click="themCaLam()" class="ch-btn ch-btn-primary" style="width:100%;justify-content:center;">
              <i class="fa-solid fa-plus"></i> Thêm Ca Làm
            </button>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-8">
        <div class="ch-card">
          <div class="ch-card-header">
            <div class="ch-card-title"><i class="fa-solid fa-list-ul"></i> Danh Sách Ca Làm Việc <span class="ch-badge ch-badge-coffee ms-2">{{ list_ca.length }}</span></div>
          </div>
          <div class="ch-card-body" style="padding-top:12px;">
            <div class="ch-table-wrap">
              <table class="ch-table">
                <thead>
                  <tr>
                    <th style="width:48px;text-align:center;">#</th>
                    <th>Tên Ca</th>
                    <th style="text-align:center;">Giờ Bắt Đầu</th>
                    <th style="text-align:center;">Giờ Kết Thúc</th>
                    <th style="text-align:center;">Trạng Thái</th>
                    <th style="text-align:center;">Thao Tác</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(value, index) in list_ca" :key="index">
                    <td style="text-align:center;color:#9c7b66;font-weight:600;">{{ index + 1 }}</td>
                    <td>
                      <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:34px;height:34px;border-radius:8px;background:linear-gradient(135deg,#6366f1,#4f46e5);display:flex;align-items:center;justify-content:center;color:#fff;flex-shrink:0;">
                          <i class="fa-solid fa-clock" style="font-size:13px;"></i>
                        </div>
                        <span style="font-weight:600;">{{ value.ten_ca }}</span>
                      </div>
                    </td>
                    <td style="text-align:center;"><span class="ch-badge ch-badge-success">{{ value.gio_bat_dau }}</span></td>
                    <td style="text-align:center;"><span class="ch-badge ch-badge-warning">{{ value.gio_ket_thuc }}</span></td>
                    <td style="text-align:center;">
                      <button v-on:click="changeStatus(value)" :class="value.tinh_trang == 1 ? 'status-toggle active' : 'status-toggle inactive'">
                        <i :class="value.tinh_trang == 1 ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle-pause'"></i>
                        {{ value.tinh_trang == 1 ? 'Hoạt Động' : 'Tạm Tắt' }}
                      </button>
                    </td>
                    <td style="text-align:center;">
                      <div style="display:flex;gap:6px;justify-content:center;">
                        <button @click="Object.assign(edit_ca, value)" class="ch-btn ch-btn-info ch-btn-sm" data-bs-toggle="modal" data-bs-target="#updateCaModal"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button @click="Object.assign(delete_ca, value)" class="ch-btn ch-btn-danger ch-btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fa-solid fa-trash"></i></button>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="list_ca.length === 0"><td colspan="6"><div class="empty-state"><div class="empty-state-icon"><i class="fa-solid fa-clock"></i></div><div class="empty-state-text">Chưa có ca làm việc nào</div></div></td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade ch-modal" id="updateCaModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header"><h5 class="modal-title"><i class="fa-solid fa-pen me-2"></i>Cập Nhật Ca Làm</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body">
            <div class="ch-form-group"><label class="ch-label">Tên Ca</label><input v-model="edit_ca.ten_ca" type="text" class="ch-input"></div>
            <div class="ch-form-group"><label class="ch-label">Giờ Bắt Đầu</label><input v-model="edit_ca.gio_bat_dau" type="time" class="ch-input"></div>
            <div class="ch-form-group"><label class="ch-label">Giờ Kết Thúc</label><input v-model="edit_ca.gio_ket_thuc" type="time" class="ch-input"></div>
            <div class="ch-form-group"><label class="ch-label">Tình Trạng</label><select v-model="edit_ca.tinh_trang" class="ch-select"><option value="1">✅ Hoạt Động</option><option value="0">⏸ Tạm Tắt</option></select></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="ch-btn ch-btn-ghost" data-bs-dismiss="modal">Hủy</button>
            <button @click="updateCaLam()" type="button" class="ch-btn ch-btn-primary" data-bs-dismiss="modal"><i class="fa-solid fa-floppy-disk"></i> Lưu</button>
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
            <p style="font-weight:600;">Xóa ca <b>{{ delete_ca.ten_ca }}</b>?</p>
          </div>
          <div class="modal-footer" style="justify-content:center;gap:12px;">
            <button type="button" class="ch-btn ch-btn-ghost" data-bs-dismiss="modal">Hủy</button>
            <button @click="deleteCaLam()" type="button" class="ch-btn ch-btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-trash"></i> Xóa</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
    name: 'CaLamViecComponent',
    data() {
        return {
            list_ca: [],
            add_ca: { ten_ca: '', gio_bat_dau: '', gio_ket_thuc: '', tinh_trang: 1 },
            edit_ca: {},
            delete_ca: {}
        };
    },
    mounted() { this.loadDataCaLam(); },
    methods: {
        loadDataCaLam() {
            axios.get("http://127.0.0.1:8000/api/admin/ca-lam-viec/get-data", { headers: { 'Authorization': `Bearer ${localStorage.getItem('xs')}` } }).then(res => { this.list_ca = res.data.data; });
        },
        themCaLam() {
            axios.post("http://127.0.0.1:8000/api/admin/ca-lam-viec/store", this.add_ca, { headers: { 'Authorization': `Bearer ${localStorage.getItem('xs')}` } })
                .then(res => { if (res.data.status) { this.$toast.success(res.data.message || "Thêm thành công"); this.loadDataCaLam(); this.add_ca = { ten_ca: '', gio_bat_dau: '', gio_ket_thuc: '', tinh_trang: 1 }; } else { this.$toast.error(res.data.message); } })
                .catch(err => { this.$toast.error(err.response?.data?.message || "Không thể kết nối!"); });
        },
        updateCaLam() {
            axios.post("http://127.0.0.1:8000/api/admin/ca-lam-viec/update", this.edit_ca, { headers: { 'Authorization': `Bearer ${localStorage.getItem('xs')}` } })
                .then(res => { if (res.data.status == 1) { this.$toast.success(res.data.message); this.loadDataCaLam(); } else { this.$toast.error(res.data.message); } })
                .catch(err => { this.$toast.error(err.response?.data?.message || "Có lỗi xảy ra!"); });
        },
        deleteCaLam() {
            axios.post("http://127.0.0.1:8000/api/admin/ca-lam-viec/delete", this.delete_ca, { headers: { 'Authorization': `Bearer ${localStorage.getItem('xs')}` } })
                .then(res => { if (res.data.status == 1) { this.$toast.success(res.data.message); this.loadDataCaLam(); } else { this.$toast.error(res.data.message); } })
                .catch(err => { this.$toast.error(err.response?.data?.message || "Có lỗi xảy ra!"); });
        },
        changeStatus(value) {
            axios.post("http://127.0.0.1:8000/api/admin/ca-lam-viec/change-status", value, { headers: { 'Authorization': `Bearer ${localStorage.getItem('xs')}` } })
                .then(res => { if (res.data.status == 1) { this.$toast.success(res.data.message); this.loadDataCaLam(); } else { this.$toast.error(res.data.message); } })
                .catch(err => { this.$toast.error(err.response?.data?.message || "Có lỗi xảy ra!"); });
        }
    }
};
</script>
<style scoped></style>
