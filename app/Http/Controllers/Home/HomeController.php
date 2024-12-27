<?php

namespace App\Http\Controllers\Home;

use App\Models\Banner;
use App\Models\Product;
use App\Models\Setting;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TimeHunter\LaravelGoogleReCaptchaV3\Validations\GoogleReCaptchaV3ValidationRule;

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


    public function aboutUs()
    {
        $bottomBanners = Banner::whereType('index-bottom')->where('is_active', 1)->orderBy('priority')->get();


        return view('home.about-us', compact('bottomBanners'));
    }

    public function contactUs()
    {
        $settingData = Setting::findOrFail(1);

        return view('home.contact-us', compact('settingData'));
    }

    public function contactUsForm(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:4|max:50',
            'email' => 'required|email',
            'subject' => 'required|string|min:4|max:500',
            'text' => 'required|string|min:4|max:5000',
            'g-recaptcha-response' => [new GoogleReCaptchaV3ValidationRule('contact_us')]
        ]);

        ContactUs::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'text' => $request->text,
        ]);

        alert()->success('با تشکر', 'پیام شما با موفقیت ثبت شد');
        return  redirect()->back();
    }
}
