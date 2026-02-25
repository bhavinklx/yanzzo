<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usp extends Model
{
    protected $table = "usp";
    protected $primaryKey = "usp_id";
    public $timestamps = false;

    protected $fillable = [
        "usp_id",
        "city_id",
        "usp_title",
        "usp_image",
        "usp_desc",
        "usp_order",
        "usp_status",
        "created_at"
    ];
}