<template>
    <div class="">
        <div class="container-fluid">
            <div class="row g-3">

                <!-- THÊM MỚI -->
                <div class="col-12 col-lg-4">

                    <div class="card radius-10 border-top border-0 border-3 border-info shadow-sm">

                        <div class="card-header bg-white">
                            <h5 class="mt-2 mb-2">
                                <b>THÊM MỚI VOUCHER</b>
                            </h5>
                        </div>

                        <div class="card-body">

                            <div class="mb-2">
                                <label>Mã Voucher</label>
                                <input v-model="add_voucher.ma_voucher" type="text" class="form-control mt-2"
                                    placeholder="Ví dụ: SUMMER20">
                            </div>

                            <div class="mb-2">
                                <label>Loại Giảm</label>
                                <select v-model="add_voucher.loai_giam" class="form-control mt-2">
                                    <option value="0">-- Chọn loại giảm --</option>
                                    <option value="1">Phần Trăm</option>
                                    <option value="2">Tiền Cố Định</option>
                                </select>
                            </div>

                            <div class="mb-2">
                                <label>Số Giảm Giá</label>
                                <input v-model="add_voucher.so_giam_gia" type="number" class="form-control mt-2"
                                    placeholder="Nhập số giảm">
                            </div>

                            <div class="mb-2">
                                <label>Ngày Bắt Đầu</label>
                                <input v-model="add_voucher.thoi_gian_bat_dau" type="date" class="form-control mt-2">
                            </div>

                            <div class="mb-2">
                                <label>Ngày Kết Thúc</label>
                                <input v-model="add_voucher.thoi_gian_ket_thuc" type="date" class="form-control mt-2">
                            </div>

                        </div>

                        <div class="card-footer text-end">
                            <button @click="themVoucher()" class="btn btn-primary">
                                Thêm Voucher
                            </button>
                        </div>

                    </div>

                </div>

                <!-- DANH SÁCH -->
                <div class="col-12 col-lg-8">

                    <div class="card radius-10 border-top border-0 border-3 border-info shadow-sm">

                        <div class="card-header bg-white">
                            <h5 class="mt-2 mb-0">
                                <b>DANH SÁCH VOUCHER</b>
                            </h5>
                        </div>

                        <div class="card-body table-responsive">

                            <table class="table table-bordered table-hover mb-0">

                                <thead class="table-primary">

                                    <tr class="text-center">
                                        <th>STT</th>
                                        <th>Mã Voucher</th>
                                        <th>Loại Giảm</th>
                                        <th>Số Giảm</th>
                                        <th>Ngày Bắt Đầu</th>
                                        <th>Ngày Kết Thúc</th>
                                        <th>Action</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    <template v-for="(value, index) in list_voucher" :key="index">

                                        <tr class="text-center">

                                            <th class="align-middle">
                                                {{ index + 1 }}
                                            </th>

                                            <td class="align-middle">
                                                {{ value.ma_voucher }}<span v-if="value.deleted_at" class="text-danger">
                                                    (đã xóa)</span>
                                            </td>

                                            <td class="align-middle">
                                                {{ value.loai_giam == 1 ? 'Phần Trăm' : 'Tiền Cố Định' }}
                                            </td>

                                            <td class="align-middle">
                                                {{ value.so_giam_gia }}
                                                {{ value.loai_giam == 1 ? '%' : 'VND' }}
                                            </td>

                                            <td class="align-middle">
                                                {{ value.thoi_gian_bat_dau }}
                                            </td>

                                            <td class="align-middle">
                                                {{ value.thoi_gian_ket_thuc }}
                                            </td>

                                            <td class="align-middle d-flex justify-content-center gap-2">

                                                <template v-if="!value.deleted_at">
                                                    <button @click="Object.assign(edit_voucher, value)"
                                                        class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#updateModal">
                                                        Cập nhật
                                                    </button>

                                                    <button @click="Object.assign(delete_voucher, value)"
                                                        class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal">
                                                        Xóa
                                                    </button>
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

        <!-- UPDATE -->
        <div class="modal fade" id="updateModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h1 class="modal-title text-white fs-5">
                            Cập nhật voucher
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label>Mã Voucher</label>
                            <input v-model="edit_voucher.ma_voucher" type="text" class="form-control mt-2">
                        </div>
                        <div class="mb-2">
                            <label>Loại Giảm</label>
                            <select v-model="edit_voucher.loai_giam" class="form-control mt-2">
                                <option value="0">-- Chọn loại giảm --</option>
                                <option value="1">Phần Trăm</option>
                                <option value="2">Tiền Cố Định</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label>Số Giảm Giá</label>
                            <input v-model="edit_voucher.so_giam_gia" type="number" class="form-control mt-2">
                        </div>
                        <div class="mb-2">
                            <label>Ngày Bắt Đầu</label>
                            <input v-model="edit_voucher.thoi_gian_bat_dau" type="date" class="form-control mt-2">
                        </div>
                        <div class="mb-2">
                            <label>Ngày Kết Thúc</label>
                            <input v-model="edit_voucher.thoi_gian_ket_thuc" type="date" class="form-control mt-2">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Đóng
                        </button>
                        <button @click="updateVoucher()" type="button" class="btn btn-primary" data-bs-dismiss="modal">
                            Lưu thay đổi
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- DELETE -->
        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h1 class="modal-title text-white fs-5">
                            Xóa voucher
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger" role="alert">
                            Bạn có chắc chắn muốn xóa voucher này không?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Đóng
                        </button>
                        <button @click="deleteVoucher()" type="button" class="btn btn-danger" data-bs-dismiss="modal">
                            Xác nhận xóa
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'VoucherComponent',
    data() {
        return {
            list_voucher: [],
            add_voucher: {
                ma_voucher: '',
                loai_giam: 0,
                so_giam_gia: 0,
                thoi_gian_bat_dau: '',
                thoi_gian_ket_thuc: '',
                so_tien_toi_da: 0,
                don_hang_toi_thieu: 0
            },
            edit_voucher: {},
            delete_voucher: {}
        }
    },
    mounted() {
        this.loadDataVoucher();
    },
    methods: {
        loadDataVoucher() {
            axios
                .get('http://127.0.0.1:8000/api/admin/voucher/get-data', {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    this.list_voucher = res.data.data || [];
                });
        },
        themVoucher() {
            axios
                .post('http://127.0.0.1:8000/api/admin/voucher/store', this.add_voucher, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message || "Thêm voucher thành công");
                        this.loadDataVoucher();
                        this.add_voucher = {
                            ma_voucher: '',
                            loai_giam: 0,
                            so_giam_gia: 0,
                            thoi_gian_bat_dau: '',
                            thoi_gian_ket_thuc: '',
                            so_tien_toi_da: 0,
                            don_hang_toi_thieu: 0
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
        updateVoucher() {
            axios
                .post('http://127.0.0.1:8000/api/admin/voucher/update', this.edit_voucher, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    if (res.data.status == 1) {
                        this.$toast.success(res.data.message);
                        this.loadDataVoucher();
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
        deleteVoucher() {
            axios
                .post('http://127.0.0.1:8000/api/admin/voucher/delete', this.delete_voucher, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    if (res.data.status == 1) {
                        this.$toast.success(res.data.message);
                        this.loadDataVoucher();
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