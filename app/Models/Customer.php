<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = "customer";
    protected $primaryKey = "customer_id";
    public $timestamps = false;

    protected $fillable = [
        "customer_id",
        "customer_name",
        "customer_image",
        "customer_email",
        "customer_mobile",
        "customer_password",
        "customer_created_ip",
        "customer_last_login_date",
        "customer_last_login_ip",
        "customer_otp",
        "customer_order",
        "customer_status",
        "created_at"
    ];
}