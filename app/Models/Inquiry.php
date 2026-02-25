<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $table = "inquiry";
    protected $primaryKey = "inquiry_id";
    public $timestamps = false;

    protected $fillable = [
        "inquiry_id",
        "inquiry_company",
        "inquiry_name",
        "inquiry_email",
        "inquiry_prefix",
        "inquiry_mobile",
        "inquiry_city",
        "inquiry_state",
        "inquiry_country",
        "inquiry_zipcode",
        "inquiry_ip",
        "inquiry_order",
        "inquiry_status",
        "created_at"
    ];
}