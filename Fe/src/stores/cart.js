import { defineStore } from 'pinia';
import { formatTuyChonText } from '../config/tuyChon';

export const useCartStore = defineStore('cart', {
  state: () => ({
    id_ban: null,
    ten_ban: '',
    items: [],
    voucher: null,
    customerInfo: {
      name: '',
      phone: ''
    },
    activeOrderId: null,
    activeOrderData: null
  }),

  getters: {
    totalCount: (state) => {
      return state.items.reduce((total, item) => total + (item.so_luong || 1), 0);
    },

    subtotal: (state) => {
      return state.items.reduce((total, item) => total + ((item.don_gia || 0) * (item.so_luong || 1)), 0);
    },

    discountAmount: (state) => {
      if (!state.voucher) return 0;
      const sub = state.items.reduce((total, item) => total + ((item.don_gia || 0) * (item.so_luong || 1)), 0);
      return Math.min(state.voucher.so_tien_giam || 0, sub);
    },

    finalTotal() {
      return Math.max(0, this.subtotal - this.discountAmount);
    }
  },

  actions: {
    /**
     * Khởi tạo bàn và khôi phục giỏ hàng theo từng bàn từ localStorage
     */
    initTable(id_ban, ten_ban = '') {
      this.id_ban = id_ban;
      this.ten_ban = ten_ban || `Bàn ${id_ban}`;

      const storageKey = `coffeehub_cart_ban_${id_ban}`;
      const saved = localStorage.getItem(storageKey);
      if (saved) {
        try {
          const parsed = JSON.parse(saved);
          this.items = parsed.items || [];
          this.voucher = parsed.voucher || null;
          this.customerInfo = parsed.customerInfo || { name: '', phone: '' };
        } catch (e) {
          console.error('Lỗi đọc giỏ hàng từ localStorage:', e);
          this.items = [];
        }
      } else {
        this.items = [];
        this.voucher = null;
      }

      // Khôi phục đơn hàng đang theo dõi (nếu có)
      const orderKey = `coffeehub_active_order_${id_ban}`;
      const savedOrder = localStorage.getItem(orderKey);
      if (savedOrder) {
        try {
          const parsedOrder = JSON.parse(savedOrder);
          this.activeOrderId = parsedOrder.id || parsedOrder.order_id || null;
          this.activeOrderData = parsedOrder;
        } catch (e) {
          this.activeOrderId = null;
          this.activeOrderData = null;
        }
      }
    },

    persist() {
      if (!this.id_ban) return;
      const storageKey = `coffeehub_cart_ban_${this.id_ban}`;
      localStorage.setItem(storageKey, JSON.stringify({
        items: this.items,
        voucher: this.voucher,
        customerInfo: this.customerInfo
      }));
    },

    /**
     * Thêm món vào giỏ với tùy chọn Snapshot
     */
    addItem(product, tuyChon = {}, soLuong = 1, ghiChu = '') {
      const basePrice = Number(product.gia_ban) || 0;
      const phuThu = Number(tuyChon.phu_thu) || 0;
      const donGia = basePrice + phuThu;

      // Sinh chữ ký nhận diện món trùng lặp option
      const toppingIds = (tuyChon.toppings || []).map(t => t.id || t).sort().join('_');
      const sizeKey = (tuyChon.size && tuyChon.size.ma) || 'S';
      const daKey = (tuyChon.da && tuyChon.da.ma) || '100%';
      const duongKey = (tuyChon.duong && tuyChon.duong.ma) || '100%';
      const signature = `${product.id}_${sizeKey}_${daKey}_${duongKey}_${toppingIds}_${ghiChu.trim()}`;

      const existingIndex = this.items.findIndex(item => item.signature === signature);

      if (existingIndex > -1) {
        this.items[existingIndex].so_luong += Number(soLuong);
      } else {
        const uid = 'cart_' + Date.now() + '_' + Math.random().toString(36).substr(2, 6);
        this.items.push({
          uid,
          signature,
          id_san_pham: product.id,
          ten_san_pham: product.ten_san_pham,
          hinh_anh: product.hinh_anh,
          kich_thuoc: sizeKey,
          gia_goc: basePrice,
          don_gia: donGia,
          so_luong: Number(soLuong),
          tuy_chon: tuyChon,
          tuy_chon_text: formatTuyChonText(tuyChon),
          ghi_chu: ghiChu.trim()
        });
      }

      this.persist();
    },

    updateQuantity(uid, delta) {
      const idx = this.items.findIndex(i => i.uid === uid);
      if (idx > -1) {
        const newQty = this.items[idx].so_luong + delta;
        if (newQty <= 0) {
          this.items.splice(idx, 1);
        } else {
          this.items[idx].so_luong = newQty;
        }
        this.persist();
      }
    },

    removeItem(uid) {
      const idx = this.items.findIndex(i => i.uid === uid);
      if (idx > -1) {
        this.items.splice(idx, 1);
        this.persist();
      }
    },

    clearCart() {
      this.items = [];
      this.voucher = null;
      this.persist();
    },

    applyVoucher(voucherData) {
      this.voucher = voucherData;
      this.persist();
    },

    removeVoucher() {
      this.voucher = null;
      this.persist();
    },

    setActiveOrder(orderData) {
      if (!this.id_ban) return;
      this.activeOrderId = orderData.order_id || orderData.id;
      this.activeOrderData = orderData;
      localStorage.setItem(`coffeehub_active_order_${this.id_ban}`, JSON.stringify(orderData));
    },

    clearActiveOrder() {
      if (!this.id_ban) return;
      this.activeOrderId = null;
      this.activeOrderData = null;
      localStorage.removeItem(`coffeehub_active_order_${this.id_ban}`);
    }
  }
});
