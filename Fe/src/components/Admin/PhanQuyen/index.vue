<template>
  <div class="animate-fade-in-up">
    <div class="page-title-bar">
      <div>
        <h1 class="page-title"><i class="fa-solid fa-user-shield me-2" style="color:#6F4E37;"></i>Phân Quyền Chức Vụ</h1>
        <p class="page-subtitle">Thiết lập quyền truy cập theo chức vụ</p>
      </div>
    </div>
    <div class="row g-3">
      <!-- Col 1: Chức vụ -->
      <div class="col-12 col-lg-4">
        <div class="ch-card">
          <div class="ch-card-header">
            <div class="ch-card-title"><i class="fa-solid fa-id-badge"></i> Danh Sách Chức Vụ</div>
          </div>
          <div class="ch-card-body" style="padding-top:12px;">
            <div class="ch-table-wrap">
              <table class="ch-table">
                <thead>
                  <tr>
                    <th style="width:48px;text-align:center;">#</th>
                    <th>Tên Chức Vụ</th>
                    <th style="text-align:center;">Phân Quyền</th>
                  </tr>
                </thead>
                <tbody>
                  <template v-for="(value, index) in list_chuc_vu" :key="index">
                    <tr :style="chuc_vu_dang_chon && chuc_vu_dang_chon.id === value.id ? 'background:#fff8f0;' : ''">
                      <td style="text-align:center;color:#9c7b66;font-weight:600;">{{ index + 1 }}</td>
                      <td style="font-weight:600;">{{ value.ten_chuc_vu }}</td>
                      <td style="text-align:center;">
                        <button @click="chuc_vu_dang_chon = value; loadDataDSDaCap(value)" class="ch-btn ch-btn-primary ch-btn-sm">
                          <i class="fa-solid fa-key"></i> Chọn
                        </button>
                      </td>
                    </tr>
                  </template>
                  <tr v-if="list_chuc_vu.length === 0"><td colspan="3"><div class="empty-state"><div class="empty-state-icon"><i class="fa-solid fa-id-badge"></i></div><div class="empty-state-text">Không có chức vụ</div></div></td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Col 2: Chức năng để cấp -->
      <div class="col-12 col-lg-4">
        <div class="ch-card">
          <div class="ch-card-header">
            <div class="ch-card-title"><i class="fa-solid fa-list-check"></i> Danh Sách Chức Năng</div>
          </div>
          <div class="ch-card-body" style="padding-top:12px;">
            <div v-if="!chuc_vu_dang_chon || !chuc_vu_dang_chon.id" class="empty-state">
              <div class="empty-state-icon"><i class="fa-solid fa-hand-pointer"></i></div>
              <div class="empty-state-text">Chọn một chức vụ để cấp quyền</div>
            </div>
            <div class="ch-table-wrap" v-else>
              <table class="ch-table">
                <thead>
                  <tr>
                    <th style="width:48px;text-align:center;">#</th>
                    <th>Tên Chức Năng</th>
                    <th style="text-align:center;">Cấp Quyền</th>
                  </tr>
                </thead>
                <tbody>
                  <template v-for="(value, index) in list_chuc_nang" :key="index">
                    <tr>
                      <td style="text-align:center;color:#9c7b66;font-weight:600;">{{ index + 1 }}</td>
                      <td>{{ value.ten_chuc_nang }}</td>
                      <td style="text-align:center;">
                        <button v-on:click="capQuyen(value)" class="ch-btn ch-btn-success ch-btn-sm">
                          <i class="fa-solid fa-plus"></i> Cấp
                        </button>
                      </td>
                    </tr>
                  </template>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Col 3: Quyền đã cấp -->
      <div class="col-12 col-lg-4">
        <div class="ch-card">
          <div class="ch-card-header">
            <div class="ch-card-title">
              <i class="fa-solid fa-shield-check"></i> Quyền Đã Cấp
              <span v-if="chuc_vu_dang_chon && chuc_vu_dang_chon.ten_chuc_vu" class="ch-badge ch-badge-coffee ms-1">{{ chuc_vu_dang_chon.ten_chuc_vu }}</span>
            </div>
          </div>
          <div class="ch-card-body" style="padding-top:12px;">
            <div class="ch-table-wrap">
              <table class="ch-table">
                <thead>
                  <tr>
                    <th style="width:48px;text-align:center;">#</th>
                    <th>Tên Chức Năng</th>
                    <th style="text-align:center;">Thu Hồi</th>
                  </tr>
                </thead>
                <tbody>
                  <template v-for="(value, index) in list_chi_tiet" :key="index">
                    <tr>
                      <td style="text-align:center;color:#9c7b66;font-weight:600;">{{ index + 1 }}</td>
                      <td>{{ value.ten_chuc_nang }}</td>
                      <td style="text-align:center;">
                        <button class="ch-btn ch-btn-danger ch-btn-sm"><i class="fa-solid fa-times"></i> Xóa</button>
                      </td>
                    </tr>
                  </template>
                  <tr v-if="list_chi_tiet.length === 0">
                    <td colspan="3">
                      <div class="empty-state">
                        <div class="empty-state-icon"><i class="fa-solid fa-shield-halved"></i></div>
                        <div class="empty-state-text">Chưa có quyền nào được cấp</div>
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
  </div>
</template>

<script>
import axios from "axios";
export default {
    data() {
        return {
            list_chuc_vu: [],
            list_chi_tiet: [],
            list_chuc_nang: [],
            chuc_vu_dang_chon: []
        }
    },
    mounted() { this.loadDataChucVu(); this.loadDataChucNang(); },
    methods: {
        loadDataChucVu() {
            const token = localStorage.getItem('xs');
            axios.get('http://127.0.0.1:8000/api/admin/chuc-vu/get-data', { headers: { 'Authorization': `Bearer ${token}` } }).then(res => { this.list_chuc_vu = res.data.data; });
        },
        loadDataChucNang() {
            const token = localStorage.getItem('xs');
            axios.get('http://127.0.0.1:8000/api/admin/chuc-nang/get-data', { headers: { 'Authorization': `Bearer ${token}` } }).then(res => { this.list_chuc_nang = res.data.data; });
        },
        capQuyen(chuc_nang) {
            const token = localStorage.getItem('xs');
            var payload = { "id_quyen": this.chuc_vu_dang_chon.id, "id_chuc_nang": chuc_nang.id };
            axios.post('http://127.0.0.1:8000/api/admin/chi-tiet-phan-quyen/cap-quyen', payload, { headers: { 'Authorization': `Bearer ${token}` } })
                .then(res => { if (res.data.status) { this.$toast.success(res.data.message); this.loadDataDSDaCap(this.chuc_vu_dang_chon); } else { this.$toast.error(res.data.message); } });
        },
        loadDataDSDaCap() {
            const token = localStorage.getItem('xs');
            var payload = { "id_quyen": this.chuc_vu_dang_chon.id };
            axios.post('http://127.0.0.1:8000/api/admin/chi-tiet-phan-quyen/danh-sach-da-cap', payload, { headers: { 'Authorization': `Bearer ${token}` } })
                .then(res => { this.list_chi_tiet = res.data.data; });
        }
    }
}
</script>
<style scoped></style>
