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
        "city_id",
        "service_title",
        "service_image",
        "service_desc",
        "service_order",
        "service_status",
        "created_at"
    ];
}