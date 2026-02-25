<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = "category";
    protected $primaryKey = "category_id";
    public $timestamps = false;

    protected $fillable = [
        "category_id",
        "category_parent",
        "category_title",
        "category_slug",
        "category_image",
        "category_icon",
        "category_desc",
        "category_meta_title",
        "category_meta_keyword",
        "category_meta_desc",
        "category_order",
        "category_status",
        "created_at"
    ];

    public function subCategory()
    {
        return $this->hasMany(self::class, 'category_parent')->orderBy('category_order');
    }
}
