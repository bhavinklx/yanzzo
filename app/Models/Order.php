<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = "order";
    protected $primaryKey = "order_id";
    public $timestamps = false;

    protected $fillable = [
        "order_id",
        "order_unique_id",
        "customer_id",
        "lounge_id",
        "cart_id",
        "order_date",
        "customer_name",
        "customer_mobile",
        "discount_id",
        "discount_code",
        "discount_price",
        "membership_id",
        "membership_discount",
        "order_gst",
        "order_paid_price",
        "order_type",
        "payment_id",
        "payment_date",
        "payment_time",
        "order_status",
        "order_ostatus",
        "order_cancel_date",
        "order_cancel_reason",
        "order_refund_date",
        "order_refund_reason",
        "order_token",
        "created_at"
    ];
}