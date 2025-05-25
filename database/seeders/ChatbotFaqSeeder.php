<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChatbotFaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Giờ làm việc của bệnh viện?',
                'answer' => 'Bệnh viện Medic mở cửa từ 8:00 đến 17:00 tất cả các ngày trong tuần. Khoa cấp cứu phục vụ 24/7.',
                'category' => 'Thông tin chung',
                'keywords' => 'giờ làm việc, mở cửa, thời gian'
            ],
            [
                'question' => 'Làm thế nào để đặt lịch khám?',
                'answer' => 'Bạn có thể đặt lịch khám qua website bằng cách đăng nhập và sử dụng mục "Appointments", gọi số hotline 037.864.9957, hoặc đến trực tiếp bệnh viện.',
                'category' => 'Đặt lịch',
                'keywords' => 'đặt lịch, hẹn khám, lịch khám, appointment'
            ],
            [
                'question' => 'Làm thế nào để hủy hoặc đổi lịch hẹn?',
                'answer' => 'Bạn có thể hủy hoặc đổi lịch hẹn bằng cách đăng nhập vào tài khoản, vào mục "Appointments", chọn lịch hẹn cần thay đổi và làm theo hướng dẫn. Lưu ý nên báo trước ít nhất 24 giờ.',
                'category' => 'Đặt lịch',
                'keywords' => 'hủy lịch, đổi lịch, thay đổi lịch hẹn'
            ],
            [
                'question' => 'Các chuyên khoa của bệnh viện?',
                'answer' => 'Bệnh viện Medic có nhiều chuyên khoa bao gồm: Nội khoa, Ngoại khoa, Sản phụ khoa, Nhi khoa, Tim mạch, Thần kinh, Tiêu hóa, Hô hấp, Da liễu, Mắt, Tai mũi họng, Răng hàm mặt, Cơ xương khớp, Nội tiết, Thận-Tiết niệu, và Ung bướu.',
                'category' => 'Dịch vụ',
                'keywords' => 'chuyên khoa, khoa, dịch vụ y tế'
            ],
            [
                'question' => 'Các loại bảo hiểm được chấp nhận?',
                'answer' => 'Bệnh viện Medic chấp nhận hầu hết các loại bảo hiểm y tế, bao gồm bảo hiểm y tế nhà nước, bảo hiểm y tế tư nhân, và các bảo hiểm quốc tế. Vui lòng liên hệ phòng bảo hiểm của chúng tôi theo số 037.864.9957 để biết thêm chi tiết.',
                'category' => 'Thanh toán',
                'keywords' => 'bảo hiểm, bhyt, thanh toán'
            ],
            [
                'question' => 'Có dịch vụ cấp cứu không?',
                'answer' => 'Có, Bệnh viện Medic có dịch vụ cấp cứu 24/7. Khoa Cấp cứu nằm ở tầng 1, khu A của bệnh viện và luôn sẵn sàng xử lý các trường hợp khẩn cấp.',
                'category' => 'Dịch vụ',
                'keywords' => 'cấp cứu, khẩn cấp, emergency'
            ],
            [
                'question' => 'Bệnh viện có dịch vụ tư vấn trực tuyến không?',
                'answer' => 'Có, bệnh viện Medic cung cấp dịch vụ tư vấn trực tuyến qua video call. Bạn có thể đăng ký dịch vụ này trong mục "Virtual Visits" trên website hoặc ứng dụng di động của chúng tôi.',
                'category' => 'Dịch vụ',
                'keywords' => 'tư vấn trực tuyến, khám online, telemedicine'
            ],
            [
                'question' => 'Địa chỉ của bệnh viện?',
                'answer' => 'Bệnh viện Medic tọa lạc tại 9500 Euclid Avenue, Cleveland, Ohio 44195. Bạn có thể tìm thêm thông tin và bản đồ chỉ dẫn trong mục "Locations & Directions" trên website của chúng tôi.',
                'category' => 'Thông tin chung',
                'keywords' => 'địa chỉ, vị trí, location, chỉ đường'
            ],
            [
                'question' => 'Làm thế nào để xem kết quả xét nghiệm?',
                'answer' => 'Bạn có thể xem kết quả xét nghiệm bằng cách đăng nhập vào tài khoản MyChart trên website hoặc ứng dụng di động. Kết quả sẽ được cập nhật sau khi được bác sĩ xác nhận.',
                'category' => 'Hồ sơ y tế',
                'keywords' => 'kết quả xét nghiệm, test results, hồ sơ y tế'
            ],
            [
                'question' => 'Chính sách về khách thăm bệnh nhân nội trú?',
                'answer' => 'Giờ thăm bệnh từ 9:00 đến 20:00 hàng ngày. Mỗi bệnh nhân được phép có tối đa 2 người thăm cùng lúc. Trẻ em dưới 12 tuổi phải có người lớn đi kèm. Khách thăm cần tuân thủ các quy định về vệ sinh và an toàn của bệnh viện.',
                'category' => 'Chính sách',
                'keywords' => 'thăm bệnh, khách thăm, visiting hours'
            ],
            [
                'question' => 'Quy trình nhập viện diễn ra như thế nào?',
                'answer' => 'Khi nhập viện, bạn cần mang theo thẻ căn cước/CMND, thẻ bảo hiểm y tế, và giấy giới thiệu của bác sĩ (nếu có). Đến quầy tiếp nhận để làm thủ tục, sau đó sẽ được hướng dẫn đến khoa phòng tương ứng. Nên mang theo đồ dùng cá nhân cần thiết cho thời gian nằm viện.',
                'category' => 'Quy trình',
                'keywords' => 'nhập viện, admission, thủ tục'
            ],
            [
                'question' => 'Bệnh viện có cung cấp dịch vụ chăm sóc tại nhà không?',
                'answer' => 'Có, Bệnh viện Medic có dịch vụ chăm sóc tại nhà cho bệnh nhân cần được theo dõi sau khi xuất viện. Dịch vụ bao gồm thăm khám, tiêm thuốc, thay băng, vật lý trị liệu tại nhà. Để đăng ký, vui lòng liên hệ số 0924.184.107.',
                'category' => 'Dịch vụ',
                'keywords' => 'chăm sóc tại nhà, home care, sau xuất viện'
            ],
            [
                'question' => 'Cách thanh toán viện phí?',
                'answer' => 'Bệnh viện Medic chấp nhận nhiều hình thức thanh toán: tiền mặt, thẻ tín dụng/ghi nợ, chuyển khoản ngân hàng, và thanh toán qua ứng dụng di động. Bạn có thể thanh toán tại quầy thu ngân hoặc trực tuyến qua website của bệnh viện.',
                'category' => 'Thanh toán',
                'keywords' => 'thanh toán, viện phí, payment'
            ],
            [
                'question' => 'Làm thế nào để nhận bản sao hồ sơ y tế?',
                'answer' => 'Để nhận bản sao hồ sơ y tế, bạn cần điền vào mẫu yêu cầu tại phòng Hồ sơ y tế (tầng 2, khu B) hoặc gửi yêu cầu trực tuyến qua tài khoản MyChart. Bạn sẽ cần xuất trình giấy tờ tùy thân khi nhận hồ sơ.',
                'category' => 'Hồ sơ y tế',
                'keywords' => 'hồ sơ y tế, bệnh án, medical records'
            ],
            [
                'question' => 'Cách đăng ký khám lần đầu?',
                'answer' => 'Để đăng ký khám lần đầu, bạn có thể gọi đến số hotline 037.864.9957, đăng ký trực tuyến qua website, hoặc đến quầy tiếp nhận tại bệnh viện. Bạn cần chuẩn bị thông tin cá nhân, bảo hiểm y tế (nếu có), và mô tả ngắn gọn về tình trạng sức khỏe.',
                'category' => 'Đặt lịch',
                'keywords' => 'đăng ký khám, khám lần đầu, new patient'
            ],
            [
                'question' => 'Bệnh viện có dịch vụ phiên dịch không?',
                'answer' => 'Có, Bệnh viện Medic cung cấp dịch vụ phiên dịch miễn phí cho nhiều ngôn ngữ khác nhau, bao gồm tiếng Anh, Trung, Hàn, Nhật và các ngôn ngữ phổ biến khác. Vui lòng thông báo nhu cầu phiên dịch khi đặt lịch hẹn.',
                'category' => 'Dịch vụ',
                'keywords' => 'phiên dịch, thông dịch, translator'
            ],
        ];

        foreach ($faqs as $faq) {
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
