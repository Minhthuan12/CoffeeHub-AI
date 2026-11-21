<template>
    <div class="ch-app" ref="appRoot">
        <!-- âœ¨ Ambient Floating Particles -->
        <div class="ch-particles" aria-hidden="true">
            <span v-for="n in 18" :key="n" class="ch-particle" :style="particleStyle(n)"></span>
        </div>

        <!-- âœ¨ Radial Glow Blob -->
        <div class="ch-glow-blob ch-glow-1"></div>
        <div class="ch-glow-blob ch-glow-2"></div>

        <!-- ======= STICKY NAVBAR ======= -->
        <nav class="ch-navbar" :class="{ 'ch-navbar--scrolled': scrolled }">
            <div class="ch-navbar__inner">
                <!-- Brand -->
                <div class="ch-brand">
                    <div class="ch-brand__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 8h1a4 4 0 0 1 0 8h-1"/>
                            <path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/>
                            <line x1="6" y1="2" x2="6" y2="4"/>
                            <line x1="10" y1="2" x2="10" y2="4"/>
                            <line x1="14" y1="2" x2="14" y2="4"/>
                        </svg>
                    </div>
                    <div class="ch-brand__text">
                        <span class="ch-brand__name">Coffee<span class="ch-brand__accent">Hub</span></span>
                        <span class="ch-brand__tagline">Est. 2026 Â· Gourmet</span>
                    </div>
                </div>

                <!-- Search bar (center, desktop) -->
                <div class="ch-search" :class="{ 'ch-search--active': searchFocused }">
                    <i class="fa-solid fa-magnifying-glass ch-search__icon"></i>
                    <input
                        v-model="searchKey"
                        type="text"
                        class="ch-search__input"
                        placeholder="TĂ¬m tĂªn mĂ³n, cĂ  phĂª, trĂ ..."
                        @focus="searchFocused = true"
                        @blur="searchFocused = false"
                    />
                    <button v-if="searchKey" @click="searchKey = ''" class="ch-search__clear" aria-label="XoĂ¡ tĂ¬m kiáº¿m">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- Right actions -->
                <div class="ch-nav-actions">
                    <div class="ch-view-toggle">
                        <button
                            @click="viewMode = 'grid'"
                            class="ch-view-btn"
                            :class="{ active: viewMode === 'grid' }"
                            title="Dáº¡ng lÆ°á»›i"
                        >
                            <i class="fa-solid fa-border-all"></i>
                        </button>
                        <button
                            @click="viewMode = 'book'"
                            class="ch-view-btn"
                            :class="{ active: viewMode === 'book' }"
                            title="Dáº¡ng menu sĂ¡ch"
                        >
                            <i class="fa-solid fa-book-open"></i>
                        </button>
                    </div>
                    <div class="ch-badge-info">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>07:00 â€“ 22:30</span>
                    </div>
                </div>
            </div>
        </nav>

        <!-- ======= HERO SECTION ======= -->
        <section class="ch-hero">
            <div class="ch-hero__content">
                <div class="ch-hero__label">
                    <span class="ch-pulsedot"></span>
                    Thá»±c ÄÆ¡n HĂ´m Nay
                </div>
                <h1 class="ch-hero__title">
                    Tinh Hoa <span class="ch-gradient-text">CĂ  PhĂª</span><br>Viá»‡t Nam
                </h1>
                <p class="ch-hero__sub">
                    KhĂ¡m phĂ¡ bá»™ sÆ°u táº­p {{ products.length }} mĂ³n Ä‘áº·c sáº¯c â€” cĂ  phĂª rang xay, trĂ  trĂ¡i cĂ¢y nhiá»‡t Ä‘á»›i,
                    Ä‘Ă¡ xay Matcha vĂ  bĂ¡nh ngá»t nÆ°á»›ng tÆ°Æ¡i má»—i ngĂ y.
                </p>
                <div class="ch-hero__stats">
                    <div class="ch-stat" v-for="(s, i) in heroStats" :key="i">
                        <span class="ch-stat__num">{{ s.value }}</span>
                        <span class="ch-stat__label">{{ s.label }}</span>
                    </div>
                </div>
                <button @click="scrollToMenu" class="ch-hero__cta">
                    Xem Thá»±c ÄÆ¡n
                    <i class="fa-solid fa-arrow-down"></i>
                </button>
            </div>
            <div class="ch-hero__visual">
                <div class="ch-hero__ring ch-hero__ring--outer"></div>
                <div class="ch-hero__ring ch-hero__ring--middle"></div>
                <div class="ch-hero__ring ch-hero__ring--inner"></div>
                <div class="ch-hero__emblem">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 8h1a4 4 0 0 1 0 8h-1"/>
                        <path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/>
                        <line x1="6" y1="2" x2="6" y2="4"/>
                        <line x1="10" y1="2" x2="10" y2="4"/>
                        <line x1="14" y1="2" x2="14" y2="4"/>
                    </svg>
                </div>
                <!-- Floating badges -->
                <div class="ch-float-badge ch-float-badge--1">
                    <i class="fa-solid fa-star"></i> Premium
                </div>
                <div class="ch-float-badge ch-float-badge--2">
                    <i class="fa-solid fa-leaf"></i> Fresh
                </div>
                <div class="ch-float-badge ch-float-badge--3">
                    <i class="fa-solid fa-fire"></i> Hot
                </div>
            </div>
        </section>

        <!-- ======= CATEGORY FILTER STRIP ======= -->
        <section class="ch-filter" ref="menuSection">
            <div class="ch-filter__inner">
                <div class="ch-filter__scroll">
                    <button
                        @click="selectedCategory = 0"
                        class="ch-filter-btn"
                        :class="{ active: selectedCategory === 0 }"
                    >
                        <i class="fa-solid fa-border-all"></i>
                        Táº¥t Cáº£
                        <span class="ch-filter-count">{{ products.length }}</span>
                    </button>
                    <button
                        v-for="cat in categories"
                        :key="cat.id"
                        @click="selectedCategory = cat.id"
                        class="ch-filter-btn"
                        :class="{ active: selectedCategory === cat.id }"
                    >
                        <i :class="getCategoryIcon(cat.ten_danh_muc)"></i>
                        {{ cat.ten_danh_muc }}
                        <span class="ch-filter-count">{{ countByCategory(cat.id) }}</span>
                    </button>
                </div>
                <div class="ch-filter__result-info">
                    <span>Hiá»ƒn thá»‹ <b>{{ filteredProducts.length }}</b> mĂ³n</span>
                    <span v-if="searchKey" class="ch-filter__search-tag">
                        "{{ searchKey }}"
                        <button @click="searchKey = ''" class="ch-filter__clear-tag"><i class="fa-solid fa-xmark"></i></button>
                    </span>
                </div>
            </div>
        </section>

        <!-- ======= GRID VIEW ======= -->
        <main v-if="viewMode === 'grid'" class="ch-grid-section">
            <!-- Empty state -->
            <div v-if="filteredProducts.length === 0" class="ch-empty">
                <div class="ch-empty__icon">
                    <i class="fa-solid fa-mug-saucer"></i>
                </div>
                <h3 class="ch-empty__title">KhĂ´ng tĂ¬m tháº¥y mĂ³n phĂ¹ há»£p</h3>
                <p class="ch-empty__sub">HĂ£y thá»­ tĂ¬m tĂªn khĂ¡c hoáº·c chá»n láº¡i danh má»¥c.</p>
                <button @click="searchKey = ''; selectedCategory = 0" class="ch-btn-primary">
                    Xem Táº¥t Cáº£ MĂ³n
                </button>
            </div>

            <!-- Product Grid -->
            <div v-else class="ch-grid">
                <div
                    v-for="(item, idx) in filteredProducts"
                    :key="item.id"
                    class="ch-card"
                    :style="{ '--delay': idx * 0.05 + 's' }"
                    @click="openModal(item)"
                >
                    <!-- Image wrap -->
                    <div class="ch-card__img-wrap">
                        <img
                            :src="item.hinh_anh || defaultImage"
                            :alt="item.ten_san_pham"
                            class="ch-card__img"
                            @error="onImgError"
                            loading="lazy"
                        />
                        <div class="ch-card__img-overlay"></div>

                        <!-- Top badges -->
                        <div class="ch-card__badges">
                            <span class="ch-badge ch-badge--cat">{{ getCategoryName(item.id_danh_muc) }}</span>
                        </div>
                        <div class="ch-card__size-badge">
                            <span class="ch-badge ch-badge--size">{{ item.kich_thuoc || 'M' }}</span>
                        </div>

                        <!-- Quick view overlay -->
                        <div class="ch-card__hover-action">
                            <span class="ch-card__eye-btn">
                                <i class="fa-solid fa-eye"></i>
                                Xem Chi Tiáº¿t
                            </span>
                        </div>
                    </div>

                    <!-- Card body -->
                    <div class="ch-card__body">
                        <h3 class="ch-card__name">{{ item.ten_san_pham }}</h3>
                        <p class="ch-card__desc">
                            {{ item.mo_ta || 'NguyĂªn liá»‡u tÆ°Æ¡i ngon pha cháº¿ thá»§ cĂ´ng má»—i ngĂ y bá»Ÿi barista cá»§a chĂºng tĂ´i.' }}
                        </p>
                        <div class="ch-card__footer">
                            <div class="ch-card__price">
                                <span class="ch-card__price-label">GiĂ¡</span>
                                <span class="ch-card__price-value">{{ formatVND(item.gia_ban) }}<small>Ä‘</small></span>
                            </div>
                            <div class="ch-card__cta-wrap">
                                <button class="ch-card__cta" @click.stop="openModal(item)">
                                    Chi Tiáº¿t <i class="fa-solid fa-arrow-right-long"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- ======= BOOK VIEW ======= -->
        <main v-else class="ch-book-section">
            <!-- Book Nav Controls -->
            <div class="ch-book-controls">
                <button @click="prevSpread" :disabled="currentSpread === 0" class="ch-book-nav-btn">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <div class="ch-book-page-info">
                    <span class="ch-book-page-title">
                        {{ currentSpread === 0 ? 'âœ¦ Trang BĂ¬a' : getSpreadCategoryTitle(currentSpread) }}
                    </span>
                    <span class="ch-book-page-num">
                        {{ currentSpread === 0 ? 'BĂ¬a' : (currentSpread * 2 - 1) + ' â€“ ' + (currentSpread * 2) }} / {{ maxSpreads * 2 }}
                    </span>
                </div>
                <button @click="nextSpread" :disabled="currentSpread >= maxSpreads" class="ch-book-nav-btn">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>

            <!-- THE GRAND BOOK -->
            <div class="ch-book" :class="{ 'ch-book--flip': isFlipping }">
                <!-- Spine -->
                <div class="ch-book__spine"></div>
                <div class="ch-book__stitch"></div>

                <!-- Binding rings -->
                <div class="ch-book__rings">
                    <div v-for="n in 9" :key="n" class="ch-book__ring"></div>
                </div>

                <!-- Edge click triggers -->
                <div v-if="currentSpread > 0" class="ch-book__edge ch-book__edge--left" @click="prevSpread">
                    <i class="fa-solid fa-chevron-left"></i>
                </div>
                <div v-if="currentSpread < maxSpreads" class="ch-book__edge ch-book__edge--right" @click="nextSpread">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>

                <!-- COVER SPREAD -->
                <div v-if="currentSpread === 0" class="ch-book__cover">
                    <!-- Left panel: decorative branding -->
                    <div class="ch-book__cover-left">
                        <div class="ch-book__corner ch-book__corner--tl"></div>
                        <div class="ch-book__corner ch-book__corner--bl"></div>

                        <div class="ch-book__cover-brand">
                            <div class="ch-book__cover-emblem">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 8h1a4 4 0 0 1 0 8h-1"/>
                                    <path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/>
                                    <line x1="6" y1="2" x2="6" y2="4"/>
                                    <line x1="10" y1="2" x2="10" y2="4"/>
                                    <line x1="14" y1="2" x2="14" y2="4"/>
                                </svg>
                            </div>
                            <h1 class="ch-book__cover-name">Coffee<span>Hub</span></h1>
                            <div class="ch-book__cover-divider"></div>
                            <p class="ch-book__cover-est">Est. 2026 Â· Gourmet Roastery</p>
                            <p class="ch-book__cover-quote">"HÆ°Æ¡ng vá»‹ thÆ¡m ná»“ng trong tá»«ng giá»t cĂ  phĂª nguyĂªn cháº¥t"</p>
                        </div>
                    </div>

                    <!-- Right panel: call to action -->
                    <div class="ch-book__cover-right">
                        <div class="ch-book__corner ch-book__corner--tr"></div>
                        <div class="ch-book__corner ch-book__corner--br"></div>

                        <div class="ch-book__cover-cta-box">
                            <span class="ch-book__cover-badge">THá»°C ÄÆ N COFFEEHUB</span>
                            <h2 class="ch-book__cover-cta-title">Má»Ÿ Cuá»‘n Thá»±c ÄÆ¡n</h2>
                            <p class="ch-book__cover-cta-desc">
                                KĂ­nh má»i quĂ½ khĂ¡ch khĂ¡m phĂ¡ {{ products.length }}+ mĂ³n cĂ  phĂª, trĂ  trĂ¡i cĂ¢y,
                                Ä‘Ă¡ xay vĂ  bĂ¡nh ngá»t bÆ¡ tÆ°Æ¡i Ä‘á»™c quyá»n táº¡i CoffeeHub.
                            </p>
                            <div class="ch-book__cover-cats">
                                <span v-for="cat in categories.slice(0, 4)" :key="cat.id" class="ch-book__cover-cat-pill">
                                    {{ cat.ten_danh_muc }}
                                </span>
                            </div>
                            <button @click="nextSpread" class="ch-btn-primary ch-btn-primary--lg">
                                Láº­t Xem Menu <i class="fa-solid fa-book-open ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- INSIDE PAGES SPREAD -->
                <div v-else class="ch-book__pages">
                    <!-- LEFT PAGE -->
                    <div class="ch-book__page ch-book__page--left">
                        <div class="ch-page__header">
                            <div>
                                <span class="ch-page__brand-mini">CoffeeHub Menu</span>
                                <h4 class="ch-page__cat-title">{{ getPageData(currentSpread, 'left').categoryName }}</h4>
                            </div>
                            <span class="ch-page__num-badge">Trang {{ currentSpread * 2 - 1 }}</span>
                        </div>
                        <div class="ch-page__items">
                            <div
                                v-for="(item, idx) in getPageData(currentSpread, 'left').items"
                                :key="idx"
                                class="ch-page__item"
                                @click="openModal(item)"
                            >
                                <div class="ch-page__item-img">
                                    <img :src="item.hinh_anh || defaultImage" :alt="item.ten_san_pham" @error="onImgError" />
                                </div>
                                <div class="ch-page__item-info">
                                    <div class="ch-page__item-row">
                                        <span class="ch-page__item-name">{{ item.ten_san_pham }}</span>
                                        <span class="ch-page__dots"></span>
                                        <span class="ch-page__item-price">{{ formatVND(item.gia_ban) }}<small>Ä‘</small></span>
                                    </div>
                                    <p class="ch-page__item-desc">{{ item.mo_ta || 'NguyĂªn liá»‡u tÆ°Æ¡i ngon pha cháº¿ thá»§ cĂ´ng.' }}</p>
                                    <span class="ch-page__item-size">Size {{ item.kich_thuoc || 'M' }}</span>
                                </div>
                            </div>
                            <div v-if="getPageData(currentSpread, 'left').items.length === 0" class="ch-page__empty">
                                <i class="fa-solid fa-leaf"></i>
                                <span>Háº¿t mĂ³n</span>
                            </div>
                        </div>
                        <div class="ch-page__footer">
                            <button @click="prevSpread" class="ch-page__flip-btn">
                                <i class="fa-solid fa-chevron-left"></i> Trang trÆ°á»›c
                            </button>
                            <span class="ch-page__footer-num">{{ currentSpread * 2 - 1 }}</span>
                        </div>
                    </div>

                    <!-- RIGHT PAGE -->
                    <div class="ch-book__page ch-book__page--right">
                        <div class="ch-page__header">
                            <div>
                                <span class="ch-page__brand-mini">CoffeeHub Menu</span>
                                <h4 class="ch-page__cat-title">{{ getPageData(currentSpread, 'right').categoryName }}</h4>
                            </div>
                            <span class="ch-page__num-badge">Trang {{ currentSpread * 2 }}</span>
                        </div>
                        <div class="ch-page__items">
                            <div
                                v-for="(item, idx) in getPageData(currentSpread, 'right').items"
                                :key="idx"
                                class="ch-page__item"
                                @click="openModal(item)"
                            >
                                <div class="ch-page__item-img">
                                    <img :src="item.hinh_anh || defaultImage" :alt="item.ten_san_pham" @error="onImgError" />
                                </div>
                                <div class="ch-page__item-info">
                                    <div class="ch-page__item-row">
                                        <span class="ch-page__item-name">{{ item.ten_san_pham }}</span>
                                        <span class="ch-page__dots"></span>
                                        <span class="ch-page__item-price">{{ formatVND(item.gia_ban) }}<small>Ä‘</small></span>
                                    </div>
                                    <p class="ch-page__item-desc">{{ item.mo_ta || 'NguyĂªn liá»‡u tÆ°Æ¡i ngon pha cháº¿ thá»§ cĂ´ng.' }}</p>
                                    <span class="ch-page__item-size">Size {{ item.kich_thuoc || 'M' }}</span>
                                </div>
                            </div>
                            <div v-if="getPageData(currentSpread, 'right').items.length === 0" class="ch-page__empty">
                                <i class="fa-solid fa-leaf"></i>
                                <span>Háº¿t trang</span>
                            </div>
                        </div>
                        <div class="ch-page__footer ch-page__footer--right">
                            <span class="ch-page__footer-num">{{ currentSpread * 2 }}</span>
                            <button @click="nextSpread" :disabled="currentSpread >= maxSpreads" class="ch-page__flip-btn">
                                Trang sau <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Spread Quick Tabs -->
            <div class="ch-book-tabs">
                <button
                    @click="gotoSpread(0)"
                    class="ch-book-tab"
                    :class="{ active: currentSpread === 0 }"
                >
                    đŸ“• BĂ¬a
                </button>
                <button
                    v-for="s in maxSpreads"
                    :key="s"
                    @click="gotoSpread(s)"
                    class="ch-book-tab"
                    :class="{ active: currentSpread === s }"
                >
                    đŸ“– {{ getSpreadCategoryTitle(s) }}
                </button>
            </div>
        </main>

        <!-- ======= DETAIL MODAL ======= -->
        <transition name="ch-modal-fade">
            <div v-if="selectedItem" class="ch-modal-overlay" @click.self="closeModal">
                <div class="ch-modal" role="dialog" aria-modal="true">
                    <!-- Close btn -->
                    <button class="ch-modal__close" @click="closeModal" aria-label="ÄĂ³ng">
                        <i class="fa-solid fa-xmark"></i>
                    </button>

                    <!-- Image col -->
                    <div class="ch-modal__img-wrap">
                        <img
                            :src="selectedItem.hinh_anh || defaultImage"
                            :alt="selectedItem.ten_san_pham"
                            class="ch-modal__img"
                            @error="onImgError"
                        />
                        <div class="ch-modal__img-overlay"></div>
                        <div class="ch-modal__img-badges">
                            <span class="ch-badge ch-badge--cat ch-badge--lg">{{ getCategoryName(selectedItem.id_danh_muc) }}</span>
                            <span class="ch-badge ch-badge--size ch-badge--lg">Size {{ selectedItem.kich_thuoc || 'M' }}</span>
                        </div>
                    </div>

                    <!-- Info col -->
                    <div class="ch-modal__info">
                        <div class="ch-modal__breadcrumb">
                            <i class="fa-solid fa-house-chimney"></i>
                            <span>Menu</span>
                            <i class="fa-solid fa-chevron-right ch-modal__breadcrumb-sep"></i>
                            <span>{{ getCategoryName(selectedItem.id_danh_muc) }}</span>
                        </div>

                        <h2 class="ch-modal__name">{{ selectedItem.ten_san_pham }}</h2>

                        <div class="ch-modal__price-wrap">
                            <span class="ch-modal__price-label">GiĂ¡ niĂªm yáº¿t</span>
                            <div class="ch-modal__price">{{ formatVND(selectedItem.gia_ban) }}<small>Ä‘</small></div>
                        </div>

                        <div class="ch-modal__divider"></div>

                        <div class="ch-modal__section">
                            <h5 class="ch-modal__section-title">
                                <i class="fa-solid fa-list-ul"></i> MĂ´ Táº£ & ThĂ nh Pháº§n
                            </h5>
                            <p class="ch-modal__desc">
                                {{ selectedItem.mo_ta || 'MĂ³n nÆ°á»›c thÆ¡m ngon háº£o háº¡ng Ä‘Æ°á»£c cĂ¡c Barista pha cháº¿ tá»« háº¡t cĂ  phĂª nguyĂªn cháº¥t cĂ¹ng Ä‘Æ°á»ng sá»¯a chuáº©n tá»‰ lá»‡. HÆ°Æ¡ng vá»‹ Ä‘áº­m Ä‘Ă , bĂ©o ngáº­y vĂ  ngá»t thanh.' }}
                            </p>
                        </div>

                        <div class="ch-modal__barista-tip">
                            <div class="ch-modal__tip-header">
                                <i class="fa-solid fa-sparkles"></i>
                                Gá»£i Ă½ tá»« Barista
                            </div>
                            <p class="ch-modal__tip-body">
                                Ngon nháº¥t khi dĂ¹ng láº¡nh. CĂ³ thá»ƒ yĂªu cáº§u gia giáº£m Ä‘Æ°á»ng 50% hoáº·c 70% Ä‘Ă¡ tĂ¹y kháº©u vá»‹ riĂªng cá»§a báº¡n.
                            </p>
                        </div>

                        <div class="ch-modal__meta-row">
                            <div class="ch-modal__meta-item">
                                <i class="fa-solid fa-mug-hot"></i>
                                <span>Phá»¥c Vá»¥ NĂ³ng / Láº¡nh</span>
                            </div>
                            <div class="ch-modal__meta-item">
                                <i class="fa-solid fa-clock"></i>
                                <span>3â€“5 phĂºt</span>
                            </div>
                            <div class="ch-modal__meta-item">
                                <i class="fa-solid fa-star"></i>
                                <span>Háº£o Háº¡ng</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>

        <!-- ======= FOOTER ======= -->
        <footer class="ch-footer">
            <div class="ch-footer__inner">
                <div class="ch-footer__brand">
                    <div class="ch-brand__icon ch-brand__icon--sm">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 8h1a4 4 0 0 1 0 8h-1"/>
                            <path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/>
                        </svg>
                    </div>
                    <span>CoffeeHub</span>
                </div>
                <div class="ch-footer__info">
                    <span><i class="fa-solid fa-clock"></i> 07:00 â€“ 22:30</span>
                    <span><i class="fa-solid fa-wifi"></i> CoffeeHub_FreePass</span>
                    <span><i class="fa-solid fa-location-dot"></i> Phá»¥c vá»¥ táº¡i bĂ n</span>
                </div>
                <div class="ch-footer__copy">Â© 2026 CoffeeHub Â· Gourmet Roastery</div>
            </div>
        </footer>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'CustomerMenuViewPremium',
    data() {
        return {
            viewMode: 'grid',
            currentSpread: 0,
            isFlipping: false,
            searchKey: '',
            searchFocused: false,
            selectedCategory: 0,
            selectedItem: null,
            scrolled: false,
            products: [],
            categories: [],
            defaultImage: 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?q=80&w=600&auto=format&fit=crop',
            sampleCategories: [
                { id: 1, ten_danh_muc: 'CĂ  PhĂª Rang Xay' },
                { id: 2, ten_danh_muc: 'TrĂ  & Soda' },
                { id: 3, ten_danh_muc: 'ÄĂ¡ Xay & Frappe' },
                { id: 4, ten_danh_muc: 'BĂ¡nh Ngá»t BÆ¡ TÆ°Æ¡i' }
            ],
            sampleProducts: [
                { id: 101, ten_san_pham: 'CĂ  PhĂª Sá»¯a ÄĂ¡', id_danh_muc: 1, gia_ban: 35000, kich_thuoc: 'M', hinh_anh: 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?q=80&w=600', mo_ta: 'CĂ  phĂª Robusta rang Ä‘áº­m káº¿t há»£p sá»¯a Ä‘áº·c bĂ©o ngáº­y truyá»n thá»‘ng Viá»‡t Nam.' },
                { id: 102, ten_san_pham: 'Báº¡c Xá»‰u CoffeeHub', id_danh_muc: 1, gia_ban: 39000, kich_thuoc: 'L', hinh_anh: 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?q=80&w=600', mo_ta: 'Nhiá»u sá»¯a tÆ°Æ¡i bĂ©o thÆ¡m quyá»‡n chĂºt cá»‘t cĂ  phĂª Ä‘áº­m vá»‹ Ä‘áº·c trÆ°ng.' },
                { id: 103, ten_san_pham: 'CĂ  PhĂª Muá»‘i Kem BĂ©o', id_danh_muc: 1, gia_ban: 42000, kich_thuoc: 'M', hinh_anh: 'https://images.unsplash.com/photo-1541167760496-1628856ab772?q=80&w=600', mo_ta: 'Lá»›p mĂ ng kem máº·n ngá»t bĂ©o Ä‘á»™c Ä‘Ă¡o â€” xu hÆ°á»›ng Ä‘ang hot nháº¥t.' },
                { id: 104, ten_san_pham: 'Espresso Double Shot', id_danh_muc: 1, gia_ban: 30000, kich_thuoc: 'S', hinh_anh: 'https://images.unsplash.com/photo-1510591509098-f4fdc6d0ff04?q=80&w=600', mo_ta: 'Chiáº¿t xuáº¥t cĂ  phĂª Ä‘áº­m Ä‘áº·c chuáº©n Ă, dĂ nh cho ngÆ°á»i tháº­t sá»± yĂªu cĂ  phĂª.' },
                { id: 105, ten_san_pham: 'TrĂ  ÄĂ o Cam Sáº£', id_danh_muc: 2, gia_ban: 45000, kich_thuoc: 'L', hinh_anh: 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?q=80&w=600', mo_ta: 'TrĂ  Ä‘en háº£o háº¡ng pha hÆ°Æ¡ng cam tÆ°Æ¡i sáº£ thÆ¡m mĂ¡t cĂ¹ng miáº¿ng Ä‘Ă o giĂ²n tan.' },
                { id: 106, ten_san_pham: 'TrĂ  Váº£i LĂ i Panna Cotta', id_danh_muc: 2, gia_ban: 48000, kich_thuoc: 'M', hinh_anh: 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?q=80&w=600', mo_ta: 'TrĂ  hoa lĂ i thanh mĂ¡t káº¿t há»£p váº£i tÆ°Æ¡i vĂ  topping Panna cotta má»m má»‹n.' },
                { id: 107, ten_san_pham: 'Matcha ÄĂ¡ Xay Cream', id_danh_muc: 3, gia_ban: 55000, kich_thuoc: 'L', hinh_anh: 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?q=80&w=600', mo_ta: 'Matcha Uji Nháº­t Báº£n xay láº¡nh quyá»‡n kem whipping bĂ´ng tuyáº¿t thÆ°á»£ng háº¡ng.' },
                { id: 108, ten_san_pham: 'Chocolate ÄĂ¡ Xay', id_danh_muc: 3, gia_ban: 52000, kich_thuoc: 'L', hinh_anh: 'https://images.unsplash.com/photo-1553361371-9b22f78e8b1d?q=80&w=600', mo_ta: 'Chocolate Bá»‰ Ä‘áº­m Ä‘Ă  xay má»‹n cĂ¹ng sá»¯a tÆ°Æ¡i bĂ©o ngáº­y vĂ  Ä‘Ă¡ láº¡nh.' },
                { id: 109, ten_san_pham: 'BĂ¡nh Croissant BÆ¡ TÆ°Æ¡i', id_danh_muc: 4, gia_ban: 32000, kich_thuoc: 'S', hinh_anh: 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?q=80&w=600', mo_ta: 'BĂ¡nh sá»«ng bĂ² nÆ°á»›ng giĂ²n tan bĂ©o ngáº­y mĂ¹i bÆ¡ PhĂ¡p thÆ°á»£ng háº¡ng.' },
                { id: 110, ten_san_pham: 'BĂ¡nh Tiramisu Truyá»n Thá»‘ng', id_danh_muc: 4, gia_ban: 42000, kich_thuoc: 'S', hinh_anh: 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?q=80&w=600', mo_ta: 'Kem Mascarpone má»m má»‹n tháº¥m vá»‹ cĂ  phĂª vĂ  cacao cao cáº¥p nguyĂªn cháº¥t.' }
            ]
        };
    },
    computed: {
        heroStats() {
            return [
                { value: this.products.length + '+', label: 'MĂ³n Chá»n Lá»c' },
                { value: this.categories.length + '+', label: 'Danh Má»¥c' },
                { value: '100%', label: 'NguyĂªn Liá»‡u TÆ°Æ¡i' },
            ];
        },
        filteredProducts() {
            let list = this.products;
            if (this.selectedCategory > 0) {
                list = list.filter(x => x.id_danh_muc == this.selectedCategory);
            }
            if (this.searchKey && this.searchKey.trim() !== '') {
                const k = this.searchKey.toLowerCase().trim();
                list = list.filter(x => x.ten_san_pham && x.ten_san_pham.toLowerCase().includes(k));
            }
            return list;
        },
        maxSpreads() {
            return Math.ceil(this.products.length / 8) || 1;
        }
    },
    mounted() {
        this.loadData();
        window.addEventListener('scroll', this.onScroll, { passive: true });
        document.addEventListener('keydown', this.onKeydown);
    },
    beforeUnmount() {
        window.removeEventListener('scroll', this.onScroll);
        document.removeEventListener('keydown', this.onKeydown);
    },
    methods: {
        loadData() {
            const token = localStorage.getItem('xs');
            const headers = token ? { 'Authorization': `Bearer ${token}` } : {};
            axios.get('http://127.0.0.1:8000/api/admin/san-pham/get-data', { headers })
                .then(res => {
                    if (res.data && res.data.data && res.data.data.length > 0) {
                        this.products = res.data.data.filter(x => !x.deleted_at);
                    } else {
                        this.products = this.sampleProducts;
                    }
                })
                .catch(() => { this.products = this.sampleProducts; });

            axios.get('http://127.0.0.1:8000/api/admin/danh-muc/get-data', { headers })
                .then(res => {
                    if (res.data && res.data.data && res.data.data.length > 0) {
                        this.categories = res.data.data;
                    } else {
                        this.categories = this.sampleCategories;
                    }
                })
                .catch(() => { this.categories = this.sampleCategories; });
        },

        onScroll() {
            this.scrolled = window.scrollY > 40;
        },
        onKeydown(e) {
            if (e.key === 'Escape' && this.selectedItem) this.closeModal();
        },
        scrollToMenu() {
            this.$refs.menuSection && this.$refs.menuSection.scrollIntoView({ behavior: 'smooth' });
        },
        openModal(item) {
            this.selectedItem = item;
            document.body.style.overflow = 'hidden';
        },
        closeModal() {
            this.selectedItem = null;
            document.body.style.overflow = '';
        },

        nextSpread() {
            if (this.currentSpread < this.maxSpreads) this.triggerFlip(() => this.currentSpread++);
        },
        prevSpread() {
            if (this.currentSpread > 0) this.triggerFlip(() => this.currentSpread--);
        },
        gotoSpread(s) {
            this.triggerFlip(() => this.currentSpread = s);
        },
        triggerFlip(fn) {
            this.isFlipping = true;
            setTimeout(() => { fn(); this.isFlipping = false; }, 300);
        },
        getPageData(spreadIndex, side) {
            const startIndex = (spreadIndex - 1) * 8 + (side === 'left' ? 0 : 4);
            const items = this.products.slice(startIndex, startIndex + 4);
            let categoryName = 'Thá»±c ÄÆ¡n';
            if (items.length > 0) categoryName = this.getCategoryName(items[0].id_danh_muc);
            return { categoryName, items };
        },
        getSpreadCategoryTitle(spreadIndex) {
            if (spreadIndex === 0) return 'BĂ¬a Menu';
            const data = this.getPageData(spreadIndex, 'left');
            return data.categoryName || 'MĂ³n Ngon';
        },
        getCategoryName(id) {
            const c = this.categories.find(x => x.id == id);
            return c ? c.ten_danh_muc : 'Thá»©c Uá»‘ng';
        },
        countByCategory(id) {
            return this.products.filter(x => x.id_danh_muc == id).length;
        },
        getCategoryIcon(name) {
            if (!name) return 'fa-solid fa-star';
            const n = name.toLowerCase();
            if (n.includes('cĂ  phĂª') || n.includes('coffee')) return 'fa-solid fa-mug-hot';
            if (n.includes('trĂ ') || n.includes('soda')) return 'fa-solid fa-leaf';
            if (n.includes('Ä‘Ă¡ xay') || n.includes('frappe')) return 'fa-solid fa-snowflake';
            if (n.includes('bĂ¡nh')) return 'fa-solid fa-cookie-bite';
            return 'fa-solid fa-utensils';
        },
        formatVND(num) {
            if (!num) return '0';
            return Number(num).toLocaleString('vi-VN');
        },
        onImgError(e) {
            e.target.src = this.defaultImage;
        },
        particleStyle(n) {
            const size = (n % 3 + 1) * 4;
            const left = ((n * 17 + 7) % 100);
            const top = ((n * 23 + 13) % 100);
            const dur = (n % 4 + 5) + 's';
            const delay = (n * 0.4) + 's';
            return {
                width: size + 'px',
                height: size + 'px',
                left: left + '%',
                top: top + '%',
                animationDuration: dur,
                animationDelay: delay,
            };
        }
    }
};
</script>

<style scoped>
/* ============================================================
   CH = CoffeeHub Design System
   Premium Dark Luxury Theme
   ============================================================ */

/* ---- Google Fonts ---- */
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;0,900;1,600;1,700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

/* ---- CSS Variables ---- */
.ch-app {
    --amber: #f59e0b;
    --amber-light: #fbbf24;
    --amber-dark: #d97706;
    --amber-deep: #b45309;
    --amber-cream: #fef3c7;
    --gold-gradient: linear-gradient(135deg, #fbbf24 0%, #f59e0b 40%, #d97706 70%, #b45309 100%);
    --dark-bg: #0c0907;
    --dark-card: #12100d;
    --dark-surface: #1a1208;
    --dark-border: rgba(245, 158, 11, 0.15);
    --dark-border-hover: rgba(245, 158, 11, 0.45);
    --glass-bg: rgba(255, 255, 255, 0.04);
    --glass-border: rgba(255, 255, 255, 0.08);
    --text-primary: #f8f4ef;
    --text-secondary: rgba(248, 244, 239, 0.65);
    --text-muted: rgba(248, 244, 239, 0.38);
    --font-serif: 'Playfair Display', Georgia, serif;
    --font-sans: 'Plus Jakarta Sans', system-ui, sans-serif;
    --radius-sm: 10px;
    --radius-md: 16px;
    --radius-lg: 24px;
    --radius-xl: 32px;
    --shadow-amber: 0 0 30px rgba(245, 158, 11, 0.25);
    --shadow-card: 0 20px 60px rgba(0, 0, 0, 0.6);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);

    font-family: var(--font-sans);
    background-color: var(--dark-bg);
    color: var(--text-primary);
    min-height: 100vh;
    position: relative;
    overflow-x: hidden;
}

/* ============================================================
   AMBIENT PARTICLES
   ============================================================ */
.ch-particles {
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: 0;
    overflow: hidden;
}
.ch-particle {
    position: absolute;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(245, 158, 11, 0.35) 0%, transparent 70%);
    animation: ch-float linear infinite;
    will-change: transform, opacity;
}
@keyframes ch-float {
    0%   { transform: translateY(0px) scale(1); opacity: 0.4; }
    50%  { transform: translateY(-30px) scale(1.2); opacity: 0.7; }
    100% { transform: translateY(0px) scale(1); opacity: 0.4; }
}

.ch-glow-blob {
    position: fixed;
    pointer-events: none;
    border-radius: 50%;
    filter: blur(100px);
    z-index: 0;
}
.ch-glow-1 {
    width: 600px; height: 600px;
    background: radial-gradient(circle, rgba(245, 158, 11, 0.12) 0%, transparent 70%);
    top: -100px; right: -200px;
    animation: ch-glow-pulse 8s ease-in-out infinite;
}
.ch-glow-2 {
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(180, 83, 9, 0.1) 0%, transparent 70%);
    bottom: 20%; left: -100px;
    animation: ch-glow-pulse 10s ease-in-out infinite reverse;
}
@keyframes ch-glow-pulse {
    0%, 100% { opacity: 0.6; transform: scale(1); }
    50%       { opacity: 1;   transform: scale(1.15); }
}

/* ============================================================
   NAVBAR
   ============================================================ */
.ch-navbar {
    position: sticky;
    top: 0;
    z-index: 100;
    padding: 0 24px;
    transition: var(--transition);
    background: transparent;
}
.ch-navbar--scrolled {
    background: rgba(12, 9, 7, 0.92);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border-bottom: 1px solid var(--dark-border);
    box-shadow: 0 4px 40px rgba(0, 0, 0, 0.5);
}
.ch-navbar__inner {
    max-width: 1280px;
    margin: 0 auto;
    height: 72px;
    display: flex;
    align-items: center;
    gap: 20px;
}

/* Brand */
.ch-brand {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
    text-decoration: none;
}
.ch-brand__icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    background: var(--gold-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    flex-shrink: 0;
    box-shadow: var(--shadow-amber);
}
.ch-brand__icon svg { width: 22px; height: 22px; }
.ch-brand__icon--sm { width: 32px; height: 32px; border-radius: 8px; }
.ch-brand__icon--sm svg { width: 16px; height: 16px; }
.ch-brand__text { display: flex; flex-direction: column; line-height: 1.2; }
.ch-brand__name {
    font-family: var(--font-serif);
    font-size: 1.35rem;
    font-weight: 700;
    color: var(--text-primary);
    letter-spacing: 0.5px;
}
.ch-brand__accent { color: var(--amber); }
.ch-brand__tagline {
    font-size: 0.68rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-weight: 500;
}

/* Search */
.ch-search {
    flex: 1;
    max-width: 440px;
    position: relative;
    margin: 0 auto;
}
.ch-search__icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 0.9rem;
    transition: color 0.2s;
    pointer-events: none;
}
.ch-search--active .ch-search__icon { color: var(--amber); }
.ch-search__input {
    width: 100%;
    height: 44px;
    padding: 0 44px 0 44px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 100px;
    color: var(--text-primary);
    font-family: var(--font-sans);
    font-size: 0.9rem;
    font-weight: 500;
    outline: none;
    transition: var(--transition);
}
.ch-search__input::placeholder { color: var(--text-muted); }
.ch-search__input:focus,
.ch-search--active .ch-search__input {
    background: rgba(245, 158, 11, 0.06);
    border-color: rgba(245, 158, 11, 0.4);
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
}
.ch-search__clear {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    width: 24px; height: 24px;
    border-radius: 50%;
    background: var(--glass-bg);
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.75rem;
    transition: var(--transition);
    padding: 0;
}
.ch-search__clear:hover { background: rgba(245, 158, 11, 0.15); color: var(--amber); }

/* Nav Actions */
.ch-nav-actions { display: flex; align-items: center; gap: 12px; flex-shrink: 0; }
.ch-view-toggle {
    display: flex;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 100px;
    padding: 4px;
    gap: 4px;
}
.ch-view-btn {
    width: 34px; height: 34px;
    border-radius: 100px;
    border: none;
    background: transparent;
    color: var(--text-muted);
    cursor: pointer;
    transition: var(--transition);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.9rem;
    padding: 0;
}
.ch-view-btn.active {
    background: var(--gold-gradient);
    color: #fff;
    box-shadow: 0 2px 10px rgba(245, 158, 11, 0.35);
}
.ch-view-btn:hover:not(.active) { color: var(--amber); background: rgba(245, 158, 11, 0.08); }

.ch-badge-info {
    display: flex;
    align-items: center;
    gap: 7px;
    padding: 8px 14px;
    background: var(--glass-bg);
    border: 1px solid var(--dark-border);
    border-radius: 100px;
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--amber-light);
    white-space: nowrap;
}
.ch-badge-info i { color: var(--amber); font-size: 0.75rem; }

/* ============================================================
   HERO SECTION
   ============================================================ */
.ch-hero {
    position: relative;
    z-index: 1;
    min-height: 88vh;
    max-width: 1280px;
    margin: 0 auto;
    padding: 80px 32px 60px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    align-items: center;
    gap: 60px;
}
.ch-hero__label {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: rgba(245, 158, 11, 0.1);
    border: 1px solid rgba(245, 158, 11, 0.25);
    border-radius: 100px;
    padding: 8px 18px;
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--amber-light);
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 28px;
}
.ch-pulsedot {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: var(--amber);
    box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.5);
    animation: ch-pulse 2s ease-in-out infinite;
}
@keyframes ch-pulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.5); }
    50%       { box-shadow: 0 0 0 8px rgba(245, 158, 11, 0); }
}

.ch-hero__title {
    font-family: var(--font-serif);
    font-size: clamp(2.8rem, 5vw, 4.5rem);
    font-weight: 900;
    line-height: 1.1;
    color: var(--text-primary);
    margin-bottom: 24px;
}
.ch-gradient-text {
    background: var(--gold-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.ch-hero__sub {
    font-size: 1.05rem;
    color: var(--text-secondary);
    line-height: 1.7;
    max-width: 480px;
    margin-bottom: 40px;
    font-weight: 400;
}

.ch-hero__stats {
    display: flex;
    gap: 32px;
    margin-bottom: 44px;
}
.ch-stat { display: flex; flex-direction: column; }
.ch-stat__num {
    font-family: var(--font-serif);
    font-size: 2rem;
    font-weight: 800;
    background: var(--gold-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1;
    margin-bottom: 4px;
}
.ch-stat__label {
    font-size: 0.75rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
}

/* CTA Button */
.ch-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: var(--gold-gradient);
    color: #1a0a00;
    border: none;
    border-radius: 100px;
    padding: 14px 32px;
    font-family: var(--font-sans);
    font-size: 1rem;
    font-weight: 800;
    cursor: pointer;
    transition: var(--transition);
    letter-spacing: 0.3px;
    text-transform: uppercase;
    box-shadow: 0 8px 30px rgba(245, 158, 11, 0.4);
}
.ch-btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 16px 40px rgba(245, 158, 11, 0.55);
}
.ch-btn-primary:active { transform: translateY(0); }
.ch-btn-primary--lg { padding: 16px 40px; font-size: 1.05rem; }

.ch-hero__cta {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    background: var(--gold-gradient);
    color: #1a0a00;
    border: none;
    border-radius: 100px;
    padding: 16px 36px;
    font-family: var(--font-sans);
    font-size: 1.05rem;
    font-weight: 800;
    cursor: pointer;
    transition: var(--transition);
    box-shadow: 0 8px 30px rgba(245, 158, 11, 0.4);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.ch-hero__cta:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 50px rgba(245, 158, 11, 0.55);
}
.ch-hero__cta i { animation: ch-bounce 1.5s ease-in-out infinite; }
@keyframes ch-bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(5px); }
}

/* Hero Visual */
.ch-hero__visual {
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    height: 500px;
}
.ch-hero__ring {
    position: absolute;
    border-radius: 50%;
    border: 1px solid rgba(245, 158, 11, 0.2);
    animation: ch-spin linear infinite;
}
.ch-hero__ring--outer  { width: 420px; height: 420px; animation-duration: 30s; }
.ch-hero__ring--middle { width: 320px; height: 320px; animation-duration: 20s; border-color: rgba(245, 158, 11, 0.3); border-style: dashed; animation-direction: reverse; }
.ch-hero__ring--inner  { width: 220px; height: 220px; animation-duration: 15s; border-color: rgba(245, 158, 11, 0.4); }
@keyframes ch-spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

.ch-hero__emblem {
    width: 140px; height: 140px;
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 2px solid rgba(245, 158, 11, 0.3);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: var(--amber);
    box-shadow: 0 0 60px rgba(245, 158, 11, 0.2), inset 0 0 30px rgba(245, 158, 11, 0.05);
    z-index: 2;
    animation: ch-emblem-float 4s ease-in-out infinite;
}
.ch-hero__emblem svg { width: 64px; height: 64px; }
@keyframes ch-emblem-float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-12px); }
}

/* Floating badges */
.ch-float-badge {
    position: absolute;
    background: var(--glass-bg);
    backdrop-filter: blur(16px);
    border: 1px solid var(--dark-border-hover);
    border-radius: 100px;
    padding: 8px 16px;
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--amber-light);
    white-space: nowrap;
    display: flex; align-items: center; gap: 6px;
    z-index: 3;
}
.ch-float-badge i { color: var(--amber); }
.ch-float-badge--1 { top: 80px; left: -20px; animation: ch-badge-float1 5s ease-in-out infinite; }
.ch-float-badge--2 { top: 60px; right: -10px; animation: ch-badge-float2 6s ease-in-out infinite; }
.ch-float-badge--3 { bottom: 90px; left: 10px; animation: ch-badge-float3 4.5s ease-in-out infinite; }
@keyframes ch-badge-float1 { 0%,100%{transform:translateY(0) rotate(-3deg)} 50%{transform:translateY(-10px) rotate(3deg)} }
@keyframes ch-badge-float2 { 0%,100%{transform:translateY(0) rotate(4deg)} 50%{transform:translateY(-14px) rotate(-2deg)} }
@keyframes ch-badge-float3 { 0%,100%{transform:translateY(0) rotate(-2deg)} 50%{transform:translateY(-8px) rotate(4deg)} }

/* ============================================================
   FILTER STRIP
   ============================================================ */
.ch-filter {
    position: sticky;
    top: 72px;
    z-index: 90;
    background: rgba(12, 9, 7, 0.88);
    backdrop-filter: blur(20px);
    border-top: 1px solid var(--dark-border);
    border-bottom: 1px solid var(--dark-border);
    padding: 16px 32px;
}
.ch-filter__inner {
    max-width: 1280px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}
.ch-filter__scroll {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    padding-bottom: 4px;
    flex: 1;
    scrollbar-width: none;
}
.ch-filter__scroll::-webkit-scrollbar { display: none; }

.ch-filter-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 18px;
    border-radius: 100px;
    border: 1px solid var(--dark-border);
    background: var(--glass-bg);
    color: var(--text-secondary);
    font-family: var(--font-sans);
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    white-space: nowrap;
    flex-shrink: 0;
}
.ch-filter-btn:hover {
    border-color: rgba(245, 158, 11, 0.4);
    color: var(--amber-light);
    background: rgba(245, 158, 11, 0.06);
}
.ch-filter-btn.active {
    background: var(--gold-gradient);
    border-color: transparent;
    color: #1a0a00;
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.35);
}
.ch-filter-btn.active .ch-filter-count {
    background: rgba(0,0,0,0.15);
    color: #1a0a00;
}
.ch-filter-count {
    background: rgba(245, 158, 11, 0.12);
    color: var(--amber);
    border-radius: 100px;
    padding: 1px 8px;
    font-size: 0.72rem;
    font-weight: 700;
}

.ch-filter__result-info {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.82rem;
    color: var(--text-muted);
    white-space: nowrap;
    flex-shrink: 0;
}
.ch-filter__result-info b { color: var(--amber-light); }
.ch-filter__search-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(245, 158, 11, 0.1);
    border: 1px solid rgba(245, 158, 11, 0.25);
    border-radius: 100px;
    padding: 3px 10px;
    color: var(--amber-light);
}
.ch-filter__clear-tag {
    background: none; border: none;
    color: var(--text-muted);
    cursor: pointer; padding: 0;
    font-size: 0.75rem;
    line-height: 1;
    transition: color 0.2s;
}
.ch-filter__clear-tag:hover { color: var(--amber); }

/* ============================================================
   GRID SECTION
   ============================================================ */
.ch-grid-section {
    position: relative;
    z-index: 1;
    max-width: 1280px;
    margin: 0 auto;
    padding: 48px 32px 80px;
}

.ch-empty {
    text-align: center;
    padding: 100px 24px;
}
.ch-empty__icon {
    font-size: 4rem;
    color: var(--text-muted);
    margin-bottom: 24px;
    opacity: 0.5;
}
.ch-empty__title {
    font-family: var(--font-serif);
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 12px;
}
.ch-empty__sub { color: var(--text-secondary); margin-bottom: 32px; }

.ch-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
}

/* PRODUCT CARD */
.ch-card {
    background: var(--dark-card);
    border: 1px solid var(--dark-border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1),
                box-shadow 0.4s ease,
                border-color 0.3s ease;
    animation: ch-card-in 0.5s both;
    animation-delay: var(--delay);
    display: flex;
    flex-direction: column;
}
@keyframes ch-card-in {
    from { opacity: 0; transform: translateY(24px); }
    to   { opacity: 1; transform: translateY(0); }
}
.ch-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: var(--shadow-card), var(--shadow-amber);
    border-color: rgba(245, 158, 11, 0.5);
}

.ch-card__img-wrap {
    position: relative;
    height: 220px;
    overflow: hidden;
    background: #1a1208;
}
.ch-card__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
    display: block;
}
.ch-card:hover .ch-card__img { transform: scale(1.12); }
.ch-card__img-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(12, 9, 7, 0.7) 0%, transparent 50%);
    pointer-events: none;
}
.ch-card__badges {
    position: absolute;
    top: 12px; left: 12px;
    z-index: 2;
}
.ch-card__size-badge {
    position: absolute;
    top: 12px; right: 12px;
    z-index: 2;
}
.ch-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 100px;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.3px;
}
.ch-badge--cat {
    background: rgba(12, 9, 7, 0.85);
    backdrop-filter: blur(10px);
    color: var(--amber-light);
    border: 1px solid rgba(245, 158, 11, 0.35);
}
.ch-badge--size {
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(10px);
    color: var(--text-primary);
    border: 1px solid rgba(255, 255, 255, 0.15);
}
.ch-badge--lg { padding: 6px 14px; font-size: 0.82rem; }

.ch-card__hover-action {
    position: absolute;
    inset: 0;
    background: rgba(12, 9, 7, 0.5);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.35s ease;
    z-index: 3;
}
.ch-card:hover .ch-card__hover-action { opacity: 1; }
.ch-card__eye-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--gold-gradient);
    color: #1a0a00;
    border-radius: 100px;
    padding: 10px 22px;
    font-weight: 800;
    font-size: 0.9rem;
    transform: scale(0.85);
    transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
}
.ch-card:hover .ch-card__eye-btn { transform: scale(1); }

.ch-card__body {
    padding: 20px;
    display: flex;
    flex-direction: column;
    flex: 1;
    background: linear-gradient(180deg, #12100d 0%, #0f0d0a 100%);
}
.ch-card__name {
    font-family: var(--font-serif);
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 8px;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.ch-card__desc {
    font-size: 0.82rem;
    color: var(--text-secondary);
    line-height: 1.55;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin: 0 0 16px;
    min-height: 2.6em;
}
.ch-card__footer {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-top: auto;
    padding-top: 14px;
    border-top: 1px solid var(--dark-border);
    gap: 10px;
}
.ch-card__price { display: flex; flex-direction: column; }
.ch-card__price-label {
    font-size: 0.7rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.8px;
    font-weight: 600;
    margin-bottom: 2px;
}
.ch-card__price-value {
    font-family: var(--font-serif);
    font-size: 1.4rem;
    font-weight: 800;
    background: var(--gold-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1;
}
.ch-card__price-value small { font-size: 0.7em; font-weight: 600; }

.ch-card__cta-wrap { flex-shrink: 0; }
.ch-card__cta {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: rgba(245, 158, 11, 0.1);
    border: 1px solid rgba(245, 158, 11, 0.3);
    border-radius: 100px;
    color: var(--amber-light);
    font-family: var(--font-sans);
    font-size: 0.8rem;
    font-weight: 700;
    cursor: pointer;
    transition: var(--transition);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.ch-card__cta:hover {
    background: var(--gold-gradient);
    color: #1a0a00;
    border-color: transparent;
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.35);
}

/* ============================================================
   BOOK SECTION
   ============================================================ */
.ch-book-section {
    position: relative;
    z-index: 1;
    padding: 40px 24px 80px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.ch-book-controls {
    display: flex;
    align-items: center;
    gap: 24px;
    margin-bottom: 28px;
}
.ch-book-nav-btn {
    width: 52px; height: 52px;
    border-radius: 50%;
    background: var(--glass-bg);
    border: 1px solid var(--dark-border-hover);
    color: var(--amber-light);
    font-size: 1rem;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: var(--transition);
    flex-shrink: 0;
    padding: 0;
}
.ch-book-nav-btn:hover:not(:disabled) {
    background: var(--gold-gradient);
    color: #1a0a00;
    border-color: transparent;
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
    transform: scale(1.1);
}
.ch-book-nav-btn:disabled { opacity: 0.3; cursor: not-allowed; }
.ch-book-page-info { text-align: center; }
.ch-book-page-title {
    display: block;
    font-family: var(--font-serif);
    font-size: 1.1rem;
    font-weight: 700;
    background: var(--gold-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 2px;
}
.ch-book-page-num {
    display: block;
    font-size: 0.75rem;
    color: var(--text-muted);
    font-weight: 500;
}

/* THE BOOK */
.ch-book {
    position: relative;
    width: 100%;
    max-width: 1200px;
    height: 820px;
    background: #1c0f06;
    border: 16px solid #2a1506;
    border-radius: 28px;
    box-shadow:
        0 50px 100px -20px rgba(0, 0, 0, 0.85),
        0 0 0 1px rgba(245, 158, 11, 0.15),
        inset 0 1px 0 rgba(255, 255, 255, 0.06);
    overflow: hidden;
    transition: opacity 0.3s ease, transform 0.3s ease;
}
.ch-book--flip {
    opacity: 0.7;
    transform: scale(0.97) rotateY(-2deg);
}
.ch-book__spine {
    position: absolute;
    top: 0; bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 28px;
    background: linear-gradient(90deg, rgba(0,0,0,0.5), rgba(255,255,255,0.06), rgba(0,0,0,0.5));
    z-index: 15;
    pointer-events: none;
}
.ch-book__stitch {
    position: absolute;
    inset: 6px;
    border: 1.5px dashed rgba(245, 158, 11, 0.25);
    border-radius: 16px;
    pointer-events: none;
    z-index: 20;
}
.ch-book__rings {
    position: absolute;
    top: 40px; bottom: 40px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 18;
    pointer-events: none;
    display: flex;
    flex-direction: column;
    justify-content: space-around;
}
.ch-book__ring {
    width: 16px; height: 7px;
    background: linear-gradient(135deg, #d97706, #fff8ec, #78350f);
    border-radius: 4px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.6);
    margin-left: -5px;
}
.ch-book__edge {
    position: absolute;
    top: 0; bottom: 0;
    width: 56px;
    z-index: 25;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
    opacity: 0;
    font-size: 1.1rem;
    color: var(--amber);
}
.ch-book__edge--left  { left: 0; border-radius: 14px 0 0 14px; }
.ch-book__edge--right { right: 0; border-radius: 0 14px 14px 0; }
.ch-book:hover .ch-book__edge { opacity: 1; }
.ch-book__edge:hover { background: rgba(245, 158, 11, 0.12); }

/* COVER */
.ch-book__cover {
    width: 100%; height: 100%;
    display: flex;
}
.ch-book__cover-left, .ch-book__cover-right {
    flex: 1;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px 44px;
}
.ch-book__cover-left {
    background: radial-gradient(ellipse at center, #2e1206 0%, #150900 100%);
    border-right: 1px solid rgba(245, 158, 11, 0.12);
}
.ch-book__cover-right {
    background: radial-gradient(ellipse at center, #1a0e04 0%, #0c0702 100%);
}
.ch-book__corner {
    position: absolute;
    width: 28px; height: 28px;
    border: 2px solid rgba(245, 158, 11, 0.5);
}
.ch-book__corner--tl { top: 16px; left: 16px; border-right: none; border-bottom: none; }
.ch-book__corner--bl { bottom: 16px; left: 16px; border-right: none; border-top: none; }
.ch-book__corner--tr { top: 16px; right: 16px; border-left: none; border-bottom: none; }
.ch-book__corner--br { bottom: 16px; right: 16px; border-left: none; border-top: none; }

.ch-book__cover-brand { text-align: center; }
.ch-book__cover-emblem {
    width: 96px; height: 96px;
    margin: 0 auto 24px;
    border-radius: 50%;
    background: rgba(245, 158, 11, 0.08);
    border: 2px solid rgba(245, 158, 11, 0.35);
    display: flex; align-items: center; justify-content: center;
    color: var(--amber);
    box-shadow: 0 0 40px rgba(245, 158, 11, 0.15);
}
.ch-book__cover-emblem svg { width: 44px; height: 44px; }
.ch-book__cover-name {
    font-family: var(--font-serif);
    font-size: 3.2rem;
    font-weight: 900;
    background: var(--gold-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin: 0 0 8px;
    line-height: 1;
}
.ch-book__cover-name span { color: rgba(245, 158, 11, 0.7); -webkit-text-fill-color: initial; }
.ch-book__cover-divider {
    width: 120px;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--amber), transparent);
    margin: 16px auto;
}
.ch-book__cover-est {
    font-size: 0.82rem;
    color: var(--amber-cream);
    opacity: 0.6;
    text-transform: uppercase;
    letter-spacing: 2px;
    font-weight: 600;
    margin: 0 0 12px;
}
.ch-book__cover-quote {
    font-family: var(--font-serif);
    font-style: italic;
    font-size: 0.95rem;
    color: var(--text-secondary);
    margin: 0;
    line-height: 1.6;
}

.ch-book__cover-cta-box {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(245, 158, 11, 0.2);
    border-radius: var(--radius-lg);
    padding: 40px 36px;
    text-align: center;
    width: 100%;
    max-width: 380px;
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.05);
}
.ch-book__cover-badge {
    display: inline-block;
    background: var(--gold-gradient);
    color: #1a0a00;
    border-radius: 100px;
    padding: 5px 16px;
    font-size: 0.72rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 20px;
}
.ch-book__cover-cta-title {
    font-family: var(--font-serif);
    font-size: 2rem;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0 0 12px;
}
.ch-book__cover-cta-desc {
    font-size: 0.9rem;
    color: var(--text-secondary);
    line-height: 1.6;
    margin: 0 0 20px;
}
.ch-book__cover-cats {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    justify-content: center;
    margin-bottom: 28px;
}
.ch-book__cover-cat-pill {
    display: inline-block;
    padding: 5px 14px;
    border: 1px solid var(--dark-border-hover);
    border-radius: 100px;
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--amber-light);
}

/* BOOK PAGES */
.ch-book__pages {
    width: 100%; height: 100%;
    display: flex;
}
.ch-book__page {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: linear-gradient(160deg, #fff9f2 0%, #fef6ec 50%, #fff9f2 100%);
    padding: 32px 36px;
}
.ch-book__page--left { border-right: 1px solid #e8d8c0; box-shadow: inset -12px 0 20px -8px rgba(0,0,0,0.06); }
.ch-book__page--right { box-shadow: inset 12px 0 20px -8px rgba(0,0,0,0.06); }

.ch-page__header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding-bottom: 16px;
    margin-bottom: 16px;
    border-bottom: 2px solid #f0dfc0;
}
.ch-page__brand-mini {
    display: block;
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: #b45309;
    margin-bottom: 4px;
}
.ch-page__cat-title {
    font-family: var(--font-serif);
    font-size: 1.2rem;
    font-weight: 800;
    color: #1a0e04;
    margin: 0;
}
.ch-page__num-badge {
    background: linear-gradient(135deg, #fbbf24, #d97706);
    color: #fff;
    border-radius: 100px;
    padding: 4px 12px;
    font-size: 0.72rem;
    font-weight: 700;
    white-space: nowrap;
    flex-shrink: 0;
}

.ch-page__items {
    flex: 1;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding-right: 6px;
}
.ch-page__items::-webkit-scrollbar { width: 4px; }
.ch-page__items::-webkit-scrollbar-track { background: #f5ead8; border-radius: 4px; }
.ch-page__items::-webkit-scrollbar-thumb { background: #d97706; border-radius: 4px; }

.ch-page__item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px;
    background: rgba(255, 255, 255, 0.7);
    border-radius: var(--radius-sm);
    border: 1px solid #f0dfc0;
    cursor: pointer;
    transition: var(--transition);
}
.ch-page__item:hover {
    background: #fffbf3;
    border-color: #d97706;
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(180, 83, 9, 0.12);
}
.ch-page__item-img {
    width: 72px; height: 72px;
    border-radius: 10px;
    overflow: hidden;
    flex-shrink: 0;
    border: 1px solid #f0dfc0;
}
.ch-page__item-img img {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s;
}
.ch-page__item:hover .ch-page__item-img img { transform: scale(1.08); }
.ch-page__item-info { flex: 1; min-width: 0; }
.ch-page__item-row {
    display: flex;
    align-items: baseline;
    gap: 8px;
    margin-bottom: 4px;
}
.ch-page__item-name {
    font-family: var(--font-serif);
    font-size: 0.95rem;
    font-weight: 700;
    color: #1a0e04;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 120px;
}
.ch-page__dots {
    flex: 1;
    border-bottom: 2px dotted #d4b896;
    height: 1px;
}
.ch-page__item-price {
    font-family: var(--font-serif);
    font-size: 1.05rem;
    font-weight: 800;
    color: #b45309;
    white-space: nowrap;
}
.ch-page__item-price small { font-size: 0.7em; }
.ch-page__item-desc {
    font-size: 0.75rem;
    color: #78563b;
    margin: 0 0 6px;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.4;
}
.ch-page__item-size {
    display: inline-block;
    background: #f5ead8;
    color: #92400e;
    border-radius: 100px;
    padding: 2px 8px;
    font-size: 0.68rem;
    font-weight: 700;
}
.ch-page__empty {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 8px;
    color: #c4a882;
    font-style: italic;
}
.ch-page__empty i { font-size: 2rem; opacity: 0.4; }

.ch-page__footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 14px;
    border-top: 1px solid #f0dfc0;
    margin-top: 12px;
}
.ch-page__footer--right { justify-content: space-between; }
.ch-page__flip-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: none;
    border: none;
    color: #b45309;
    font-family: var(--font-sans);
    font-size: 0.8rem;
    font-weight: 700;
    cursor: pointer;
    padding: 0;
    transition: var(--transition);
}
.ch-page__flip-btn:hover { color: #d97706; }
.ch-page__flip-btn:disabled { opacity: 0.3; cursor: not-allowed; }
.ch-page__footer-num {
    font-family: var(--font-serif);
    font-style: italic;
    font-size: 0.85rem;
    color: #c4a882;
}

/* Book tabs */
.ch-book-tabs {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 24px;
    max-width: 1200px;
    width: 100%;
}
.ch-book-tab {
    padding: 8px 16px;
    border-radius: 100px;
    border: 1px solid var(--dark-border);
    background: var(--glass-bg);
    color: var(--text-secondary);
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    white-space: nowrap;
}
.ch-book-tab:hover { border-color: rgba(245, 158, 11, 0.4); color: var(--amber-light); }
.ch-book-tab.active {
    background: var(--gold-gradient);
    border-color: transparent;
    color: #1a0a00;
}

/* ============================================================
   MODAL
   ============================================================ */
.ch-modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 200;
    background: rgba(0, 0, 0, 0.75);
    backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
}
.ch-modal {
    position: relative;
    width: 100%;
    max-width: 880px;
    max-height: 90vh;
    background: linear-gradient(135deg, #15100b 0%, #1e1409 100%);
    border: 1px solid rgba(245, 158, 11, 0.25);
    border-radius: var(--radius-xl);
    overflow: hidden;
    display: grid;
    grid-template-columns: 380px 1fr;
    box-shadow: 0 40px 100px rgba(0, 0, 0, 0.8), 0 0 0 1px rgba(245, 158, 11, 0.1);
    animation: ch-modal-in 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
}
@keyframes ch-modal-in {
    from { opacity: 0; transform: scale(0.88) translateY(20px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}

.ch-modal__close {
    position: absolute;
    top: 16px; right: 16px;
    z-index: 10;
    width: 36px; height: 36px;
    border-radius: 50%;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,0.12);
    color: var(--text-secondary);
    font-size: 1rem;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: var(--transition);
    padding: 0;
}
.ch-modal__close:hover { background: rgba(245, 158, 11, 0.2); color: var(--amber); border-color: rgba(245, 158, 11, 0.4); }

.ch-modal__img-wrap {
    position: relative;
    overflow: hidden;
    background: #0c0907;
}
.ch-modal__img {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.5s ease;
}
.ch-modal:hover .ch-modal__img { transform: scale(1.05); }
.ch-modal__img-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(12,9,7,0.6) 0%, transparent 50%);
    pointer-events: none;
}
.ch-modal__img-badges {
    position: absolute;
    bottom: 16px; left: 16px;
    display: flex; gap: 8px; flex-wrap: wrap;
}

.ch-modal__info {
    padding: 32px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.ch-modal__info::-webkit-scrollbar { width: 4px; }
.ch-modal__info::-webkit-scrollbar-thumb { background: rgba(245, 158, 11, 0.3); border-radius: 4px; }

.ch-modal__breadcrumb {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.75rem;
    color: var(--text-muted);
    font-weight: 500;
}
.ch-modal__breadcrumb i { font-size: 0.7rem; }
.ch-modal__breadcrumb-sep { font-size: 0.6rem; }

.ch-modal__name {
    font-family: var(--font-serif);
    font-size: 2rem;
    font-weight: 900;
    color: var(--text-primary);
    margin: 0;
    line-height: 1.15;
}

.ch-modal__price-wrap { display: flex; flex-direction: column; gap: 2px; }
.ch-modal__price-label {
    font-size: 0.72rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
}
.ch-modal__price {
    font-family: var(--font-serif);
    font-size: 2.4rem;
    font-weight: 900;
    background: var(--gold-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1;
}
.ch-modal__price small { font-size: 0.55em; }

.ch-modal__divider {
    height: 1px;
    background: linear-gradient(90deg, var(--dark-border), transparent);
}

.ch-modal__section-title {
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--amber-light);
    text-transform: uppercase;
    letter-spacing: 1px;
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0 0 10px;
}
.ch-modal__desc {
    font-size: 0.92rem;
    color: var(--text-secondary);
    line-height: 1.7;
    margin: 0;
}

.ch-modal__barista-tip {
    background: rgba(245, 158, 11, 0.06);
    border: 1px solid rgba(245, 158, 11, 0.2);
    border-radius: var(--radius-md);
    padding: 16px;
}
.ch-modal__tip-header {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--amber-light);
    margin-bottom: 8px;
}
.ch-modal__tip-header i { color: var(--amber); }
.ch-modal__tip-body {
    font-size: 0.85rem;
    color: var(--text-secondary);
    line-height: 1.55;
    margin: 0;
}

.ch-modal__meta-row {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    margin-top: auto;
    padding-top: 16px;
    border-top: 1px solid var(--dark-border);
}
.ch-modal__meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--text-muted);
}
.ch-modal__meta-item i { color: var(--amber); font-size: 0.8rem; }

/* Modal Transition */
.ch-modal-fade-enter-active { animation: ch-modal-overlay-in 0.3s ease; }
.ch-modal-fade-leave-active { animation: ch-modal-overlay-out 0.25s ease; }
@keyframes ch-modal-overlay-in { from { opacity: 0; } to { opacity: 1; } }
@keyframes ch-modal-overlay-out { from { opacity: 1; } to { opacity: 0; } }

/* ============================================================
   FOOTER
   ============================================================ */
.ch-footer {
    position: relative;
    z-index: 1;
    border-top: 1px solid var(--dark-border);
    background: rgba(12, 9, 7, 0.7);
    backdrop-filter: blur(12px);
    padding: 24px 32px;
    margin-top: auto;
}
.ch-footer__inner {
    max-width: 1280px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    gap: 24px;
    flex-wrap: wrap;
    justify-content: space-between;
}
.ch-footer__brand {
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: var(--font-serif);
    font-size: 1rem;
    font-weight: 700;
    color: var(--amber-light);
}
.ch-footer__info {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}
.ch-footer__info span {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 0.82rem;
    color: var(--text-muted);
    font-weight: 500;
}
.ch-footer__info i { color: var(--amber); font-size: 0.75rem; }
.ch-footer__copy { font-size: 0.75rem; color: var(--text-muted); }

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 1024px) {
    .ch-hero {
        grid-template-columns: 1fr;
        padding: 60px 24px 40px;
        min-height: auto;
        gap: 40px;
        text-align: center;
    }
    .ch-hero__visual { display: none; }
    .ch-hero__sub { margin: 0 auto 36px; }
    .ch-hero__stats { justify-content: center; }
    .ch-hero__cta { margin: 0 auto; }
    .ch-book { height: auto; min-height: 700px; }
    .ch-book__pages { flex-direction: column; }
    .ch-book__page { padding: 24px; }
    .ch-modal { grid-template-columns: 1fr; max-height: 95vh; }
    .ch-modal__img-wrap { height: 260px; }
}

@media (max-width: 768px) {
    .ch-navbar__inner { gap: 12px; }
    .ch-search { max-width: 200px; }
    .ch-badge-info { display: none; }
    .ch-hero__title { font-size: 2.4rem; }
    .ch-filter { padding: 12px 16px; top: 60px; }
    .ch-grid-section { padding: 32px 16px 60px; }
    .ch-grid { grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px; }
    .ch-book-section { padding: 24px 12px 60px; }
    .ch-book { border-width: 8px; }
    .ch-footer__inner { flex-direction: column; text-align: center; }
    .ch-footer__info { justify-content: center; }
}

@media (max-width: 480px) {
    .ch-grid { grid-template-columns: 1fr; }
    .ch-hero__stats { gap: 20px; }
    .ch-modal__info { padding: 20px; }
}
</style>
