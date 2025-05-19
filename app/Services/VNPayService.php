<?php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VNPayService
{
    protected $vnp_TmnCode;
    protected $vnp_HashSecret;
    protected $vnp_Url;
    protected $vnp_ReturnUrl;

    public function __construct()
    {
        $this->vnp_TmnCode = env('VNPAY_TMN_CODE', '');
        $this->vnp_HashSecret = env('VNPAY_HASH_SECRET', '');
        $this->vnp_Url = env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
        $this->vnp_ReturnUrl = env('VNPAY_RETURN_URL', route('vnpay.callback'));
    }

    /**
     * Create a payment URL for VNPay
     *
     * @param Appointment $appointment
     * @return string
     */
    public function createPaymentUrl(Appointment $appointment)
    {
        $vnp_TxnRef = $appointment->AppointmentID . '-' . time(); // Unique transaction reference
        $vnp_OrderInfo = 'Payment for appointment #' . $appointment->AppointmentID;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $appointment->amount * 100; // Amount in VND, multiply by 100 as per VNPay requirements
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();
        $vnp_ReturnUrl = $this->vnp_ReturnUrl;

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $this->vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";

        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $this->vnp_Url . "?" . $query;

        if ($this->vnp_HashSecret) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $this->vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        // Update appointment with payment details
        $appointment->payment_id = $vnp_TxnRef;
        $appointment->save();

        \Illuminate\Support\Facades\Log::info('Created VNPay payment URL', [
            'appointment_id' => $appointment->AppointmentID,
            'txn_ref' => $vnp_TxnRef
        ]);

        return $vnp_Url;
    }

    /**
     * Process the VNPay callback response
     *
     * @param array $request
     * @return array
     */
    public function processCallback($request)
    {
        $inputData = [];
        foreach ($request as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);

        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $this->vnp_HashSecret);

        $vnp_TxnRef = $inputData['vnp_TxnRef'] ?? '';
        $vnp_Amount = $inputData['vnp_Amount'] ?? 0;
        $vnp_ResponseCode = $inputData['vnp_ResponseCode'] ?? '';
        $vnp_TransactionStatus = $inputData['vnp_TransactionStatus'] ?? '';

        // Extract appointment ID from TxnRef
        $appointmentId = explode('-', $vnp_TxnRef)[0] ?? null;

        // Check signature
        $checkSignature = ($secureHash == $vnp_SecureHash);

        // Check if payment is successful
        $isSuccess = ($vnp_ResponseCode == '00' && $vnp_TransactionStatus == '00');

        return [
            'is_success' => $isSuccess && $checkSignature,
            'appointment_id' => $appointmentId,
            'response_code' => $vnp_ResponseCode,
            'transaction_status' => $vnp_TransactionStatus,
            'amount' => $vnp_Amount / 100, // Convert back to VND
            'transaction_ref' => $vnp_TxnRef,
            'raw_data' => $inputData,
        ];
    }
}