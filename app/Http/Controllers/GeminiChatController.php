<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GeminiChatController extends Controller
{
    /**
     * Hiển thị trang chat
     */
    public function index()
    {
        return view('chat');
    }

    /**
     * Xử lý tin nhắn và trả về phản hồi từ Google Gemini
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'session_id' => 'nullable|string',
        ]);

        $message = $request->input('message');
        $sessionId = $request->input('session_id', Str::uuid()->toString());

        try {
            // Kiểm tra API key đã cấu hình chưa
            $apiKey = config('services.gemini.api_key');
            if (empty($apiKey)) {
                Log::error('Gemini API key is not configured');
                return response()->json([
                    'message' => 'API key Gemini chưa được cấu hình. Vui lòng liên hệ quản trị viên.',
                    'session_id' => $sessionId,
                    'error' => 'api_key_missing'
                ], 500);
            }

            // Lấy lịch sử chat từ session (nếu có)
            $chatHistory = session("chat_history_{$sessionId}", []);

            // Chuẩn bị nội dung trò chuyện
            $systemMessage = $this->getSystemPrompt();

            // Số lượng tin nhắn lịch sử tối đa để thêm vào context
            $maxHistory = 5;

            // Xây dựng nội dung context từ lịch sử
            $conversationContext = $systemMessage . "\n\n";

            // Thêm lịch sử chat (giới hạn theo cấu hình)
            $recentHistory = array_slice($chatHistory, -$maxHistory);
            foreach ($recentHistory as $chat) {
                if ($chat['role'] == 'user') {
                    $conversationContext .= "Người dùng: " . $chat['content'] . "\n";
                } else {
                    $conversationContext .= "Trợ lý: " . $chat['content'] . "\n";
                }
            }

            // Thêm tin nhắn mới của người dùng
            $conversationContext .= "Người dùng: " . $message . "\n";
            $conversationContext .= "Trợ lý: ";

            // Ghi log tin nhắn gửi đi để debug
            Log::info('Sending to Gemini: ', ['context_length' => strlen($conversationContext)]);

            // Chuẩn bị dữ liệu để gửi tới Gemini API - sử dụng model tương thích
            $endpoint = "https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash-002:generateContent?key={$apiKey}";

            $data = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $conversationContext]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 500,
                ]
            ];

            // Gọi API Gemini
            $response = Http::timeout(30)
                ->post($endpoint, $data);

            if (!$response->successful()) {
                Log::error('Gemini API Error: ', ['status' => $response->status(), 'body' => $response->body()]);
                throw new \Exception('Gemini API returned error: ' . $response->body());
            }

            $result = $response->json();

            // Lấy phản hồi từ API
            $aiResponse = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Xin lỗi, tôi không thể xử lý yêu cầu của bạn lúc này.';

            // Lưu vào lịch sử chat
            $chatHistory[] = ['role' => 'user', 'content' => $message];
            $chatHistory[] = ['role' => 'assistant', 'content' => $aiResponse];
            session(["chat_history_{$sessionId}" => $chatHistory]);

            return response()->json([
                'message' => $aiResponse,
                'session_id' => $sessionId
            ]);

        } catch (\Exception $e) {
            Log::error('Gemini Error: ' . $e->getMessage());

            // Kiểm tra các loại lỗi cụ thể
            if (strpos($e->getMessage(), 'API_KEY_INVALID') !== false) {
                return response()->json([
                    'message' => 'API key không hợp lệ. Vui lòng kiểm tra cấu hình.',
                    'session_id' => $sessionId,
                    'error' => 'invalid_api_key'
                ], 401);
            } elseif (strpos($e->getMessage(), 'PERMISSION_DENIED') !== false || strpos($e->getMessage(), 'QUOTA_EXCEEDED') !== false) {
                return response()->json([
                    'message' => 'Đã vượt quá giới hạn quota của Gemini API. Vui lòng liên hệ quản trị viên.',
                    'session_id' => $sessionId,
                    'error' => 'quota_exceeded'
                ], 429);
            }

            // Lỗi chung khác
            return response()->json([
                'message' => 'Có lỗi xảy ra khi xử lý yêu cầu: ' . $e->getMessage(),
                'session_id' => $sessionId,
                'error' => 'general_error'
            ], 500);
        }
    }

    /**
     * Tạo prompt hệ thống có chứa kiến thức y tế và thông tin trang web
     */
    private function getSystemPrompt()
    {
        return "Bạn là trợ lý AI thông minh của Medic Hospital, được thiết kế để cung cấp thông tin y tế và hỗ trợ bệnh nhân. Bạn cũng có thể trả lời các câu hỏi tổng quát khác ngoài lĩnh vực y tế.

THÔNG TIN VỀ MEDIC HOSPITAL:
- Bệnh viện đa khoa Medic Hospital được thành lập cách đây 10 năm
- Địa chỉ: 9500 Euclid Avenue, Vietnam
- Hotline: 037.864.9957 (tổng đài) và 0924.184.107 (hỗ trợ)
- Giờ làm việc: 8:00-17:00 hàng ngày, khoa cấp cứu phục vụ 24/7
- Website: medicvn.com
- Chúng tôi có hơn 200 bác sĩ chuyên khoa và 500 giường bệnh
- Các khoa chính: Nội, Ngoại, Sản, Nhi, Tim mạch, Thần kinh, Tiêu hóa, Hô hấp, Cấp cứu
- Chúng tôi chấp nhận hầu hết các loại bảo hiểm y tế

KIẾN THỨC Y TẾ CƠ BẢN:
1. Các triệu chứng của cảm cúm: sốt, ho, đau họng, đau nhức cơ thể, mệt mỏi
2. Đột quỵ - dấu hiệu FAST: Face (mặt méo), Arms (tay yếu), Speech (nói khó), Time (thời gian quan trọng)
3. Đau tim: đau ngực, khó thở, đau lan lên cánh tay trái
4. Tiểu đường: khát nước, đi tiểu nhiều, mệt mỏi, sụt cân không rõ nguyên nhân
5. Huyết áp cao: nhức đầu, chóng mặt, mờ mắt, khó thở
6. Các biện pháp phòng bệnh: ăn uống lành mạnh, tập thể dục đều đặn, không hút thuốc, hạn chế rượu bia, khám sức khỏe định kỳ

DỊCH VỤ CHÍNH:
1. Đặt lịch khám qua website hoặc hotline 037.864.9957
2. Khám chuyên khoa với bác sĩ giỏi
3. Xét nghiệm và chẩn đoán hình ảnh hiện đại
4. Phẫu thuật và điều trị nội trú
5. Khám sức khỏe tổng quát
6. Tư vấn dinh dưỡng và lối sống
7. Cấp cứu 24/7

NGUYÊN TẮC TRẢ LỜI:
1. PHÂN TÍCH CÂU HỎI: Xác định chính xác mục đích của người dùng.
2. ƯU TIÊN Y TẾ: Nếu câu hỏi liên quan đến y tế, sức khỏe hoặc bệnh viện, ưu tiên trả lời với thông tin chính xác.
3. CÂU HỎI NGOÀI Y TẾ: Đối với câu hỏi không liên quan đến y tế, trả lời như một trợ lý AI thông thường với kiến thức rộng.
4. GIỮ GIỌNG ĐIỆU CHUYÊN NGHIỆP: Luôn lịch sự, hữu ích và mang tính hỗ trợ.
5. RÕ RÀNG & NGẮN GỌN: Trả lời dưới 5 câu, dùng ngôn ngữ dễ hiểu.
6. KHUYẾN CÁO Y TẾ: Luôn nhắc nhở người dùng tham khảo ý kiến bác sĩ cho các vấn đề sức khỏe nghiêm trọng.

Lưu ý: Thông tin y tế này mang tính chất tham khảo và không thay thế cho tư vấn y tế chuyên nghiệp. Nếu người dùng có vấn đề sức khỏe nghiêm trọng, hãy khuyên họ liên hệ bác sĩ hoặc đến bệnh viện ngay lập tức.";
    }

    /**
     * Xóa lịch sử chat
     */
    public function clearChat(Request $request)
    {
        $sessionId = $request->input('session_id');

        if ($sessionId) {
            session()->forget("chat_history_{$sessionId}");
        }

        return response()->json([
            'success' => true,
            'message' => 'Lịch sử chat đã được xóa'
        ]);
    }
}