<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = "payment";
    protected $primaryKey = "payment_id";
    public $timestamps = false;

    protected $fillable = [
        "page_id",
        "msorder_id",
        "order_id",
        "ORDERID",
        "TXNAMOUNT",
        "CURRENCY",
        "TXNID",
        "BANKTXNID",
        "STATUS",
        "RESPCODE",
        "RESPMSG",
        "TXNDATE",
        "GATEWAYNAME",
        "PAYMENTMODE",
        "CHECKSUMHASH",
        "BANKNAME",
        "REFERENCE",
        "created_at"
    ];
}
