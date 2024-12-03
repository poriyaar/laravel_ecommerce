<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_variations';
    protected $guarded = [];
    protected $append = ['is_sale'];


    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }


    public function getIsSaleAttribute()
    {
        return ($this->sale_price != null && $this->date_on_sale_from < now() && $this->date_on_sale_to > now() ? true : false);
    }
}
