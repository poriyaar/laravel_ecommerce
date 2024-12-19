<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'qtybutton' => 'required',
        ]);

        $product = Product::findOrFail($request->product_id);
        $productVariaction = ProductVariation::findOrFail(json_decode($request->variation)->id);


        if ($request->qtybutton > $productVariaction->quantity) {
            alert()->error('دقت کنید', 'تعداد وارد شده درست نمیباشد');
            return redirect()->back();
        }

        $rowId = $product->id . '-' . $productVariaction->id;

        if (\Cart::get($rowId) == null) {
            // add the product to cart
            \Cart::add(array(
                'id' => $rowId,
                'name' => $product->name,
                'price' => $productVariaction->is_sale ? $productVariaction->sale_price : $productVariaction->price,
                'quantity' => $request->qtybutton,
                'attributes' => $productVariaction->toArray(),
                'associatedModel' => $product
            ));
        } else {
            alert()->warning('دقت کنید', 'محصول مورد نظر به سبد خرید شما اضافه شده است');
            return redirect()->back();
        }


        alert()->success('با تشکر', 'محصول مورد نظر به سبد خرید شما اضافه شد');
        return redirect()->back();
    }

    public function index()
    {
        return view('home.cart.index');
    }

    public function update(Request $request)
    {

        $request->validate([
            'qtybutton' => 'required'
        ]);

        foreach ($request->qtybutton as $rowId => $quantity) {

            $item = \Cart::get($rowId);

            if ($quantity > $item->attributes->quantity) {
                alert()->error('دقت کنید', 'تعداد وارد شده درست نمیباشد');
                return redirect()->back();
            }


            \Cart::update($rowId, [
                'quantity' =>  [
                    'relative' => false,
                    'value' => $quantity
                ]
            ]);
        }


        alert()->success('با تشکر', 'سبد خرید شما ویرایش شد');
        return redirect()->back();
    }


    public function remove($rowId)
    {
        \Cart::remove($rowId);

        alert()->success('با تشکر', 'محصول مورد نظر از سبد شما حذف شد');
        return redirect()->back();
    }

    public function clear()
    {
        \Cart::clear();

        alert()->warning('با تشکر', 'سبد خرید شما خالی شد');
        return redirect()->back();
    }

    public function checkCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required'
        ]);

        if (!auth()->check()) {
            alert()->error('با تشکر', 'برای اعمال کد تخفیف ابتدا وارد سایت شوید');
            return redirect()->back();
        }

        $result = checkCoupon($request->code);

        if (array_key_exists('error', $result)) {
            alert()->error('دقت کنید', $result['error']);
        } else {
            alert()->success('با تشکر', $result['success']);
        }

        return redirect()->back();
    }
}
