<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class States extends Model
{
    protected $table = "states";
    protected $primaryKey = "states_id";
    public $timestamps = false;

    protected $fillable = [
        "states_id",
        "country_id",
        "states_name",
        "states_status"
    ];
}