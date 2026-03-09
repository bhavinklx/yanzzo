<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = "product";
    protected $primaryKey = "product_id";
    public $timestamps = false;

    protected $fillable = [
        "product_id",
        'customer_id',
        'category_id',
        'subcategory_id',
        'state_id',
        'city_id',
        'product_listing_id',
        'product_title',
        'product_slug',
        'product_date',
        'product_short_desc',
        'product_desc',
        'product_specification',
        'product_price',
        'product_brand',
        'product_model',
        'product_location',
        'product_meta_title',
        'product_meta_keyword',
        'product_meta_desc',
        'product_order',
        'product_status',
        'product_view',
        'product_is_sold',
        "created_at"
    ];
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(Category::class, 'subcategory_id', 'category_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'state_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'city_id');
    }

    public function pimages()
    {
        return $this->hasMany(Pimage::class, 'product_id', 'product_id');
    }
}
