<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory,  Sluggable;

    protected $table = 'products';

    protected $guarded = [];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name' //table filed name
            ]
        ];
    }

    protected $appends = ['quantity_check', 'sale_check', 'price_check'];

    public function getIsActiveAttribute($is_active)
    {
        return $is_active ? 'فعال' : 'غیر فعال';
    }

    public function getQuantityCheckAttribute()
    {
        return $this->variations()->where('quantity', '>', 0)->first() ?? 0;
    }

    public function getSaleCheckAttribute()
    {
        return $this->variations()->where('quantity', '>', 0)->whereNotNull('sale_price')->where('date_on_sale_from', '<', now())->where('date_on_sale_to', '>', now())->orderBy('sale_price')->first() ?? false;
    }

    public function getPriceCheckAttribute()
    {
        return $this->variations()->where('quantity', '>', 0)->orderBy('price')->first() ?? false;
    }

    /*********************************************\
     *****************start Scope ****************/


    public function scopeFilter($query)
    {

        if (request()->has('attribute')) {

            foreach (request()->attribute as $attribute) {

                $query->whereHas('attributes', function ($query) use ($attribute) {
                    foreach (explode('-', $attribute) as $index => $item) {
                        if ($index == 0) {
                            $query->where('value', $item);
                        } else {
                            $query->orWhere('value', $item);
                        }
                    }
                });
            }
        }


        if (request()->has('variation')) {
            $query->whereHas('variations', function ($query) {
                foreach (explode('-', request()->variation) as $key => $value) {
                    if ($key == 0) {
                        $query->where('value', $value);
                    } else {
                        $query->orWhere('value', $value);
                    }
                }
            });
        }

        if (request()->has('sortBy')) {
            $sortBy = request('sortBy');

            switch ($sortBy) {
                case 'max':
                    $query->orderByDesc(ProductVariation::select('price')->whereColumn('product_variations.product_id', 'products.id')->orderBy('sale_price', 'desc')->take(1));
                    break;
                case 'min':
                    $query->orderBy(ProductVariation::select('price')->whereColumn('product_variations.product_id', 'products.id')->orderBy('sale_price', 'desc')->take(1));
                    break;
                case 'oldest':
                    $query->oldest();
                    break;
                case 'latest':
                    $query->latest();
                    break;

                default:
                    $query;
                    break;
            }
        }

        return $query;
    }


    public function scopeSearch($query)
    {
        $keyboard = request('search');
        if(request()->has('search') && trim($keyboard) != '')
        {
            $query->where('name' , 'LIKE' , '%' . trim($keyboard) . '%');
        }

        return $query;

    }




    /*********************************************\
     *****************start Relation *************\
     */

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function rates()
    {
        return $this->hasMany(ProductRate::class);
    }
}
