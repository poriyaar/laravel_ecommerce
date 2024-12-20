<?php

namespace App\Http\Controllers\Home;

use App\Models\City;
use App\Models\Province;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $provinces = Province::all();
        $addresses  = UserAddress::where('user_id', auth()->id())->get();
        return view('home.users_profile.addresses', compact('provinces', 'addresses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validateWithBag('addressStore', [
            'title' => 'required',
            "cellphone" => "required|iran_mobile",
            "province_id" => "required",
            "city_id" => "required",
            "address" => "required",
            "postal_code" => "required|iran_postal_code"
        ]);

        UserAddress::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'cellphone' => $request->cellphone,
            'province_id' => $request->province_id,
            'city_id' => $request->city_id,
            'address' => $request->address,
            'postal_code' => $request->postal_code,
        ]);

        alert()->success('با تشکر', 'آدرس مورد نظر ایجاد شد');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserAddress $address)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            "cellphone" => "required|iran_mobile",
            "province_id" => "required",
            "city_id" => "required",
            "address" => "required",
            "postal_code" => "required|iran_postal_code"
        ]);

        if ($validator->fails()) {
            $validator->errors()->add('address_id', $address->id);
            return redirect()->back()->withErrors($validator, 'addressUpdate')->withInput();
        }


        $address->update([
            'title' => $request->title,
            'cellphone' => $request->cellphone,
            'province_id' => $request->province_id,
            'city_id' => $request->city_id,
            'address' => $request->address,
            'postal_code' => $request->postal_code,
        ]);

        alert()->success('با تشکر', 'آدرس مورد نظر ویرایش شد');

        return redirect()->route('home.addresses.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getProvinceCitiesList(Request $request)
    {
        $cities = City::where('province_id', $request->province_id)->get();

        return $cities;
    }
}
