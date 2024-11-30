<?php

namespace App\Http\Controllers\Home;

use App\Models\Banner;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Banner::whereType('slider')->where('is_active', 1)->orderBy('priority')->get();
        $indexTopBanners = Banner::whereType('index-top')->where('is_active', 1)->orderBy('priority')->get()->chunk(3)->first();
        $indexBottomBanners = Banner::whereType('index-top')->where('is_active', 1)->orderBy('priority')->get()->chunk(3)->last();
        $bottomBanners = Banner::whereType('index-bottom')->where('is_active', 1)->orderBy('priority')->get();

        $products = Product::where('is_active', 1)->get()->take(5);

       


        return view('home.index', compact('sliders', 'indexTopBanners', 'indexBottomBanners', 'bottomBanners', 'products'));
    }
}
