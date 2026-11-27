<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('phien_cas', function (Blueprint $table) {
            $table->id();
            $table->string('ma_phien_ca', 50)->unique()->comment('Mã phiên ca, ví dụ: PC-20261127-0001');
            $table->unsignedBigInteger('id_nhan_vien')->comment('Thu ngân / Nhân viên mở ca');
            $table->unsignedBigInteger('id_ca_lam_viec')->nullable()->comment('Ca làm việc đăng ký (nếu có)');
            $table->dateTime('thoi_gian_mo_ca')->comment('Thời gian bắt đầu mở ca');
            $table->dateTime('thoi_gian_dong_ca')->nullable()->comment('Thời gian chốt kết ca');
            $table->decimal('tien_dau_ca', 15, 2)->default(0)->comment('Tiền mặt bàn giao đầu ca trong két');
            $table->decimal('tong_thu_tien_mat', 15, 2)->default(0)->comment('Tổng tiền mặt thu được từ hóa đơn trong ca');
            $table->decimal('tong_thu_chuyen_khoan', 15, 2)->default(0)->comment('Tổng VietQR/chuyển khoản thu được trong ca');
            $table->decimal('tong_doanh_thu', 15, 2)->default(0)->comment('Tổng doanh thu bán hàng trong ca');
            $table->decimal('tien_mat_he_thong', 15, 2)->default(0)->comment('Tiền mặt hệ thống tính = Tiền đầu ca + Tiền mặt thu');
            $table->decimal('tien_mat_thuc_te', 15, 2)->nullable()->comment('Tiền mặt thực tế thu ngân đếm được khi chốt ca');
            $table->decimal('tien_chenh_lech', 15, 2)->default(0)->comment('Chênh lệch = Thực tế - Hệ thống tính');
            $table->integer('so_luong_don_hang')->default(0)->comment('Số lượng hóa đơn thanh toán thành công');
            $table->integer('so_luong_don_huy')->default(0)->comment('Số lượng hóa đơn bị hủy/từ chối');
            $table->text('ghi_chu')->nullable()->comment('Ghi chú bàn giao hoặc giải trình chênh lệch');
            $table->tinyInteger('trang_thai')->default(1)->comment('1: Đang mở (Đang phục vụ), 2: Đã chốt ca');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['id_nhan_vien', 'trang_thai']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phien_cas');
    }
};
