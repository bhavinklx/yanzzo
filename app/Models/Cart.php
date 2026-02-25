<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = "cart";
    protected $primaryKey = "cart_id";
    public $timestamps = false;

    protected $fillable = [
        "cart_id",
        "customer_id",
        "lounge_id",
        "cart_start_date",
        "cart_start_time",
        "cart_duration",
        "cart_adults",
        "cart_children",
        "cart_amount",
        "cart_status",
        "cart_reschedule",
        "created_ip",
        "created_at"
    ];
}