<?php

namespace App\Services;

use App\Models\Appointment;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ZaloPayService
{
    protected $app_id;
    protected $key1;
    protected $key2;
    protected $endpoint;
    protected $return_url;
    protected $sandbox;

    public function __construct()
    {
        // Hardcoded values since env() is not working correctly
        $this->app_id = "2553";
        $this->key1 = "PcY4iZIKFCIdgZvA6ueMcMHHUbRLYjPL";
        $this->key2 = "kLtgPl8HHhfvMuDHPwKfgfsY4Ydm9eIz";
        $this->sandbox = true;
        $this->endpoint = "https://sb-openapi.zalopay.vn/v2/create";
        $this->return_url = "http://127.0.0.1:8000/zalopay/callback";

        // Log configuration for debugging
        Log::debug('ZaloPay configuration loaded', [
            'app_id' => $this->app_id,
            'endpoint' => $this->endpoint,
            'return_url' => $this->return_url,
            'sandbox' => $this->sandbox ? 'true' : 'false'
        ]);
    }

    protected function generateMac($data)
    {
        return hash_hmac('sha256', $data, $this->key1);
    }

    protected function verifyMac($data, $mac)
    {
        return hash_hmac('sha256', $data, $this->key2) === $mac;
    }

    public function createPaymentUrl(Appointment $appointment)
    {
        try {
            $amount = (int)$appointment->amount;
            if ($amount <= 0) {
                Log::error('Invalid amount for ZaloPay payment', [
                    'appointment_id' => $appointment->AppointmentID,
                    'amount' => $amount
                ]);
                throw new \Exception('Amount must be greater than 0');
            }

            $app_trans_id = $appointment->AppointmentID . '_' . time() . '_' . Str::random(6);
            $app_user = "user_" . ($appointment->UserID ?? 'guest');
            $app_time = round(microtime(true) * 1000);
            $description = "Thanh toán cuộc hẹn #" . $appointment->AppointmentID;

            $order = [
                "app_id" => $this->app_id,
                "app_trans_id" => $app_trans_id,
                "app_user" => $app_user,
                "app_time" => $app_time,
                "amount" => $amount,
                "description" => $description,
                "bank_code" => "zalopayapp",
                "item" => json_encode([
                    ["appointment_id" => $appointment->AppointmentID, "amount" => $amount]
                ]),
                "embed_data" => json_encode([
                    "redirecturl" => $this->return_url
                ]),
                "callback_url" => $this->return_url
            ];

            $data = $this->app_id . "|" . $app_trans_id . "|" . $app_user . "|" . $amount . "|" .
                    $app_time . "|" . $order["embed_data"] . "|" . $order["item"];
            $order["mac"] = $this->generateMac($data);

            // Debug log for request
            Log::debug('ZaloPay payment request', [
                'endpoint' => $this->endpoint,
                'app_id' => $this->app_id,
                'app_trans_id' => $app_trans_id,
                'amount' => $amount
            ]);

            // Store transaction info first
            $appointment->payment_id = $app_trans_id;
            $appointment->save();

            // Using GuzzleHttp instead of file_get_contents
            $client = new Client(['timeout' => 30]);
            $response = $client->post($this->endpoint, [
                'form_params' => $order,
            ]);

            $result = json_decode($response->getBody(), true);

            Log::info('Created ZaloPay payment URL', [
                'appointment_id' => $appointment->AppointmentID,
                'app_trans_id' => $app_trans_id,
                'response' => $result
            ]);

            if (isset($result['return_code']) && $result['return_code'] == 1) {
                return $result['order_url'];
            }

            Log::error('Failed to create ZaloPay payment URL', [
                'appointment_id' => $appointment->AppointmentID,
                'response' => $result
            ]);
            throw new \Exception('Failed to create payment URL: ' . ($result['return_message'] ?? 'Error code: ' . ($result['return_code'] ?? 'Unknown')));
        } catch (RequestException $e) {
            Log::error('ZaloPay API request failed', [
                'appointment_id' => $appointment->AppointmentID ?? null,
                'error' => $e->getMessage()
            ]);

            // Rollback appointment status changes if payment fails
            if (isset($appointment->id)) {
                // You may want to update appointment status or delete it depending on your business logic
                // $appointment->status = 'payment_failed';
                // $appointment->save();
            }

            throw new \Exception('Failed to connect to ZaloPay: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('ZaloPay payment creation exception', [
                'appointment_id' => $appointment->AppointmentID ?? null,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    public function processCallback($request)
    {
        try {
            Log::info('ZaloPay callback received', $request);

            if (!isset($request['data']) || !isset($request['mac'])) {
                Log::error('Invalid ZaloPay callback data', ['request' => $request]);
                return [
                    'is_success' => false,
                    'appointment_id' => null,
                    'response_code' => 'INVALID_REQUEST',
                    'transaction_status' => 'FAILED',
                    'amount' => 0,
                    'transaction_ref' => null,
                    'raw_data' => $request,
                ];
            }

            $data = $request['data'];
            $mac = $request['mac'];
            $verifiedMac = $this->verifyMac($data, $mac);

            if (!$verifiedMac) {
                Log::error('ZaloPay MAC verification failed', [
                    'received' => $mac,
                    'expected' => hash_hmac('sha256', $data, $this->key2)
                ]);
                return [
                    'is_success' => false,
                    'appointment_id' => null,
                    'response_code' => 'MAC_FAILED',
                    'transaction_status' => 'FAILED',
                    'amount' => 0,
                    'transaction_ref' => null,
                    'raw_data' => $request,
                ];
            }

            $decodedData = json_decode($data, true);
            if (!$decodedData) {
                Log::error('ZaloPay data decoding failed', ['data' => $data]);
                return [
                    'is_success' => false,
                    'appointment_id' => null,
                    'response_code' => 'DATA_FAILED',
                    'transaction_status' => 'FAILED',
                    'amount' => 0,
                    'transaction_ref' => null,
                    'raw_data' => $request,
                ];
            }

            $app_trans_id = $decodedData['app_trans_id'] ?? '';
            $status = $decodedData['status'] ?? 0;
            $amount = $decodedData['amount'] ?? 0;
            $parts = explode('_', $app_trans_id);
            $appointmentId = $parts[0] ?? null;

            if ($appointmentId) {
                $appointment = Appointment::find($appointmentId);
                if ($appointment && $appointment->amount != $amount) {
                    Log::error('Amount mismatch in ZaloPay callback', [
                        'appointment_id' => $appointmentId,
                        'expected_amount' => $appointment->amount,
                        'received_amount' => $amount
                    ]);
                    return [
                        'is_success' => false,
                        'appointment_id' => $appointmentId,
                        'response_code' => 'AMOUNT_MISMATCH',
                        'transaction_status' => 'FAILED',
                        'amount' => $amount,
                        'transaction_ref' => $app_trans_id,
                        'raw_data' => $decodedData,
                    ];
                }
            }

            // Check if transaction was successful
            $isSuccess = ($status == 1); // 1 is success in ZaloPay

            if ($isSuccess && $appointmentId) {
                $appointment = Appointment::find($appointmentId);
                if ($appointment) {
                    $appointment->payment_status = 'SUCCESS';
                    $appointment->save();
                    Log::info('Updated appointment status', [
                        'appointment_id' => $appointmentId,
                        'status' => 'SUCCESS'
                    ]);
                }
            }

            return [
                'is_success' => $isSuccess && $verifiedMac,
                'appointment_id' => $appointmentId,
                'response_code' => (string)$status,
                'transaction_status' => $isSuccess ? 'SUCCESS' : 'FAILED',
                'amount' => $amount,
                'transaction_ref' => $app_trans_id,
                'raw_data' => $decodedData,
            ];
        } catch (\Exception $e) {
            Log::error('ZaloPay callback processing exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'raw_data' => $request ?? []
            ]);

            return [
                'is_success' => false,
                'appointment_id' => null,
                'response_code' => 'EXCEPTION',
                'transaction_status' => 'FAILED',
                'amount' => 0,
                'transaction_ref' => null,
                'raw_data' => $request ?? [],
                'error_message' => $e->getMessage()
            ];
        }
    }

    public function queryOrderStatus($app_trans_id)
    {
        $data = [
            'app_id' => $this->app_id,
            'app_trans_id' => $app_trans_id,
            'mac' => $this->generateMac($this->app_id . '|' . $app_trans_id),
        ];

        $client = new Client(['timeout' => 30]);
        try {
            $response = $client->post('https://sandbox.zalopay.com.vn/v001/tpe/getstatusbyapptransid', [
                'form_params' => $data,
            ]);
            $result = json_decode($response->getBody(), true);

            Log::info('Queried ZaloPay order status', [
                'app_trans_id' => $app_trans_id,
                'response' => $result
            ]);

            return $result;
        } catch (RequestException $e) {
            Log::error('Failed to query ZaloPay order status', [
                'app_trans_id' => $app_trans_id,
                'error' => $e->getMessage()
            ]);
            throw new \Exception('Failed to query order status: ' . $e->getMessage());
        }
    }
}