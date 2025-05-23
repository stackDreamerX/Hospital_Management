<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Hóa đơn nhập viện</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .hospital-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .hospital-address {
            font-size: 10px;
            margin-bottom: 5px;
        }
        .document-title {
            font-size: 16px;
            font-weight: bold;
            margin: 15px 0;
            text-transform: uppercase;
            text-align: center;
        }
        .patient-info {
            margin-bottom: 15px;
        }
        .patient-info table {
            width: 100%;
        }
        .patient-info th {
            text-align: left;
            width: 150px;
            padding: 3px 0;
        }
        .section-title {
            font-weight: bold;
            margin: 15px 0 5px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #ddd;
            padding: 5px;
        }
        table.data-table th {
            background-color: #f5f5f5;
            text-align: center;
        }
        .summary-table {
            width: 350px;
            float: right;
            margin-top: 15px;
        }
        .summary-table th {
            text-align: left;
        }
        .summary-table td {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            page-break-inside: avoid;
        }
        .signature-section {
            float: right;
            width: 200px;
            text-align: center;
            margin-top: 25px;
        }
        .clear {
            clear: both;
        }
        .text-center {
            text-align: center;
        }
        .text-bold {
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .amount {
            font-family: 'DejaVu Sans', sans-serif;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="hospital-name">BỆNH VIỆN ĐA KHOA</div>
        <div class="hospital-address">123 Đường Nguyễn Văn Linh, Quận 7, TP. Hồ Chí Minh</div>
        <div>SĐT: 028.1234.5678 - Email: info@benhvien.vn</div>
    </div>

    <div class="document-title">
        HÓA ĐƠN VIỆN PHÍ
    </div>

    <div class="patient-info">
        <table>
            <tr>
                <th>Mã bệnh nhân:</th>
                <td>{{ $patient->UserID ?? 'N/A' }}</td>
                <th>Ngày lập:</th>
                <td>{{ $generatedDate }}</td>
            </tr>
            <tr>
                <th>Họ và tên:</th>
                <td>{{ $patient->FullName ?? 'N/A' }}</td>
                <th>Giới tính:</th>
                <td>{{ $patient->Gender ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Ngày sinh:</th>
                <td>{{ $patient->DateOfBirth ? date('d/m/Y', strtotime($patient->DateOfBirth)) : 'N/A' }}</td>
                <th>Số điện thoại:</th>
                <td>{{ $patient->PhoneNumber ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Địa chỉ:</th>
                <td colspan="3">{{ $patient->Address ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">THÔNG TIN NHẬP VIỆN</div>
    <table class="data-table">
        <tr>
            <th width="25%">Khoa/Phòng</th>
            <th width="15%">Giường số</th>
            <th width="30%">Ngày nhập viện</th>
            <th width="30%">Ngày xuất viện</th>
        </tr>
        <tr>
            <td class="text-center">{{ $ward }}</td>
            <td class="text-center">{{ $bed }}</td>
            <td class="text-center">{{ $startDate }}</td>
            <td class="text-center">{{ $endDate }}</td>
        </tr>
    </table>

    @if(count($medications) > 0)
    <div class="section-title">DANH SÁCH THUỐC ĐÃ SỬ DỤNG</div>
    <table class="data-table">
        <tr>
            <th width="5%">STT</th>
            <th width="35%">Tên thuốc</th>
            <th width="15%">Liều lượng</th>
            <th width="20%">Thời gian dùng</th>
            <th width="25%">Bác sĩ kê đơn</th>
        </tr>
        @foreach($medications as $index => $medication)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>{{ $medication->medication_name }}</td>
            <td class="text-center">{{ $medication->dosage }}</td>
            <td class="text-center">{{ $medication->frequency }}</td>
            <td class="text-center">{{ $medication->doctor->FullName ?? 'N/A' }}</td>
        </tr>
        @endforeach
    </table>
    @endif

    @if(count($patientMonitorings) > 0)
    <div class="section-title">THÔNG TIN THEO DÕI SỨC KHỎE</div>
    <table class="data-table">
        <tr>
            <th width="5%">STT</th>
            <th width="20%">Ngày giờ</th>
            <th width="15%">Huyết áp</th>
            <th width="15%">Nhịp tim</th>
            <th width="15%">Nhiệt độ</th>
            <th width="15%">SpO2</th>
            <th width="15%">Trạng thái</th>
        </tr>
        @foreach($patientMonitorings as $index => $monitoring)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td class="text-center">{{ date('d/m/Y H:i', strtotime($monitoring->recorded_at)) }}</td>
            <td class="text-center">{{ $monitoring->blood_pressure }} mmHg</td>
            <td class="text-center">{{ $monitoring->heart_rate }} bpm</td>
            <td class="text-center">{{ $monitoring->temperature }}°C</td>
            <td class="text-center">{{ $monitoring->spo2 }}%</td>
            <td class="text-center">
                @if($monitoring->treatment_outcome == 'improved')
                    Cải thiện
                @elseif($monitoring->treatment_outcome == 'stable')
                    Ổn định
                @else
                    Xấu đi
                @endif
            </td>
        </tr>
        @endforeach
    </table>
    @endif

    <div class="section-title">CHI PHÍ ĐIỀU TRỊ</div>
    <table class="data-table">
        <tr>
            <th width="5%">STT</th>
            <th width="55%">Mô tả</th>
            <th width="20%">Đơn giá (VNĐ)</th>
            <th width="20%">Thành tiền (VNĐ)</th>
        </tr>
        <tr>
            <td class="text-center">1</td>
            <td>Tiền giường ({{ $durationInDays }} ngày)</td>
            <td class="text-right">{{ number_format($dailyRate, 0, ',', '.') }}</td>
            <td class="text-right">{{ number_format($totalCost, 0, ',', '.') }}</td>
        </tr>
    </table>

    <table class="summary-table">
        <tr>
            <th>Tổng chi phí:</th>
            <td class="amount">{{ number_format($totalCost, 0, ',', '.') }} VNĐ</td>
        </tr>
        <tr>
            <th>Giảm giá bảo hiểm ({{ $discountPercentage }}%):</th>
            <td class="amount">{{ number_format($discountAmount, 0, ',', '.') }} VNĐ</td>
        </tr>
        <tr>
            <th class="text-bold">Số tiền phải thanh toán:</th>
            <td class="amount">{{ number_format($finalCost, 0, ',', '.') }} VNĐ</td>
        </tr>
    </table>

    <div class="clear"></div>

    @if($additionalNotes)
    <div class="section-title">GHI CHÚ</div>
    <div>{{ $additionalNotes }}</div>
    @endif

    <div class="footer">
        <div class="signature-section">
            <p>TP. Hồ Chí Minh, {{ date('d/m/Y') }}</p>
            <p>Người lập hóa đơn</p>
            <br><br><br>
            <p>{{ $generatedBy }}</p>
        </div>
        <div class="clear"></div>
        <p style="margin-top: 50px; font-style: italic; font-size: 10px;">
            Lưu ý: Hóa đơn này có giá trị làm chứng từ kế toán, không thay thế cho hóa đơn tài chính theo quy định của Bộ Tài Chính.<br>
            Mọi thắc mắc vui lòng liên hệ phòng Tài chính kế toán, điện thoại: 028.1234.5679
        </p>
    </div>
</body>
</html>