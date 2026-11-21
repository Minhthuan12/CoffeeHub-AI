import axios from "axios"
import { createToaster } from "@meforma/vue-toaster";

const toaster = createToaster({ position: "top-right" });

export default function checkLogin(to, form, next) {
    const token = localStorage.getItem('xs');
    axios.get(`http://127.0.0.1:8000/api/check-login`, {
        headers: {
            'Authorization': `Bearer ${token}`
        }
    })
        .then((res) => {
            if (res.data.status == 1) {
                next();
            } else {
                toaster.error(res.data.message || "Bạn chưa đăng nhập!");
                next('/admin/login');
            }
        })
        .catch((error) => {
            toaster.error("Phiên đăng nhập đã hết hạn hoặc không hợp lệ!");
            next('/admin/login');
        })

}