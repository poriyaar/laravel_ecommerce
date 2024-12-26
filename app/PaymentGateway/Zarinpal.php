<?php

namespace App\PaymentGateway;


class Zarinpal extends Payment
{

    public function send($amounts, $description = null, $addressId)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sandbox.zarinpal.com/pg/v4/payment/request.json',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                'merchant_id' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
                'amount' => $amounts['paying_amount'] . '0',
                'callback_url' => route('home.payment.veryfy', ['gatewayName' => 'zarinpal']),
                'description' =>  'description',
                'metadata' => [
                    'mobile' => '09121234567',
                    'email' => 'info.test@example.com',
                ],
            ]),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $response = json_decode($response, true);

        if ($err) {
            return ['errors' => "cURL Error #:" . $err];
        } else {

            if (isset($response['errors']) && !empty($response['errors'])) {
                return ['errors' => 'ERR: ' . $response["errors"]['message'] . 'status code is' . $response["errors"]['code']];
            } elseif (isset($response['data']['code']) && $response['data']['code'] == 100) {

                $order  = parent::createOrder($addressId, $amounts, $response['data']['authority'], 'zarinpal');

                if (array_key_exists('errors', $order)) {
                    return $order;
                }

                return ['success' => 'https://sandbox.zarinpal.com/pg/StartPay/' . $response['data']['authority']];
            }
        }
    }

    public function verify($authority, $amounts)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sandbox.zarinpal.com/pg/v4/payment/verify.json',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                "merchant_id" => "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
                "amount" => $amounts['paying_amount'] . '0',
                "authority" => $authority
            ]),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return ['errors' => "cURL Error #:" . $err];
            exit;
        }

        $response = json_decode($response, true);

        if (isset($response['errors']) && !empty($response['errors'])) {
            return ['errors' => 'Transaction failed. Error Message: ' . $response['errors']['message'] . 'Error Code: ' . $response['errors']['code']];
        } elseif (isset($response['data']['code']) && $response['data']['code'] == 100) {

            $updateOrder = parent::updateOrder($authority, $response['data']['ref_id']);

            if (array_key_exists('errors', $updateOrder)) {
                return $updateOrder;
            }

            \Cart::clear();


            return ['success' => 'Transaction success. RefID: ' . $response['data']['ref_id']];
        } else {
            return ['errors' => 'Transaction failed. Unexpected response.'];
        }
    }
}
