<template>
  <div class="animate-fade-in-up">
    <div class="page-title-bar">
      <div>
        <h1 class="page-title"><i class="fa-solid fa-chair me-2" style="color:#6F4E37;"></i>Quản Lý Bàn</h1>
        <p class="page-subtitle">Quản lý bàn và trạng thái phục vụ</p>
      </div>
    </div>
    <div class="row g-3">
      <div class="col-12 col-lg-4">
        <div class="ch-card">
          <div class="ch-card-header">
            <div class="ch-card-title"><i class="fa-solid fa-plus-circle"></i> Thêm Bàn Mới</div>
          </div>
          <div class="ch-card-body">
            <div class="ch-form-group"><label class="ch-label">Khu Vực Bàn</label><select v-model="add_ban.id_khu_vuc_ban" class="ch-select"><option value="">-- Chọn khu vực --</option><option v-for="(v, k) in list_khu_vuc" :key="k" :value="v.id">{{ v.ten_khu_vuc }}</option></select></div>
            <div class="ch-form-group"><label class="ch-label">Tên Bàn</label><input v-model="add_ban.ten_ban" type="text" class="ch-input" placeholder="VD: Bàn 01, Bàn VIP..."></div>
            <div class="ch-form-group"><label class="ch-label">Tình Trạng</label><select v-model="add_ban.tinh_trang" class="ch-select"><option value="1">✅ Hoạt Động</option><option value="0">⏸ Tạm Tắt</option></select></div>
          </div>
          <div class="ch-card-footer">
            <button @click="themBan()" class="ch-btn ch-btn-primary" style="width:100%;justify-content:center;">
              <i class="fa-solid fa-plus"></i> Thêm Bàn
            </button>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-8">
        <div class="ch-card">
          <div class="ch-card-header">
            <div class="ch-card-title"><i class="fa-solid fa-list-ul"></i> Danh Sách Bàn <span class="ch-badge ch-badge-coffee ms-2">{{ list_ban.length }} bàn</span></div>
          </div>
          <div class="ch-card-body" style="padding-top:12px;">
            <div class="ch-search-wrap mb-3" style="max-width:350px;">
              <i class="ch-search-icon fa-solid fa-search"></i>
              <input v-model="key_search" type="text" class="ch-input" placeholder="Tìm kiếm bàn...">
            </div>
            <div class="ch-table-wrap">
              <table class="ch-table">
                <thead>
                  <tr>
                    <th style="width:48px;text-align:center;">#</th>
                    <th>Tên Bàn</th>
                    <th>Khu Vực</th>
                    <th style="text-align:center;">Trạng Thái</th>
                    <th style="text-align:center;">Thao Tác</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(value, index) in list_ban_loc" :key="index">
                    <td style="text-align:center;color:#9c7b66;font-weight:600;">{{ index + 1 }}</td>
                    <td>
                      <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:34px;height:34px;border-radius:8px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;flex-shrink:0;">
                          <i class="fa-solid fa-chair"></i>
                        </div>
                        <span style="font-weight:600;">{{ value.ten_ban }}</span>
                      </div>
                    </td>
                    <td><span class="ch-badge ch-badge-warning">{{ value.ten_khu_vuc }}</span></td>
                    <td style="text-align:center;">
                      <button v-on:click="changeStatus(value)" :class="value.tinh_trang == 1 ? 'status-toggle active' : 'status-toggle inactive'">
                        <i :class="value.tinh_trang == 1 ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle-pause'"></i>
                        {{ value.tinh_trang == 1 ? 'Hoạt Động' : 'Tạm Tắt' }}
                      </button>
                    </td>
                    <td style="text-align:center;">
                      <div style="display:flex;gap:6px;justify-content:center;">
                        <button @click="Object.assign(edit_ban, value)" class="ch-btn ch-btn-info ch-btn-sm" data-bs-toggle="modal" data-bs-target="#editBanModal"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button @click="Object.assign(delete_ban, value)" class="ch-btn ch-btn-danger ch-btn-sm" data-bs-toggle="modal" data-bs-target="#deleteTableModal"><i class="fa-solid fa-trash"></i></button>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="list_ban_loc.length === 0"><td colspan="5"><div class="empty-state"><div class="empty-state-icon"><i class="fa-solid fa-chair"></i></div><div class="empty-state-text">Chưa có bàn nào</div></div></td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade ch-modal" id="editBanModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header"><h5 class="modal-title"><i class="fa-solid fa-pen me-2"></i>Cập Nhật Bàn</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body">
            <div class="ch-form-group"><label class="ch-label">Tên Bàn</label><input v-model="edit_ban.ten_ban" type="text" class="ch-input"></div>
            <div class="ch-form-group"><label class="ch-label">Khu Vực</label><select v-model="edit_ban.id_khu_vuc_ban" class="ch-select"><option v-for="(v, k) in list_khu_vuc" :key="k" :value="v.id">{{ v.ten_khu_vuc }}</option></select></div>
            <div class="ch-form-group"><label class="ch-label">Tình Trạng</label><select v-model="edit_ban.tinh_trang" class="ch-select"><option value="1">✅ Hoạt Động</option><option value="0">⏸ Tạm Tắt</option></select></div>
          </div>
          <div class="modal-footer">
            <button class="ch-btn ch-btn-ghost" data-bs-dismiss="modal">Hủy</button>
            <button @click="updateBan()" class="ch-btn ch-btn-primary" data-bs-dismiss="modal"><i class="fa-solid fa-floppy-disk"></i> Lưu</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade ch-modal" id="deleteTableModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
          <div class="modal-header danger-header"><h5 class="modal-title"><i class="fa-solid fa-triangle-exclamation me-2"></i>Xác Nhận Xóa</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body" style="text-align:center;padding:28px 20px;">
            <div style="width:64px;height:64px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;"><i class="fa-solid fa-trash" style="font-size:24px;color:#ef4444;"></i></div>
            <p style="font-weight:600;">Xóa bàn <b>{{ delete_ban.ten_ban }}</b>?</p>
          </div>
          <div class="modal-footer" style="justify-content:center;gap:12px;">
            <button class="ch-btn ch-btn-ghost" data-bs-dismiss="modal">Hủy</button>
            <button @click="deleteBan()" class="ch-btn ch-btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-trash"></i> Xóa</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: 'BanComponent',
  data() {
    return {
      list_ban: [],
      list_khu_vuc: [],
      key_search: '',
      add_ban: { id_khu_vuc_ban: '', ten_ban: '', tinh_trang: 1 },
      edit_ban: {},
      delete_ban: {}
    }
  },
  computed: {
    list_ban_loc() {
      if (!this.key_search) return this.list_ban;
      return this.list_ban.filter(item => item.ten_ban && item.ten_ban.toLowerCase().includes(this.key_search.toLowerCase().trim()));
    }
  },
  mounted() { this.loadDataBan(); this.loadKhuVuc(); },
  methods: {
    loadDataBan() {
      axios.get('http://127.0.0.1:8000/api/admin/ban/get-data', { headers: { 'Authorization': `Bearer ${localStorage.getItem('xs')}` } }).then(res => { this.list_ban = res.data.data; });
    },
    loadKhuVuc() {
      axios.get('http://127.0.0.1:8000/api/admin/khu-vuc-ban/get-data', { headers: { 'Authorization': `Bearer ${localStorage.getItem('xs')}` } }).then(res => { this.list_khu_vuc = res.data.data; });
    },
    themBan() {
      axios.post('http://127.0.0.1:8000/api/admin/ban/store', this.add_ban, { headers: { 'Authorization': `Bearer ${localStorage.getItem('xs')}` } })
        .then(res => { if (res.data.status) { this.$toast.success(res.data.message || "Thêm bàn thành công"); this.loadDataBan(); this.add_ban = { id_khu_vuc_ban: '', ten_ban: '', tinh_trang: 1 }; } else { this.$toast.error(res.data.message || "Thêm thất bại"); } })
        .catch(err => { this.$toast.error(err.response?.data?.message || "Có lỗi xảy ra!"); });
    },
    updateBan() {
      axios.post('http://127.0.0.1:8000/api/admin/ban/update', this.edit_ban, { headers: { 'Authorization': `Bearer ${localStorage.getItem('xs')}` } })
        .then(res => { if (res.data.status == 1) { this.$toast.success(res.data.message); this.loadDataBan(); } else { this.$toast.error(res.data.message); } })
        .catch(err => { this.$toast.error(err.response?.data?.message || "Có lỗi xảy ra!"); });
    },
    deleteBan() {
      axios.post('http://127.0.0.1:8000/api/admin/ban/delete', this.delete_ban, { headers: { 'Authorization': `Bearer ${localStorage.getItem('xs')}` } })
        .then(res => { if (res.data.status == 1) { this.$toast.success(res.data.message); this.loadDataBan(); } else { this.$toast.error(res.data.message); } })
        .catch(err => { this.$toast.error(err.response?.data?.message || "Có lỗi xảy ra!"); });
    },
    changeStatus(value) {
      axios.post('http://127.0.0.1:8000/api/admin/ban/change-status', value, { headers: { 'Authorization': `Bearer ${localStorage.getItem('xs')}` } })
        .then(res => { if (res.data.status == 1) { this.$toast.success(res.data.message); this.loadDataBan(); } else { this.$toast.error(res.data.message); } })
        .catch(err => { this.$toast.error(err.response?.data?.message || "Có lỗi xảy ra!"); });
    }
  }
}
</script>
<style scoped></style>