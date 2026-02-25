<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = "service";
    protected $primaryKey = "service_id";
    public $timestamps = false;

    protected $fillable = [
        "service_id",
        "service_title",
        "service_slug",
        "service_image",
        "service_desc",
        "service_meta_title",
        "service_meta_keyword",
        "service_meta_desc",
        "service_order",
        "service_status",
        "service_type",
        "created_at"
    ];
}