/**
 * CoffeeHub Frontend - Core UI Utilities
 */

// Format tiền tệ VNĐ
function formatVND(amount) {
  return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
}

// Hiển thị Toast thông báo nhanh
function showToast(message, type = 'info') {
  const toast = document.createElement('div');
  const bgColors = {
    success: 'bg-emerald-600',
    error: 'bg-rose-600',
    warning: 'bg-amber-600',
    info: 'bg-slate-800'
  };
  toast.className = `fixed bottom-5 right-5 z-50 text-white px-4 py-3 rounded-xl shadow-2xl flex items-center gap-3 text-sm font-medium transition-all transform duration-300 ${bgColors[type] || bgColors.info}`;
  toast.innerHTML = `<i class="fa-solid fa-circle-info"></i> <span>${message}</span>`;
  document.body.appendChild(toast);
  setTimeout(() => {
    toast.classList.add('opacity-0', 'translate-y-2');
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}

console.log('CoffeeHub App UI Utilities ready.');
