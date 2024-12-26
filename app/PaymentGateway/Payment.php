<?php

namespace App\PaymentGateway;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\DB;


class Payment
{
    public function createOrder($addressId, $amounts, $token, $getName)
    {
        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => auth()->id(),
                'address_id' => $addressId,
                'coupon_id' => session()->has('coupon') ? session()->get('coupon.id') : null,
                'total_amount' => $amounts['total_amount'],
                'delivery_amount' => $amounts['delivery_amount'],
                'coupon_amount' => $amounts['coupon_amount'],
                'paying_amount' => $amounts['paying_amount'],
                'payment_type' => 'online',

            ]);

            foreach (\Cart::getContent() as $item) {

                OrderItem::create([
                    'order_id' => $order->id,
                    'Product_id' => $item->associatedModel->id,
                    'product_variation_id' => $item->attributes->id,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->price * $item->quantity
                ]);
            }


            Transaction::create([
                'user_id' =>  auth()->id(),
                'order_id' => $order->id,
                'amount' => $amounts['paying_amount'],
                'token' => $token,
                'gateway_name' => $getName
            ]);


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['errors' => $e->getMessage()];
        }

        return ['success' => 'success!'];
    }

    public function updateOrder($token, $refId)
    {
        $transaction =  Transaction::where('token', $token)->firstOrFail();

        $transaction->update([
            'status' => 1,
            'ref_id' => $refId
        ]);

        $order = Order::findOrFail($transaction->order_id);

        $order->update([
            'payment_status' => 1,
            'status' => 1
        ]);

        foreach (\Cart::getContent() as $item) {

            $variation = ProductVariation::find($item->attributes->id);

            $variation->update([
                'quantity' => $variation->quantity - $item->quantity
            ]);
        }

        try {
            DB::beginTransaction();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['errors' => $e->getMessage()];
        }

        return ['success' => 'success!'];
    }
}
