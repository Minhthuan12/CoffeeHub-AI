<template>
    <div>
        <div class="container-fluid">
            <div class="row g-3">

                <!-- CỘT 1: ĐANG CHẾ BIẾN (col-lg-8) -->
                <div class="col-lg-8 col-md-12">
                    <div class="card radius-10 border-top border-0 border-3 border-danger shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="mb-0 d-flex align-items-center">
                                <i class="fa-solid fa-fire text-danger me-2"></i>
                                <b>ĐANG CHẾ BIẾN</b>
                                <span class="badge bg-danger rounded-pill ms-2 font-13">{{ list_dang_che_bien.length }}</span>
                            </h5>
                            <button class="btn btn-primary btn-sm px-3 fw-bold" @click="bep_che_bien">
                                <i class="fa-solid fa-arrows-rotate me-1"></i>Làm mới
                            </button>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-hover mb-0 align-middle">
                                <thead class="table-primary">
                                    <tr class="text-center">
                                        <th style="width: 45px;">STT</th>
                                        <th style="width: 80px;">Bàn</th>
                                        <th>Tên Món Ăn</th>
                                        <th style="width: 60px;">SL</th>
                                        <th style="width: 130px;">Ghi Chú</th>
                                        <th style="min-width: 175px;">Thời Gian Chờ</th>
                                        <th style="width: 85px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(value, index) in list_dang_che_bien" :key="value.id || index">
                                        <tr class="text-center" :class="{ 'table-danger-subtle': getWaitLevel(value.created_at) === 'danger' }">
                                            <th class="align-middle text-center">{{ index + 1 }}</th>
                                            <td class="align-middle fw-bold text-primary">{{ value.ten_ban }}</td>
                                            <td class="align-middle text-start ps-3">
                                                <b>{{ value.ten_san_pham }}</b>
                                                <i v-if="getWaitLevel(value.created_at) === 'danger'"
                                                    class="fa-solid fa-triangle-exclamation text-danger ms-1"
                                                    title="Món này đang chờ quá lâu!"></i>
                                            </td>
                                            <td class="align-middle text-primary fw-bold fs-6">{{ value.so_luong }}</td>
                                            <td class="align-middle text-muted text-start small">
                                                {{ value.ghi_chu || '-' }}
                                            </td>
                                            <!-- Thanh thời gian chờ -->
                                            <td class="align-middle px-3">
                                                <div class="d-flex flex-column gap-1">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="badge fw-bold" :class="getBadgeClass(value.created_at)">
                                                            <i class="fa-regular fa-clock me-1"></i>{{ getElapsedTime(value.created_at) }}
                                                        </span>
                                                        <span class="text-muted" style="font-size: 11px;">
                                                            {{ formatTimeOnly(value.created_at) }}
                                                        </span>
                                                    </div>
                                                    <div class="progress" style="height: 6px; background-color: #e9ecef;" title="Thời gian chờ">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                            :class="getProgressBarClass(value.created_at)"
                                                            :style="{ width: getProgressPercent(value.created_at) + '%' }">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <button class="btn btn-success btn-sm px-3 fw-bold"
                                                    style="border-radius: 4px;" @click="xongMon(value)">
                                                    <i class="fa-solid fa-check me-1"></i>Xong
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr v-if="list_dang_che_bien.length === 0">
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            <i class="fa-solid fa-mug-hot fa-2x mb-2 d-block text-secondary opacity-50"></i>
                                            Hiện không có món nào đang chờ chế biến
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- CỘT 2: MÓN ĐÃ HOÀN THÀNH (col-lg-4) -->
                <div class="col-lg-4 col-md-12">
                    <div class="card radius-10 border-top border-0 border-3 border-success shadow-sm"
                        style="min-height: 295px;">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="mb-0 d-flex align-items-center">
                                <i class="fa-solid fa-circle-check text-success me-2"></i>
                                <b>MÓN ĐÃ HOÀN THÀNH</b>
                                <span class="badge bg-success rounded-pill ms-2 font-13">{{ list_hoan_thanh.length }}</span>
                            </h5>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-hover mb-0 align-middle">
                                <thead class="table-primary">
                                    <tr class="text-center">
                                        <th style="width: 40px;">STT</th>
                                        <th style="width: 75px;">Bàn</th>
                                        <th>Tên Món</th>
                                        <th style="width: 50px;">SL</th>
                                        <th style="width: 75px;">Giờ Xong</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(value, index) in list_hoan_thanh" :key="value.id || index">
                                        <tr class="text-center">
                                            <th class="align-middle text-center">{{ index + 1 }}</th>
                                            <td class="align-middle fw-bold">{{ value.ten_ban }}</td>
                                            <td class="align-middle text-start ps-2"><b>{{ value.ten_san_pham }}</b></td>
                                            <td class="align-middle fw-bold text-success">{{ value.so_luong }}</td>
                                            <td class="align-middle small text-muted">
                                                {{ formatTimeOnly(value.updated_at) }}
                                            </td>
                                        </tr>
                                    </template>
                                    <tr v-if="list_hoan_thanh.length === 0">
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fa-solid fa-circle-check fa-2x mb-2 d-block text-secondary opacity-50"></i>
                                            Chưa có món nào hoàn thành gần đây
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
import axios from 'axios';
export default {
    data() {
        return {
            list_che_bien: [],
            now: Date.now(),
            timer: null,
            refreshInterval: null,
            syncChannel: null,
            onStorageChange: null,
        }
    },
    computed: {
        list_hoan_thanh() {
            return this.list_che_bien
                .filter(item => item.tinh_trang == 2)
                .sort((a, b) => {
                    const timeA = new Date(a.updated_at || a.created_at || 0).getTime();
                    const timeB = new Date(b.updated_at || b.created_at || 0).getTime();
                    return timeB - timeA; // Món vừa xong xếp lên trước
                });
        },
        list_dang_che_bien() {
            return this.list_che_bien
                .filter(item => item.tinh_trang == 1)
                .sort((a, b) => {
                    // Sắp xếp thứ tự ưu tiên: món có thời gian gọi lâu hơn (created_at nhỏ hơn) xếp lên trước
                    const timeA = new Date(a.created_at || a.updated_at || 0).getTime();
                    const timeB = new Date(b.created_at || b.updated_at || 0).getTime();
                    return timeA - timeB;
                });
        }
    },
    methods: {
        bep_che_bien() {
            axios
                .get('http://127.0.0.1:8000/api/admin/chi-tiet-hoa-don/bep-che-bien', {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    this.list_che_bien = res.data.data || [];
                })
                .catch((err) => {
                    console.error('Lỗi tải danh sách bếp chế biến:', err);
                });
        },
        xongMon(value) {
            axios
                .post('http://127.0.0.1:8000/api/admin/chi-tiet-hoa-don/xong-mon', value, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.bep_che_bien();

                        // Bắn tín hiệu sang màn hình Bán Hàng cập nhật tức thì trạng thái món đã xong
                        if (typeof BroadcastChannel !== 'undefined') {
                            const bc = new BroadcastChannel('coffeehub_sync');
                            bc.postMessage({ type: 'CHEF_XONG_MON', item: value });
                            bc.close();
                        }
                        localStorage.setItem('coffeehub_sync_time', Date.now());
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .catch((err) => {
                    this.$toast.error('Có lỗi xảy ra khi hoàn thành món!');
                });
        },
        getElapsedSeconds(createdAt) {
            if (!createdAt) return 0;
            const created = new Date(createdAt).getTime();
            if (isNaN(created)) return 0;
            return Math.max(0, Math.floor((this.now - created) / 1000));
        },
        getElapsedTime(createdAt) {
            const sec = this.getElapsedSeconds(createdAt);
            const m = Math.floor(sec / 60);
            const s = sec % 60;
            if (m < 60) {
                return `${m}p ${s < 10 ? '0' : ''}${s}s`;
            }
            const h = Math.floor(m / 60);
            const remM = m % 60;
            return `${h}h ${remM}p`;
        },
        getProgressPercent(createdAt) {
            // Chu kỳ chuẩn hiển thị: 15 phút (900s) = 100% của thanh thời gian
            const sec = this.getElapsedSeconds(createdAt);
            const percent = Math.min(100, Math.round((sec / 900) * 100));
            return Math.max(8, percent);
        },
        getWaitLevel(createdAt) {
            const sec = this.getElapsedSeconds(createdAt);
            const min = sec / 60;
            if (min >= 10) return 'danger';    // Chờ quá 10 phút
            if (min >= 5) return 'warning';     // Chờ 5 - 10 phút
            return 'success';                  // Dưới 5 phút
        },
        getBadgeClass(createdAt) {
            const level = this.getWaitLevel(createdAt);
            if (level === 'danger') return 'bg-danger text-white';
            if (level === 'warning') return 'bg-warning text-dark';
            return 'bg-success text-white';
        },
        getProgressBarClass(createdAt) {
            const level = this.getWaitLevel(createdAt);
            if (level === 'danger') return 'bg-danger';
            if (level === 'warning') return 'bg-warning';
            return 'bg-success';
        },
        formatTimeOnly(timeStr) {
            if (!timeStr) return '';
            const d = new Date(timeStr);
            if (isNaN(d.getTime())) return '';
            const h = String(d.getHours()).padStart(2, '0');
            const m = String(d.getMinutes()).padStart(2, '0');
            const s = String(d.getSeconds()).padStart(2, '0');
            return `${h}:${m}:${s}`;
        }
    },
    mounted() {
        this.bep_che_bien();
        // Cập nhật bộ đếm thời gian thực mỗi 1 giây
        this.timer = setInterval(() => {
            this.now = Date.now();
        }, 1000);
        // Tự động tải mới danh sách món mỗi 5 giây
        this.refreshInterval = setInterval(() => {
            this.bep_che_bien();
        }, 5000);

        // Lắng nghe sự kiện đồng bộ từ Bán Hàng (khi có món mới in pha chế hoặc bàn thanh toán)
        if (typeof BroadcastChannel !== 'undefined') {
            this.syncChannel = new BroadcastChannel('coffeehub_sync');
            this.syncChannel.onmessage = (event) => {
                if (event.data && (event.data.type === 'IN_PHA_CHE' || event.data.type === 'BILL_PAID')) {
                    this.bep_che_bien();
                }
            };
        }
        this.onStorageChange = (e) => {
            if (e.key === 'coffeehub_sync_time') {
                this.bep_che_bien();
            }
        };
        window.addEventListener('storage', this.onStorageChange);
    },
    beforeUnmount() {
        if (this.timer) clearInterval(this.timer);
        if (this.refreshInterval) clearInterval(this.refreshInterval);
        if (this.syncChannel) this.syncChannel.close();
        if (this.onStorageChange) window.removeEventListener('storage', this.onStorageChange);
    }
}
</script>

<style scoped>
.table-danger-subtle {
    background-color: #fff5f5 !important;
}
</style>
