<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Đơn thuốc</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            padding: 20px;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2 {
            margin-bottom: 5px;
            font-size: 24px;
        }
        .hospital-info {
            text-align: center;
            margin-bottom: 20px;
        }
        .hospital-info h5 {
            margin: 10px 0;
            font-weight: bold;
            font-size: 18px;
        }
        .patient-info {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }
        .patient-info p {
            margin: 5px 0;
        }
        .medicine-list {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .medicine-list th, .medicine-list td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .medicine-list th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
        }
        .signature {
            margin-top: 60px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>ĐƠN THUỐC</h2>
        <p>Ngày: {{ is_string($prescription->PrescriptionDate) ? $prescription->PrescriptionDate : $prescription->PrescriptionDate->format('d/m/Y') }}</p>
    </div>

    <div class="hospital-info">
        <h5>BỆNH VIỆN ĐA KHOA</h5>
        <p>Địa chỉ: 123 Đường Trường Chinh, Hà Nội</p>
        <p>Điện thoại: (024) 123-4567</p>
    </div>

    <div class="patient-info">
        <p><strong>Mã đơn thuốc:</strong> {{ $prescription->PrescriptionID }}</p>
        <p><strong>Bệnh nhân:</strong> {{ $prescription->user->FullName }}</p>
        <p><strong>Số điện thoại:</strong> {{ $prescription->user->PhoneNumber ?? 'Không có' }}</p>
        <p><strong>Email:</strong> {{ $prescription->user->Email ?? 'Không có' }}</p>
    </div>

    <div style="margin-bottom: 20px;">
        <h4>Thông tin khám bệnh</h4>
        @if($prescription->Diagnosis)
            <p><strong>Chẩn đoán:</strong> {{ $prescription->Diagnosis }}</p>
        @endif

        @if($prescription->TestResults)
            <p><strong>Kết quả xét nghiệm:</strong> {{ $prescription->TestResults }}</p>
        @endif

        <div style="display: flex; justify-content: space-between; margin-top: 10px;">
            <div style="width: 48%;">
                @if($prescription->BloodPressure)
                    <p><strong>Huyết áp:</strong> {{ $prescription->BloodPressure }} mmHg</p>
                @endif
                @if($prescription->HeartRate)
                    <p><strong>Nhịp tim:</strong> {{ $prescription->HeartRate }} BPM</p>
                @endif
            </div>
            <div style="width: 48%;">
                @if($prescription->Temperature)
                    <p><strong>Nhiệt độ:</strong> {{ $prescription->Temperature }} °C</p>
                @endif
                @if($prescription->SpO2)
                    <p><strong>SpO2:</strong> {{ $prescription->SpO2 }} %</p>
                @endif
            </div>
        </div>
    </div>

    <div style="margin-bottom: 20px;">
        <h4>Thuốc đã kê</h4>
        <table class="medicine-list">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên thuốc</th>
                    <th>Liều dùng</th>
                    <th>Tần suất</th>
                    <th>Thời gian</th>
                    <th>Số lượng</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prescription->prescriptionDetail as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $detail->medicine->MedicineName }}</td>
                    <td>{{ $detail->Dosage }}</td>
                    <td>{{ $detail->Frequency }}</td>
                    <td>{{ $detail->Duration }}</td>
                    <td>{{ $detail->Quantity }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($prescription->Instructions)
        <div style="margin-top: 20px;">
            <h4>Lời dặn của bác sĩ</h4>
            <p>{{ $prescription->Instructions }}</p>
        </div>
    @endif

    <div class="footer">
        <p>Bác sĩ kê đơn</p>
        <div class="signature">
            <p><strong>{{ $doctor->FullName }}</strong></p>
        </div>
    </div>
</body>
</html>