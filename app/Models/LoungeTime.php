<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoungeTime extends Model
{
    protected $table = "lounge_time";
    protected $primaryKey = "ltime_id";
    public $timestamps = false;

    protected $fillable = [
        "ltime_id",
        "lounge_id",
        "ltime_day",
        "ltime_open_hour",
        "ltime_open_time",
        "ltime_open_ap",
        "ltime_close_hour",
        "ltime_close_time",
        "ltime_close_ap",
        "ltime_text",
        "ltime_order",
        "ltime_status",
        "created_at"
    ];
}
