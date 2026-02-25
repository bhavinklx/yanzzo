<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Franchise extends Model
{
    protected $table = "franchise";
    protected $primaryKey = "franchise_id";
    public $timestamps = false;

    protected $fillable = [
        "franchise_id",
        "franchise_company_name",
        "franchise_owner_name",
        "franchise_email",
        "franchise_mobile1",
        "franchise_mobile2",
        "franchise_mobile3",
        "franchise_address",
        "franchise_pan",
        "franchise_gst",
        "franchise_gst_percentage",
        "franchise_bank_ac",
        "franchise_bank_name",
        "franchise_bank_ifsc",
        "franchise_bank_type",
        "franchise_order",
        "franchise_status",
        "created_at"
    ];
}