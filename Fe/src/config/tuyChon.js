/**
 * Cấu hình tập trung cho Size, Đá, Đường và Topping
 * CoffeeHub AI - CDIO 4
 */

export const SIZES = [
  { ma: 'S', ten: 'Size Nhỏ (S)', gia: 0 },
  { ma: 'M', ten: 'Size Vừa (M)', gia: 5000 },
  { ma: 'L', ten: 'Size Lớn (L)', gia: 10000 }
];

export const DA_OPTIONS = [
  { ma: '100%', ten: '100% Đá (Bình thường)', gia: 0 },
  { ma: '70%', ten: '70% Đá', gia: 0 },
  { ma: '50%', ten: '50% Đá (Ít đá)', gia: 0 },
  { ma: '0%', ten: 'Không đá', gia: 0 },
  { ma: 'NONG', ten: 'Uống nóng', gia: 0 }
];

export const DUONG_OPTIONS = [
  { ma: '100%', ten: '100% Đường (Chuẩn)', gia: 0 },
  { ma: '70%', ten: '70% Đường', gia: 0 },
  { ma: '50%', ten: '50% Đường (Ít ngọt)', gia: 0 },
  { ma: '30%', ten: '30% Đường', gia: 0 },
  { ma: '0%', ten: 'Không đường', gia: 0 }
];

export const TOPPINGS = [
  { id: 'tran_chau_den', ten: 'Trân châu đen', gia: 5000 },
  { id: 'tran_chau_trang', ten: 'Trân châu trắng', gia: 5000 },
  { id: 'thach_ca_phe', ten: 'Thạch cà phê', gia: 5000 },
  { id: 'kem_cheese', ten: 'Kem Cheese béo', gia: 10000 }
];

/**
 * Định dạng đối tượng tùy chọn (hoặc JSON string) thành chuỗi văn bản dễ đọc
 * Phục vụ hiển thị tại giỏ hàng khách, POS thu ngân, Bếp KDS và Hóa đơn in.
 */
export function formatTuyChonText(tuyChon) {
  if (!tuyChon) return '';
  let obj = tuyChon;
  if (typeof tuyChon === 'string') {
    try {
      obj = JSON.parse(tuyChon);
    } catch (e) {
      return tuyChon;
    }
  }

  const parts = [];

  // Size
  if (obj.size && obj.size.ma) {
    parts.push(`Size ${obj.size.ma}`);
  } else if (typeof obj.size === 'string') {
    parts.push(`Size ${obj.size}`);
  }

  // Đá
  let daStr = '';
  if (obj.da && obj.da.ten) daStr = obj.da.ten;
  else if (obj.da && obj.da.ma) daStr = obj.da.ma;
  else if (typeof obj.da === 'string') daStr = obj.da;

  if (daStr) {
    const hasDa = daStr.toLowerCase().includes('đá') || daStr.toUpperCase() === 'NONG' || daStr.toLowerCase().includes('nóng');
    parts.push(hasDa ? daStr : `${daStr} đá`);
  }

  // Đường
  let duongStr = '';
  if (obj.duong && obj.duong.ten) duongStr = obj.duong.ten;
  else if (obj.duong && obj.duong.ma) duongStr = obj.duong.ma;
  else if (typeof obj.duong === 'string') duongStr = obj.duong;

  if (duongStr) {
    const hasDuong = duongStr.toLowerCase().includes('đường');
    parts.push(hasDuong ? duongStr : `${duongStr} đường`);
  }

  // Topping
  if (Array.isArray(obj.toppings) && obj.toppings.length > 0) {
    const toppingNames = obj.toppings.map(t => {
      if (typeof t === 'string') return t;
      return t.ten || t.name || t.id;
    });
    parts.push(`+ ${toppingNames.join(', ')}`);
  }

  return parts.join(' • ');
}

/**
 * Phân tích tùy chọn thành danh sách badge/tag riêng biệt có màu sắc để tự động xuống dòng (flex-wrap),
 * tránh tràn bảng và hiển thị trực quan cho Barista KDS và Thu ngân POS.
 */
export function getTuyChonBadges(tuyChon) {
  if (!tuyChon) return [];
  let obj = tuyChon;
  if (typeof tuyChon === 'string') {
    try {
      obj = JSON.parse(tuyChon);
    } catch (e) {
      return [{ text: tuyChon, class: 'bg-light text-secondary border' }];
    }
  }

  const badges = [];

  // 1. Size
  if (obj.size) {
    const ma = obj.size.ma || (typeof obj.size === 'string' ? obj.size : '');
    if (ma) {
      badges.push({
        text: `Size ${ma}`,
        class: ma === 'L' ? 'bg-primary text-white fw-bold shadow-xs' : (ma === 'M' ? 'bg-info-subtle text-info-emphasis border' : 'bg-light text-secondary border')
      });
    }
  }

  // 2. Đá
  let daStr = '';
  if (obj.da && obj.da.ten) daStr = obj.da.ten;
  else if (obj.da && obj.da.ma) daStr = obj.da.ma;
  else if (typeof obj.da === 'string') daStr = obj.da;

  if (daStr) {
    let label = daStr;
    const hasDa = label.toLowerCase().includes('đá') || label.toUpperCase() === 'NONG' || label.toLowerCase().includes('nóng');
    if (!hasDa) label = `${label} đá`;

    badges.push({
      text: label,
      class: label.toLowerCase().includes('không') || label.toLowerCase().includes('nóng')
        ? 'bg-danger-subtle text-danger border border-danger-subtle fw-bold'
        : 'bg-primary-subtle text-primary border border-primary-subtle'
    });
  }

  // 3. Đường
  let duongStr = '';
  if (obj.duong && obj.duong.ten) duongStr = obj.duong.ten;
  else if (obj.duong && obj.duong.ma) duongStr = obj.duong.ma;
  else if (typeof obj.duong === 'string') duongStr = obj.duong;

  if (duongStr) {
    let label = duongStr;
    if (!label.toLowerCase().includes('đường')) label = `${label} đường`;

    badges.push({
      text: label,
      class: label.toLowerCase().includes('không') || label.toLowerCase().includes('ít') || label.includes('30%') || label.includes('0%')
        ? 'bg-warning-subtle text-warning-emphasis border border-warning-subtle'
        : 'bg-light text-dark border'
    });
  }

  // 4. Toppings
  if (Array.isArray(obj.toppings) && obj.toppings.length > 0) {
    obj.toppings.forEach(t => {
      const name = typeof t === 'string' ? t : (t.ten || t.name || t.id);
      if (name) {
        badges.push({
          text: `+ ${name}`,
          class: 'bg-success-subtle text-success-emphasis border border-success-subtle fw-semibold'
        });
      }
    });
  }

  return badges;
}
