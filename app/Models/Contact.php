<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = "contact";
    protected $primaryKey = "contact_id";
    public $timestamps = false;

    protected $fillable = [
        "contact_id",
        "contact_name",
        "contact_email",
        "contact_country",
        "contact_prefix",
        "contact_mobile",
        "contact_city",
        "contact_zipcode",
        "contact_subject",
        "contact_message",
        "contact_ip",
        "contact_order",
        "contact_status",
        "created_at"
    ];
}