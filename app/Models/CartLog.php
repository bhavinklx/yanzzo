<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartLog extends Model
{
    protected $table = "cart_log";
    protected $primaryKey = "clog_id";
    public $timestamps = false;

    protected $fillable = [
        "clog_id",
        "cart_id",
        "customer_id",
        "lounge_id",
        "clog_start_date",
        "clog_start_time",
        "clog_duration",
        "created_ip",
        "created_at"
    ];
}