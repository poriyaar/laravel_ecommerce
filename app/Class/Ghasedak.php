<?php

namespace App\Class;

use Illuminate\Support\Facades\Http;

class Ghasedak
{
    public function sendOTPSms($code, $mobile = '09330477131', $templateName)
    {
        $response = Http::withHeaders([
            'accept' => 'text/plain',
            'ApiKey' => env("GHASEDAK_API_KEY"),
        ])->post(
            'https://gateway.ghasedak.me/rest/api/v1/WebService/SendOtpSMS',
            [

                "receptors" => [
                    [
                        "mobile" => $mobile,
                        "clientReferenceId" => "1"
                    ]
                ],
                "templateName" => $templateName,
                "inputs" => [
                    [
                        "param" => "Code",
                        "value" => (string)$code
                    ]
                ],
                "udh" => false
            ]
        );

        if ($response->successful()) {
            return response()->json([
                'message' => 'OTP sent successfully!',
                'data' => $response->json(),
            ]);
        }


        $errorDetails = $response->json();
        $errorMessage = isset($errorDetails['message']) ? json_decode('"' . $errorDetails['message'] . '"') : 'Unknown error';

        return response()->json([
            'error' => 'Failed to send OTP',
            'details' => [
                'status' => $response->status(),
                'message' => $errorMessage,
            ],
        ]);
    }
}
