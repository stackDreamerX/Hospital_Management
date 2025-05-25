<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicalEmergencyFaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $emergencyFaqs = [
            // Đau bụng
            [
                'question' => 'Làm sao để sơ cứu đau bụng?',
                'answer' => 'Khi sơ cứu đau bụng: 1) Cho người bệnh nằm nghỉ tư thế thoải mái, đầu hơi cao và đầu gối co nhẹ. 2) Đặt gối ấm (không quá nóng) lên vùng đau nếu không phải đau bụng dữ dội. 3) Uống nước ấm từ từ. 4) Tránh ăn uống, đặc biệt là thức ăn cứng, đồ uống có gas hoặc caffeine. 5) Tìm kiếm hỗ trợ y tế ngay nếu đau dữ dội, kéo dài hoặc kèm theo sốt, nôn mửa, tiêu chảy.',
                'category' => 'Sơ cứu',
                'keywords' => 'sơ cứu đau bụng, đau bụng, đau dạ dày, đau bụng cấp, cứu đau bụng'
            ],
            // Sơ cứu chảy máu
            [
                'question' => 'Cách sơ cứu khi bị chảy máu?',
                'answer' => 'Sơ cứu chảy máu: 1) Đeo găng tay nếu có. 2) Ấn trực tiếp lên vết thương bằng gạc sạch hoặc vải sạch. 3) Duy trì áp lực ít nhất 5-10 phút không gián đoạn. 4) Nếu máu thấm qua, đắp thêm lớp gạc mới lên trên, không gỡ lớp cũ. 5) Nâng cao vùng bị thương trên mức tim nếu có thể. 6) Khi máu ngừng chảy, băng vết thương. 7) Tìm kiếm hỗ trợ y tế ngay nếu máu vẫn tiếp tục chảy mạnh, vết thương sâu hoặc dài trên 2.5cm.',
                'category' => 'Sơ cứu',
                'keywords' => 'sơ cứu chảy máu, vết thương, cầm máu, băng bó'
            ],
            // Bỏng
            [
                'question' => 'Cách sơ cứu khi bị bỏng?',
                'answer' => 'Sơ cứu bỏng: 1) Làm mát vùng bỏng ngay lập tức bằng nước mát (không lạnh) trong 10-15 phút. 2) Không chạm trực tiếp vào vùng bỏng. 3) Không bôi kem, dầu, thuốc đánh răng lên vết bỏng. 4) Không vỡ bóng nước. 5) Che phủ nhẹ vết bỏng bằng gạc vô trùng không dính. 6) Uống nhiều nước. 7) Đến cơ sở y tế ngay nếu bỏng rộng hơn lòng bàn tay, sâu, ở mặt/bàn tay/sinh dục, hoặc do điện/hóa chất.',
                'category' => 'Sơ cứu',
                'keywords' => 'sơ cứu bỏng, bị bỏng, phỏng, bỏng độ 1, bỏng độ 2'
            ],
            // Gãy xương
            [
                'question' => 'Cách sơ cứu khi bị gãy xương?',
                'answer' => 'Sơ cứu gãy xương: 1) Bất động vùng nghi ngờ gãy bằng nẹp tạm thời (thanh gỗ, bìa cứng, tạp chí cuộn). 2) Không cố định quá chặt làm cản trở tuần hoàn. 3) Cố định cả khớp trên và dưới vị trí gãy. 4) Đắp đá trong túi, quấn khăn để giảm sưng (15-20 phút/lần). 5) Không tự nắn xương. 6) Gọi cấp cứu hoặc đưa nạn nhân đến cơ sở y tế ngay, đặc biệt khi nghi ngờ gãy xương cột sống, xương chậu, xương đùi.',
                'category' => 'Sơ cứu',
                'keywords' => 'sơ cứu gãy xương, gãy tay, gãy chân, trật khớp, bó bột'
            ],
            // Hóc dị vật
            [
                'question' => 'Cách sơ cứu khi bị hóc dị vật?',
                'answer' => 'Sơ cứu hóc dị vật (người lớn): 1) Khuyến khích nạn nhân ho mạnh. 2) Nếu không hiệu quả, thực hiện thủ thuật Heimlich: đứng sau nạn nhân, vòng tay qua bụng, nắm tay đặt phía trên rốn dưới xương ức, ép mạnh vào bụng hướng lên trên 5 lần. 3) Với trẻ em (<1 tuổi): đặt trẻ úp trên cẳng tay, đầu thấp, vỗ 5 cái vào giữa lưng, sau đó lật ngửa và ấn 5 lần vào giữa ngực. 4) Gọi cấp cứu ngay nếu dị vật không ra hoặc nạn nhân bất tỉnh.',
                'category' => 'Sơ cứu',
                'keywords' => 'sơ cứu hóc dị vật, nghẹn, hóc xương, thủ thuật Heimlich'
            ],
            // Ngất
            [
                'question' => 'Cách sơ cứu khi bị ngất?',
                'answer' => 'Sơ cứu khi có người ngất: 1) Đặt nạn nhân nằm ngửa, nâng chân cao 20-30cm. 2) Nới lỏng quần áo chật. 3) Đảm bảo không gian thông thoáng. 4) Kiểm tra hơi thở và mạch. 5) Đặt khăn mát lên trán và cổ. 6) Khi tỉnh lại, cho uống nước từ từ. 7) Không cho ăn/uống khi đang bất tỉnh. 8) Gọi cấp cứu nếu ngất kéo dài trên 2 phút, có co giật, đau đầu dữ dội, hoặc xảy ra ở người cao tuổi/bệnh tim mạch.',
                'category' => 'Sơ cứu',
                'keywords' => 'sơ cứu ngất, xỉu, bất tỉnh, choáng, hạ đường huyết'
            ],
            // Đột quỵ
            [
                'question' => 'Cách nhận biết và sơ cứu đột quỵ?',
                'answer' => 'Nhận biết và sơ cứu đột quỵ bằng kỹ thuật F.A.S.T: F (Face/Mặt): Yêu cầu cười, kiểm tra méo miệng. A (Arms/Tay): Yêu cầu giơ hai tay, kiểm tra yếu một bên. S (Speech/Nói): Yêu cầu nói một câu đơn giản, kiểm tra khó nói. T (Time/Thời gian): Gọi cấp cứu ngay lập tức nếu có bất kỳ dấu hiệu nào. Trong khi chờ: 1) Ghi nhớ thời điểm xuất hiện triệu chứng. 2) Để nạn nhân nằm nghiêng an toàn. 3) Không cho ăn uống. 4) Theo dõi hơi thở và mạch. 5) Đảm bảo đường thở thông thoáng.',
                'category' => 'Sơ cứu',
                'keywords' => 'sơ cứu đột quỵ, tai biến, nhồi máu não, xuất huyết não, FAST'
            ],
            // Sốc phản vệ
            [
                'question' => 'Cách sơ cứu sốc phản vệ?',
                'answer' => 'Sơ cứu sốc phản vệ: 1) Gọi cấp cứu ngay lập tức. 2) Nếu nạn nhân có bút tiêm Adrenaline (EpiPen), hỗ trợ họ sử dụng. 3) Đặt nạn nhân nằm ngửa, chân cao hơn thân mình. 4) Nếu khó thở, đặt ở tư thế nửa nằm nửa ngồi. 5) Nới lỏng quần áo chật. 6) Kiểm tra hơi thở và mạch. 7) Nếu ngừng thở/tim, thực hiện CPR. 8) Loại bỏ nguyên nhân nếu có thể (gỡ vòng đeo/trang sức nếu bị ong đốt). 9) KHÔNG chờ đợi, tình trạng có thể nhanh chóng trở nên nguy kịch.',
                'category' => 'Sơ cứu',
                'keywords' => 'sơ cứu sốc phản vệ, dị ứng nặng, phản ứng dị ứng, EpiPen, adrenaline'
            ],
            // CPR
            [
                'question' => 'Cách thực hiện CPR?',
                'answer' => 'Thực hiện CPR (người lớn): 1) Đảm bảo môi trường an toàn. 2) Kiểm tra đáp ứng và hơi thở. 3) Gọi cấp cứu. 4) Đặt nạn nhân nằm ngửa trên mặt phẳng cứng. 5) Đặt gót bàn tay giữa ngực, tay kia chồng lên. 6) Ép ngực: sâu 5-6cm, tốc độ 100-120 lần/phút. 7) Sau 30 lần ép ngực, thực hiện 2 hơi thổi (nếu được huấn luyện). 8) Tiếp tục chu kỳ 30:2 cho đến khi có hỗ trợ y tế. Nếu không được huấn luyện, chỉ ép ngực liên tục đến khi có hỗ trợ.',
                'category' => 'Sơ cứu',
                'keywords' => 'CPR, hồi sức tim phổi, ngừng tim, ngừng thở, ép tim'
            ],
            // Co giật, động kinh
            [
                'question' => 'Cách sơ cứu khi có người bị co giật, động kinh?',
                'answer' => 'Sơ cứu co giật/động kinh: 1) Đảm bảo an toàn cho nạn nhân: di chuyển đồ vật nguy hiểm, đệm đầu. 2) KHÔNG cố ghì giữ người bệnh. 3) KHÔNG đặt bất cứ thứ gì vào miệng. 4) Ghi nhớ thời gian bắt đầu co giật. 5) Khi co giật dừng, đặt nằm nghiêng an toàn. 6) Kiểm tra hơi thở. 7) Ở lại với nạn nhân đến khi tỉnh hoàn toàn. 8) Gọi cấp cứu nếu: co giật kéo dài >5 phút, xảy ra nhiều cơn liên tiếp, nạn nhân bị thương, đang mang thai, hoặc là cơn đầu tiên.',
                'category' => 'Sơ cứu',
                'keywords' => 'sơ cứu co giật, động kinh, kinh giật, epilepsy, seizure'
            ],
        ];

        foreach ($emergencyFaqs as $faq) {
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
