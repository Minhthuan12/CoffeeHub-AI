<template>
    <div class="row">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <span class="input-group-text">Từ ngày</span>
                                    <input v-model="khoang_ngay.tu_ngay" type="date" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <span class="input-group-text">Đến ngày</span>
                                    <input v-model="khoang_ngay.den_ngay" type="date" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <button @click="loadData()" class="btn btn-primary w-100">Thống Kê</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mt-2">DOANH THU KHU VỰC</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Khu Vực</th>
                                    <th class="text-center">Doanh Thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(value, index) in list_data" :key="index">
                                    <th class="text-center">{{ index + 1 }}</th>
                                    <th class="text-center">{{ value.ten_khu_vuc }}</th>
                                    <th class="text-center">{{ value.tong_doanh_thu }}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card p-3">
                    <Pie v-if="loaded" :options="chartOptions" :data="chartData" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Pie } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement, CategoryScale, LinearScale } from 'chart.js'
import axios from 'axios'
ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale, LinearScale)

export default {
    name: 'DoanhThuTheoKhuVuc',
    components: { Pie },
    data() {
        return {
            khoang_ngay: {},
            loaded: false,
            list_data: [],
            chartData: {
                labels: [],
                datasets: []
            },
            chartOptions: {
                responsive: true
            }
        }
    },
    mounted() {
        this.loadData();
    },
    methods: {
        loadData() {
            this.loaded = false;
            axios
                .post('http://127.0.0.1:8000/api/admin/thong-ke/doanh-thu-theo-khu-vuc', this.khoang_ngay, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('xs')}`
                    }
                })
                .then((res) => {
                    if (res.data.status) {
                        if (!this.khoang_ngay.tu_ngay && res.data.tu_ngay) {
                            this.khoang_ngay.tu_ngay = res.data.tu_ngay;
                            this.khoang_ngay.den_ngay = res.data.den_ngay;
                        }
                        this.chartData = res.data.data;
                        this.list_data = res.data.list;
                        this.loaded = true;
                    }
                })
                .catch((error) => {
                    console.error("Lỗi khi tải dữ liệu thống kê:", error);
                });
        }
    }
}
</script>

<style></style>