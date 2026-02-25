<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartDetail extends Model
{
    protected $table = "cart_detail";
    protected $primaryKey = "cdetail_id";
    public $timestamps = false;

    protected $fillable = [
        "cdetail_id",
        "cart_id",
        "cdetail_start_time",
        "cdetail_end_time",
        "cdetail_amount",
        "created_at"
    ];
}