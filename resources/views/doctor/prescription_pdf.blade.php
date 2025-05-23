                @if($prescription->TestResults)
                    <p><strong>Kết quả xét nghiệm:</strong> {{ $prescription->TestResults }}</p>
                @endif

                @if($prescription->Instructions)
                    <p style="margin-top: 10px;"><strong>Lời dặn:</strong> {{ $prescription->Instructions }}</p>
                @endif

                <div style="margin-top: 20px; margin-bottom: 20px;">
                    <p><strong>Thông tin bệnh nhân:</strong></p>
                    <p>Họ và tên: {{ $prescription->user->FullName }}</p>
                    <p>Số điện thoại: {{ $prescription->user->PhoneNumber ?? 'Không có' }}</p>
                    <p>Email: {{ $prescription->user->Email ?? 'Không có' }}</p>
                </div>

                <div style="margin-top: 30px; text-align: right;">
                    <p style="margin-bottom: 40px;">Ngày kê đơn: {{ is_string($prescription->PrescriptionDate) ? $prescription->PrescriptionDate : $prescription->PrescriptionDate->format('d/m/Y') }}</p>
                    <p><strong>Bác sĩ</strong></p>
                    <p>{{ $doctor->FullName }}</p>
                </div>