<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TechFaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $techFaqs = [
            // Laravel
            [
                'question' => 'Laravel là gì?',
                'answer' => 'Laravel là một PHP framework mã nguồn mở, được sử dụng để phát triển các ứng dụng web, bao gồm cả website của bệnh viện chúng tôi. Đây là thông tin kỹ thuật, không liên quan đến y tế. Tôi có thể giúp bạn với các thông tin y tế hoặc dịch vụ của bệnh viện không?',
                'category' => 'Công nghệ',
                'keywords' => 'laravel, php, framework, lập trình, web, coding'
            ],
            // PHP
            [
                'question' => 'PHP là gì?',
                'answer' => 'PHP là ngôn ngữ lập trình được sử dụng để phát triển website của bệnh viện chúng tôi. Đây là thông tin kỹ thuật, không liên quan đến y tế. Tôi có thể giúp bạn với các câu hỏi về dịch vụ y tế hoặc sức khỏe không?',
                'category' => 'Công nghệ',
                'keywords' => 'php, lập trình, code, ngôn ngữ lập trình'
            ],
            // Vue.js
            [
                'question' => 'Vue.js là gì?',
                'answer' => 'Vue.js là một framework JavaScript được sử dụng để phát triển giao diện người dùng của website bệnh viện, bao gồm cả chatbot này. Đây là thông tin kỹ thuật, không liên quan đến y tế. Tôi có thể hỗ trợ bạn về các vấn đề sức khỏe hoặc dịch vụ của bệnh viện không?',
                'category' => 'Công nghệ',
                'keywords' => 'vue, vuejs, javascript, frontend, framework'
            ],
            // Chatbot
            [
                'question' => 'Chatbot này được xây dựng như thế nào?',
                'answer' => 'Chatbot này được xây dựng bằng Laravel (PHP) ở backend, Vue.js ở frontend, và sử dụng cả cơ sở dữ liệu FAQ cùng với API OpenAI để trả lời câu hỏi. Tôi được thiết kế để hỗ trợ bệnh nhân với thông tin y tế, dịch vụ bệnh viện và hướng dẫn sơ cứu. Tôi có thể giúp bạn với câu hỏi về sức khỏe không?',
                'category' => 'Công nghệ',
                'keywords' => 'chatbot, ai, trí tuệ nhân tạo, openai, bot'
            ],
            // OpenAI
            [
                'question' => 'OpenAI là gì?',
                'answer' => 'OpenAI là công ty phát triển công nghệ trí tuệ nhân tạo, cung cấp API được sử dụng trong chatbot này để trả lời câu hỏi phức tạp. Đây là thông tin kỹ thuật, không liên quan đến y tế. Tôi có thể hỗ trợ bạn về các vấn đề sức khỏe hoặc dịch vụ của bệnh viện không?',
                'category' => 'Công nghệ',
                'keywords' => 'openai, gpt, trí tuệ nhân tạo, ai, chatgpt'
            ],
            // Lỗi kết nối
            [
                'question' => 'Tại sao chatbot không trả lời đúng câu hỏi của tôi?',
                'answer' => 'Có thể do một trong các nguyên nhân sau: 1) Câu hỏi nằm ngoài phạm vi kiến thức của tôi về y tế và bệnh viện. 2) Kết nối đến dịch vụ AI đang gặp vấn đề. 3) Cách diễn đạt câu hỏi chưa rõ ràng. Tôi có thể trợ giúp tốt nhất với các câu hỏi về thông tin bệnh viện, dịch vụ y tế, và hướng dẫn sơ cứu cơ bản. Vui lòng thử diễn đạt lại câu hỏi hoặc liên hệ trực tiếp với nhân viên y tế qua hotline 037.864.9957.',
                'category' => 'Công nghệ',
                'keywords' => 'lỗi, error, không hoạt động, không trả lời, sai'
            ],
            // MySQL
            [
                'question' => 'MySQL là gì?',
                'answer' => 'MySQL là hệ quản trị cơ sở dữ liệu được sử dụng trong website bệnh viện để lưu trữ thông tin. Đây là thuật ngữ kỹ thuật về công nghệ thông tin, không liên quan đến y tế. Tôi có thể giúp bạn với các câu hỏi về sức khỏe, dịch vụ y tế hoặc thông tin bệnh viện không?',
                'category' => 'Công nghệ',
                'keywords' => 'mysql, database, cơ sở dữ liệu, db'
            ]
        ];

        foreach ($techFaqs as $faq) {
            DB::table('chatbot_faqs')->insert([
                'question' => $faq['question'],
                'answer' => $faq['answer'],
                'category' => $faq['category'],
                'keywords' => $faq['keywords'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}