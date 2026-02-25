<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    protected $table = "sponsor";
    protected $primaryKey = "sponsor_id";
    public $timestamps = false;

    protected $fillable = [
        "sponsor_id",
        "sponsor_title",
        "sponsor_image",
        "sponsor_order",
        "sponsor_status",
        "created_at"
    ];
}