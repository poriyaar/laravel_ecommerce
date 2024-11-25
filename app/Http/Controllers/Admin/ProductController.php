<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\ProductAttributeController;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest()->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brands = Brand::all();
        $tags = Tag::all();
        $categories = Category::where('parent_id', '!=', 0)->get();

        return view('admin.products.create', compact('brands', 'tags', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string',
            'brand_id' => 'required',
            'is_active' => 'required',
            'tag_ids' => 'required',
            'description' => 'required',
            'primary_image' => 'required|mimes:jpg,jpeg,svg',
            'images.*' => 'mimes:jpg,jpeg,svg',
            'images' => 'required',
            'category_id' => 'required',
            'attribute_ids' => 'required',
            'attribute_ids.*' => 'required',
            'variation_values' => 'required',
            'variation_values.*.*' => 'required',
            'variation_values.price.*' => 'integer',
            'variation_values.quantity.*' => 'integer',
            'delivery_amount' => 'required|integer',
            'delivery_amount_per_product' => 'nullable|integer',
        ]);

        try {
            DB::beginTransaction();

            // upload image in server
            $productImageController  = new ProductImageController();
            $fileNameImages = $productImageController->upload($request->primary_image, $request->images);

            // create product
            $product = Product::create([
                'name' => $request->name,
                'brand_id' => $request->brand_id,
                'category_id' => $request->category_id,
                'primary_image' => $fileNameImages['fileNamePrimaryImage'],
                'description' => $request->description,
                'is_active' => $request->is_active,
                'delivery_amount' => $request->delivery_amount,
                'delivery_amount_per_product' => $request->delivery_amount_per_product,
            ]);

            // create image for product
            foreach ($fileNameImages['fileNameImages'] as $fileNameImage) {

                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $fileNameImage
                ]);
            }

            // create attributes for product
            $productAttributeController =   new ProductAttributeController();
            $productAttributeController->store($request->attribute_ids, $product);

            // get attribute_id from product category
            $attribute_id = Category::find($request->category_id)->attributes()->wherePivot('is_variation', 1)->first()->id;

            // create variations for product
            $productVariationController =   new ProductVariationController();
            $productVariationController->store($request->variation_values, $attribute_id, $product);

            // create tags for product
            $product->tags()->attach($request->tag_ids);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            alert()->error('مشکل در ایجاد محصول', $e->getMessage())->persistent('حله');
            return redirect()->back();
        }

        alert()->success("محصول {$product->name} با موفقیت ایجاد شد", "با تشکر");
        return redirect()->route('admin.products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $productAttributes = $product->attributes()->with('attribute')->get();
        $productVariations = $product->variations;
        $productImages = $product->images;

        return view('admin.products.show', compact('product', 'productAttributes', 'productVariations', 'productImages'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $brands = Brand::all();
        $tags = Tag::all();
        $productAttributes = $product->attributes()->with('attribute')->get();
        $productVariations = $product->variations;

        return view('admin.products.edit', compact('product', 'brands', 'tags', 'productAttributes', 'productVariations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string',
            'brand_id' => 'required|exists:brands,id',
            'is_active' => 'required',
            'tag_ids' => 'required',
            'tag_ids.*' => 'exists:tags,id',
            'description' => 'required',
            'attribute_values' => 'required',
            'variation_values' => 'required',
            'variation_values.*.price' => 'required|integer',
            'variation_values.*.quantity' => 'required|integer',
            'variation_values.*.sale_price' => 'nullable|integer',
            'variation_values.*.sale_price' => 'nullable|integer',
            'variation_values.*.date_on_sale_from' => 'nullable|date',
            'variation_values.*.date_on_sale_to' => 'nullable|date',
            'delivery_amount' => 'required|integer',
            'delivery_amount_per_product' => 'nullable|integer',
        ]);


        try {
            DB::beginTransaction();

            // create product
            $product->update([
                'name' => $request->name,
                'brand_id' => $request->brand_id,
                'description' => $request->description,
                'is_active' => $request->is_active,
                'delivery_amount' => $request->delivery_amount,
                'delivery_amount_per_product' => $request->delivery_amount_per_product,
            ]);



            // create attributes for product
            $productAttributeController =   new ProductAttributeController();
            $productAttributeController->update($request->attribute_values);

            // get attribute_id from product category

            // create variations for product
            $productVariationController =   new ProductVariationController();
            $productVariationController->update($request->variation_values);

            // create tags for product
            $product->tags()->sync($request->tag_ids);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            alert()->error('مشکل در ویرایش محصول', $e->getMessage())->persistent('حله');
            return redirect()->back();
        }

        alert()->success("محصول {$product->name} با موفقیت ویرایش شد", "با تشکر");
        return redirect()->route('admin.products.index');
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



    public function editCategory(Product $product, Request $request)
    {
        $categories = Category::where('parent_id', '!=', 0)->get();
        return view('admin.products.edit_category', compact('product', 'categories'));
    }

    public function updateCategory(Product $product, Request $request)
    {

        $request->validate([
            'category_id' => 'required',
            'attribute_ids' => 'required',
            'attribute_ids.*' => 'required',
            'variation_values' => 'required',
            'variation_values.*.*' => 'required',
            'variation_values.price.*' => 'integer',
            'variation_values.quantity.*' => 'integer',
        ]);
        try {
            DB::beginTransaction();

            // create product
            $product->update([
                'category_id' => $request->category_id,

            ]);

            // create attributes for product
            $productAttributeController =   new ProductAttributeController();
            $productAttributeController->change($request->attribute_ids, $product);

            // get attribute_id from product category
            $attribute_id = Category::find($request->category_id)->attributes()->wherePivot('is_variation', 1)->first()->id;

            // create variations for product
            $productVariationController =   new ProductVariationController();
            $productVariationController->change($request->variation_values, $attribute_id, $product);



            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            alert()->error('مشکل در ایجاد محصول', $e->getMessage())->persistent('حله');
            return redirect()->back();
        }

        alert()->success("محصول {$product->name} با موفقیت ایجاد شد", "با تشکر");
        return redirect()->route('admin.products.index');
    }
}
