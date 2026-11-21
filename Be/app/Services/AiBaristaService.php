<?php

namespace App\Services;

use App\Models\SanPham;
use App\Models\NguyenLieu;
use App\Models\CongThucMon;
use App\Models\HoaDon;
use App\Models\ChiTietHoaDon;
use App\Models\NhaCungCap;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AiBaristaService
{
    /**
     * Tạo câu trả lời tư vấn đồ uống từ AI Barista
     *
     * @param string $userMessage Câu hỏi/yêu cầu của khách
     * @param int|null $tableId Bàn khách đang ngồi
     * @param string|null $weather Ngữ cảnh thời tiết (nếu có: nắng nóng, mưa lạnh,...)
     * @return array [ 'reply' => string, 'recommendations' => array, 'source' => 'gemini'|'heuristic' ]
     */
    public function generateBaristaResponse(string $userMessage, ?int $tableId = null, ?string $weather = null): array
    {
        // 1. Lấy danh sách thực đơn còn hàng
        $availableProducts = $this->getAvailableProducts();

        // 2. Thử gọi Google Gemini API nếu có cấu hình API Key
        $apiKey = env('GEMINI_API_KEY');
        if (!empty($apiKey)) {
            try {
                $geminiResponse = $this->callGeminiApi($userMessage, $availableProducts, $weather, $apiKey);
                if ($geminiResponse) {
                    return $geminiResponse;
                }
            } catch (\Exception $e) {
                Log::warning('Gemini API call failed, falling back to Heuristic Engine: ' . $e->getMessage());
            }
        }

        // 3. Fallback: Heuristic AI Barista Engine (Nghiệp vụ AI nội tại đảm bảo sẵn sàng 100%)
        return $this->heuristicBaristaEngine($userMessage, $availableProducts, $weather);
    }

    /**
     * Lấy các món đang hoạt động và còn nguyên liệu
     */
    protected function getAvailableProducts()
    {
        return SanPham::where('tinh_trang', 1)
            ->select('id', 'ten_san_pham', 'gia_ban', 'kich_thuoc', 'mo_ta', 'hinh_anh', 'id_danh_muc')
            ->get();
    }

    /**
     * Gọi Google Gemini API (gemini-1.5-flash)
     */
    protected function callGeminiApi(string $userMessage, $products, ?string $weather, string $apiKey): ?array
    {
        $menuList = [];
        foreach ($products as $p) {
            $menuList[] = sprintf(
                "ID: %d | Tên: %s (%s) | Giá: %s đ | Mô tả: %s",
                $p->id,
                $p->ten_san_pham,
                $p->kich_thuoc,
                number_format($p->gia_ban, 0, ',', '.'),
                $p->mo_ta ?? 'Thức uống đặc trưng CoffeeHub'
            );
        }
        $menuContext = implode("\n", $menuList);

        $systemPrompt = "Bạn là Trợ lý AI Barista độc quyền của quán CoffeeHub.
Tính cách: Chu đáo, am hiểu sâu sắc về cà phê & trà, tư vấn lịch thiệp, tạo cảm giác thư giãn cho khách.
QUY TẮC BẮT BUỘC:
1. CHỈ ĐƯỢC GỢI Ý các món có trong Danh mục thực đơn CoffeeHub dưới đây. Tuyệt đối không gợi ý món ngoài menu.
2. Trả lời bằng tiếng Việt tự nhiên, thân thiện.
3. Gợi ý từ 1 đến 2 món phù hợp nhất với khẩu vị/tâm trạng của khách. Kèm theo gợi ý combo hoặc topping (ví dụ thêm trân châu, kem muối, hoặc bánh ngọt) để upsell tinh tế.
4. Cuối câu trả lời, trả về một khối JSON theo định dạng chuẩn:
```json
{
  \"recommended_ids\": [mã_id_1, mã_id_2],
  \"upsell_topping\": \"Tên topping hoặc combo gợi ý thêm\"
}
```

DANH MỤC THỰC ĐƠN HIỆN CÓ CỦA COFFEEHUB:
{$menuContext}
";

        $userPrompt = "Khách hàng nói: \"{$userMessage}\"";
        if (!empty($weather)) {
            $userPrompt .= " (Ngữ cảnh thời tiết hiện tại: {$weather})";
        }

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}";

        $response = Http::timeout(6)->post($url, [
            'contents' => [
                [
                    'role'  => 'user',
                    'parts' => [
                        ['text' => $systemPrompt . "\n\n" . $userPrompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature'     => 0.7,
                'maxOutputTokens' => 800,
            ]
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $rawText = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if ($rawText) {
                // Tách JSON recommendation nếu có
                $recommendedIds = [];
                $cleanReply = $rawText;

                if (preg_match('/```json\s*(\{.*?\})\s*```/s', $rawText, $matches)) {
                    $jsonContent = json_decode($matches[1], true);
                    if (!empty($jsonContent['recommended_ids'])) {
                        $recommendedIds = $jsonContent['recommended_ids'];
                    }
                    $cleanReply = trim(str_replace($matches[0], '', $rawText));
                }

                $recProducts = $products->whereIn('id', $recommendedIds)->values();

                return [
                    'reply'           => $cleanReply,
                    'recommendations' => $recProducts,
                    'source'          => 'gemini'
                ];
            }
        }

        return null;
    }

    /**
     * Heuristic Barista Engine (Quy tắc gợi ý chuyên nghiệp theo khẩu vị & ngữ cảnh)
     */
    protected function heuristicBaristaEngine(string $userMessage, $products, ?string $weather): array
    {
        $text = mb_strtolower($userMessage, 'UTF-8');
        $matchedIds = [];
        $advice = "";
        $upsell = "";

        // 1. Phân loại theo nhu cầu khẩu vị & tâm trạng
        if (preg_match('/(nắng|nóng|thanh mát|giải nhiệt|khát|mát mẻ|trưa|hè)/u', $text)) {
            // Nhóm trà trái cây giải nhiệt
            $advice = "Chào bạn! Hôm nay trời oi bức, Barista CoffeeHub gợi ý bạn thưởng thức dòng **Trà Trái Cây Thanh Mát** giúp giải nhiệt tức thì, vị chua ngọt tự nhiên sảng khoái.";
            $matched = $products->filter(function ($p) {
                return preg_match('/(trà|đào|mãng cầu|dâu|chanh|vải|cam)/ui', $p->ten_san_pham);
            });
            $matchedIds = $matched->pluck('id')->take(2)->toArray();
            $upsell = "Bạn có thể chọn thêm topping **Trân Châu Trắng** hoặc **Đào Miếng** để ly nước thêm phần trọn vị nhé!";
        } elseif (preg_match('/(tỉnh táo|tập trung|buổi sáng|sáng|đậm|cà phê đen|espresso|robusta|mệt|ngủ gật)/u', $text)) {
            // Nhóm cà phê truyền thống & pha máy đậm vị
            $advice = "Để khởi đầu ngày mới tràn đầy năng lượng hoặc cần sự tỉnh táo tập trung cao độ, Barista xin gợi ý ly **Cà Phê Đậm Vị** rang mộc chuẩn gu người Việt:";
            $matched = $products->filter(function ($p) {
                return preg_match('/(đen|espresso|americano|sữa đá)/ui', $p->ten_san_pham);
            });
            $matchedIds = $matched->pluck('id')->take(2)->toArray();
            $upsell = "Mách nhỏ: Bạn có thể thêm 1 shot Espresso tăng cường độ đậm nếu cần chạy deadline nhé!";
        } elseif (preg_match('/(ngọt|béo|muối|matcha|trà sữa|thư giãn|êm dịu|kem)/u', $text)) {
            // Nhóm signature béo thơm, êm dịu
            $advice = "Nếu bạn yêu thích vị ngọt ngào, êm dịu xen lẫn chút béo ngậy tinh tế, đừng bỏ lỡ món **Signature đặc sắc** được yêu thích nhất tại CoffeeHub:";
            $matched = $products->filter(function ($p) {
                return preg_match('/(muối|dừa|matcha|trà sữa|cappuccino)/ui', $p->ten_san_pham);
            });
            $matchedIds = $matched->pluck('id')->take(2)->toArray();
            $upsell = "Gợi ý: Lớp **Kem Cheese Béo Ngậy** hoặc **Kem Muối Biển** kết hợp cùng món này sẽ khiến bạn mê mẩn đấy!";
        } elseif (preg_match('/(ít ngọt|ít đường|không đường|diet|healthy)/u', $text)) {
            // Nhóm ít ngọt, healthy
            $advice = "Dành riêng cho bạn thích vị thanh dịu và hạn chế đường, Barista đã chọn ra các món chuẩn phong cách sống healthy:";
            $matched = $products->filter(function ($p) {
                return preg_match('/(americano|đen|không đường|trà nhài)/ui', $p->ten_san_pham);
            });
            $matchedIds = $matched->pluck('id')->take(2)->toArray();
            $upsell = "Đừng quên để lại ghi chú: *30% đường, nhiều đá* khi nhấn Đặt Món nhé!";
        } else {
            // Mặc định: Gợi ý các món Best-seller của quán
            $advice = "Chào bạn! Barista CoffeeHub rất vui được đón tiếp bạn. Dưới đây là những món **Best-seller được đông đảo khách hàng yêu thích nhất** tại quán hôm nay:";
            $matched = $products->filter(function ($p) {
                return preg_match('/(muối|sữa đá|trà đào|cappuccino)/ui', $p->ten_san_pham);
            });
            $matchedIds = $matched->pluck('id')->take(2)->toArray();
            $upsell = "Bạn có thể bấm **+ Thêm vào giỏ** ngay bên dưới để chọn Size và mức Đường/Đá theo ý thích!";
        }

        // Đảm bảo luôn có ít nhất 1-2 món
        if (empty($matchedIds)) {
            $matchedIds = $products->take(2)->pluck('id')->toArray();
        }

        $recProducts = $products->whereIn('id', $matchedIds)->values();

        $fullReply = $advice . "\n\n" . $upsell;

        return [
            'reply'           => $fullReply,
            'recommendations' => $recProducts,
            'source'          => 'heuristic'
        ];
    }

    /**
     * AI Copilot: Dự báo tiêu thụ nguyên vật liệu 3 ngày tới (UC36)
     */
    public function getInventoryForecast(): array
    {
        // 1. Phân tích số lượng bán trong 14 ngày qua
        $startDate = Carbon::now()->subDays(14)->startOfDay();

        $sales = ChiTietHoaDon::join('hoa_dons', 'chi_tiet_hoa_dons.id_hoa_don', '=', 'hoa_dons.id')
            ->where('hoa_dons.tinh_trang', '>=', 2) // Đơn đã thanh toán
            ->where('hoa_dons.created_at', '>=', $startDate)
            ->select('chi_tiet_hoa_dons.id_san_pham', \DB::raw('SUM(chi_tiet_hoa_dons.so_luong) as tong_ban'))
            ->groupBy('chi_tiet_hoa_dons.id_san_pham')
            ->get()
            ->keyBy('id_san_pham');

        // 2. Tính mức tiêu thụ trung bình mỗi ngày theo định lượng BOM
        $dailyMaterialUsage = []; // [id_nguyen_lieu => so_luong_ngay]

        foreach ($sales as $productId => $sale) {
            $dailyQty = $sale->tong_ban / 14.0;
            $recipes = CongThucMon::where('id_san_pham', $productId)->get();

            foreach ($recipes as $r) {
                if (!isset($dailyMaterialUsage[$r->id_nguyen_lieu])) {
                    $dailyMaterialUsage[$r->id_nguyen_lieu] = 0;
                }
                $dailyMaterialUsage[$r->id_nguyen_lieu] += ($dailyQty * (float)$r->so_luong);
            }
        }

        // 3. Dự báo nhu cầu 3 ngày tiếp theo (+ 15% hệ số an toàn cuối tuần)
        $safetyFactor = 1.15;
        $forecastDays = 3;

        $materials = NguyenLieu::leftJoin('nha_cung_caps', 'nguyen_lieus.id_nha_cung_cap', '=', 'nha_cung_caps.id')
            ->select(
                'nguyen_lieus.id',
                'nguyen_lieus.ten_nguyen_lieu',
                'nguyen_lieus.don_vi_tinh',
                'nguyen_lieus.so_luong_ton',
                'nguyen_lieus.muc_canh_bao',
                'nha_cung_caps.ten_nha_cung_cap',
                'nha_cung_caps.so_dien_thoai'
            )
            ->get();

        $forecastReport = [];
        $deficitCount = 0;

        foreach ($materials as $m) {
            $dailyNeed = $dailyMaterialUsage[$m->id] ?? 0.5; // Giả lập mức tối thiểu nếu chưa có nhiều đơn
            $predictedUsage3Days = round($dailyNeed * $forecastDays * $safetyFactor, 2);
            $remainingAfter3Days = round($m->so_luong_ton - $predictedUsage3Days, 2);

            $isDeficit = ($remainingAfter3Days < $m->muc_canh_bao);
            $suggestedImport = 0;
            if ($isDeficit) {
                $deficitCount++;
                $suggestedImport = round(($m->muc_canh_bao * 2) - $remainingAfter3Days, 1);
                if ($suggestedImport < 5) $suggestedImport = 5;
            }

            $forecastReport[] = [
                'id_nguyen_lieu'       => $m->id,
                'ten_nguyen_lieu'      => $m->ten_nguyen_lieu,
                'don_vi_tinh'          => $m->don_vi_tinh,
                'ton_hien_tai'         => (float)$m->so_luong_ton,
                'muc_canh_bao'         => (float)$m->muc_canh_bao,
                'du_bao_tieu_thu_3ngay'=> $predictedUsage3Days,
                'ton_du_kien_sau_3ngay'=> $remainingAfter3Days,
                'nguy_co_thieu_hang'   => $isDeficit,
                'so_luong_can_nhap'    => $suggestedImport,
                'nha_cung_cap'         => $m->ten_nha_cung_cap ?? 'Chưa gán',
                'sdt_lien_he'          => $m->so_dien_thoai ?? ''
            ];
        }

        return [
            'forecast_period' => '3 ngày tới',
            'safety_factor'   => '+15% buffer',
            'total_materials' => count($forecastReport),
            'deficit_alerts'  => $deficitCount,
            'details'         => $forecastReport
        ];
    }
}
