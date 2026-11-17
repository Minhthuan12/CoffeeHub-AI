/**
 * CoffeeHub Frontend - REST API Integration Service
 * Kết nối tới Backend Laravel (AICoffeeHub/Be)
 */

// Cấu hình URL cơ sở của Backend Laravel
// Có thể chạy qua php artisan serve (http://127.0.0.1:8000/api)
// Hoặc qua XAMPP Apache (http://localhost/AICoffeeHub/Be/public/api)
const API_BASE_URL = window.location.port === '8000' 
  ? 'http://127.0.0.1:8000/api'
  : (window.location.origin.includes('localhost') 
      ? 'http://localhost/AICoffeeHub/Be/public/api' 
      : 'http://127.0.0.1:8000/api');

class CoffeeHubApi {
  constructor(baseUrl) {
    this.baseUrl = baseUrl;
    this.token = localStorage.getItem('coffeehub_token') || null;
  }

  setToken(token) {
    this.token = token;
    localStorage.setItem('coffeehub_token', token);
  }

  getHeaders() {
    const headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    };
    if (this.token) {
      headers['Authorization'] = `Bearer ${this.token}`;
    }
    return headers;
  }

  async request(endpoint, options = {}) {
    const url = `${this.baseUrl}${endpoint}`;
    const config = {
      headers: this.getHeaders(),
      ...options
    };

    try {
      const response = await fetch(url, config);
      const data = await response.json();
      if (!response.ok) {
        throw new Error(data.message || 'Lỗi kết nối tới máy chủ');
      }
      return data;
    } catch (error) {
      console.warn(`[API Error] ${endpoint}:`, error.message);
      // Fallback mock data nếu Backend chưa bật
      return { success: false, error: error.message, isMock: true };
    }
  }

  // --- 1. XÁC THỰC & NGƯỜI DÙNG ---
  async login(email, password) {
    const res = await this.request('/admin/nhan-vien/login', {
      method: 'POST',
      body: JSON.stringify({ email, password })
    });
    if (res && res.token) {
      this.setToken(res.token);
    }
    return res;
  }

  async checkLogin() {
    return this.request('/check-login');
  }

  async getProfile() {
    return this.request('/admin/trang-ca-nhan/thong-tin');
  }

  // --- 2. THỰC ĐƠN & GỌI MÓN (QR BÀN) ---
  async getCategories() {
    return this.request('/menu/danh-muc');
  }

  async getActiveProducts() {
    return this.request('/menu/public');
  }

  async getTables() {
    return this.request('/ban/public');
  }

  async getTableMenu(tableCode) {
    return this.request(`/menu/public`);
  }

  async createQrOrder(orderPayload) {
    return this.request('/orders/qr-create', {
      method: 'POST',
      body: JSON.stringify(orderPayload)
    });
  }

  async getOrderStatus(orderId) {
    return this.request(`/orders/${orderId}/status`);
  }

  // --- 3. THU NGÂN (POS) & DUYỆT ĐƠN (PAY-FIRST) ---
  async getPendingQrOrders() {
    return this.request('/pos/orders/pending');
  }

  async approveOrderPayment(orderId, paymentMethod = 'VIETQR') {
    return this.request(`/pos/orders/${orderId}/approve`, {
      method: 'POST',
      body: JSON.stringify({ payment_method: paymentMethod })
    });
  }

  async openTable(id_ban) {
    return this.request(`/admin/hoa-don/mo-ban/${id_ban}`);
  }

  async getTableInvoice(id_ban) {
    return this.request(`/admin/hoa-don/get-hoa-don/${id_ban}`);
  }

  async addOrderItem(id_ban, id_san_pham, so_luong = 1, don_gia = 0, ghi_chu = '') {
    return this.request('/admin/chi-tiet-hoa-don/them-mon', {
      method: 'POST',
      body: JSON.stringify({
        id_hd: id_ban,
        id_san_pham: id_san_pham,
        so_luong: so_luong,
        don_gia: don_gia,
        ghi_chu: ghi_chu
      })
    });
  }

  async confirmInvoicePayment(id_hoa_don, paymentMethod = 2) {
    return this.request('/admin/hoa-don/xac-nhan-thanh-toan', {
      method: 'POST',
      body: JSON.stringify({
        id_hoa_don: id_hoa_don,
        phuong_thuc_thanh_toan: paymentMethod
      })
    });
  }

  // --- 4. BARISTA (KDS REALTIME) ---
  async getKdsOrders() {
    return this.request('/kds/queue');
  }

  async startMakingOrder(orderId) {
    return this.request(`/kds/orders/${orderId}/start`, { method: 'POST' });
  }

  async completeOrder(orderId) {
    return this.request(`/kds/orders/${orderId}/complete`, { method: 'POST' });
  }

  // --- 5. KHO & ĐỊNH LƯỢNG (BOM) ---
  async getBomRecipes() {
    return this.request('/inventory/recipes');
  }

  async getInventoryItems() {
    return this.request('/inventory/items');
  }

  // --- 6. TRÍ TUỆ NHÂN TẠO (AI) ---
  async askAiBarista(query, userPreferences = {}) {
    return this.request('/ai/barista-chat', {
      method: 'POST',
      body: JSON.stringify({ query, preferences: userPreferences })
    });
  }

  async getAiStockForecast() {
    return this.request('/ai/stock-forecast');
  }

  async askAiCopilot(question) {
    return this.request('/ai/copilot-chat', {
      method: 'POST',
      body: JSON.stringify({ question })
    });
  }
}

// Khởi tạo instance toàn cục
window.coffeeApi = new CoffeeHubApi(API_BASE_URL);
console.log('CoffeeHub API Client initialized. Base URL:', API_BASE_URL);
