<?php

namespace App\PaymentGateway;


class Pay extends Payment
{
    public function send($amounts, $addressId)
    {
        $api = 'test';
        $amount = $amounts['paying_amount'] . '0';
        // $mobile = "شماره موبایل";
        // $factorNumber = "شماره فاکتور";
        // $description = "توضیحات";
        $redirect = route('home.payment.veryfy' , ['gatewayName' => 'pay']);
        // $result = $this->send($api, $amount, $redirect, $mobile, $factorNumber, $description);
        $result = $this->sendRequest($api, $amount, $redirect);
        $result = json_decode($result);

        if ($result->status) {

            $order  = parent::createOrder($addressId, $amounts, $result->token, 'pay');

            if (array_key_exists('errors', $order)) {
                return $order;
            }

            $go = "https://pay.ir/pg/$result->token";
            return ['success' => $go];
        } else {
            return ['errors' => $result->errorMessage];
        }
    }




    public function sendRequest($api, $amount, $redirect, $mobile = null, $factorNumber = null, $description = null)
    {
        return $this->curl_post('https://pay.ir/pg/send', [
            'api'          => $api,
            'amount'       => $amount,
            'redirect'     => $redirect,
            'mobile'       => $mobile,
            'factorNumber' => $factorNumber,
            'description'  => $description,
        ]);
    }


    public function curl_post($url, $params)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }


    public function verify($request)
    {
        $api = 'test';
        $token = $request->token;
        $result = json_decode($this->verifyRequest($api, $token));
        if (isset($result->status)) {
            if ($result->status == 1) {
                $updateOrder = parent::updateOrder($token, $result->transId);

                if (array_key_exists('errors', $updateOrder)) {
                    return $updateOrder;
                }

                \Cart::clear();
                return ['success' => 'پرداخت شما با موفقیت انجام شد'];
            } else {
                return ['errors' => 'پرداخت با خطا مواجه شد'];
            }
        } else {
            if ($request->status == 0) {
                return ['errors' => '!!!!پرداخت با خطا مواجه شد'];
            }
        }
    }

    public function verifyRequest($api, $token)
    {
        return $this->curl_post('https://pay.ir/pg/verify', [
            'api'     => $api,
            'token' => $token,
        ]);
    }
}
