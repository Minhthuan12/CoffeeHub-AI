<template>
    <div class="">
        <div class="container-fluid">
            <div class="row g-3">
                <div class="col-12 col-lg-4">
                    <div class="card border-0 border-top border-3  border-info shadow-sm">
                        <div class="card-header bg-white">
                            <h4 class="mt-2 mb-2"><b>THÊM CÔNG THỨC</b></h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <label>Sản Phẩm</label>
                                <select v-model="add_cong_thuc.id_san_pham" class="form-control mt-2">
                                    <option value="">-- Chọn sản phẩm --</option>
                                    <option v-for="(v, k) in list_san_pham" :key="k" :value="v.id">{{ v.ten_san_pham }}
                                    </option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label>Nguyên Liệu</label>
                                <select v-model="add_cong_thuc.id_nguyen_lieu" class="form-control mt-2">
                                    <option value="">-- Chọn nguyên liệu --</option>
                                    <option v-for="(v, k) in list_nguyen_lieu_active" :key="k" :value="v.id">{{
                                        v.ten_nguyen_lieu }}</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label>Số Lượng</label>
                                <input v-model="add_cong_thuc.so_luong" type="number" class="form-control mt-2"
                                    placeholder="Nhập số lượng">
                            </div>
                            <div class="mb-2">
                                <label>Đơn Vị</label>
                                <input v-model="add_cong_thuc.don_vi" type="text" class="form-control mt-2"
                                    placeholder="Gram, Kg, ...">
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button @click="themCongThuc()" class="btn btn-primary">Thêm Mới</button>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-8">
                    <div class="card h-100 border-0 border-top border-3  border-info shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h4 class="mt-2 mb-0"><b>DANH SÁCH CÔNG THỨC</b></h4>
                            <button class="btn btn-success"><i class="fa-regular fa-file-excel"></i> Xuất Excel</button>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="table-primary">
                                    <tr class="text-center">
                                        <th>STT</th>
                                        <th>Sản Phẩm</th>
                                        <th>Nguyên Liệu</th>
                                        <th>Số Lượng</th>
                                        <th>Đơn Vị</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(value, index) in list_cong_thuc" :key="index" class="text-center">
                                        <th class="align-middle">{{ index + 1 }}</th>
                                        <td class="align-middle">{{ value.ten_san_pham }}<span v-if="value.deleted_at"
                                                class="text-danger"> (đã xóa)</span></td>
                                        <td class="align-middle">{{ value.ten_nguyen_lieu }}</td>
                                        <td class="align-middle">{{ value.so_luong }}</td>
                                        <td class="align-middle">{{ value.don_vi }}</td>
                                        <td class="align-middle d-flex justify-content-center gap-2">
                                            <template v-if="!value.deleted_at">
                                                <button @click="Object.assign(edit_cong_thuc, value)"
                                                    class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#updateModal">Cập nhật</button>
                                                <button @click="Object.assign(delete_cong_thuc, value)"
                                                    class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal">Xóa</button>
                                            </template>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary ">
                        <h1 class="modal-title text-white fs-5" id="updateModalLabel">Cập nhật</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label>Sản Phẩm</label>
                            <select v-model="edit_cong_thuc.id_san_pham" class="form-control mt-2">
                                <option value="">-- Chọn sản phẩm --</option>
                                <option v-for="(v, k) in list_san_pham" :key="k" :value="v.id">{{ v.ten_san_pham }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label>Nguyên Liệu</label>
                            <select v-model="edit_cong_thuc.id_nguyen_lieu" class="form-control mt-2">
                                <option value="">-- Chọn nguyên liệu --</option>
                                <option v-for="(v, k) in list_nguyen_lieu" :key="k" :value="v.id">{{ v.ten_nguyen_lieu
                                    }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label>Số Lượng</label>
                            <input v-model="edit_cong_thuc.so_luong" type="number" class="form-control mt-2"
                                placeholder="Nhập số lượng">
                        </div>
                        <div class="mb-2">
                            <label>Đơn Vị</label>
                            <input v-model="edit_cong_thuc.don_vi" type="text" class="form-control mt-2"
                                placeholder="Gram, Kg, ...">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button @click="updateCongThuc()" type="button" class="btn btn-primary"
                            data-bs-dismiss="modal">Lưu thay
                            đổi</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger ">
                        <h1 class="modal-title text-white fs-5" id="deleteModalLabel">Xóa</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger" role="alert">
                            Bạn có chắc chắn muốn xóa không?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button @click="deleteCongThuc()" type="button" class="btn btn-danger"
                            data-bs-dismiss="modal">Xác nhận
                            xóa</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
export default {
    name: 'CongThucMonComponent',
    data() {
        return {
            list_cong_thuc: [],
            list_san_pham: [],
            list_nguyen_lieu: [],
            list_nguyen_lieu_active: [],
            add_cong_thuc: {
                id_san_pham: '',
                id_nguyen_lieu: '',
                so_luong: '',
                don_vi: ''
            },
            edit_cong_thuc: {},
            delete_cong_thuc: {}
        }
    },
    mounted() {
        this.loadDataCongThuc();
        this.loadSanPham();
        this.loadNguyenLieu();
        this.loadNguyenLieuActive();
    },
    methods: {
        loadDataCongThuc() {
            axios
                .get('http://127.0.0.1:8000/api/admin/cong-thuc-mon/get-data', {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    this.list_cong_thuc = res.data.data;
                });
        },
        loadSanPham() {
            axios
                .get('http://127.0.0.1:8000/api/admin/san-pham/get-data', {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    this.list_san_pham = res.data.data;
                });
        },
        loadNguyenLieu() {
            axios
                .get('http://127.0.0.1:8000/api/admin/nguyen-lieu/get-data', {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    this.list_nguyen_lieu = res.data.data;
                });
        },
        loadNguyenLieuActive() {
            axios
                .get('http://127.0.0.1:8000/api/admin/nguyen-lieu/get-data-active', {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    this.list_nguyen_lieu_active = res.data.data;
                });
        },
        themCongThuc() {
            axios
                .post('http://127.0.0.1:8000/api/admin/cong-thuc-mon/store', this.add_cong_thuc, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message || "Thêm công thức thành công");
                        this.loadDataCongThuc();
                        this.add_cong_thuc = {
                            id_san_pham: '',
                            id_nguyen_lieu: '',
                            so_luong: '',
                            don_vi: ''
                        };
                    } else {
                        this.$toast.error(res.data.message || "Thêm thất bại");
                    }
                })
                .catch((error) => {
                    console.error(error);
                    if (error.response && error.response.data) {
                        this.$toast.error(error.response.data.message || "Có lỗi xảy ra!");
                    } else {
                        this.$toast.error("Không thể kết nối đến máy chủ!");
                    }
                });
        },
        updateCongThuc() {
            axios
                .post('http://127.0.0.1:8000/api/admin/cong-thuc-mon/update', this.edit_cong_thuc, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    if (res.data.status == 1) {
                        this.$toast.success(res.data.message);
                        this.loadDataCongThuc();
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .catch((error) => {
                    console.error(error);
                    if (error.response && error.response.data) {
                        this.$toast.error(error.response.data.message || "Có lỗi xảy ra!");
                    } else {
                        this.$toast.error("Không thể kết nối đến máy chủ!");
                    }
                });
        },
        deleteCongThuc() {
            axios
                .post('http://127.0.0.1:8000/api/admin/cong-thuc-mon/delete', this.delete_cong_thuc, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    if (res.data.status == 1) {
                        this.$toast.success(res.data.message);
                        this.loadDataCongThuc();
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .catch((error) => {
                    console.error(error);
                    if (error.response && error.response.data) {
                        this.$toast.error(error.response.data.message || "Có lỗi xảy ra!");
                    } else {
                        this.$toast.error("Không thể kết nối đến máy chủ!");
                    }
                });
        }
    }
}
</script>
<style></style>