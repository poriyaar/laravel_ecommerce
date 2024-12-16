<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    public function add(Product $product)
    {

        if (session()->has('compareProducts')) {

            if (in_array($product->id, session()->get('compareProducts'))) {
                alert()->warning('دقت کنید', 'این محصول قبلا به لیست مقایسه شما اضافه شده است');
                return redirect()->back();
            }
            session()->push('compareProducts', $product->id);
        } else {
            session()->put('compareProducts', [$product->id]);
        }

        alert()->success('با تشکر', 'محصول مورد نظر به لیست مقایسه اضافه شد');
        return redirect()->back();
    }

    public function index()
    {

        if (session()->has('compareProducts')) {

            $products = Product::findOrFail(session()->get('compareProducts'));

            return view('home.compare.index', compact('products'));
        }

        alert()->warning('دقت کنید', 'کاربر گرامی شما محصولی برای مقایسه ندارید');
        return redirect()->back();
    }

    public function remove($id)
    {
        if (session()->has('compareProducts')) {

            foreach (session()->get('compareProducts') as $key => $item) {
                if ($item == $id) {
                    session()->pull('compareProducts.' . $key);
                }
            }

            if (session()->get('compareProducts') == []) {
                session()->forget('compareProducts');
                return redirect()->route('home.index');
            }


            return redirect()->route('home.compare.index');
        }

        alert()->warning('دقت کنید', 'کاربر گرامی شما محصولی برای مقایسه ندارید');
        return redirect()->back();
    }
}
