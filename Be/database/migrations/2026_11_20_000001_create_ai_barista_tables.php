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
        // 1. Bảng lưu trữ phiên trò chuyện với AI Barista
        Schema::create('ai_chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('table_id')->nullable()->comment('Mã bàn nếu khách quét QR tại bàn');
            $table->dateTime('start_time')->comment('Thời gian bắt đầu phiên chat');
            $table->string('status', 20)->default('active')->comment('Trạng thái phiên: active, completed, closed');
            $table->timestamps();

            $table->index('table_id');
            $table->index('status');
        });

        // 2. Bảng lưu trữ tin nhắn hội thoại
        Schema::create('ai_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id')->comment('Mã phiên trò chuyện');
            $table->string('sender', 20)->comment('Người gửi: User hoặc AI');
            $table->text('content')->comment('Nội dung tin nhắn');
            $table->json('metadata')->nullable()->comment('Dữ liệu bổ sung: danh sách món gợi ý, ý định, thời tiết');
            $table->dateTime('timestamp')->comment('Thời gian gửi tin nhắn');
            $table->timestamps();

            $table->foreign('session_id')->references('id')->on('ai_chat_sessions')->onDelete('cascade');
            $table->index('session_id');
            $table->index('sender');
        });

        // 3. Bảng ghi nhận nhật ký gợi ý và tỷ lệ chốt đơn (Conversion Rate)
        Schema::create('ai_recommendation_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id')->comment('Mã phiên trò chuyện');
            $table->unsignedBigInteger('product_id')->comment('Mã món ăn/thức uống được AI gợi ý');
            $table->boolean('is_purchased')->default(0)->comment('Khách có chốt mua không? (1 = Có, 0 = Chưa)');
            $table->dateTime('created_at')->comment('Thời điểm gợi ý');
            $table->dateTime('updated_at')->nullable();

            $table->foreign('session_id')->references('id')->on('ai_chat_sessions')->onDelete('cascade');
            $table->index(['session_id', 'product_id']);
            $table->index('is_purchased');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_recommendation_logs');
        Schema::dropIfExists('ai_chat_messages');
        Schema::dropIfExists('ai_chat_sessions');
    }
};
