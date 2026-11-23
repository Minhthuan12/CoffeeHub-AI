<template>
  <div class="ch-customer-app">
    <!-- TOP HEADER (LIGHT GLASSMORPHISM) -->
    <header class="ch-top-header">
      <div class="container-fluid max-w-app d-flex justify-content-between align-items-center py-2.5 px-3">
        <div class="d-flex align-items-center gap-2.5">
          <div class="ch-logo-badge shadow-xs">
            <i class="fa-solid fa-mug-hot text-amber"></i>
          </div>
          <div>
            <h1 class="ch-brand-title mb-0">Coffee<span class="text-amber">Hub</span></h1>
            <div class="d-flex align-items-center gap-1.5 mt-0.5" v-if="currentTable">
              <span class="ch-table-live-dot"></span>
              <small class="ch-brand-sub fw-semibold text-dark">{{ currentTable.ten_ban }}</small>
            </div>
            <small class="ch-brand-sub text-danger fw-semibold" v-else>
              <i class="fa-solid fa-triangle-exclamation me-1"></i>Chưa định danh bàn
            </small>
          </div>
        </div>

        <div class="d-flex align-items-center gap-2">
          <!-- Active order tracking pill -->
          <button
            v-if="cartStore.activeOrderId"
            class="btn btn-sm btn-outline-amber rounded-pill px-2.5 py-1 tracking-pill pulse-amber shadow-xs"
            @click="showTrackingModal = true"
          >
            <i class="fa-solid fa-clock-rotate-left me-1"></i>
            <span class="d-none d-sm-inline">Tiến độ</span> #{{ cartStore.activeOrderId }}
          </button>

          <!-- Cart button -->
          <button class="btn btn-amber rounded-pill px-3 py-1.5 position-relative shadow-sm ch-cart-btn" @click="showCartDrawer = true">
            <i class="fa-solid fa-bag-shopping me-1.5"></i>
            <span class="fw-bold">{{ formatVND(cartStore.finalTotal) }}</span>
            <span
              v-if="cartStore.totalCount > 0"
              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white shadow-xs ch-badge-bounce"
            >
              {{ cartStore.totalCount }}
            </span>
          </button>
        </div>
      </div>
    </header>

    <!-- FLOATING AI BARISTA BUTTON -->
    <button class="ch-floating-ai-btn shadow-lg" @click="showAiModal = true" title="Hỏi Trợ lý AI Barista">
      <div class="ai-sparkle-ring"></div>
      <i class="fa-solid fa-wand-magic-sparkles"></i>
      <span class="ai-btn-label d-none d-md-inline ms-1">AI Barista</span>
    </button>

    <!-- MAIN BODY -->
    <main class="container-fluid max-w-app py-3 px-3">
      <!-- ERROR ALERT IF TABLE IS INVALID -->
      <div v-if="!currentTable && !loadingTable" class="alert alert-warning border-warning shadow-sm rounded-4 mb-4 p-3">
        <div class="d-flex align-items-start gap-3">
          <i class="fa-solid fa-circle-exclamation fs-3 text-warning mt-1"></i>
          <div class="flex-grow-1">
            <h6 class="fw-bold mb-1">Bạn chưa quét mã QR bàn hợp lệ!</h6>
            <p class="small text-muted mb-2">
              Để hệ thống tự động ghi nhận món về đúng vị trí bàn của bạn, vui lòng quét mã QR đặt tại mặt bàn hoặc chọn nhanh bàn bên dưới để thử nghiệm:
            </p>
            <div class="d-flex flex-wrap gap-2">
              <button
                v-for="b in availableTables.slice(0, 8)"
                :key="b.id"
                class="btn btn-sm btn-outline-dark rounded-pill"
                @click="selectTable(b)"
              >
                {{ b.ten_ban }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- ACTIVE ORDER QUICK BANNER (LIGHT & CLEAN) -->
      <div v-if="cartStore.activeOrderId" class="ch-active-banner p-3 mb-4 rounded-4 shadow-sm animate__animated animate__fadeIn">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
          <div class="d-flex align-items-center gap-3">
            <div class="ch-active-icon-box shadow-xs">
              <i class="fa-solid fa-fire-burner text-amber fs-5"></i>
            </div>
            <div>
              <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0.5 fw-bold">
                  <span class="live-dot-green me-1"></span> Đang xử lý Pay-First
                </span>
                <span class="small fw-bold text-dark">Đơn #{{ cartStore.activeOrderId }}</span>
              </div>
              <small class="text-muted d-block">Barista đang chuẩn bị đồ uống, bấm để xem KDS realtime</small>
            </div>
          </div>
          <button class="btn btn-sm btn-amber rounded-pill px-3 py-1.5 fw-bold shadow-xs" @click="showTrackingModal = true">
            Xem tiến độ <i class="fa-solid fa-chevron-right ms-1"></i>
          </button>
        </div>
      </div>

      <!-- HERO BANNER & AI TEASER (BRIGHT, SUNNY LUXURY STYLE) -->
      <div class="ch-hero-card p-3 p-md-4 rounded-4 mb-4 shadow-sm position-relative overflow-hidden animate__animated animate__fadeIn">
        <div class="position-relative z-1">
          <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-amber-subtle text-amber border border-amber-subtle px-2.5 py-1 rounded-pill fw-bold">
              <i class="fa-solid fa-wand-magic-sparkles me-1 text-amber"></i>AI Barista cá nhân hóa
            </span>
            <span class="small text-muted fw-semibold">Sẵn sàng tư vấn chuẩn vị 24/7</span>
          </div>
          <h2 class="ch-hero-title mb-2">Hôm nay bạn muốn thưởng thức hương vị gì?</h2>
          <p class="ch-hero-desc text-muted mb-3 small">
            Trò chuyện với AI Barista để nhận tư vấn đồ uống chuẩn gu theo tâm trạng và thời tiết hiện tại.
          </p>
          <div class="d-flex flex-wrap gap-2">
            <button class="btn btn-hero-chip btn-sm rounded-pill px-3 py-1.5 fw-semibold shadow-xs" @click="openAiWithPrompt('Trời nắng nóng nên uống gì giải nhiệt?')">
              ☀️ Nắng nóng giải nhiệt
            </button>
            <button class="btn btn-hero-chip btn-sm rounded-pill px-3 py-1.5 fw-semibold shadow-xs" @click="openAiWithPrompt('Tư vấn cà phê kem béo ít ngọt')">
              ☕ Cà phê kem béo
            </button>
            <button class="btn btn-hero-chip btn-sm rounded-pill px-3 py-1.5 fw-semibold shadow-xs" @click="openAiWithPrompt('Trà trái cây thanh mát không quá ngọt')">
              🍓 Trà trái cây tươi
            </button>
          </div>
        </div>
        <div class="ch-hero-art">
          <i class="fa-solid fa-mug-saucer"></i>
        </div>
      </div>

      <!-- SEARCH & FILTER BAR -->
      <div class="ch-search-bar mb-3">
        <div class="input-group bg-white rounded-pill shadow-sm border p-1">
          <span class="input-group-text bg-transparent border-0 text-muted ps-3">
            <i class="fa-solid fa-magnifying-glass"></i>
          </span>
          <input
            type="text"
            class="form-control border-0 bg-transparent shadow-none"
            placeholder="Tìm cà phê, trà sữa, đá xay, bánh ngọt..."
            v-model="searchKeyword"
          />
          <button v-if="searchKeyword" class="btn btn-link text-muted pe-3" @click="searchKeyword = ''">
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>
      </div>

      <!-- CATEGORY TABS -->
      <div class="ch-cat-nav mb-4">
        <div class="d-flex gap-2 overflow-auto pb-2 scrollbar-none">
          <button
            class="btn btn-sm rounded-pill px-3 cat-pill"
            :class="selectedCatId === 0 ? 'btn-amber text-dark fw-bold' : 'btn-light border text-secondary'"
            @click="selectedCatId = 0"
          >
            Tất cả ({{ products.length }})
          </button>
          <button
            v-for="cat in categories"
            :key="cat.id"
            class="btn btn-sm rounded-pill px-3 cat-pill text-nowrap"
            :class="selectedCatId === cat.id ? 'btn-amber text-dark fw-bold' : 'btn-light border text-secondary'"
            @click="selectedCatId = cat.id"
          >
            {{ cat.ten_danh_muc }}
          </button>
        </div>
      </div>

      <!-- PRODUCT GRID -->
      <div v-if="loadingProducts" class="text-center py-5">
        <div class="spinner-border text-amber" role="status"></div>
        <p class="text-muted small mt-2">Đang tải thực đơn CoffeeHub...</p>
      </div>

      <div v-else-if="filteredProducts.length === 0" class="text-center py-5 bg-white rounded-4 border">
        <i class="fa-solid fa-mug-saucer fs-1 text-muted mb-3 opacity-50"></i>
        <h6 class="text-muted fw-bold">Không tìm thấy món phù hợp</h6>
        <p class="small text-muted mb-3">Vui lòng thử từ khóa khác hoặc chuyển danh mục.</p>
        <button class="btn btn-sm btn-outline-dark rounded-pill" @click="searchKeyword = ''; selectedCatId = 0">
          Xem tất cả món
        </button>
      </div>

      <div v-else class="row g-3">
        <div
          v-for="(p, pIdx) in filteredProducts"
          :key="p.id"
          class="col-6 col-md-4 col-lg-3 animate__animated animate__fadeInUp"
          :style="{ animationDelay: `${(pIdx % 8) * 0.05}s` }"
        >
          <div class="card h-100 ch-product-card rounded-4 border-0 shadow-sm overflow-hidden d-flex flex-column">
            <!-- Image wrap -->
            <div class="position-relative ch-card-img-wrap" @click="openCustomizeModal(p)">
              <img
                :src="p.hinh_anh || defaultProductImg"
                :alt="p.ten_san_pham"
                class="card-img-top ch-product-img"
                loading="lazy"
                @error="onImgError"
              />
              <span class="badge bg-white bg-opacity-90 text-dark position-absolute bottom-0 start-0 m-2 rounded-pill px-2.5 py-1 small fw-semibold shadow-xs border">
                Size {{ p.kich_thuoc || 'M' }}
              </span>
            </div>

            <!-- Content -->
            <div class="card-body p-2.5 p-sm-3 d-flex flex-column justify-content-between flex-grow-1">
              <div>
                <h6 class="card-title fw-bold mb-1 ch-product-title" :title="p.ten_san_pham">
                  {{ p.ten_san_pham }}
                </h6>
                <p class="card-text text-muted small mb-2 ch-product-desc">
                  {{ p.mo_ta || 'Hương vị thơm ngon hảo hạng từ hạt cà phê nguyên chất và nguyên liệu cao cấp.' }}
                </p>
              </div>

              <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                <div>
                  <span class="text-muted smaller d-block">Giá từ</span>
                  <span class="fw-bold text-amber fs-6">
                    {{ formatVND(p.gia_ban) }}
                  </span>
                </div>
                <button
                  class="btn btn-amber btn-sm rounded-circle ch-add-btn shadow-sm"
                  @click="openCustomizeModal(p)"
                  title="Tùy chỉnh & Thêm vào giỏ"
                >
                  <i class="fa-solid fa-plus"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- ======================================================== -->
    <!-- MODAL 1: TÙY CHỈNH SIZE, ĐÁ, ĐƯỜNG, TOPPING (UC12)        -->
    <!-- ======================================================== -->
    <div
      v-if="showCustomModal && customizingProduct"
      class="ch-modal-backdrop"
      @click.self="showCustomModal = false"
    >
      <div class="ch-modal-sheet">
        <div class="ch-sheet-header p-3 border-bottom d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center gap-2">
            <img
              :src="customizingProduct.hinh_anh || defaultProductImg"
              class="rounded-3"
              style="width: 46px; height: 46px; object-fit: cover;"
              @error="onImgError"
            />
            <div>
              <h6 class="fw-bold mb-0">{{ customizingProduct.ten_san_pham }}</h6>
              <small class="text-amber fw-bold">{{ formatVND(customUnitPrice) }} / ly</small>
            </div>
          </div>
          <button class="btn-close" @click="showCustomModal = false"></button>
        </div>

        <div class="ch-sheet-body p-3 overflow-auto" style="max-height: 65vh;">
          <!-- CHỌN SIZE -->
          <div class="mb-3">
            <label class="form-label fw-bold small text-uppercase text-muted mb-2">
              1. Chọn kích thước (Size)
            </label>
            <div class="row g-2">
              <div v-for="sz in SIZES" :key="sz.ma" class="col-4">
                <div
                  class="ch-option-card p-2.5 rounded-3 text-center border cursor-pointer"
                  :class="selectedSize.ma === sz.ma ? 'border-amber bg-amber-light active' : 'bg-light'"
                  @click="selectedSize = sz"
                >
                  <div class="fw-bold small">{{ sz.ten }}</div>
                  <div class="text-muted smaller">{{ sz.gia > 0 ? `+${formatVND(sz.gia)}` : 'Gốc' }}</div>
                </div>
              </div>
            </div>
          </div>

          <!-- CHỌN MỨC ĐÁ -->
          <div class="mb-3">
            <label class="form-label fw-bold small text-uppercase text-muted mb-2">
              2. Lượng đá
            </label>
            <div class="d-flex flex-wrap gap-2">
              <button
                v-for="d in DA_OPTIONS"
                :key="d.ma"
                type="button"
                class="btn btn-sm rounded-pill"
                :class="selectedDa.ma === d.ma ? 'btn-dark' : 'btn-outline-secondary'"
                @click="selectedDa = d"
              >
                {{ d.ten }}
              </button>
            </div>
          </div>

          <!-- CHỌN MỨC ĐƯỜNG -->
          <div class="mb-3">
            <label class="form-label fw-bold small text-uppercase text-muted mb-2">
              3. Lượng đường / Ngọt
            </label>
            <div class="d-flex flex-wrap gap-2">
              <button
                v-for="du in DUONG_OPTIONS"
                :key="du.ma"
                type="button"
                class="btn btn-sm rounded-pill"
                :class="selectedDuong.ma === du.ma ? 'btn-dark' : 'btn-outline-secondary'"
                @click="selectedDuong = du"
              >
                {{ du.ten }}
              </button>
            </div>
          </div>

          <!-- CHỌN TOPPING -->
          <div class="mb-3">
            <label class="form-label fw-bold small text-uppercase text-muted mb-2">
              4. Topping thêm thơm ngon
            </label>
            <div class="row g-2">
              <div v-for="top in TOPPINGS" :key="top.id" class="col-6">
                <div
                  class="ch-topping-item p-2 rounded-3 border d-flex justify-content-between align-items-center cursor-pointer"
                  :class="isToppingSelected(top.id) ? 'border-amber bg-amber-light' : 'bg-white'"
                  @click="toggleTopping(top)"
                >
                  <div>
                    <div class="small fw-semibold">{{ top.ten }}</div>
                    <div class="text-amber smaller fw-bold">+{{ formatVND(top.gia) }}</div>
                  </div>
                  <i
                    class="fa-solid"
                    :class="isToppingSelected(top.id) ? 'fa-circle-check text-amber' : 'fa-circle text-muted opacity-25'"
                  ></i>
                </div>
              </div>
            </div>
          </div>

          <!-- GHI CHÚ RIÊNG -->
          <div class="mb-3">
            <label class="form-label fw-bold small text-uppercase text-muted mb-1">
              5. Ghi chú đặc biệt cho Barista
            </label>
            <input
              type="text"
              class="form-control form-control-sm rounded-3"
              placeholder="Ví dụ: Để đá riêng, nhiều sữa đặc, không bọt..."
              v-model="customNote"
            />
          </div>

          <!-- SỐ LƯỢNG LY -->
          <div class="d-flex justify-content-between align-items-center pt-2 border-top">
            <span class="fw-bold small text-muted">Số lượng:</span>
            <div class="input-group input-group-sm" style="width: 120px;">
              <button class="btn btn-outline-secondary" @click="customQuantity = Math.max(1, customQuantity - 1)">
                <i class="fa-solid fa-minus"></i>
              </button>
              <input
                type="text"
                class="form-control text-center fw-bold bg-white"
                v-model="customQuantity"
                readonly
              />
              <button class="btn btn-outline-secondary" @click="customQuantity++">
                <i class="fa-solid fa-plus"></i>
              </button>
            </div>
          </div>
        </div>

        <div class="ch-sheet-footer p-3 border-top bg-light d-flex gap-2">
          <button class="btn btn-outline-secondary rounded-pill w-25" @click="showCustomModal = false">
            Đóng
          </button>
          <button class="btn btn-amber rounded-pill w-75 fw-bold" @click="addCustomizedItemToCart">
            Thêm vào giỏ • {{ formatVND(customUnitPrice * customQuantity) }}
          </button>
        </div>
      </div>
    </div>

    <!-- ======================================================== -->
    <!-- DRAWER 2: GIỎ HÀNG & THANH TOÁN (UC10, 11, 13, 14, 18)   -->
    <!-- ======================================================== -->
    <div
      v-if="showCartDrawer"
      class="ch-modal-backdrop"
      @click.self="showCartDrawer = false"
    >
      <div class="ch-cart-drawer d-flex flex-column">
        <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-white">
          <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-bag-shopping text-amber fs-5"></i>
            <h6 class="fw-bold mb-0">Giỏ hàng của bạn ({{ cartStore.totalCount }})</h6>
          </div>
          <button class="btn-close" @click="showCartDrawer = false"></button>
        </div>

        <!-- Cart items list -->
        <div class="flex-grow-1 p-3 overflow-auto bg-light">
          <div v-if="cartStore.items.length === 0" class="text-center py-5">
            <i class="fa-solid fa-basket-shopping fs-1 text-muted opacity-25 mb-3"></i>
            <h6 class="text-muted fw-bold">Giỏ hàng đang trống</h6>
            <p class="small text-muted mb-3">Hãy chọn những ly thức uống ngon tuyệt từ thực đơn nhé!</p>
            <button class="btn btn-sm btn-amber rounded-pill px-3" @click="showCartDrawer = false">
              Xem thực đơn ngay
            </button>
          </div>

          <div v-else class="d-flex flex-column gap-2.5">
            <div
              v-for="item in cartStore.items"
              :key="item.uid"
              class="bg-white p-3 rounded-4 shadow-sm border position-relative"
            >
              <div class="d-flex gap-2.5">
                <img
                  :src="item.hinh_anh || defaultProductImg"
                  class="rounded-3 ch-cart-thumb"
                  @error="onImgError"
                />
                <div class="flex-grow-1">
                  <div class="d-flex justify-content-between align-items-start">
                    <h6 class="fw-bold mb-0 fs-6">{{ item.ten_san_pham }}</h6>
                    <button class="btn btn-sm text-danger p-0 border-0" @click="cartStore.removeItem(item.uid)">
                      <i class="fa-solid fa-trash-can"></i>
                    </button>
                  </div>

                  <!-- Snapshot options string -->
                  <div class="text-muted smaller my-1" v-if="item.tuy_chon_text">
                    <i class="fa-solid fa-sliders me-1 text-amber"></i>{{ item.tuy_chon_text }}
                  </div>
                  <div class="text-muted smaller" v-if="item.ghi_chu">
                    <i class="fa-regular fa-note-sticky me-1"></i>"{{ item.ghi_chu }}"
                  </div>

                  <div class="d-flex justify-content-between align-items-center mt-2">
                    <span class="text-amber fw-bold">{{ formatVND(item.don_gia) }}</span>
                    <!-- Stepper -->
                    <div class="input-group input-group-sm" style="width: 100px;">
                      <button class="btn btn-outline-secondary py-0" @click="cartStore.updateQuantity(item.uid, -1)">-</button>
                      <span class="input-group-text bg-white fw-bold px-2 py-0 border-secondary">{{ item.so_luong }}</span>
                      <button class="btn btn-outline-secondary py-0" @click="cartStore.updateQuantity(item.uid, 1)">+</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Cart footer / Voucher & Total -->
        <div class="p-3 bg-white border-top shadow-lg" v-if="cartStore.items.length > 0">
          <!-- VOUCHER INPUT (UC18) -->
          <div class="mb-3">
            <div class="d-flex gap-2">
              <input
                type="text"
                class="form-control form-control-sm text-uppercase rounded-pill border"
                placeholder="Nhập mã voucher (Ví dụ: BANMOI)"
                v-model="voucherInput"
                :disabled="!!cartStore.voucher"
              />
              <button
                v-if="!cartStore.voucher"
                class="btn btn-sm btn-dark rounded-pill px-3 fw-semibold text-nowrap"
                :disabled="checkingVoucher || !voucherInput.trim()"
                @click="checkAndApplyVoucher"
              >
                <span v-if="checkingVoucher" class="spinner-border spinner-border-sm me-1"></span>
                Áp dụng
              </button>
              <button
                v-else
                class="btn btn-sm btn-outline-danger rounded-pill px-3"
                @click="removeVoucher"
              >
                Hủy
              </button>
            </div>
            <small v-if="cartStore.voucher" class="text-success fw-semibold d-block mt-1">
              <i class="fa-solid fa-tag me-1"></i>Đã áp dụng mã <strong>{{ cartStore.voucher.ma_voucher }}</strong>: Giảm {{ formatVND(cartStore.discountAmount) }}
            </small>
            <small v-if="voucherError" class="text-danger d-block mt-1">
              <i class="fa-solid fa-circle-exclamation me-1"></i>{{ voucherError }}
            </small>
          </div>

          <!-- TOTAL SUMMARY -->
          <div class="small mb-2 d-flex justify-content-between text-muted">
            <span>Tạm tính ({{ cartStore.totalCount }} ly):</span>
            <span>{{ formatVND(cartStore.subtotal) }}</span>
          </div>
          <div v-if="cartStore.discountAmount > 0" class="small mb-2 d-flex justify-content-between text-success fw-semibold">
            <span>Giảm giá Voucher:</span>
            <span>-{{ formatVND(cartStore.discountAmount) }}</span>
          </div>
          <div class="fs-5 fw-bold d-flex justify-content-between text-dark mb-3 pt-2 border-top">
            <span>Tổng thanh toán:</span>
            <span class="text-amber">{{ formatVND(cartStore.finalTotal) }}</span>
          </div>

          <!-- BUTTON OPEN CHECKOUT MODAL -->
          <button class="btn btn-amber w-100 py-2.5 rounded-pill fw-bold fs-6 shadow-sm" @click="openCheckoutModal">
            Đặt món & Thanh toán Pay-First <i class="fa-solid fa-arrow-right ms-1"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL 3: XÁC NHẬN THANH TOÁN (VIETQR / TIỀN MẶT - UC09, 15, 16, 17) -->
    <!-- ======================================================== -->
    <div
      v-if="showCheckoutModal"
      class="ch-modal-backdrop"
      @click.self="showCheckoutModal = false"
    >
      <div class="ch-modal-dialog bg-white rounded-4 shadow-xl overflow-hidden">
        <div class="p-3 px-4 border-bottom d-flex justify-content-between align-items-center bg-white">
          <div class="d-flex align-items-center gap-2.5">
            <div class="ch-badge-icon bg-amber-subtle text-amber">
              <i class="fa-solid fa-credit-card"></i>
            </div>
            <div>
              <h6 class="fw-bold mb-0 text-dark fs-6">Xác nhận đơn & Thanh toán</h6>
              <small class="text-muted">Quy trình Pay-First tự động</small>
            </div>
          </div>
          <button class="btn-close shadow-none" @click="showCheckoutModal = false"></button>
        </div>

        <div class="p-3 px-4">
          <!-- Thông tin khách -->
          <div class="mb-3">
            <label class="form-label small text-muted fw-bold mb-1.5 d-flex justify-content-between">
              <span>Số điện thoại liên hệ</span>
              <span class="fw-normal text-muted smaller">Tùy chọn</span>
            </label>
            <div class="input-group input-group-sm">
              <span class="input-group-text bg-light border-end-0 text-muted rounded-start-3 ps-3">
                <i class="fa-solid fa-phone"></i>
              </span>
              <input
                type="tel"
                class="form-control form-control-sm border-start-0 rounded-end-3 py-2 shadow-none"
                placeholder="Nhập SĐT để tích điểm & nhận hóa đơn điện tử..."
                v-model="customerPhone"
              />
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label small text-muted fw-bold mb-1.5 d-flex justify-content-between">
              <span>Tên quý khách</span>
              <span class="fw-normal text-muted smaller">Để nhân viên xưng hô</span>
            </label>
            <div class="input-group input-group-sm">
              <span class="input-group-text bg-light border-end-0 text-muted rounded-start-3 ps-3">
                <i class="fa-solid fa-user"></i>
              </span>
              <input
                type="text"
                class="form-control form-control-sm border-start-0 rounded-end-3 py-2 shadow-none"
                placeholder="Tên của bạn (Ví dụ: Anh Nam, Chị Linh)..."
                v-model="customerName"
              />
            </div>
          </div>

          <!-- CHỌN PHƯƠNG THỨC -->
          <label class="form-label small text-muted fw-bold mb-2">Chọn phương thức thanh toán</label>
          <div class="d-flex flex-column gap-2 mb-3">
            <label
              class="ch-pay-option p-3 rounded-4 border d-flex align-items-center gap-3 cursor-pointer"
              :class="paymentMethod === 'VIETQR' ? 'border-amber bg-amber-light shadow-xs' : 'bg-white'"
            >
              <input type="radio" value="VIETQR" v-model="paymentMethod" class="form-check-input mt-0" />
              <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-center mb-0.5">
                  <span class="fw-bold small text-dark d-flex align-items-center gap-1.5">
                    <i class="fa-solid fa-qrcode text-amber"></i> Chuyển khoản VietQR
                  </span>
                  <span class="badge bg-amber text-white smaller rounded-pill px-2">Khuyên dùng</span>
                </div>
                <div class="text-muted smaller">Quét mã QR Mobile Banking chuyển khoản chính xác 100%</div>
              </div>
            </label>

            <label
              class="ch-pay-option p-3 rounded-4 border d-flex align-items-center gap-3 cursor-pointer"
              :class="paymentMethod === 'TIEN_MAT' ? 'border-amber bg-amber-light shadow-xs' : 'bg-white'"
            >
              <input type="radio" value="TIEN_MAT" v-model="paymentMethod" class="form-check-input mt-0" />
              <div class="flex-grow-1">
                <div class="fw-bold small text-dark d-flex align-items-center gap-1.5 mb-0.5">
                  <i class="fa-solid fa-money-bill-wave text-success"></i> Tiền mặt tại bàn
                </div>
                <div class="text-muted smaller">Thu ngân sẽ tới tận bàn để nhận tiền mặt và xác nhận đơn</div>
              </div>
            </label>
          </div>

          <!-- TỔNG TIỀN THANH TOÁN -->
          <div class="ch-checkout-summary-box p-3 rounded-4 mb-3 d-flex justify-content-between align-items-center">
            <div>
              <span class="smaller text-muted d-block fw-semibold text-uppercase">Số tiền cần thanh toán</span>
              <small class="text-muted">Đã bao gồm topping & voucher</small>
            </div>
            <span class="fs-4 fw-extrabold text-amber">{{ formatVND(cartStore.finalTotal) }}</span>
          </div>

          <div v-if="orderError" class="alert alert-danger small p-2.5 rounded-3 mb-3 d-flex align-items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span>{{ orderError }}</span>
          </div>

          <button
            class="btn btn-amber w-100 py-2.5 rounded-pill fw-bold shadow-sm fs-6"
            :disabled="submittingOrder"
            @click="submitOrder"
          >
            <span v-if="submittingOrder" class="spinner-border spinner-border-sm me-1.5"></span>
            Xác nhận gọi món • {{ formatVND(cartStore.finalTotal) }}
          </button>
        </div>
      </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL 4: HIỂN THỊ VIETQR & TIẾN TRÌNH THEO DÕI (UC16, 19) -->
    <!-- ======================================================== -->
    <div
      v-if="showTrackingModal"
      class="ch-modal-backdrop"
      @click.self="showTrackingModal = false"
    >
      <div class="ch-modal-dialog bg-white rounded-4 shadow-xl overflow-hidden">
        <div class="p-3 px-4 border-bottom d-flex justify-content-between align-items-center bg-white">
          <div class="d-flex align-items-center gap-2.5">
            <div class="ch-badge-icon bg-success-subtle text-success">
              <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
              <h6 class="fw-bold mb-0 text-dark fs-6">Đơn hàng #{{ cartStore.activeOrderId }}</h6>
              <small class="text-muted">Trạng thái cập nhật tự động realtime</small>
            </div>
          </div>
          <button class="btn-close shadow-none" @click="showTrackingModal = false"></button>
        </div>

        <div class="p-3 px-4 overflow-auto" style="max-height: 80vh;">
          <!-- NẾU CHƯA THANH TOÁN & LÀ VIETQR THÌ HIỆN MÃ QR -->
          <div
            v-if="activeOrderState && !activeOrderState.is_paid && activeOrderState.order.phuong_thuc_thanh_toan == 2"
            class="text-center p-3 mb-3 bg-amber-light rounded-4 border border-amber"
          >
            <span class="badge bg-warning text-dark mb-2 fw-bold">Bước 1: Quét mã thanh toán Pay-First</span>
            <h6 class="fw-bold text-dark mb-1">Mã hóa đơn: {{ activeOrderState.order.ma_hoa_don }}</h6>
            <div class="text-danger fw-bold fs-5 mb-2">{{ formatVND(activeOrderState.order.tong_tien) }}</div>

            <!-- VietQR Image -->
            <div class="bg-white p-2 rounded-3 d-inline-block shadow-sm mb-2 border">
              <img
                :src="activeVietQrUrl"
                alt="VietQR Pay-First"
                class="img-fluid"
                style="max-width: 230px;"
              />
            </div>
            <p class="smaller text-muted mb-0">
              Vui lòng giữ nguyên nội dung chuyển khoản để hệ thống tự động đối soát và đẩy lệnh pha chế xuống quầy Barista.
            </p>
          </div>

          <!-- NẾU CHỌN TIỀN MẶT VÀ CHƯA THANH TOÁN -->
          <div
            v-else-if="activeOrderState && !activeOrderState.is_paid && activeOrderState.order.phuong_thuc_thanh_toan == 1"
            class="text-center p-3 mb-3 bg-light rounded-4 border"
          >
            <i class="fa-solid fa-person-walking-luggage fs-1 text-amber mb-2"></i>
            <h6 class="fw-bold text-dark mb-1">Đang chờ thu ngân thu tiền tại bàn</h6>
            <p class="small text-muted mb-0">
              Thu ngân đang chuẩn bị tới bàn <strong>{{ currentTable ? currentTable.ten_ban : '' }}</strong> để nhận số tiền <strong>{{ formatVND(activeOrderState.order.tong_tien) }}</strong>.
            </p>
          </div>

          <!-- TIMELINE TIẾN TRÌNH THEO DÕI REALTIME (UC19) -->
          <div class="mb-3 p-3 bg-light rounded-4 border">
            <h6 class="fw-bold small text-muted text-uppercase mb-3">Tiến độ phục vụ</h6>

            <div class="ch-timeline">
              <!-- Step 1 -->
              <div class="ch-timeline-step" :class="{ 'step-active': trackingStep === 1, 'step-done': trackingStep > 1 }">
                <div class="step-dot">
                  <i class="fa-solid" :class="trackingStep > 1 ? 'fa-check' : 'fa-receipt'"></i>
                </div>
                <div class="step-content">
                  <div class="fw-bold small">Tiếp nhận đơn hàng</div>
                  <div class="smaller text-muted">
                    {{ activeOrderState && activeOrderState.is_paid ? 'Đã xác nhận thanh toán Pay-First' : 'Đang chờ xác nhận thanh toán' }}
                  </div>
                </div>
              </div>

              <!-- Step 2 -->
              <div class="ch-timeline-step" :class="{ 'step-active': trackingStep === 2, 'step-done': trackingStep > 2 }">
                <div class="step-dot">
                  <i class="fa-solid" :class="trackingStep > 2 ? 'fa-check' : 'fa-fire-burner'"></i>
                </div>
                <div class="step-content">
                  <div class="fw-bold small">Quầy Barista pha chế</div>
                  <div class="smaller text-muted">
                    {{ trackingStep > 2 ? 'Barista đã hoàn tất pha chế' : (trackingStep === 2 ? 'Barista đang chuẩn bị nguyên liệu và pha chế' : 'Chờ chuyển sang quầy pha chế') }}
                  </div>
                </div>
              </div>

              <!-- Step 3 -->
              <div class="ch-timeline-step" :class="{ 'step-active': trackingStep === 3, 'step-done': trackingStep >= 3 }">
                <div class="step-dot">
                  <i class="fa-solid" :class="trackingStep >= 3 ? 'fa-check' : 'fa-mug-hot'"></i>
                </div>
                <div class="step-content">
                  <div class="fw-bold small">Đồ uống hoàn tất</div>
                  <div class="smaller text-muted">
                    {{ trackingStep >= 3 ? 'Đồ uống đã pha xong, nhân viên đang mang tới bàn!' : 'Chờ hoàn tất ly nước' }}
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- DANH SÁCH MÓN ĐANG PHA -->
          <div v-if="activeOrderState && activeOrderState.items" class="mb-3">
            <h6 class="fw-bold small text-muted text-uppercase mb-2">Chi tiết món trong đơn</h6>
            <div class="d-flex flex-column gap-2">
              <div
                v-for="it in activeOrderState.items"
                :key="it.id"
                class="p-2.5 rounded-3 bg-white border d-flex justify-content-between align-items-center"
              >
                <div>
                  <div class="fw-bold small">{{ it.ten_san_pham }} x{{ it.so_luong }}</div>
                  <div class="text-muted smaller" v-if="it.ghi_chu">{{ it.ghi_chu }}</div>
                </div>
                <span
                  class="badge rounded-pill"
                  :class="it.tinh_trang == 2 ? 'bg-success' : (it.tinh_trang == 1 ? 'bg-warning text-dark' : 'bg-secondary')"
                >
                  {{ it.tinh_trang == 2 ? 'Đã xong' : (it.tinh_trang == 1 ? 'Đang pha' : 'Chờ duyệt') }}
                </span>
              </div>
            </div>
          </div>

          <button class="btn btn-outline-dark w-100 rounded-pill py-2 small fw-semibold" @click="showTrackingModal = false">
            Tiếp tục xem thực đơn
          </button>
        </div>
      </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL 5: TRỢ LÝ AI BARISTA TƯ VẤN KHẨU VỊ (UC08)          -->
    <!-- ======================================================== -->
    <div
      v-if="showAiModal"
      class="ch-modal-backdrop"
      @click.self="showAiModal = false"
    >
      <div class="ch-ai-chat-box bg-white rounded-4 shadow-2xl overflow-hidden d-flex flex-column">
        <!-- AI Header (Bright Sunrise Gradient) -->
        <div class="p-3 bg-gradient-sunrise text-white d-flex justify-content-between align-items-center shadow-xs">
          <div class="d-flex align-items-center gap-2.5">
            <div class="ai-avatar-pulse">
              <i class="fa-solid fa-wand-magic-sparkles"></i>
            </div>
            <div>
              <h6 class="fw-bold mb-0">Trợ Lý AI Barista</h6>
              <small class="text-white-50">Tư vấn đồ uống cá nhân hóa chuẩn gu</small>
            </div>
          </div>
          <button class="btn-close btn-close-white" @click="showAiModal = false"></button>
        </div>

        <!-- Chat messages stream -->
        <div class="flex-grow-1 p-3 overflow-auto ch-chat-stream bg-slate-light" ref="chatStream">
          <div
            v-for="(msg, i) in aiMessages"
            :key="i"
            class="mb-3 d-flex"
            :class="msg.sender === 'user' ? 'justify-content-end' : 'justify-content-start'"
          >
            <div
              class="ch-chat-bubble p-3 rounded-4 shadow-xs"
              :class="msg.sender === 'user' ? 'bubble-user bg-gradient-amber-btn text-white' : 'bubble-ai bg-white border text-dark'"
            >
              <div class="d-flex align-items-center gap-1.5 mb-1 text-amber smaller fw-bold" v-if="msg.sender === 'ai'">
                <i class="fa-solid fa-robot"></i> AI Barista
              </div>
              <p class="small mb-2 whitespace-pre-line">{{ msg.text }}</p>

              <!-- If AI returned drink recommendations -->
              <div v-if="msg.recommendations && msg.recommendations.length > 0" class="mt-2 pt-2 border-top">
                <div class="smaller fw-bold text-muted mb-1.5">Gợi ý dành riêng cho bạn:</div>
                <div class="d-flex flex-column gap-1.5">
                  <div
                    v-for="rec in msg.recommendations"
                    :key="rec.id"
                    class="d-flex justify-content-between align-items-center bg-light p-2 rounded-3 border"
                  >
                    <div>
                      <div class="small fw-bold text-dark">{{ rec.ten_san_pham }}</div>
                      <div class="text-amber smaller fw-bold">{{ formatVND(rec.gia_ban) }}</div>
                    </div>
                    <button class="btn btn-sm btn-amber rounded-pill px-2.5 py-1 smaller fw-bold" @click="pickAiRecommended(rec)">
                      Chọn món này
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Loading message from AI -->
          <div v-if="aiLoading" class="d-flex justify-content-start mb-3">
            <div class="bg-white p-3 rounded-4 border shadow-sm small text-muted d-flex align-items-center gap-2">
              <span class="spinner-grow spinner-grow-sm text-amber" role="status"></span>
              <span>AI Barista đang đọc thực đơn và pha chế gợi ý...</span>
            </div>
          </div>
        </div>

        <!-- Quick suggestion prompt chips -->
        <div class="px-3 py-2 bg-white border-top overflow-auto d-flex gap-1.5 scrollbar-none">
          <button
            v-for="(chip, cIdx) in quickChips"
            :key="cIdx"
            class="btn btn-sm btn-light border rounded-pill px-2.5 py-0.5 smaller text-nowrap"
            @click="sendAiMessage(chip)"
          >
            {{ chip }}
          </button>
        </div>

        <!-- Chat input bar -->
        <div class="p-2.5 bg-white border-top d-flex gap-2 align-items-center">
          <input
            type="text"
            class="form-control rounded-pill form-control-sm px-3 shadow-none border"
            placeholder="Hỏi về sở thích, vị giác, thời tiết hôm nay..."
            v-model="aiInputText"
            @keyup.enter="sendAiMessage()"
            :disabled="aiLoading"
          />
          <button
            class="btn btn-amber btn-sm rounded-circle px-2.5 py-2 shadow-sm"
            @click="sendAiMessage()"
            :disabled="aiLoading || !aiInputText.trim()"
          >
            <i class="fa-solid fa-paper-plane"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { useCartStore } from '../../../stores/cart';
import { SIZES, DA_OPTIONS, DUONG_OPTIONS, TOPPINGS, formatTuyChonText } from '../../../config/tuyChon';

export default {
  name: 'CustomerMenuView',
  setup() {
    const cartStore = useCartStore();
    return { cartStore };
  },
  data() {
    return {
      // Cấu hình hằng số nhập từ file config tập trung
      SIZES,
      DA_OPTIONS,
      DUONG_OPTIONS,
      TOPPINGS,

      // Dữ liệu bàn & thực đơn
      currentTable: null,
      availableTables: [],
      loadingTable: true,
      categories: [],
      products: [],
      loadingProducts: true,
      selectedCatId: 0,
      searchKeyword: '',
      defaultProductImg: 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?q=80&w=600&auto=format&fit=crop',

      // Modal Tùy chỉnh món (UC12)
      showCustomModal: false,
      customizingProduct: null,
      selectedSize: SIZES[0],
      selectedDa: DA_OPTIONS[0],
      selectedDuong: DUONG_OPTIONS[0],
      selectedToppings: [],
      customQuantity: 1,
      customNote: '',

      // Giỏ hàng & Drawer (UC10, 11, 13, 14)
      showCartDrawer: false,
      voucherInput: '',
      checkingVoucher: false,
      voucherError: '',

      // Màn hình Thanh toán (UC09, 15, 16, 17)
      showCheckoutModal: false,
      customerPhone: '',
      customerName: '',
      paymentMethod: 'VIETQR',
      submittingOrder: false,
      orderError: '',

      // Màn hình Theo dõi đơn Realtime (UC19)
      showTrackingModal: false,
      activeOrderState: null,
      pollingTimer: null,

      // Trợ lý AI Barista (UC08)
      showAiModal: false,
      aiSessionId: null,
      aiInputText: '',
      aiLoading: false,
      aiMessages: [
        {
          sender: 'ai',
          text: 'Xin chào! Mình là Trợ lý AI Barista của CoffeeHub. Hôm nay bạn đang có tâm trạng thế nào, hoặc muốn thử hương vị cà phê đậm đà, kem béo ngọt ngào hay trà trái cây thanh mát giải nhiệt?'
        }
      ],
      quickChips: [
        'Trời nắng nóng nên uống gì giải nhiệt?',
        'Tư vấn cà phê kem béo ít ngọt',
        'Trà trái cây thanh mát',
        'Thức uống không có caffein'
      ]
    };
  },
  computed: {
    filteredProducts() {
      let list = this.products;
      if (this.selectedCatId > 0) {
        list = list.filter(p => Number(p.id_danh_muc) === Number(this.selectedCatId));
      }
      if (this.searchKeyword.trim() !== '') {
        const kw = this.searchKeyword.toLowerCase().trim();
        list = list.filter(p => p.ten_san_pham && p.ten_san_pham.toLowerCase().includes(kw));
      }
      return list;
    },

    customUnitPrice() {
      if (!this.customizingProduct) return 0;
      const base = Number(this.customizingProduct.gia_ban) || 0;
      const sizePhuThu = Number(this.selectedSize.gia) || 0;
      const toppingsPhuThu = this.selectedToppings.reduce((sum, t) => sum + (Number(t.gia) || 0), 0);
      return base + sizePhuThu + toppingsPhuThu;
    },

    activeVietQrUrl() {
      if (!this.activeOrderState || !this.activeOrderState.order) return '';
      const amount = this.activeOrderState.order.tong_tien;
      const code = this.activeOrderState.order.ma_hoa_don;
      return `https://img.vietqr.io/image/MB-06468111-compact2.png?amount=${amount}&addInfo=${encodeURIComponent(code)}&accountName=VO%20MINH%20THUAN`;
    },

    trackingStep() {
      if (!this.activeOrderState) return 1;
      return this.activeOrderState.step || 1;
    }
  },
  mounted() {
    this.detectAndInitTable();
    this.fetchMenuData();

    // Nếu đã có activeOrderId thì khởi động polling kiểm tra tiến độ
    if (this.cartStore.activeOrderId) {
      this.pollOrderStatus(this.cartStore.activeOrderId);
    }
  },
  beforeUnmount() {
    this.stopPolling();
  },
  methods: {
    formatVND(val) {
      return (Number(val) || 0).toLocaleString('vi-VN') + 'đ';
    },

    onImgError(e) {
      e.target.src = this.defaultProductImg;
    },

    /**
     * Nhận diện bàn từ URL (?ban=4 hoặc param) và khởi tạo
     */
    detectAndInitTable() {
      this.loadingTable = true;
      const queryBan = this.$route.query.ban || this.$route.query.id_ban || this.$route.query.table;

      axios.get('http://127.0.0.1:8000/api/ban/public')
        .then(res => {
          this.availableTables = res.data.data || [];
          if (queryBan) {
            const found = this.availableTables.find(b => String(b.id) === String(queryBan) || b.ten_ban.toLowerCase().includes(String(queryBan).toLowerCase()));
            if (found) {
              this.currentTable = found;
              this.cartStore.initTable(found.id, found.ten_ban);
            } else {
              // Bàn không hợp lệ
              this.currentTable = null;
            }
          } else {
            // Chưa có query bàn, kiểm tra localStorage
            const savedBanId = localStorage.getItem('coffeehub_last_table_id');
            if (savedBanId) {
              const f = this.availableTables.find(b => String(b.id) === String(savedBanId));
              if (f) {
                this.currentTable = f;
                this.cartStore.initTable(f.id, f.ten_ban);
              }
            }
          }
        })
        .catch(err => {
          console.error('Lỗi tải danh sách bàn:', err);
        })
        .finally(() => {
          this.loadingTable = false;
        });
    },

    selectTable(ban) {
      this.currentTable = ban;
      localStorage.setItem('coffeehub_last_table_id', ban.id);
      this.cartStore.initTable(ban.id, ban.ten_ban);
      this.$router.replace({ query: { ban: ban.id } }).catch(() => {});
    },

    /**
     * Lấy danh mục và sản phẩm đang bán từ Backend thật
     */
    fetchMenuData() {
      this.loadingProducts = true;
      Promise.all([
        axios.get('http://127.0.0.1:8000/api/menu/danh-muc'),
        axios.get('http://127.0.0.1:8000/api/menu/public')
      ])
        .then(([catRes, prodRes]) => {
          this.categories = catRes.data.data || [];
          this.products = prodRes.data.data || [];
        })
        .catch(err => {
          console.error('Lỗi tải thực đơn:', err);
          if (this.$toast) this.$toast.error('Không thể tải thực đơn. Vui lòng thử lại!');
        })
        .finally(() => {
          this.loadingProducts = false;
        });
    },

    /**
     * Mở modal tùy chỉnh món
     */
    openCustomizeModal(product) {
      this.customizingProduct = product;
      this.selectedSize = this.SIZES[0];
      this.selectedDa = this.DA_OPTIONS[0];
      this.selectedDuong = this.DUONG_OPTIONS[0];
      this.selectedToppings = [];
      this.customQuantity = 1;
      this.customNote = '';
      this.showCustomModal = true;
    },

    isToppingSelected(id) {
      return this.selectedToppings.some(t => t.id === id);
    },

    toggleTopping(top) {
      const idx = this.selectedToppings.findIndex(t => t.id === top.id);
      if (idx > -1) {
        this.selectedToppings.splice(idx, 1);
      } else {
        this.selectedToppings.push(top);
      }
    },

    addCustomizedItemToCart() {
      if (!this.currentTable) {
        alert('Vui lòng quét hoặc chọn bàn trước khi thêm món!');
        return;
      }

      const sizePhuThu = Number(this.selectedSize.gia) || 0;
      const toppingsPhuThu = this.selectedToppings.reduce((sum, t) => sum + (Number(t.gia) || 0), 0);

      const tuyChonSnapshot = {
        size: {
          ma: this.selectedSize.ma,
          ten: this.selectedSize.ten,
          gia: sizePhuThu
        },
        da: {
          ma: this.selectedDa.ma,
          ten: this.selectedDa.ten,
          gia: 0
        },
        duong: {
          ma: this.selectedDuong.ma,
          ten: this.selectedDuong.ten,
          gia: 0
        },
        toppings: this.selectedToppings.map(t => ({
          id: t.id,
          ten: t.ten,
          gia: t.gia
        })),
        phu_thu: sizePhuThu + toppingsPhuThu
      };

      this.cartStore.addItem(
        this.customizingProduct,
        tuyChonSnapshot,
        this.customQuantity,
        this.customNote
      );

      this.showCustomModal = false;
      if (this.$toast) {
        this.$toast.success(`Đã thêm ${this.customQuantity}x ${this.customizingProduct.ten_san_pham} vào giỏ!`);
      }
    },

    /**
     * Kiểm tra và áp dụng mã giảm giá voucher (UC18)
     */
    checkAndApplyVoucher() {
      const code = this.voucherInput.trim().toUpperCase();
      if (!code) return;
      this.checkingVoucher = true;
      this.voucherError = '';

      axios.post('http://127.0.0.1:8000/api/voucher/check', {
        ma_voucher: code,
        tong_tien: this.cartStore.subtotal
      })
        .then(res => {
          if (res.data.status === 1) {
            this.cartStore.applyVoucher({
              ma_voucher: code,
              so_tien_giam: res.data.so_tien_giam,
              loai_giam: res.data.loai_giam,
              so_giam_gia: res.data.so_giam_gia
            });
            if (this.$toast) this.$toast.success(res.data.message);
          } else {
            this.voucherError = res.data.message || 'Mã voucher không hợp lệ';
          }
        })
        .catch(err => {
          this.voucherError = err.response?.data?.message || 'Có lỗi xảy ra khi kiểm tra voucher';
        })
        .finally(() => {
          this.checkingVoucher = false;
        });
    },

    removeVoucher() {
      this.cartStore.removeVoucher();
      this.voucherInput = '';
      this.voucherError = '';
    },

    openCheckoutModal() {
      if (!this.currentTable) {
        alert('Vui lòng quét hoặc chọn bàn!');
        return;
      }
      if (this.cartStore.items.length === 0) {
        alert('Giỏ hàng đang trống!');
        return;
      }
      this.customerPhone = this.cartStore.customerInfo.phone || '';
      this.customerName = this.cartStore.customerInfo.name || '';
      this.orderError = '';
      this.showCartDrawer = false;
      this.showCheckoutModal = true;
    },

    /**
     * Gửi đơn gọi món Pay-First lên Backend (UC09, 15, 16, 17)
     */
    submitOrder() {
      this.submittingOrder = true;
      this.orderError = '';

      const payload = {
        id_ban: this.currentTable.id,
        items: this.cartStore.items.map(i => ({
          id_san_pham: i.id_san_pham,
          so_luong: i.so_luong,
          don_gia: i.don_gia,
          tuy_chon: i.tuy_chon,
          ghi_chu: i.ghi_chu
        })),
        phuong_thuc: this.paymentMethod,
        ma_voucher: this.cartStore.voucher ? this.cartStore.voucher.ma_voucher : null,
        customer_phone: this.customerPhone.trim(),
        customer_name: this.customerName.trim()
      };

      axios.post('http://127.0.0.1:8000/api/orders/qr-create', payload)
        .then(res => {
          if (res.data.status === 1) {
            // Lưu active order và dọn giỏ hàng
            this.cartStore.setActiveOrder(res.data);
            this.cartStore.clearCart();
            this.showCheckoutModal = false;

            if (this.$toast) this.$toast.success(res.data.message);

            // Bắt đầu polling tiến độ
            this.pollOrderStatus(res.data.order_id);
            this.showTrackingModal = true;
          } else {
            this.orderError = res.data.message || 'Lỗi đặt đơn hàng!';
          }
        })
        .catch(err => {
          this.orderError = err.response?.data?.message || 'Có lỗi xảy ra khi tạo đơn hàng!';
        })
        .finally(() => {
          this.submittingOrder = false;
        });
    },

    /**
     * Polling theo dõi trạng thái đơn hàng (UC19)
     */
    pollOrderStatus(orderId) {
      this.stopPolling();
      const check = () => {
        axios.get(`http://127.0.0.1:8000/api/orders/${orderId}/status`)
          .then(res => {
            if (res.data.status === 1) {
              this.activeOrderState = res.data;
            }
          })
          .catch(() => {});
      };

      check();
      this.pollingTimer = setInterval(check, 3500);
    },

    stopPolling() {
      if (this.pollingTimer) {
        clearInterval(this.pollingTimer);
        this.pollingTimer = null;
      }
    },

    /**
     * Trợ lý AI Barista (UC08)
     */
    openAiWithPrompt(prompt) {
      this.showAiModal = true;
      this.sendAiMessage(prompt);
    },

    sendAiMessage(customText) {
      const text = customText || this.aiInputText.trim();
      if (!text || this.aiLoading) return;

      this.aiMessages.push({
        sender: 'user',
        text
      });
      this.aiInputText = '';
      this.aiLoading = true;

      this.$nextTick(() => {
        this.scrollChatToBottom();
      });

      axios.post('http://127.0.0.1:8000/api/ai/chat', {
        message: text,
        session_id: this.aiSessionId,
        table_id: this.currentTable ? this.currentTable.id : null
      })
        .then(res => {
          if (res.data.status === 1) {
            if (res.data.session_id) {
              this.aiSessionId = res.data.session_id;
            }
            this.aiMessages.push({
              sender: 'ai',
              text: res.data.reply || 'Dưới đây là một số gợi ý phù hợp nhất với yêu cầu của bạn:',
              recommendations: res.data.recommendations || []
            });
          } else {
            this.aiMessages.push({
              sender: 'ai',
              text: 'Xin lỗi bạn, AI Barista đang bận một chút. Bạn có thể chọn các món ngon trên thực đơn nhé!'
            });
          }
        })
        .catch(() => {
          this.aiMessages.push({
            sender: 'ai',
            text: 'Không thể kết nối tới máy chủ AI lúc này. Hãy chọn món theo danh mục trên màn hình nhé!'
          });
        })
        .finally(() => {
          this.aiLoading = false;
          this.$nextTick(() => {
            this.scrollChatToBottom();
          });
        });
    },

    scrollChatToBottom() {
      if (this.$refs.chatStream) {
        this.$refs.chatStream.scrollTop = this.$refs.chatStream.scrollHeight;
      }
    },

    pickAiRecommended(rec) {
      this.showAiModal = false;
      const full = this.products.find(p => p.id === rec.id) || rec;
      this.openCustomizeModal(full);
    }
  }
};
</script>

<style scoped>
/* ======================================================== */
/* BRIGHT LUXURY CAFE THEME (LIGHT & ANIMATED)              */
/* CoffeeHub AI - CDIO 4                                    */
/* ======================================================== */

.ch-customer-app {
  background: #f8fafc;
  background-image: radial-gradient(ellipse at 20% 0%, rgba(254, 243, 199, 0.45) 0%, rgba(255, 255, 255, 0.8) 50%, #f8fafc 100%);
  min-height: 100vh;
  font-family: 'Outfit', 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
  color: #1e293b;
  padding-bottom: 95px;
}

.max-w-app {
  max-width: 1024px;
  margin: 0 auto;
}

/* BRAND ACCENT COLORS */
.text-amber {
  color: #d97706 !important;
}

.bg-amber {
  background-color: #d97706 !important;
  color: #ffffff !important;
}

.btn-amber {
  background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
  color: #ffffff;
  border: none;
  font-weight: 600;
  transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
}

.btn-amber:hover {
  background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
  color: #ffffff;
  transform: translateY(-1px);
  box-shadow: 0 4px 14px rgba(217, 119, 6, 0.3);
}

.btn-amber:active {
  transform: scale(0.96);
}

.btn-outline-amber {
  border: 1.5px solid #d97706;
  color: #d97706;
  background: #fffbeb;
  font-weight: 600;
  transition: all 0.2s ease;
}

.btn-outline-amber:hover {
  background: #d97706;
  color: #ffffff;
}

.bg-amber-light {
  background-color: #fffbeb !important;
}

.bg-amber-subtle {
  background-color: #fef3c7 !important;
}

.border-amber {
  border-color: #f59e0b !important;
}

.border-amber-subtle {
  border-color: #fde68a !important;
}

.shadow-xs {
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

/* TOP HEADER (FROSTED GLASS) */
.ch-top-header {
  background: rgba(255, 255, 255, 0.92);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  border-bottom: 1px solid rgba(226, 232, 240, 0.9);
  box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04);
  position: sticky;
  top: 0;
  z-index: 100;
  transition: all 0.3s ease;
}

.ch-logo-badge {
  width: 42px;
  height: 42px;
  border-radius: 14px;
  background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
  box-shadow: 0 2px 10px rgba(245, 158, 11, 0.2);
  transition: transform 0.2s ease;
}

.ch-logo-badge:hover {
  transform: rotate(-6deg) scale(1.05);
}

.ch-brand-title {
  font-size: 1.25rem;
  font-weight: 800;
  letter-spacing: -0.5px;
  color: #0f172a;
}

.ch-table-live-dot {
  display: inline-block;
  width: 7px;
  height: 7px;
  border-radius: 50%;
  background: #10b981;
  box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.25);
  animation: live-pulse 1.8s infinite;
}

@keyframes live-pulse {
  0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
  70% { transform: scale(1.1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
  100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

.ch-brand-sub {
  font-size: 0.8rem;
  display: block;
}

.ch-cart-btn {
  box-shadow: 0 4px 14px rgba(217, 119, 6, 0.35);
  transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.ch-badge-bounce {
  animation: badge-pop 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

@keyframes badge-pop {
  0% { transform: translate(-50%, -50%) scale(0.5); }
  70% { transform: translate(-50%, -50%) scale(1.25); }
  100% { transform: translate(-50%, -50%) scale(1); }
}

/* ACTIVE ORDER BANNER */
.ch-active-banner {
  background: linear-gradient(135deg, #ffffff 0%, #fffbeb 100%);
  border: 1px solid #fed7aa;
  border-left: 5px solid #f59e0b;
  border-radius: 18px;
  box-shadow: 0 6px 20px rgba(245, 158, 11, 0.08);
  transition: transform 0.2s ease;
}

.ch-active-banner:hover {
  transform: translateY(-2px);
}

.ch-active-icon-box {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  background: #fef3c7;
  display: flex;
  align-items: center;
  justify-content: center;
}

.live-dot-green {
  display: inline-block;
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: #10b981;
  animation: live-pulse 1.5s infinite;
}

/* HERO BANNER & AI TEASER */
.ch-hero-card {
  background: linear-gradient(135deg, #ffffff 0%, #fffbeb 50%, #fef3c7 100%);
  border: 1px solid #fde68a;
  border-radius: 20px;
  box-shadow: 0 10px 30px -5px rgba(217, 119, 6, 0.1);
  position: relative;
  overflow: hidden;
}

.ch-hero-title {
  font-size: 1.35rem;
  font-weight: 800;
  color: #451a03;
  line-height: 1.3;
  letter-spacing: -0.3px;
}

.ch-hero-desc {
  color: #78350f !important;
  font-weight: 500;
}

.btn-hero-chip {
  background: #ffffff;
  color: #78350f;
  border: 1px solid #fde68a;
  box-shadow: 0 2px 6px rgba(217, 119, 6, 0.08);
  transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.btn-hero-chip:hover {
  background: #fef3c7;
  color: #451a03;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(217, 119, 6, 0.15);
}

.btn-hero-chip:active {
  transform: scale(0.95);
}

.ch-hero-art {
  position: absolute;
  right: -10px;
  bottom: -20px;
  font-size: 8rem;
  color: rgba(245, 158, 11, 0.08);
  pointer-events: none;
  animation: float-slow 4s ease-in-out infinite alternate;
}

@keyframes float-slow {
  0% { transform: translateY(0) rotate(0deg); }
  100% { transform: translateY(-8px) rotate(4deg); }
}

/* SEARCH BAR */
.ch-search-bar .input-group {
  transition: box-shadow 0.2s ease, border-color 0.2s ease;
}

.ch-search-bar .input-group:focus-within {
  border-color: #f59e0b !important;
  box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.18) !important;
}

/* CATEGORY PILLS */
.cat-pill {
  transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
  font-weight: 600;
}

.cat-pill:hover {
  transform: translateY(-1px);
}

.cat-pill.btn-amber {
  box-shadow: 0 4px 14px rgba(217, 119, 6, 0.28);
  transform: scale(1.02);
}

/* PRODUCT CARD (LUXURY HOVER LIFT) */
.ch-product-card {
  background: #ffffff;
  border: 1px solid #f1f5f9 !important;
  border-radius: 18px !important;
  box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
  transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.25s ease;
}

.ch-product-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 16px 36px rgba(217, 119, 6, 0.14) !important;
  border-color: #fed7aa !important;
}

.ch-card-img-wrap {
  width: 100%;
  aspect-ratio: 4 / 3;
  overflow: hidden;
  background: #f8fafc;
  cursor: pointer;
  border-radius: 18px 18px 0 0;
}

.ch-product-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}

.ch-product-card:hover .ch-product-img {
  transform: scale(1.08);
}

.ch-product-title {
  font-size: 0.95rem;
  color: #0f172a;
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.ch-product-desc {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  line-height: 1.35;
  color: #64748b !important;
}

.ch-add-btn {
  width: 34px;
  height: 34px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.85rem;
  box-shadow: 0 3px 10px rgba(217, 119, 6, 0.28);
  transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.ch-add-btn:hover {
  transform: scale(1.1);
}

.ch-add-btn:active {
  transform: scale(0.9);
}

/* FLOATING AI BARISTA BUTTON (PULSING & FLOATING) */
.ch-floating-ai-btn {
  position: fixed;
  bottom: 24px;
  right: 20px;
  z-index: 1050;
  background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
  color: #ffffff;
  border: 2px solid #fef08a;
  border-radius: 50px;
  padding: 12px 18px;
  display: flex;
  align-items: center;
  cursor: pointer;
  box-shadow: 0 8px 25px rgba(234, 88, 12, 0.38);
  animation: float-bob 3s ease-in-out infinite;
  transition: all 0.2s ease;
}

.ch-floating-ai-btn:hover {
  transform: scale(1.08) translateY(-2px);
  box-shadow: 0 12px 30px rgba(234, 88, 12, 0.5);
}

@keyframes float-bob {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-7px); }
}

.pulse-amber {
  animation: pulse-border 2s infinite;
}

@keyframes pulse-border {
  0% { box-shadow: 0 0 0 0 rgba(217, 119, 6, 0.5); }
  70% { box-shadow: 0 0 0 10px rgba(217, 119, 6, 0); }
  100% { box-shadow: 0 0 0 0 rgba(217, 119, 6, 0); }
}

/* MODAL & BOTTOM SHEETS */
.ch-modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 42, 0.45);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  z-index: 1060;
  display: flex;
  align-items: flex-end;
  justify-content: center;
  animation: modal-fade-in 0.2s ease-out;
}

@keyframes modal-fade-in {
  from { opacity: 0; }
  to { opacity: 1; }
}

@media (min-width: 576px) {
  .ch-modal-backdrop {
    align-items: center;
  }
}

.ch-modal-sheet {
  background: #ffffff;
  width: 100%;
  max-width: 540px;
  border-radius: 24px 24px 0 0;
  overflow: hidden;
  box-shadow: 0 -10px 40px rgba(15, 23, 42, 0.12);
  animation: slide-up 0.28s cubic-bezier(0.16, 1, 0.3, 1);
}

@media (min-width: 576px) {
  .ch-modal-sheet {
    border-radius: 24px;
    box-shadow: 0 20px 50px rgba(15, 23, 42, 0.15);
  }
}

.ch-cart-drawer {
  background: #ffffff;
  width: 100%;
  max-width: 480px;
  height: 90vh;
  border-radius: 24px 24px 0 0;
  overflow: hidden;
  box-shadow: 0 -10px 40px rgba(15, 23, 42, 0.12);
  animation: slide-up 0.28s cubic-bezier(0.16, 1, 0.3, 1);
}

@media (min-width: 576px) {
  .ch-cart-drawer {
    height: 85vh;
    border-radius: 24px;
    box-shadow: 0 20px 50px rgba(15, 23, 42, 0.15);
  }
}

/* MODAL DIALOG (CHECKOUT & TRACKING FIX FOR WIDE-SCREEN STRETCH) */
.ch-modal-dialog {
  background: #ffffff;
  width: 92%;
  max-width: 480px !important;
  margin: auto !important;
  border-radius: 24px !important;
  box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.25) !important;
  border: 1px solid rgba(226, 232, 240, 0.8);
  animation: modal-pop 0.28s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes modal-pop {
  0% { transform: scale(0.92) translateY(20px); opacity: 0; }
  100% { transform: scale(1) translateY(0); opacity: 1; }
}

.ch-checkout-summary-box {
  background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
  border: 1.5px dashed #fde68a;
}

@keyframes slide-up {
  from { transform: translateY(100%); }
  to { transform: translateY(0); }
}

.ch-cart-thumb {
  width: 64px;
  height: 64px;
  object-fit: cover;
  border-radius: 12px;
}

.smaller {
  font-size: 0.75rem;
}

.cursor-pointer {
  cursor: pointer;
}

/* OPTION CARD HOVER */
.ch-option-card {
  transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.ch-option-card:hover {
  border-color: #f59e0b !important;
  background-color: #fffbeb !important;
}

.ch-option-card.active {
  box-shadow: 0 0 0 2px #f59e0b;
}

.ch-topping-item {
  transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.ch-topping-item:hover {
  border-color: #f59e0b !important;
}

/* TIMELINE (UC19) */
.ch-timeline {
  display: flex;
  flex-direction: column;
  gap: 16px;
  position: relative;
  padding-left: 28px;
}

.ch-timeline::before {
  content: "";
  position: absolute;
  top: 10px;
  bottom: 10px;
  left: 11px;
  width: 2px;
  background: #e2e8f0;
}

.ch-timeline-step {
  position: relative;
}

.ch-timeline-step .step-dot {
  position: absolute;
  left: -28px;
  top: 0;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background: #e2e8f0;
  color: #64748b;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.7rem;
  z-index: 2;
  transition: all 0.3s ease;
}

.ch-timeline-step.step-active .step-dot {
  background: #f59e0b;
  color: #ffffff;
  box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.25);
  animation: pulse-border 1.8s infinite;
}

.ch-timeline-step.step-done .step-dot {
  background: #10b981;
  color: #ffffff;
}

.ch-badge-icon {
  width: 36px;
  height: 36px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.1rem;
}

/* AI CHAT BOX (UC08) */
.ch-ai-chat-box {
  width: 100%;
  max-width: 480px;
  height: 82vh;
  border-radius: 24px;
  box-shadow: 0 20px 50px rgba(15, 23, 42, 0.2);
}

.bg-gradient-sunrise {
  background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
}

.bg-gradient-amber-btn {
  background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.bg-slate-light {
  background-color: #fcfbf9 !important;
}

.ai-avatar-pulse {
  width: 38px;
  height: 38px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.22);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.05rem;
  border: 1px solid rgba(255, 255, 255, 0.4);
}

.ch-chat-bubble {
  max-width: 85%;
  font-size: 0.9rem;
  line-height: 1.5;
}

.bubble-user {
  border-bottom-right-radius: 4px !important;
}

.bubble-ai {
  border-bottom-left-radius: 4px !important;
}

.whitespace-pre-line {
  white-space: pre-line;
}
</style>
