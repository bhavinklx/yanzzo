<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = "discount";
    protected $primaryKey = "discount_id";
    public $timestamps = false;

    protected $fillable = [
        "discount_id",
        "discount_scenario_type",
        "discount_title",
        "discount_code",
        "discount_type",
        "discount_amount",
        "discount_start_date",
        "discount_start_time",
        "discount_end_date",
        "discount_end_time",
        "discount_min_amount",
        "discount_max_discount",
        "discount_status",
        "created_at"
    ];
}