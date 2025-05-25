<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\ChatbotFAQ;
use App\Models\ChatbotConversation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Xử lý tin nhắn từ người dùng và trả lời
     */
    public function processMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'session_id' => 'required|string',
        ]);

        $userMessage = $request->input('message');
        $sessionId = $request->input('session_id');
        $userId = null;

        // Kiểm tra xác thực người dùng
        if (Auth::check()) {
            $userId = Auth::id();
        }

        // Lưu tin nhắn của người dùng
        $this->saveConversation($sessionId, $userId, 'user', $userMessage);

        // Kiểm tra nếu là câu hỏi về ChatGPT, AI hoặc khả năng trò chuyện
        $isGeneralQuestion = $this->isGeneralConversationQuestion($userMessage);

        // Ưu tiên sử dụng OpenAI API với tham khảo từ database
        $response = null;

        // Kiểm tra trạng thái API
        $openaiStatus = $this->checkOpenAIStatus();

        if ($openaiStatus) {
            try {
                // Với câu hỏi thông thường, tìm FAQs từ database
                $relevantFAQs = $isGeneralQuestion ? [] : $this->getRelevantFAQs($userMessage);

                // Sử dụng OpenAI API với context từ FAQs nếu có
                $response = $this->getAnswerFromAI($userMessage, $sessionId, $relevantFAQs, $isGeneralQuestion);
            } catch (\Exception $e) {
                Log::error('Lỗi khi gọi API AI: ' . $e->getMessage());
                // Nếu API gặp lỗi, fallback vào database
                $response = $this->findExactAnswerFromDatabase($userMessage);
            }
        } else {
            // Nếu API không khả dụng, dùng database
            $response = $this->findExactAnswerFromDatabase($userMessage);
        }

        // Nếu vẫn không có câu trả lời
        if (!$response) {
            if ($isGeneralQuestion) {
                $response = "Rất tiếc, tôi không thể trả lời câu hỏi này vào lúc này do kết nối AI đang bị giới hạn. Tôi có thể giúp bạn với các thông tin y tế hoặc dịch vụ bệnh viện không?";
            } else {
                $response = "Rất tiếc, tôi không thể trả lời câu hỏi này vào lúc này. Vui lòng liên hệ bác sĩ qua hotline 037.864.9957 để được hỗ trợ.";
            }
        }

        // Lưu trả lời của chatbot
        $this->saveConversation($sessionId, $userId, 'bot', $response);

        return response()->json([
            'message' => $response
        ]);
    }

    /**
     * Kiểm tra xem đây có phải là câu hỏi tổng quát về AI, khả năng trò chuyện
     */
    private function isGeneralConversationQuestion($message)
    {
        $lowerMessage = strtolower(trim($message));

        // Các từ khóa liên quan đến câu hỏi tổng quát
        $generalKeywords = [
            'ai', 'chat', 'gpt', 'ngoài lề', 'ngoài y tế', 'không liên quan',
            'trò chuyện', 'hỏi gì cũng được', 'tán gẫu', 'chuyện phiếm',
            'thể thao', 'giải trí', 'âm nhạc', 'phim', 'du lịch', 'ẩm thực',
            'bạn biết', 'bạn có thể', 'bạn là ai', 'bạn tên gì'
        ];

        foreach ($generalKeywords as $keyword) {
            if (strpos($lowerMessage, $keyword) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Kiểm tra trạng thái của Gemini API
     */
    private function checkOpenAIStatus()
    {
        // Kiểm tra xem đã có lỗi gần đây không
        $cacheKey = 'gemini_api_status';

        // Nếu không có cache hoặc cache đã hết hạn, luôn thử lại API
        if (!cache()->has($cacheKey)) {
            return $this->testGeminiConnection();
        }

        // Nếu gần đây thành công, trả về true
        if (cache()->get($cacheKey) === true) {
            return true;
        }

        // Nếu có lỗi gần đây, kiểm tra xem có nên thử lại chưa
        // Thử lại sau mỗi 30 phút kể từ lần lỗi trước
        $lastErrorTime = cache()->get($cacheKey . '_time');
        if ($lastErrorTime && (time() - $lastErrorTime > 1800)) { // 30 phút = 1800 giây
            return $this->testGeminiConnection();
        }

        // Nếu mới lỗi gần đây, trả về false
        return false;
    }

    /**
     * Kiểm tra kết nối đến Gemini
     */
    private function testGeminiConnection()
    {
        $cacheKey = 'gemini_api_status';

        try {
            // Lấy API key từ config
            $apiKey = config('services.gemini.api_key');

            // Thiết lập API URL
            $apiUrl = "https://generativelanguage.googleapis.com/v1/models?key={$apiKey}";

            // Thiết lập headers
            $headers = [
                'Content-Type' => 'application/json'
            ];

            // Gọi API đơn giản để kiểm tra trạng thái
            $response = Http::timeout(10)->withHeaders($headers)->get($apiUrl);

            // Log kết quả kiểm tra kết nối
            Log::info('Gemini Connection Test Status: ' . $response->status());

            // Kiểm tra lỗi quota trong response
            $hasQuotaError = false;
            $hasAuthError = false;

            if (!$response->successful()) {
                $body = $response->json();

                // Kiểm tra lỗi quota
                if (isset($body['error']) &&
                    (isset($body['error']['code']) && $body['error']['code'] === 429)) {
                    $hasQuotaError = true;
                }

                // Kiểm tra lỗi xác thực
                if (isset($body['error']) &&
                    (isset($body['error']['status']) && $body['error']['status'] === 'INVALID_ARGUMENT')) {
                    $hasAuthError = true;
                }
            }

            // Nếu lỗi xác thực, cache lâu hơn vì cần sửa API key
            if ($hasAuthError) {
                Log::error('Gemini API Authentication Error: ' . $response->body());
                cache()->put($cacheKey, false, now()->addHours(12)); // Cache lỗi trong 12 giờ
                cache()->put($cacheKey . '_time', time(), now()->addHours(12));
                cache()->put($cacheKey . '_reason', 'auth_error', now()->addHours(12));
                return false;
            }

            // Nếu lỗi quota
            if ($hasQuotaError || !$response->successful()) {
                Log::warning('Gemini API không khả dụng: ' . $response->body());
                cache()->put($cacheKey, false, now()->addHours(3)); // Cache lỗi trong 3 giờ
                cache()->put($cacheKey . '_time', time(), now()->addHours(3));
                cache()->put($cacheKey . '_reason', 'quota_or_other', now()->addHours(3));
                return false;
            }

            // Nếu thành công, cache trạng thái trong 1 phút để tránh gọi API liên tục
            cache()->put($cacheKey, true, now()->addMinute());
            return true;
        } catch (\Exception $e) {
            Log::error('Lỗi khi kiểm tra trạng thái Gemini: ' . $e->getMessage());
            cache()->put($cacheKey, false, now()->addHours(1)); // Cache lỗi trong 1 giờ
            cache()->put($cacheKey . '_time', time(), now()->addHours(1));
            cache()->put($cacheKey . '_reason', 'exception', now()->addHours(1));
            return false;
        }
    }

    /**
     * Lấy các FAQ liên quan từ database làm context cho AI
     */
    private function getRelevantFAQs($question)
    {
        // Chuẩn hóa câu hỏi
        $normalizedQuestion = trim(strtolower($question));
        $relevantFAQs = [];

        // Tìm kiếm dựa trên từ khóa
        $words = explode(' ', $normalizedQuestion);
        $keywordQuery = DB::table('chatbot_faqs');

        foreach ($words as $word) {
            if (strlen($word) > 3) { // Chỉ tìm kiếm từ có ít nhất 4 ký tự
                $keywordQuery->orWhere('keywords', 'like', '%' . $word . '%')
                            ->orWhere('question', 'like', '%' . $word . '%');
            }
        }

        // Lấy tối đa 3 FAQ phù hợp nhất
        $faqs = $keywordQuery->limit(3)->get();

        if ($faqs->count() > 0) {
            foreach ($faqs as $faq) {
                $relevantFAQs[] = [
                    'question' => $faq->question,
                    'answer' => $faq->answer
                ];
            }
        }

        return $relevantFAQs;
    }

    /**
     * Tìm câu trả lời chính xác từ database (chỉ dùng khi OpenAI không khả dụng)
     */
    private function findExactAnswerFromDatabase($question)
    {
        // Chuẩn hóa câu hỏi
        $normalizedQuestion = trim(strtolower($question));

        // Tìm kiếm chính xác
        $faq = ChatbotFAQ::where('question', 'like', '%' . $question . '%')
            ->orWhere('keywords', 'like', '%' . $question . '%')
            ->first();

        if ($faq) {
            return $faq->answer;
        }

        // Tìm kiếm dựa trên từ khóa với tìm kiếm fulltext
        try {
            $faq = DB::table('chatbot_faqs')
                ->whereRaw("MATCH(question, keywords) AGAINST(? IN BOOLEAN MODE)", [$question])
                ->first();

            if ($faq) {
                return $faq->answer;
            }
        } catch (\Exception $e) {
            // Bỏ qua lỗi fulltext search nếu có
        }

        // Trả về đặc biệt cho câu hỏi về Laravel hoặc công nghệ
        if (stripos($normalizedQuestion, 'laravel') !== false ||
            stripos($normalizedQuestion, 'php') !== false ||
            stripos($normalizedQuestion, 'framework') !== false ||
            stripos($normalizedQuestion, 'lập trình') !== false) {

            return "Laravel là một PHP framework mã nguồn mở, được sử dụng để phát triển các ứng dụng web, bao gồm cả website của bệnh viện chúng tôi. Đây là thông tin kỹ thuật, không liên quan đến y tế.";
        }

        return null;
    }

    /**
     * Lấy câu trả lời từ Gemini API với context từ FAQs
     */
    private function getAnswerFromAI($message, $sessionId, $relevantFAQs = [], $isGeneralQuestion = false)
    {
        try {
            // Lấy lịch sử hội thoại
            $history = ChatbotConversation::where('session_id', $sessionId)
                ->orderBy('created_at', 'desc')
                ->limit(4) // Tăng lên 4 tin nhắn để có ngữ cảnh cuộc trò chuyện tốt hơn
                ->get()
                ->map(function ($conversation) {
                    return json_decode($conversation->messages, true);
                })
                ->flatten(1)
                ->toArray();

            // Lấy API key từ config
            $apiKey = config('services.gemini.api_key');

            // Tạo context từ FAQs nếu có và không phải câu hỏi tổng quát
            $faqContext = "";
            if (!empty($relevantFAQs) && !$isGeneralQuestion) {
                $faqContext = "Dưới đây là một số thông tin liên quan từ cơ sở dữ liệu bệnh viện để bạn tham khảo:\n\n";
                foreach ($relevantFAQs as $index => $faq) {
                    $faqContext .= "Thông tin #" . ($index + 1) . ":\n";
                    $faqContext .= "Câu hỏi: " . $faq['question'] . "\n";
                    $faqContext .= "Câu trả lời: " . $faq['answer'] . "\n\n";
                }
            }

            // Chuẩn bị prompt với thông tin y tế và trang web
            $systemPrompt = "Bạn là trợ lý AI thông minh của Medic Hospital, được thiết kế để cung cấp thông tin y tế và hỗ trợ bệnh nhân. Bạn cũng có thể trả lời các câu hỏi tổng quát khác ngoài lĩnh vực y tế.

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

" . ($faqContext ? $faqContext : "");

            // Xây dựng nội dung trò chuyện từ lịch sử
            $conversationContext = $systemPrompt . "\n\n";

            // Thêm lịch sử chat
            if (!empty($history)) {
                $historyMessages = array_slice($history, -($isGeneralQuestion ? 6 : 4), $isGeneralQuestion ? 3 : 2);
                foreach ($historyMessages as $msg) {
                    if ($msg['role'] == 'user') {
                        $conversationContext .= "Người dùng: " . $msg['content'] . "\n";
                    } else {
                        $conversationContext .= "Trợ lý: " . $msg['content'] . "\n";
                    }
                }
            }

            // Thêm tin nhắn hiện tại
            $conversationContext .= "Người dùng: " . $message . "\n";
            $conversationContext .= "Trợ lý: ";

            // Chuẩn bị dữ liệu gửi đến Gemini API
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
                    'temperature' => $isGeneralQuestion ? 0.8 : 0.7,
                    'maxOutputTokens' => $isGeneralQuestion ? 600 : 500,
                ]
            ];

            // Log thông tin yêu cầu
            Log::info('Gemini Request: ' . json_encode([
                'endpoint' => $endpoint,
                'context_length' => strlen($conversationContext)
            ]));

            // Gọi API Gemini với timeout 20 giây
            $response = Http::timeout(20)->post($endpoint, $data);

            // Log thông tin phản hồi
            Log::info('Gemini Response Status: ' . $response->status());

            // Kiểm tra lỗi quota
            if ($response->status() === 429) {
                // Cache trạng thái lỗi quota, thời gian ngắn hơn cho câu hỏi tổng quát
                cache()->put('gemini_api_status', false, now()->addHours($isGeneralQuestion ? 1 : 3));
                cache()->put('gemini_api_status_time', time(), now()->addHours($isGeneralQuestion ? 1 : 3));
                Log::error('Gemini API Quota Error: ' . $response->body());

                throw new \Exception("Gemini API quota exceeded");
            }

            if ($response->successful()) {
                $result = $response->json();
                if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                    return $result['candidates'][0]['content']['parts'][0]['text'];
                } else {
                    Log::error('Gemini API Response Format Error: ' . json_encode($result));
                    throw new \Exception("Invalid response format from Gemini API");
                }
            } else {
                $errorBody = $response->body();
                Log::error('Gemini API Error: ' . $errorBody);

                // Nếu có lỗi API key không hợp lệ
                if ($response->status() == 400 &&
                    isset($response->json()['error']['status']) &&
                    $response->json()['error']['status'] === 'INVALID_ARGUMENT') {
                    throw new \Exception("Gemini API authentication error: Invalid API key");
                }

                throw new \Exception("Gemini API error: " . $response->status() . " - " . $errorBody);
            }
        } catch (\Exception $e) {
            Log::error('Exception in getAnswerFromAI: ' . $e->getMessage());
            // Trả về null để fallback vào database
            return null;
        }
    }

    /**
     * Lưu hội thoại vào database
     */
    private function saveConversation($sessionId, $userId, $role, $content)
    {
        $conversation = ChatbotConversation::firstOrNew([
            'session_id' => $sessionId
        ]);

        $messages = $conversation->exists ? json_decode($conversation->messages, true) : [];
        $messages[] = [
            'role' => $role,
            'content' => $content,
            'timestamp' => now()->toIso8601String()
        ];

        $conversation->user_id = $userId;
        $conversation->messages = json_encode($messages);
        $conversation->save();
    }

    /**
     * Xóa lịch sử hội thoại
     */
    public function clearConversation(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
        ]);

        ChatbotConversation::where('session_id', $request->input('session_id'))->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lịch sử hội thoại đã được xóa'
        ]);
    }

    /**
     * Kiểm tra trạng thái của Gemini API và trả về cho frontend
     */
    public function checkStatus()
    {
        $cacheKey = 'gemini_api_status';
        $status = [
            'aiAvailable' => true,
            'lastChecked' => cache()->get($cacheKey . '_time', null),
            'reason' => cache()->get($cacheKey . '_reason', null)
        ];

        // Nếu đã có cache, trả về trạng thái từ cache
        if (cache()->has($cacheKey)) {
            $status['aiAvailable'] = cache()->get($cacheKey);
            return response()->json($status);
        }

        // Nếu chưa có cache, kiểm tra và cập nhật cache
        $status['aiAvailable'] = $this->testGeminiConnection();
        return response()->json($status);
    }
}