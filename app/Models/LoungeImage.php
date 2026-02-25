<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoungeImage extends Model
{
    protected $table = "lounge_image";
    protected $primaryKey = "limage_id";
    public $timestamps = false;

    protected $fillable = [
        "limage_id",
        "lounge_id",
        "limage_image",
        "created_at"
    ];
}
