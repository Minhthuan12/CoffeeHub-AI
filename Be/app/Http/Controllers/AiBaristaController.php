<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AiChatSession;
use App\Models\AiChatMessage;
use App\Models\AiRecommendationLog;
use App\Models\SanPham;
use App\Services\AiBaristaService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AiBaristaController extends Controller
{
    protected $aiBaristaService;

    public function __construct(AiBaristaService $aiBaristaService)
    {
        $this->aiBaristaService = $aiBaristaService;
    }

    /**
     * Bắt đầu một phiên trò chuyện mới với AI Barista
     */
    public function createSession(Request $request)
    {
        $tableId = $request->table_id ?? null;

        $session = AiChatSession::create([
            'table_id'   => $tableId,
            'start_time' => Carbon::now(),
            'status'     => 'active',
        ]);

        $welcomeMessage = "Xin chào! Tôi là Trợ lý AI Barista của CoffeeHub ☕. Rất vui được đồng hành cùng bạn hôm nay! Bạn đang tìm kiếm một món uống như thế nào? (Ví dụ: ít ngọt thanh mát, cà phê tỉnh táo cho buổi sáng, hay món signature béo ngậy của quán?)";

        AiChatMessage::create([
            'session_id' => $session->id,
            'sender'     => 'AI',
            'content'    => $welcomeMessage,
            'metadata'   => ['type' => 'welcome'],
            'timestamp'  => Carbon::now(),
        ]);

        return response()->json([
            'status'     => 1,
            'message'    => 'Khởi tạo phiên hội thoại AI Barista thành công',
            'session_id' => $session->id,
            'table_id'   => $session->table_id,
            'welcome'    => $welcomeMessage
        ]);
    }

    /**
     * Gửi tin nhắn trò chuyện và nhận tư vấn thông minh từ AI Barista (UC08)
     */
    public function chat(Request $request)
    {
        $request->validate([
            'session_id' => 'required',
            'message'    => 'required|string|max:1000',
        ]);

        $session = AiChatSession::find($request->session_id);
        if (!$session) {
            $session = AiChatSession::create([
                'table_id'   => $request->table_id ?? null,
                'start_time' => Carbon::now(),
                'status'     => 'active',
            ]);
        }

        $now = Carbon::now();

        // 1. Lưu tin nhắn của khách
        AiChatMessage::create([
            'session_id' => $session->id,
            'sender'     => 'User',
            'content'    => $request->message,
            'timestamp'  => $now,
        ]);

        // 2. Xử lý tư vấn bằng AI Barista Service
        $aiResult = $this->aiBaristaService->generateBaristaResponse(
            $request->message,
            $session->table_id,
            $request->weather ?? null
        );

        $recommendedProducts = $aiResult['recommendations'] ?? [];
        $recIds = [];
        if (!empty($recommendedProducts)) {
            foreach ($recommendedProducts as $p) {
                $recIds[] = is_object($p) ? $p->id : $p['id'];
            }
        }

        // 3. Lưu câu trả lời của AI
        AiChatMessage::create([
            'session_id' => $session->id,
            'sender'     => 'AI',
            'content'    => $aiResult['reply'],
            'metadata'   => [
                'source'           => $aiResult['source'] ?? 'heuristic',
                'recommended_ids'  => $recIds,
            ],
            'timestamp'  => Carbon::now(),
        ]);

        // 4. Lưu nhật ký gợi ý vào ai_recommendation_logs để đo lường chuyển đổi (Conversion Rate)
        foreach ($recIds as $productId) {
            AiRecommendationLog::create([
                'session_id'   => $session->id,
                'product_id'   => $productId,
                'is_purchased' => 0,
                'created_at'   => Carbon::now(),
            ]);
        }

        return response()->json([
            'status'          => 1,
            'session_id'      => $session->id,
            'reply'           => $aiResult['reply'],
            'recommendations' => $recommendedProducts,
            'source'          => $aiResult['source'] ?? 'heuristic'
        ]);
    }

    /**
     * Lấy toàn bộ lịch sử trò chuyện trong phiên
     */
    public function getSessionHistory($sessionId)
    {
        $session = AiChatSession::with('messages')->find($sessionId);
        if (!$session) {
            return response()->json([
                'status'  => 0,
                'message' => 'Phiên hội thoại không tồn tại'
            ], 404);
        }

        return response()->json([
            'status'   => 1,
            'data'     => [
                'session_id' => $session->id,
                'table_id'   => $session->table_id,
                'status'     => $session->status,
                'start_time' => $session->start_time,
                'messages'   => $session->messages
            ]
        ]);
    }

    /**
     * Ghi nhận khách hàng chọn mua món từ gợi ý của AI Barista (+ Thêm vào giỏ)
     */
    public function markPurchased(Request $request)
    {
        $request->validate([
            'session_id' => 'required',
            'product_id' => 'required',
        ]);

        $updated = AiRecommendationLog::where('session_id', $request->session_id)
            ->where('product_id', $request->product_id)
            ->update(['is_purchased' => 1, 'updated_at' => Carbon::now()]);

        return response()->json([
            'status'  => 1,
            'message' => 'Đã ghi nhận chuyển đổi chốt đơn thành công từ AI Barista',
            'updated' => $updated
        ]);
    }

    /**
     * Thống kê hiệu quả tư vấn và tỷ lệ chuyển đổi của AI Barista (Báo cáo Quản lý)
     */
    public function getAnalytics()
    {
        $totalSessions = AiChatSession::count();
        $totalMessages = AiChatMessage::count();
        $totalRecommendations = AiRecommendationLog::count();
        $totalPurchased = AiRecommendationLog::where('is_purchased', 1)->count();

        $conversionRate = $totalRecommendations > 0 
            ? round(($totalPurchased / $totalRecommendations) * 100, 1) 
            : 0;

        // Top 5 món được AI gợi ý nhiều nhất
        $topRecommended = AiRecommendationLog::join('san_phams', 'ai_recommendation_logs.product_id', '=', 'san_phams.id')
            ->select(
                'san_phams.id',
                'san_phams.ten_san_pham',
                'san_phams.gia_ban',
                DB::raw('COUNT(ai_recommendation_logs.id) as so_lan_goi_y'),
                DB::raw('SUM(ai_recommendation_logs.is_purchased) as so_lan_chot_mua')
            )
            ->groupBy('san_phams.id', 'san_phams.ten_san_pham', 'san_phams.gia_ban')
            ->orderByDesc('so_lan_goi_y')
            ->limit(5)
            ->get();

        return response()->json([
            'status' => 1,
            'data'   => [
                'total_sessions'        => $totalSessions,
                'total_messages'        => $totalMessages,
                'total_recommendations' => $totalRecommendations,
                'total_purchased'       => $totalPurchased,
                'conversion_rate_pct'   => $conversionRate,
                'top_recommended'       => $topRecommended
            ]
        ]);
    }

    /**
     * AI Copilot: Dự báo tiêu thụ nguyên vật liệu 3 ngày tới (UC36)
     */
    public function getCopilotForecast()
    {
        $forecast = $this->aiBaristaService->getInventoryForecast();

        return response()->json([
            'status' => 1,
            'data'   => $forecast
        ]);
    }
}
