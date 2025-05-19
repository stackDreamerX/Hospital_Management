<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Xác nhận thanh toán lịch hẹn</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            background-color: #0275d8;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
            border: 1px solid #ddd;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
        .appointment-details {
            background-color: #f9f9f9;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #0275d8;
        }
        .success-badge {
            background-color: #5cb85c;
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            display: inline-block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Xác nhận thanh toán lịch hẹn khám bệnh</h2>
    </div>

    <div class="content">
        <p>Kính gửi {{ $appointment->user->FullName }},</p>

        <p>Chúng tôi xác nhận đã nhận được thanh toán của bạn cho lịch hẹn khám bệnh. Chi tiết lịch hẹn như sau:</p>

        <div class="appointment-details">
            <p><strong>Mã lịch hẹn:</strong> #{{ $appointment->AppointmentID }}</p>
            <p><strong>Bác sĩ:</strong> {{ $appointment->doctor->Title }} {{ $appointment->doctor->user->FullName }}</p>
            <p><strong>Chuyên khoa:</strong> {{ $appointment->doctor->Speciality }}</p>
            <p><strong>Ngày khám:</strong> {{ date('d/m/Y', strtotime($appointment->AppointmentDate)) }}</p>
            <p><strong>Giờ khám:</strong> {{ date('H:i', strtotime($appointment->AppointmentTime)) }}</p>
            <p><strong>Lý do khám:</strong> {{ $appointment->Reason }}</p>
            <p><strong>Số tiền:</strong> {{ number_format($appointment->amount) }} VND</p>
            <p><strong>Trạng thái thanh toán:</strong> <span class="success-badge">Đã thanh toán</span></p>
        </div>

        <p>Vui lòng đến trước giờ hẹn 15 phút để làm thủ tục. Mang theo giấy tờ tùy thân và thẻ bảo hiểm y tế (nếu có).</p>

        <p>Nếu bạn cần thay đổi lịch hẹn hoặc có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi qua số điện thoại 1900-1234 hoặc email support@hospital.com.</p>

        <p>Trân trọng,<br>
        Bệnh viện Quốc tế</p>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} Bệnh viện Quốc tế. Tất cả các quyền được bảo lưu.</p>
        <p>Địa chỉ: 123 Đường Sức Khỏe, Quận 1, TP. Hồ Chí Minh</p>
    </div>
</body>
</html>