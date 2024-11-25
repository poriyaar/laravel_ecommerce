<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use App\Http\Controllers\Controller;

class ProductAttributeController extends Controller
{
    public function store($attributes, $product)
    {
        foreach ($attributes as $key => $value) {
            ProductAttribute::create([
                'product_id' => $product->id,
                'attribute_id' => $key,
                'value' => $value,
            ]);
        }
    }

    public function update($attributeIds)
    {

        foreach ($attributeIds as $key => $value) {

            $productAttribute = ProductAttribute::findOrFail($key);

            $productAttribute->update([
                'value' => $value
            ]);
        }
    }


    public function change($attributeIds, $product)
    {
        ProductAttribute::where('product_id', $product->id)->delete();
        foreach ($attributeIds as $key => $value) {
            ProductAttribute::create([
                'product_id' => $product->id,
                'attribute_id' => $key,
                'value' => $value,
            ]);
        }
    }
}
