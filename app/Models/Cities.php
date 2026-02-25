<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    protected $table = "cities";
    protected $primaryKey = "cities_id";
    public $timestamps = false;

    protected $fillable = [
        "cities_id",
        "states_id",
        "cities_name",
        "cities_status"
    ];
}