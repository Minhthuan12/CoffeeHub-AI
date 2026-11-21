<template>
    <div class="">
        <div class="container-fluid">
            <div class="row g-3">
                <div class="col-lg-12">
                    <div class="card border-0 border-top border-3  border-info shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h4 class="mt-2 mb--2"><b>DANH SÁCH SẢN PHẨM</b></h4>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createModal">
                                <i class="fa-solid fa-plus me-2"></i>Thêm Sản Phẩm
                            </button>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="table-primary">
                                    <tr class="text-center">
                                        <th>#</th>
                                        <th>Tên Sản Phẩm</th>
                                        <th>Danh Mục</th>
                                        <th>Giá Bán</th>
                                        <th>Kích Thước</th>
                                        <th>Hình Ảnh</th>
                                        <th>Mô Tả</th>
                                        <th>Tình Trạng</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(value, index) in list_san_pham" :key="index">
                                        <tr class="text-center align-middle">
                                            <th>{{ index + 1 }}</th>
                                            <td>{{ value.ten_san_pham }}<span v-if="value.deleted_at"
                                                    class="text-danger"> (đã xóa)</span></td>
                                            <td>{{ getTenDanhMuc(value.id_danh_muc) || value.ten_danh_muc }}</td>
                                            <td>{{ value.gia_ban }}</td>
                                            <td>{{ value.kich_thuoc }}</td>
                                            <td>
                                                <img :src="value.hinh_anh" width="50" height="50"
                                                    style="object-fit: cover;">
                                            </td>
                                            <td>{{ value.mo_ta }}</td>
                                            <td>
                                                <button v-on:click="changeStatus(value)" :disabled="!!value.deleted_at"
                                                    v-if="value.tinh_trang == 1"
                                                    class="btn btn-success w-100 btn-sm">Hoạt Động</button>
                                                <button v-on:click="changeStatus(value)" :disabled="!!value.deleted_at"
                                                    v-else class="btn btn-secondary w-100 btn-sm">Tạm Tắt</button>
                                            </td>
                                            <td class="text-nowrap">
                                                <template v-if="!value.deleted_at">
                                                    <button @click="Object.assign(edit_san_pham, value)"
                                                        class="btn btn-primary me-2 " data-bs-toggle="modal"
                                                        data-bs-target="#updateModal">Cập nhật</button>
                                                    <button @click="Object.assign(delete_san_pham, value)"
                                                        class="btn btn-danger " data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal">Xóa</button>
                                                </template>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary ">
                        <h1 class="modal-title text-white fs-5" id="createModalLabel">Thêm mới sản phẩm</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label>Tên Sản Phẩm</label>
                            <input v-model="add_san_pham.ten_san_pham" type="text" class="form-control mt-2"
                                placeholder="Nhập tên sản phẩm">
                        </div>
                        <div class="mb-2">
                            <label>Danh Mục</label>
                            <select v-model="add_san_pham.id_danh_muc" class="form-control mt-2">
                                <option value="">-- Chọn danh mục --</option>
                                <option v-for="(v, k) in list_danh_muc" :key="k" :value="v.id">{{ v.ten_danh_muc }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label>Giá Bán</label>
                            <input v-model="add_san_pham.gia_ban" type="number" class="form-control mt-2"
                                placeholder="Nhập giá bán">
                        </div>
                        <div class="mb-2">
                            <label>Hình Ảnh (URL)</label>
                            <input v-model="add_san_pham.hinh_anh" type="text" class="form-control mt-2"
                                placeholder="Nhập đường dẫn hình ảnh">
                        </div>
                        <div class="mb-2">
                            <label>Kích Thước</label>
                            <input v-model="add_san_pham.kich_thuoc" type="text" class="form-control mt-2"
                                placeholder="Vừa, Lớn, ...">
                        </div>
                        <div class="mb-2">
                            <label>Mô Tả</label>
                            <textarea v-model="add_san_pham.mo_ta" class="form-control mt-2" rows="3"
                                placeholder="Nhập mô tả sản phẩm"></textarea>
                        </div>
                        <div class="mb-2">
                            <label>Tình Trạng</label>
                            <select v-model="add_san_pham.tinh_trang" class="form-control mt-2">
                                <option value="1">Hoạt Động</option>
                                <option value="0">Tạm Tắt</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button @click="themSanPham()" type="button" class="btn btn-primary"
                            data-bs-dismiss="modal">Thêm
                            Mới</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary ">
                        <h1 class="modal-title text-white fs-5" id="updateModalLabel">Cập nhật sản phẩm</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label>Tên Sản Phẩm</label>
                            <input v-model="edit_san_pham.ten_san_pham" type="text" class="form-control mt-2"
                                placeholder="Nhập tên sản phẩm">
                        </div>
                        <div class="mb-2">
                            <label>Danh Mục</label>
                            <select v-model="edit_san_pham.id_danh_muc" class="form-control mt-2">
                                <option value="">-- Chọn danh mục --</option>
                                <option v-for="(v, k) in list_danh_muc" :key="k" :value="v.id">{{ v.ten_danh_muc }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label>Giá Bán</label>
                            <input v-model="edit_san_pham.gia_ban" type="number" class="form-control mt-2"
                                placeholder="Nhập giá bán">
                        </div>
                        <div class="mb-2">
                            <label>Hình Ảnh (URL)</label>
                            <input v-model="edit_san_pham.hinh_anh" type="text" class="form-control mt-2"
                                placeholder="Nhập đường dẫn hình ảnh">
                        </div>
                        <div class="mb-2">
                            <label>Kích Thước</label>
                            <input v-model="edit_san_pham.kich_thuoc" type="text" class="form-control mt-2"
                                placeholder="Vừa, Lớn, ...">
                        </div>
                        <div class="mb-2">
                            <label>Mô Tả</label>
                            <textarea v-model="edit_san_pham.mo_ta" class="form-control mt-2" rows="3"
                                placeholder="Nhập mô tả sản phẩm"></textarea>
                        </div>
                        <div class="mb-2">
                            <label>Tình Trạng</label>
                            <select v-model="edit_san_pham.tinh_trang" class="form-control mt-2">
                                <option value="1">Hoạt Động</option>
                                <option value="0">Tạm Tắt</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button @click="updateSanPham()" type="button" class="btn btn-primary"
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
                            Bạn có chắc chắn muốn xóa sản phẩm này không?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button @click="deleteSanPham()" type="button" class="btn btn-danger"
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
    name: 'SanPhamComponent',
    data() {
        return {
            list_san_pham: [],
            list_danh_muc: [],
            add_san_pham: {
                ten_san_pham: '',
                slug_san_pham: '',
                mo_ta: '',
                kich_thuoc: '',
                gia_ban: 0,
                hinh_anh: '',
                tinh_trang: 1,
                id_danh_muc: ''
            },
            edit_san_pham: {},
            delete_san_pham: {}
        }
    },
    watch: {
        'add_san_pham.ten_san_pham'(newVal) {
            this.add_san_pham.slug_san_pham = this.toSlug(newVal);
        },
        'edit_san_pham.ten_san_pham'(newVal) {
            this.edit_san_pham.slug_san_pham = this.toSlug(newVal);
        }
    },
    mounted() {
        this.loadDataSanPham();
        this.loadDanhMuc();
    },
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
        loadDataSanPham() {
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
        loadDanhMuc() {
            axios
                .get('http://127.0.0.1:8000/api/admin/danh-muc/get-data', {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    this.list_danh_muc = res.data.data;
                });
        },
        getTenDanhMuc(id) {
            const dm = this.list_danh_muc.find(x => x.id === id);
            return dm ? dm.ten_danh_muc : id;
        },
        themSanPham() {
            axios
                .post('http://127.0.0.1:8000/api/admin/san-pham/store', this.add_san_pham, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message || "Thêm sản phẩm thành công");
                        this.loadDataSanPham();
                        this.add_san_pham = {
                            ten_san_pham: '',
                            slug_san_pham: '',
                            mo_ta: '',
                            kich_thuoc: '',
                            gia_ban: 0,
                            hinh_anh: '',
                            tinh_trang: 1,
                            id_danh_muc: ''
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
        updateSanPham() {
            axios
                .post('http://127.0.0.1:8000/api/admin/san-pham/update', this.edit_san_pham, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    if (res.data.status == 1) {
                        this.$toast.success(res.data.message);
                        this.loadDataSanPham();
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
        deleteSanPham() {
            axios
                .post('http://127.0.0.1:8000/api/admin/san-pham/delete', this.delete_san_pham, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    if (res.data.status == 1) {
                        this.$toast.success(res.data.message);
                        this.loadDataSanPham();
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
        changeStatus(value) {
            axios
                .post('http://127.0.0.1:8000/api/admin/san-pham/change-status', value, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    if (res.data.status == 1) {
                        this.$toast.success(res.data.message);
                        this.loadDataSanPham();
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
    },
}
</script>
<style></style>