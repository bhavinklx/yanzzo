<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoungeMaintenanceTime extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at']; // optional in Laravel 9+, automatic
    
    protected $table = "lounge_maintenance_time";
    protected $primaryKey = "lmtime_id";
    public $timestamps = false;

    protected $fillable = [
        "lmtime_id",
        "lounge_id",
        "ltime_day",
        "lmtime_open_date",
        "lmtime_open_time",
        "lmtime_open_ap",
        "lmtime_close_time",
        "lmtime_close_ap",
        "is_fullday_close",
        "created_at"
    ];
}
