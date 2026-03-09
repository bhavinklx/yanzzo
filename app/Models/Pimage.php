<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pimage extends Model
{
    protected $table = 'pimage';
    protected $primaryKey = 'pimage_id';
    public $timestamps = false;

    protected $fillable = [
        'pimage_id',
        'product_id',
        'pimage_image',
        'created_at',
        'updated_at',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
