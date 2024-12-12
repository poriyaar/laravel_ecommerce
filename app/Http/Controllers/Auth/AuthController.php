<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\OTPSms;
use Faker\UniqueGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Unique;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handelProviderCallback($provider)
    {
        try {
            $socialite_user = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login');
        }

        $user =  User::whereEmail($socialite_user->getEmail())->first();
        if (!$user) {
            $user = User::create([
                'name' => $socialite_user->getName(),
                'provider_name' => $provider,
                'avatar' => $socialite_user->getAvatar(),
                'email' => $socialite_user->getEmail(),
                'password' => Hash::make($socialite_user->getId()),
                'email_verified_at' => now()

            ]);
        }

        auth()->login($user, $remember = true);

        alert()->success('با موفقیت وارد شدید', 'ورود شما موفقیت امیز بود')->persistent('حله');

        return redirect()->route('home.index');
    }


    public function login(Request $request)
    {
        if ($request->method() == 'GET') {
            return view('auth.otpLogin');
        }

        $request->validate([
            'cellphone' => 'required|iran_mobile'
        ]);

        try {
            $user = User::where('cellphone', $request->cellphone)->first();

            $data = ['A', 'a', 'B', 'b', 'C', 'c', 'D', 'd', 'E', 'e', 'F', 'f', 'G', 'g', 'H', 'h', 'I', 'i', 'J', 'j', 'K', 'k', 'L', 'l', 'M', 'm', 'N', 'n', 'O', 'o', 'P', 'p', 'Q', 'q', 'R', 'r', 'S', 's', 'T', 't', 'U', 'u', 'V', 'v', 'W', 'w', 'X', 'x', 'Y', 'y', 'Z', 'z', 0, 1, 2, 3, 4, 5, 6, 7, 8, 9];

            $random = rand(10, count($data));
            $str = '';

            for ($i = 0; $i < $random; $i++) {
                $key = rand(0, count($data) - 1);
                $str .= $data[$key];
            }

            $optCode = mt_rand('1000', '9999');
            $loginToken = Hash::make($str);

            if ($user) {
                $user->update([
                    'otp' => $optCode,
                    'login_token' => $loginToken
                ]);
            } else {
                $user = User::create([
                    'cellphone' => $request->cellphone,
                    'otp' => $optCode,
                    'login_token' => $loginToken
                ]);
            }
            $user->notify(new OTPSms($optCode));


            return response(['login_token' => $loginToken], 200);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 422);
        }
    }

    public function checkOTP(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:4',
            'login_token' => 'required'
        ]);

        try {

            $user  = User::where('login_token', $request->login_token)->firstOrFail();

            if ($user->otp == $request->otp) {
                auth()->login($user, true);
                return response(['ورود با موفقیت انجام شد'], 200);
            } else {
                return response(['errors' => ["otp" => ["کد وارد شده اشتباه است"]]], 422);
            }
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 422);
        }
    }


    public function resendOTP(Request $request)
    {
        $request->validate([
            'login_token' => 'required'
        ]);

        try {
            $user = User::where('login_token', $request->login_token)->firstOrFail();

            $data = ['A', 'a', 'B', 'b', 'C', 'c', 'D', 'd', 'E', 'e', 'F', 'f', 'G', 'g', 'H', 'h', 'I', 'i', 'J', 'j', 'K', 'k', 'L', 'l', 'M', 'm', 'N', 'n', 'O', 'o', 'P', 'p', 'Q', 'q', 'R', 'r', 'S', 's', 'T', 't', 'U', 'u', 'V', 'v', 'W', 'w', 'X', 'x', 'Y', 'y', 'Z', 'z', 0, 1, 2, 3, 4, 5, 6, 7, 8, 9];

            $random = rand(10, count($data));
            $str = '';

            for ($i = 0; $i < $random; $i++) {
                $key = rand(0, count($data) - 1);
                $str .= $data[$key];
            }

            $optCode = mt_rand('1000', '9999');
            $loginToken = Hash::make($str);

            if ($user) {
                $user->update([
                    'otp' => $optCode,
                    'login_token' => $loginToken
                ]);
            }

            $user->notify(new OTPSms($optCode));


            return response(['login_token' => $loginToken], 200);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 422);
        }
    }
}
