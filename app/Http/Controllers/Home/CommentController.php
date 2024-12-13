<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;
use App\Models\ProductRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function store(Request $request, Product $product)
    {

        $validatior = Validator::make($request->all(), [
            'text' => 'required|min:5|max:7000',
            'rate' => 'required|digits_between:0,5'
        ]);

        if ($validatior->fails()) {
            return redirect()->to(url()->previous() . '#comments')->withErrors($validatior);
        }

        if (auth()->check()) {

            try {
                DB::beginTransaction();

                Comment::create([
                    'user_id' => auth()->id(),
                    'product_id' => $product->id,
                    'text' => $request->text
                ]);

                if ($product->rates()->where('user_id', auth()->id())->exists()) {

                    $productRate = $product->rates()->where('user_id', auth()->id())->first();
                    $productRate->update([
                        'rate' => $request->rate
                    ]);
                } else {
                    ProductRate::create([
                        'user_id' => auth()->id(),
                        'product_id' => $product->id,
                        'rate' => $request->rate
                    ]);
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                alert()->error('مشکلی در ثبت نظر پیش آمده است', $e->getMessage())->persistent('با تشکر');
                return redirect()->back();
            }

            alert()->success('با تشکر', 'نظر شما ثبت شد دوست عزیز');
            return redirect()->back();
        } else {
            alert()->warning('دقت کنید', 'برای ثبت نظر باید ابتدا وارد سایت شوید')->persistent('با تشکر');
            return redirect()->back();
        }



        dd($request->all(), $product);
    }


    public function usersProfileIndex()
    {

        $comments = Comment::where('user_id' , auth()->id())->get();
        return view('home.users_profile.comments' , compact('comments'));
    }
}
