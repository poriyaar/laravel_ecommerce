<?php

use App\Models\City;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Province;

if (!function_exists('generateFileName')) {
    function generateFileName($name)
    {
        $year = now()->year;
        $month = now()->month;
        $day = now()->day;
        $hour = now()->hour;
        $minute = now()->minute;
        $second = now()->second;
        $microsecond = now()->microsecond;

        return $year . '-' . $month . '-' . $day . '-' . $hour . '-' . $minute . '-' . $second . '-' . $microsecond . '-' . $name;
    }
}

if (!function_exists('generateProductImageLink')) {
    function generateProductImageLink($imageName)
    {
        return url(env('PRODUCT_IMAGES_UPLOAD_PATH') . $imageName);
    }
}

if (!function_exists('generateBannerImageLink')) {
    function generateBannerImageLink($imageName)
    {
        return url(env('BANNER_IMAGES_UPLOAD_PATH') . $imageName);
    }
}

if (!function_exists('convertShamsiToGregorianDate')) {
    function convertShamsiToGregorianDate($date)
    {
        if (!is_null($date)) {

            $pattern = "/[-\s]/";

            $shamsiDateSplit = preg_split($pattern, $date);

            $arrayGregorianDate = verta()->jalaliToGregorian($shamsiDateSplit[0], $shamsiDateSplit[1], $shamsiDateSplit[2]);

            return implode("-", $arrayGregorianDate) . " " . $shamsiDateSplit[3];
        }

        return null;
    }
}


if (!function_exists('cartTotalSaleAmount')) {
    function cartTotalSaleAmount()
    {

        $cartTotalSaleAmount = 0;
        foreach (\Cart::getContent() as $item) {
            if ($item->attributes->is_sale) {
                $cartTotalSaleAmount +=
                    $item->quantity *
                    ($item->attributes->price - $item->attributes->sale_price);
            }
        }

        return $cartTotalSaleAmount;
    }
}

if (!function_exists('cartTotalDeliveryAmount')) {
    function cartTotalDeliveryAmount()
    {

        $cartTotalDeliveryAmount = 0;
        foreach (\Cart::getContent() as $item) {

            $cartTotalDeliveryAmount += $item->associatedModel->delivery_amount;
        }

        return $cartTotalDeliveryAmount;
    }
}

if (!function_exists('cartTotalAmount')) {
    function cartTotalAmount()
    {

        if (session()->has('coupon')) {

            if (session()->get('coupon.amount') > (\Cart::getTotal() + cartTotalDeliveryAmount())) {
                return 0;
            } else {
                return (\Cart::getTotal() + cartTotalDeliveryAmount()) - session()->get('coupon.amount');
            }
        } else {
            return  \Cart::getTotal() + cartTotalDeliveryAmount();
        }
    }
}


if (!function_exists('checkCoupon')) {
    function checkCoupon($code)
    {
        $coupon =  Coupon::whereCode($code)->where('expired_at', '>', now())->first();

        if ($coupon == null) {
            return ["error" => "کد تخفیف وجود ندارد"];
        }


        if (Order::where('user_id', auth()->id())->where('coupon_id', $coupon->id)->where('payment_Status', 1)->exists()) {
            return ['error' => 'این کوپن استفاده شده است'];
        }


        if ($coupon->getRawOriginal('type') == 'amount') {

            session()->put('coupon', ['code' => $coupon->code, 'amount' => $coupon->amount]);
        } else {

            $total = \Cart::getTotal();


            $amount =  (($total * $coupon->percentage) / 100) > $coupon->max_percentage_amount ? $coupon->max_percentage_amount : (($total * $coupon->percentage) / 100);

            session()->put('coupon', ['code' => $coupon->code, 'amount' => $amount]);
        }

        return ['success' => 'کد تخفیف برای شما ثبت شد'];
    }
}


if (!function_exists('province_name')) {
    function province_name($id)
    {
        return Province::findOrFail($id)->name;
    }
}

if (!function_exists('city_name')) {
    function city_name($id)
    {
        return City::findOrFail($id)->name;
    }
}
