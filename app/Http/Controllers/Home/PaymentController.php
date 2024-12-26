<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\PaymentGateway\Pay;
use App\PaymentGateway\Zarinpal;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function payment(Request $request)
    {

        $validatior = Validator::make($request->all(), [
            'payment_method' => 'required',
            'address_id' => 'required'
        ]);

        if ($validatior->fails()) {
            alert()->error('دقت کنید', 'انتخاب آدرس الزامی میباشد')->presistent('حله');
            return redirect()->route('home.index');
        }

        $checkart = $this->checkCart();

        if (array_key_exists('errors', $checkart)) {
            alert()->error('دقت کنید', $checkart['errors']);
            \Cart::clear();
            return redirect()->route('home.index');
        }

        $amounts = $this->getAmounts();
        if (array_key_exists('errors', $amounts)) {
            alert()->error('دقت کنید', $amounts['errors']);
            return redirect()->route('home.index');
        }

        if ($request->payment_method == 'pay') {

            $payGateway = new Pay();
            $payGatewayResulte = $payGateway->send($amounts, $request->address_id);
            if (array_key_exists('errors', $payGatewayResulte)) {
                alert()->error('دقت کنید', $payGatewayResulte['errors'])->persistent('حله');
                return redirect()->route('home.index');
            } else {
                return redirect()->to($payGatewayResulte['success']);
            }
        }

        if ($request->payment_method == 'zarinpal') {


            $zarinPalGateway = new Zarinpal();
            $zarinPalGatewayResulte = $zarinPalGateway->send($amounts, null, $request->address_id);
            if (array_key_exists('errors', $zarinPalGatewayResulte)) {
                alert()->error('دقت کنید', $zarinPalGatewayResulte['errors'])->persistent('حله');
                return redirect()->route('home.index');
            } else {
                return redirect()->to($zarinPalGatewayResulte['success']);
            }
        }


        alert()->error('دقت کنید', 'درگاه پرداخت انتخابی درست نمیباشد');
        return redirect()->back();
    }

    public function paymentVerify(Request $request, $gatewayName)
    {

        if ($gatewayName == 'pay') {

            $payGateway = new Pay();
            $payGatewayResulte = $payGateway->verify($request);
            if (array_key_exists('errors', $payGatewayResulte)) {
                alert()->error('دقت کنید', $payGatewayResulte['errors'])->persistent('حله');
                return redirect()->back();
            } else {
                alert()->success('با تشکر', $payGatewayResulte['success']);

                return redirect()->route('home.index');
            }
        }


        if ($gatewayName == 'zarinpal') {


            $amounts = $this->getAmounts();
            if (array_key_exists('errors', $amounts)) {
                alert()->error('دقت کنید', $amounts['errors']);
                return redirect()->route('home.index');
            }

            $zarinPalGateway = new Zarinpal();
            $zarinPalGatewayResulte = $zarinPalGateway->verify($request->Authority, $amounts, $request->address_id);
            if (array_key_exists('errors', $zarinPalGatewayResulte)) {
                alert()->error('دقت کنید', $zarinPalGatewayResulte['errors'])->persistent('حله');
                return redirect()->back();
            } else {
                alert()->success('با تشکر', $zarinPalGatewayResulte['success']);

                return redirect()->route('home.index');
            }
        }



        alert()->error('دقت کنید', 'مسیر بازگشتی اشتباه میباشد');
        return redirect()->route('home.orders.checkout');
    }





    public function checkCart()
    {
        if (\Cart::isEmpty()) {
            return ['errors' => 'سبد خرید شما خالی میباشد'];
        }

        foreach (\Cart::getContent() as $item) {

            $variation = ProductVariation::find($item->attributes->id);

            if ($variation->is_sale) {
                $price = $variation->sale_price;
            } else {
                $price = $variation->price;
            }

            if ($item->price != $price) {

                return ['errors' => 'قیمت محصول تغییر پیدا کرد'];
            }

            if ($item->quantity > $variation->quantity) {

                return ['errors' => 'موجودی محصول تغییر پیدا کرد'];
            }


            return ['success' => 'success!'];
        }
    }


    public function getAmounts()
    {
        if (session()->has('coupon')) {
            $checkCoupon = checkCoupon(session()->get('coupon.code'));

            if (array_key_exists('error', $checkCoupon)) {

                return $checkCoupon;
            }
        }


        return [
            'total_amount' => (\Cart::getTotal() + cartTotalSaleAmount()),
            'delivery_amount' => cartTotalDeliveryAmount(),
            'coupon_amount' => session()->has('coupon') ? session()->get('coupon.amount') : 0,
            'paying_amount' => cartTotalAmount(),
        ];
    }
}
