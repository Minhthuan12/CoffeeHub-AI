<template>
  <div class="animate-fade-in-up">
    <div class="page-title-bar">
      <div>
        <h1 class="page-title"><i class="fa-solid fa-map-location-dot me-2" style="color:#6F4E37;"></i>Quản Lý Khu Vực Bàn</h1>
        <p class="page-subtitle">Phân chia và quản lý khu vực ngồi của quán</p>
      </div>
    </div>
    <div class="row g-3">
      <div class="col-12 col-lg-4">
        <div class="ch-card">
          <div class="ch-card-header">
            <div class="ch-card-title"><i class="fa-solid fa-plus-circle"></i> Thêm Khu Vực</div>
          </div>
          <div class="ch-card-body">
            <div class="ch-form-group"><label class="ch-label">Tên Khu Vực</label><input v-model="add_khu_vuc.ten_khu_vuc" type="text" class="ch-input" placeholder="VD: Tầng 1, Sân vườn, VIP..."></div>
            <div class="ch-form-group"><label class="ch-label">Mô Tả</label><textarea v-model="add_khu_vuc.mo_ta" class="ch-textarea" rows="3" placeholder="Mô tả về khu vực này..."></textarea></div>
          </div>
          <div class="ch-card-footer">
            <button @click="themKhuVuc()" class="ch-btn ch-btn-primary" style="width:100%;justify-content:center;">
              <i class="fa-solid fa-plus"></i> Thêm Khu Vực
            </button>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-8">
        <div class="ch-card">
          <div class="ch-card-header">
            <div class="ch-card-title"><i class="fa-solid fa-list-ul"></i> Danh Sách Khu Vực <span class="ch-badge ch-badge-coffee ms-2">{{ list_khu_vuc.length }}</span></div>
          </div>
          <div class="ch-card-body" style="padding-top:12px;">
            <div class="ch-table-wrap">
              <table class="ch-table">
                <thead>
                  <tr>
                    <th style="width:48px;text-align:center;">#</th>
                    <th>Tên Khu Vực</th>
                    <th>Mô Tả</th>
                    <th style="text-align:center;">Thao Tác</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(value, index) in list_khu_vuc" :key="index">
                    <td style="text-align:center;color:#9c7b66;font-weight:600;">{{ index + 1 }}</td>
                    <td>
                      <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:34px;height:34px;border-radius:8px;background:linear-gradient(135deg,#6F4E37,#3d1f00);display:flex;align-items:center;justify-content:center;color:#fff;flex-shrink:0;">
                          <i class="fa-solid fa-map-pin" style="font-size:13px;"></i>
                        </div>
                        <span style="font-weight:600;">{{ value.ten_khu_vuc }}<span v-if="value.deleted_at" style="color:#ef4444;font-size:11px;"> (đã xóa)</span></span>
                      </div>
                    </td>
                    <td style="color:#6b5744;font-size:13px;">{{ value.mo_ta }}</td>
                    <td style="text-align:center;">
                      <template v-if="!value.deleted_at">
                        <div style="display:flex;gap:6px;justify-content:center;">
                          <button @click="Object.assign(edit_khu_vuc, value)" class="ch-btn ch-btn-info ch-btn-sm" data-bs-toggle="modal" data-bs-target="#updateModal"><i class="fa-solid fa-pen-to-square"></i></button>
                          <button @click="Object.assign(delete_khu_vuc, value)" class="ch-btn ch-btn-danger ch-btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fa-solid fa-trash"></i></button>
                        </div>
                      </template>
                    </td>
                  </tr>
                  <tr v-if="list_khu_vuc.length === 0"><td colspan="4"><div class="empty-state"><div class="empty-state-icon"><i class="fa-solid fa-map"></i></div><div class="empty-state-text">Chưa có khu vực nào</div></div></td></tr>
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
          <div class="modal-header"><h5 class="modal-title"><i class="fa-solid fa-pen me-2"></i>Cập Nhật Khu Vực</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body">
            <div class="ch-form-group"><label class="ch-label">Tên Khu Vực</label><input v-model="edit_khu_vuc.ten_khu_vuc" type="text" class="ch-input" placeholder="Nhập tên khu vực"></div>
            <div class="ch-form-group"><label class="ch-label">Mô Tả</label><textarea v-model="edit_khu_vuc.mo_ta" class="ch-textarea" rows="3" placeholder="Nhập mô tả khu vực"></textarea></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="ch-btn ch-btn-ghost" data-bs-dismiss="modal">Hủy</button>
            <button @click="updateKhuVuc()" type="button" class="ch-btn ch-btn-primary" data-bs-dismiss="modal"><i class="fa-solid fa-floppy-disk"></i> Lưu</button>
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
            <p style="font-weight:600;">Xóa khu vực <b>{{ delete_khu_vuc.ten_khu_vuc }}</b>?</p>
          </div>
          <div class="modal-footer" style="justify-content:center;gap:12px;">
            <button type="button" class="ch-btn ch-btn-ghost" data-bs-dismiss="modal">Hủy</button>
            <button @click="deleteKhuVuc()" type="button" class="ch-btn ch-btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-trash"></i> Xóa</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
    name: 'KhuVucBan',
    data() {
        return {
            list_khu_vuc: [],
            add_khu_vuc: {},
            edit_khu_vuc: {},
            delete_khu_vuc: {}
        }
    },
    mounted() { this.loadKhuVuc(); },
    methods: {
        loadKhuVuc() {
            axios.get('http://127.0.0.1:8000/api/admin/khu-vuc-ban/get-data', { headers: { 'Authorization': `Bearer ${localStorage.getItem('xs')}` } }).then(res => { this.list_khu_vuc = res.data.data; });
        },
        themKhuVuc() {
            axios.post('http://127.0.0.1:8000/api/admin/khu-vuc-ban/store', this.add_khu_vuc, { headers: { 'Authorization': `Bearer ${localStorage.getItem('xs')}` } })
                .then(res => { if (res.data.status) { this.$toast.success(res.data.message || "Thêm thành công"); this.loadKhuVuc(); this.add_khu_vuc = {}; } else { this.$toast.error(res.data.message); } })
                .catch(err => { this.$toast.error(err.response?.data?.message || "Không thể kết nối!"); });
        },
        updateKhuVuc() {
            axios.post('http://127.0.0.1:8000/api/admin/khu-vuc-ban/update', this.edit_khu_vuc, { headers: { 'Authorization': `Bearer ${localStorage.getItem('xs')}` } })
                .then(res => { if (res.data.status == 1) { this.$toast.success(res.data.message); this.loadKhuVuc(); } else { this.$toast.error(res.data.message); } })
                .catch(err => { this.$toast.error(err.response?.data?.message || "Có lỗi xảy ra!"); });
        },
        deleteKhuVuc() {
            axios.post('http://127.0.0.1:8000/api/admin/khu-vuc-ban/delete', this.delete_khu_vuc, { headers: { 'Authorization': `Bearer ${localStorage.getItem('xs')}` } })
                .then(res => { if (res.data.status == 1) { this.$toast.success(res.data.message); this.loadKhuVuc(); } else { this.$toast.error(res.data.message); } })
                .catch(err => { this.$toast.error(err.response?.data?.message || "Có lỗi xảy ra!"); });
        }
    }
}
</script>
<style scoped></style>