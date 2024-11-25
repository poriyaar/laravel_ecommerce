<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ProductVariation;
use App\Http\Controllers\Controller;

class ProductVariationController extends Controller
{

    public function store($variations, $attribute_id, $product)
    {
        $counter = count($variations['value']);


        for ($i = 0; $i < $counter; $i++) {

            ProductVariation::create([
                'attribute_id' => $attribute_id,
                'product_id' => $product->id,
                'value' => $variations['value'][$i],
                'price' => $variations['price'][$i],
                'quantity' => $variations['quantity'][$i],
                'sku' => $variations['sku'][$i],


            ]);
        }
    }

    public function update($variationIds)
    {
        foreach ($variationIds as $key => $value) {

            $productVariation = ProductVariation::findOrFail($key);

            $productVariation->update([
                'price' => $value['price'],
                'quantity' => $value['quantity'],
                'sku' => $value['sku'],
                'sale_price' => $value['sale_price'],
                'date_on_sale_from' => convertShamsiToGregorianDate($value['date_on_sale_from']),
                'date_on_sale_to' => convertShamsiToGregorianDate($value['date_on_sale_to']),
            ]);
        }
    }

    public function change($variations, $attribute_id, $product)
    {
        ProductVariation::where('product_id' , $product->id)->delete();
        $counter = count($variations['value']);

        for ($i = 0; $i < $counter; $i++) {

            ProductVariation::create([
                'attribute_id' => $attribute_id,
                'product_id' => $product->id,
                'value' => $variations['value'][$i],
                'price' => $variations['price'][$i],
                'quantity' => $variations['quantity'][$i],
                'sku' => $variations['sku'][$i],


            ]);
        }
    }
}
