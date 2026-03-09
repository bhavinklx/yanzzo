<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = "city";
    protected $primaryKey = "city_id";
    public $timestamps = false;

    protected $fillable = [
        "city_id",
        "state_id",
        "city_name",
        "city_status"
    ];
}
