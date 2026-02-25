<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipOrder extends Model
{
    protected $table = "membership_order";
    protected $primaryKey = "msorder_id";
    public $timestamps = false;

    protected $fillable = [
        "msorder_id",
        "msorder_unique_id",
        "customer_id",
        "membership_id",
        "msorder_date",
        "customer_name",
        "customer_mobile",
        "msorder_start_date",
        "msorder_end_date",
        "membership_title",
        "membership_price",
        "discount_id",
        "discount_code",
        "discount_price",
        "msorder_paid_price",
        "msorder_type",
        "payment_id",
        "payment_date",
        "payment_time",
        "msorder_status",
        "created_at"
    ];
}