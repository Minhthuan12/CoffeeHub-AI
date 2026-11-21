<template>
  <div class="animate-fade-in-up">
    <div class="page-title-bar">
      <div>
        <h1 class="page-title"><i class="fa-solid fa-tags me-2" style="color:#6F4E37;"></i>Quản Lý Danh Mục</h1>
        <p class="page-subtitle">Quản lý danh mục sản phẩm của quán</p>
      </div>
    </div>
    <div class="row g-3">
      <div class="col-12 col-lg-4">
        <div class="ch-card">
          <div class="ch-card-header">
            <div class="ch-card-title"><i class="fa-solid fa-folder-plus"></i> Thêm Danh Mục Mới</div>
          </div>
          <div class="ch-card-body">
            <div class="ch-form-group"><label class="ch-label">Tên Danh Mục</label><input v-model="add_danh_muc.ten_danh_muc" type="text" class="ch-input" placeholder="VD: Cà phê, Trà sữa..."></div>
            <div class="ch-form-group"><label class="ch-label">Slug</label><input v-model="add_danh_muc.slug_danh_muc" type="text" class="ch-input" readonly style="background:#f8f4ef;"></div>
            <div class="ch-form-group"><label class="ch-label">Icon (CSS class)</label><input v-model="add_danh_muc.icon_danh_muc" type="text" class="ch-input" placeholder="fa-solid fa-mug-hot"></div>
            <div class="ch-form-group"><label class="ch-label">Tình Trạng</label><select v-model="add_danh_muc.tinh_trang" class="ch-select"><option value="1">✅ Hiển Thị</option><option value="0">⏸ Tạm Tắt</option></select></div>
            <div class="ch-form-group"><label class="ch-label">Danh Mục Cha</label><select v-model="add_danh_muc.id_danh_muc_cha" class="ch-select"><option value="0">Không có (Danh mục gốc)</option><option v-for="(v, k) in list_danh_muc" :key="k" :value="v.id">{{ v.ten_danh_muc }}</option></select></div>
          </div>
          <div class="ch-card-footer">
            <button @click="themDanhMuc()" class="ch-btn ch-btn-primary" style="width:100%;justify-content:center;">
              <i class="fa-solid fa-plus"></i> Thêm Danh Mục
            </button>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-8">
        <div class="ch-card">
          <div class="ch-card-header">
            <div class="ch-card-title"><i class="fa-solid fa-list-ul"></i> Danh Sách Danh Mục <span class="ch-badge ch-badge-coffee ms-2">{{ list_danh_muc.length }}</span></div>
          </div>
          <div class="ch-card-body" style="padding-top:12px;">
            <div class="ch-table-wrap">
              <table class="ch-table">
                <thead>
                  <tr>
                    <th style="width:48px;text-align:center;">#</th>
                    <th>Tên Danh Mục</th>
                    <th>Slug</th>
                    <th style="text-align:center;">Icon</th>
                    <th style="text-align:center;">Tình Trạng</th>
                    <th>DM Cha</th>
                    <th style="text-align:center;">Thao Tác</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(value, index) in list_danh_muc" :key="index">
                    <td style="text-align:center;color:#9c7b66;font-weight:600;">{{ index + 1 }}</td>
                    <td>
                      <span style="font-weight:600;">{{ value.ten_danh_muc }}</span>
                      <span v-if="value.deleted_at" style="color:#ef4444;font-size:11px;"> (đã xóa)</span>
                    </td>
                    <td><code style="background:#f8f4ef;padding:2px 8px;border-radius:4px;font-size:12px;color:#6F4E37;">{{ value.slug_danh_muc }}</code></td>
                    <td style="text-align:center;"><i :class="value.icon_danh_muc" style="font-size:18px;color:#6F4E37;"></i></td>
                    <td style="text-align:center;">
                      <button v-on:click="changeStatus(value)" :class="value.tinh_trang == 1 ? 'status-toggle active' : 'status-toggle inactive'">
                        <i :class="value.tinh_trang == 1 ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle-pause'"></i>
                        {{ value.tinh_trang == 1 ? 'Hiển Thị' : 'Tạm Tắt' }}
                      </button>
                    </td>
                    <td>{{ getTenDanhMucCha(value.id_danh_muc_cha) }}</td>
                    <td style="text-align:center;">
                      <template v-if="!value.deleted_at">
                        <div style="display:flex;gap:6px;justify-content:center;">
                          <button @click="Object.assign(edit_danh_muc, value)" class="ch-btn ch-btn-info ch-btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fa-solid fa-pen-to-square"></i></button>
                          <button @click="Object.assign(delete_danh_muc, value)" class="ch-btn ch-btn-danger ch-btn-sm" data-bs-toggle="modal" data-bs-target="#delModal"><i class="fa-solid fa-trash"></i></button>
                        </div>
                      </template>
                    </td>
                  </tr>
                  <tr v-if="list_danh_muc.length === 0"><td colspan="7"><div class="empty-state"><div class="empty-state-icon"><i class="fa-solid fa-folder-open"></i></div><div class="empty-state-text">Chưa có danh mục nào</div></div></td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade ch-modal" id="editModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header"><h5 class="modal-title"><i class="fa-solid fa-pen me-2"></i>Cập Nhật Danh Mục</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body">
            <div class="ch-form-group"><label class="ch-label">Tên Danh Mục</label><input v-model="edit_danh_muc.ten_danh_muc" type="text" class="ch-input"></div>
            <div class="ch-form-group"><label class="ch-label">Slug</label><input v-model="edit_danh_muc.slug_danh_muc" type="text" class="ch-input" readonly style="background:#f8f4ef;"></div>
            <div class="ch-form-group"><label class="ch-label">Icon</label><input v-model="edit_danh_muc.icon_danh_muc" type="text" class="ch-input"></div>
            <div class="ch-form-group"><label class="ch-label">Tình Trạng</label><select v-model="edit_danh_muc.tinh_trang" class="ch-select"><option value="1">✅ Hiển Thị</option><option value="0">⏸ Tạm Tắt</option></select></div>
            <div class="ch-form-group"><label class="ch-label">Danh Mục Cha</label><select v-model="edit_danh_muc.id_danh_muc_cha" class="ch-select"><option value="0">Không có</option><option v-for="(v, k) in list_danh_muc" :key="k" :value="v.id">{{ v.ten_danh_muc }}</option></select></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="ch-btn ch-btn-ghost" data-bs-dismiss="modal">Hủy</button>
            <button @click="updateDanhMuc()" type="button" class="ch-btn ch-btn-primary" data-bs-dismiss="modal"><i class="fa-solid fa-floppy-disk"></i> Lưu</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Xóa -->
    <div class="modal fade ch-modal" id="delModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
          <div class="modal-header danger-header"><h5 class="modal-title"><i class="fa-solid fa-triangle-exclamation me-2"></i>Xác Nhận Xóa</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body" style="text-align:center;padding:28px 20px;">
            <div style="width:64px;height:64px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;"><i class="fa-solid fa-trash" style="font-size:24px;color:#ef4444;"></i></div>
            <p style="font-weight:600;">Xóa danh mục <b>{{ delete_danh_muc.ten_danh_muc }}</b>?</p>
          </div>
          <div class="modal-footer" style="justify-content:center;gap:12px;">
            <button type="button" class="ch-btn ch-btn-ghost" data-bs-dismiss="modal">Hủy</button>
            <button @click="deleteDanhMuc()" type="button" class="ch-btn ch-btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-trash"></i> Xóa</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
    name: 'DanhMucComponent',
    data() {
        return {
            list_danh_muc: [],
            add_danh_muc: { ten_danh_muc: '', slug_danh_muc: '', icon_danh_muc: '', tinh_trang: 1, id_danh_muc_cha: 0 },
            edit_danh_muc: {},
            delete_danh_muc: {}
        }
    },
    watch: {
        'add_danh_muc.ten_danh_muc'(newVal) { this.add_danh_muc.slug_danh_muc = this.toSlug(newVal); },
        'edit_danh_muc.ten_danh_muc'(newVal) { this.edit_danh_muc.slug_danh_muc = this.toSlug(newVal); }
    },
    mounted() { this.loadData(); },
    methods: {
        toSlug(str) {
            str = str.toLowerCase();
            str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
            str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
            str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
            str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
            str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
            str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
            str = str.replace(/đ/g, "d");
            str = str.replace(/[^a-z0-9 -]/g, "");
            str = str.replace(/\s+/g, "-");
            str = str.replace(/-+/g, "-");
            return str.trim();
        },
        loadData() {
            axios.get('http://127.0.0.1:8000/api/admin/danh-muc/get-data', { headers: { 'Authorization': `Bearer ${localStorage.getItem('xs')}` } })
                .then((res) => { this.list_danh_muc = res.data.data; });
        },
        getTenDanhMucCha(idCha) {
            if (!idCha || idCha == 0) return 'Không có';
            const cha = this.list_danh_muc.find(c => c.id == idCha);
            return cha ? cha.ten_danh_muc : 'Không có';
        },
        themDanhMuc() {
            axios.post('http://127.0.0.1:8000/api/admin/danh-muc/store', this.add_danh_muc, { headers: { 'Authorization': `Bearer ${localStorage.getItem('xs')}` } })
                .then((res) => {
                    if (res.data.status) { this.$toast.success(res.data.message || "Thêm thành công"); this.loadData(); this.add_danh_muc = { ten_danh_muc: '', slug_danh_muc: '', icon_danh_muc: '', tinh_trang: 1, id_danh_muc_cha: 0 }; }
                    else { this.$toast.error(res.data.message || "Thêm thất bại"); }
                }).catch(err => { this.$toast.error(err.response?.data?.message || "Không thể kết nối!"); });
        },
        updateDanhMuc() {
            axios.post('http://127.0.0.1:8000/api/admin/danh-muc/update', this.edit_danh_muc, { headers: { 'Authorization': `Bearer ${localStorage.getItem('xs')}` } })
                .then((res) => { if (res.data.status == 1) { this.$toast.success(res.data.message); this.loadData(); } else { this.$toast.error(res.data.message); } })
                .catch(err => { this.$toast.error(err.response?.data?.message || "Có lỗi xảy ra!"); });
        },
        deleteDanhMuc() {
            axios.post('http://127.0.0.1:8000/api/admin/danh-muc/delete', this.delete_danh_muc, { headers: { 'Authorization': `Bearer ${localStorage.getItem('xs')}` } })
                .then((res) => { if (res.data.status == 1) { this.$toast.success(res.data.message); this.loadData(); } else { this.$toast.error(res.data.message); } })
                .catch(err => { this.$toast.error(err.response?.data?.message || "Có lỗi xảy ra!"); });
        },
        changeStatus(value) {
            axios.post('http://127.0.0.1:8000/api/admin/danh-muc/change-status', value, { headers: { 'Authorization': `Bearer ${localStorage.getItem('xs')}` } })
                .then((res) => { if (res.data.status == 1) { this.$toast.success(res.data.message); this.loadData(); } else { this.$toast.error(res.data.message); } })
                .catch(err => { this.$toast.error(err.response?.data?.message || "Có lỗi xảy ra!"); });
        }
    }
}
</script>
<style scoped></style>