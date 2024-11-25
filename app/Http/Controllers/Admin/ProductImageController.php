<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProductImageController extends Controller
{

    public function upload($primaryImage, $images)
    {
        $fileNamePrimaryImage = generateFileName($primaryImage->getClientOriginalName());

        $primaryImage->move(public_path(env('PRODUCT_IMAGES_UPLOAD_PATH')), $fileNamePrimaryImage);

        $fileNameImages = [];
        foreach ($images as $image) {
            $fileNameImage = generateFileName($image->getClientOriginalName());

            $image->move(public_path(env('PRODUCT_IMAGES_UPLOAD_PATH')), $fileNameImage);

            array_push($fileNameImages, $fileNameImage);
        }



        return [
            'fileNamePrimaryImage' =>  $fileNamePrimaryImage,
            'fileNameImages' =>  $fileNameImages,

        ];
    }


    public function edit(Product $product)
    {
        return view('admin.products.edit_image', compact('product'));
    }


    public function destroy(Request $request)
    {
        $request->validate([
            'image_id' => 'required|exists:product_images,id'
        ]);

        ProductImage::destroy($request->image_id);

        alert()->success('تصویر مورد نظر با موفقیت حذف شد', 'با تشکر');
        return redirect()->back();
    }


    public function setPrimary(Request $request, Product $product)
    {
        $request->validate([
            'image_id' => 'required|exists:product_images,id'
        ]);

        $productImage = ProductImage::findOrFail($request->image_id);

        $product->update(['primary_image' => $productImage->image]);

        alert()->success('ویرایش تصویر اصلی محصول با موفقیت انجام شد', 'با تشکر');
        return redirect()->back();
    }


    public function add(Request $request, Product $product)
    {
        $request->validate([
            'primary_image' => 'nullable|mimes:jpg,jpeg,svg',
            'images.*' => 'nullable|mimes:jpg,jpeg,svg',
        ]);

        try {
            DB::beginTransaction();

            if ($request->primary_image == null && $request->images == null) {

                return redirect()->back()->withErrors(['msg' => 'تصویر یا تصاویر الزامی است']);
            }

            if ($request->has('primary_image')) {


                $fileNamePrimaryImage = generateFileName($request->primary_image->getClientOriginalName());

                $request->primary_image->move(public_path(env('PRODUCT_IMAGES_UPLOAD_PATH')), $fileNamePrimaryImage);

                $product->update([
                    'primary_image' => $fileNamePrimaryImage
                ]);
            }

            if ($request->has('images')) {

                foreach ($request->images as $image) {
                    $fileNameImage = generateFileName($image->getClientOriginalName());

                    $image->move(public_path(env('PRODUCT_IMAGES_UPLOAD_PATH')), $fileNameImage);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $fileNameImage
                    ]);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            alert()->error($e->getMessage(), 'مشکل در تغییر عکس')->persistent('حله');
            redirect()->back();
        }


        alert()->success('ویرایش تصویر اصلی محصول با موفقیت انجام شد', 'با تشکر');
        return redirect()->back();
    }
}
